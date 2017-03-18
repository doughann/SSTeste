<?php
################################################################################################
#	Order Product Edit
#	Jesse W. Wallace
#	3/11/13
#	OpenCart 1.5.6
#	v1.2.9 Alpha
################################################################################################
class ControllerSaleOrderProductEdit extends Controller {
	
	// Create Error Array
	private $error = array();
	
	public function getProductOptions(){
		
		// Load Model File With SQL Statements
		$this->load->model('sale/order_product_edit');

		// Get Order Product ID
		$order_product_id = $this->request->get['order_product_id'];
		
		// Get Product Info
		$order_product_info = $this->model_sale_order_product_edit->getOrderProductInfo($order_product_id);	
		$product_id = $order_product_info['product_id'];
		$order_product_id = $order_product_info['order_product_id'];	
		
		// Get Order Info
		$order_id = $order_product_info['order_id'];
		$order_info = $this->model_sale_order_product_edit->getOrderInfo($order_id);	
		$customer_group_id = $order_info['customer_group_id'];
		
		// Get All Product Options
		$product_options = 	$this->model_sale_order_product_edit->getProductOptions($product_id, $order_info['language_id']);
		
		// Get Option Type, Value and Value Desc
		$options = array();
		foreach ($product_options as $product_option){
		
			// Get Option Values
			$product_option_values = $this->model_sale_order_product_edit->getProductOptionValues($product_option['product_option_id'], $order_info['language_id']);
			
			// Get Current Option Value
			$current_value =  $this->model_sale_order_product_edit->getOrderProductOptionValue($order_product_id,$product_option['product_option_id']);
			
			// Create Return Array
			$options[] = array(
				'product_option' 			=> 		$product_option,
				'product_option_values'		=>		$product_option_values,
				'current_value'				=>		$current_value,
			);
		}
		
		$order_product = array(
			'order_product_id'	=>	$order_product_id,
			'options'			=>	$options,
		);
		
	// Determin if We Are Entering or Exiting This Form
		
		// If save validate and call add to DB query
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {			
		
			// Get Order Product Info
			$order_product_info = $this->model_sale_order_product_edit->getOrderProductInfo($this->request->post['order_product_id']);
			
			// Set Name Overide
			if(!$this->request->post['name']){ $product_name = $order_product_info['name']; }else{ $product_name = $this->request->post['name']; }
				
			// Get Product SKU / Model		
			if(!$this->request->post['model']){ $product_sku = $this->model_sale_order_product_edit->getProductSKU($product_id); }else{ $product_sku = $this->request->post['model']; }
									
			// Set Qty
			$product_qty = (int)$this->request->post['qty'];
			if(!$this->request->post['qty']){ $product_qty = $order_product_info['quantity']; }
				
			// Update Inventory
				
			// Get Base Price for QTY
			$base_price = $this->model_sale_order_product_edit->getBasePrice($product_id,$product_qty, $customer_group_id);
			
			// Get All Changes From Options			
			$new_price = 0;
			$one_time = 0;
			$per_piece = 0;
			$reward = 0;
			$weight = 0;			
			
			if(isset($this->request->post['option_id'])){				
				foreach($this->request->post['option_id'] as $product_option_id => $product_option_value_id){
									
					// Get Option ID				
					$option_id = $this->model_sale_order_product_edit->getOptionID($product_option_id);
				
					// Get Name & Type
					$option_info = $this->model_sale_order_product_edit->getOptionInfo($option_id);
					
					// Look For Checkbox & Multipule File Upload Array
					$values = array();
					if(is_array($product_option_value_id)){
						foreach($product_option_value_id as $key => $value){
							array_push($values, $value);
						}
					}else{
						array_push($values, $product_option_value_id);
					}
					
					foreach($values as $value){
					
						// Get Options If Select, Radio, or Checkbox
						if($option_info['type'] == "select" || $option_info['type'] == "radio" || $option_info['type'] == "checkbox"){
							$product_option_value = $this->model_sale_order_product_edit->getOptionIncrease($value);							
						}else{
							$product_option_value = 0;
							$product_option_value_id = 0;
						}					
				
						// Ensure There Are Options
						if ($product_option_value){
							
							// Get The Human Readable Value
							$option_value = $this->model_sale_order_product_edit->getOptionValue($product_option_value['option_value_id']);
							$value = $option_value['name'];
							
							// Add Up Price
							if($product_option_value['price_prefix'] == '1'){
								$one_time += $product_option_value['price'];
							}
							if($product_option_value['price_prefix'] == '%'){
								$per_piece += ($base_price * ($product_option_value['price'] / 100));
							}			
							if($product_option_value['price_prefix'] == '+'){
								$per_piece += $product_option_value['price'];
							}
							if($product_option_value['price_prefix'] == '-'){
								$per_piece -= $product_option_value['price'];
							}									
							if($product_option_value['price_prefix'] == '*'){
								$per_piece += ($base_price * $product_option_value['price']);
							}
							if($product_option_value['price_prefix'] == '='){
								$new_price = $product_option_value['price'];
							}
							
							// Add Up Rewards Price
							if($product_option_value['points_prefix'] == '+'){
								$reward += $product_option_value['points'];
							}
							if($product_option_value['points_prefix'] == '-'){
								$reward -= $product_option_value['points'];
							}
							
							// Add Up Weight
							if($product_option_value['weight_prefix'] == '+'){
								$weight += $product_option_value['weight'];
							}
							if($product_option_value['weight_prefix'] == '-'){
								$weight -= $product_option_value['weight'];
							}
							
							// Check For SKU Change
							if(array_key_exists('ob_sku',$product_option_value)){
								if($product_option_value['ob_sku'] && $product_option_value['ob_sku_override']){ $product_sku = $product_option_value['ob_sku']; }
							}
						}
				
						$order_option[] = array(
							'product_option_id'			=>		$product_option_id,
							'product_option_value_id'	=>		$product_option_value_id,
							'type'						=>		$option_info['type'],					
							'name'						=>		$option_info['name'],
							'value'						=>		$value,
						);
					}
						
				}
			
				// Delete All Options & Resave
				$this->model_sale_order_product_edit->updateOrderOption($order_option,$order_id,$order_product_id);
			}
			
			// Set Total if Price Override Set
			if(strtolower($this->request->post['ppp']) == "free"){
				$price_override = 1;
				$product_total = 0;
			}elseif((float)$this->request->post['ppp'] > 0){
				$price_override = 1;
				$new_price = (float)$this->request->post['ppp'] * $product_qty;
				$product_total = $new_price;
			}else{
				$price_override = 0;
			}
			
			// If No Override Calculate New Price based on Options
			if(!$price_override){
				if($new_price){
					$product_total = $new_price;
				}else{
					$product_total = ((($base_price + $per_piece) * $product_qty) + $one_time);	
				}
			}
						
			// Calcualte Price Per Piece
			if($product_total > 0){
				$per_piece = $product_total / $product_qty;
			}else{
				$per_piece = 0;
			}
			
			// Set Individual Product Tax
			$tax = 0;
						 			
			// Add Rewards to Base Rewards			
			
			// Update Order Product Total
			$order_product = array(
				'order_product_id' 		=>	$order_product_id,
				'quantity'				=>	$product_qty,
				'name'					=>	$product_name,
				'model'					=>	$product_sku,
				'price'					=>	$per_piece,
				'total'					=> 	$product_total,
				'tax'					=>	$tax,
				'reward'				=>	$reward,
			);
			$this->model_sale_order_product_edit->updateOrderProduct($order_product);
			
			// Get Order Total
			$order_total = $this->model_sale_order_product_edit->getOrderTotal($order_id);	
			
			// Get All Order Products		
			$order_products = $this->model_sale_order_product_edit->getOrderProductIDs($order_id);
			
			// Get Customer Shipping GeoZone
			$customer_shipping_geozone = $this->model_sale_order_product_edit->getGeoZone($order_id, "shipping");
			
			// Get Customer Billing GeoZone
			$customer_billing_geozone = $this->model_sale_order_product_edit->getGeoZone($order_id, "billing");
			
			// Get Store GeoZone
			$store_geozone = $this->model_sale_order_product_edit->getGeoZone(0, "store");
			
			// Calculate Taxes
			$order_taxes = array();
			foreach($order_products as $order_product){
				
				// Get Tax Class For Product
				$product_tax_class = $this->model_sale_order_product_edit->getProductTaxClass($order_product['product_id']);
				
				
				if($product_tax_class > 0){
					
					// Get Tax Rates For Class
					$product_tax_rates = $this->model_sale_order_product_edit->getProductTaxRates($product_tax_class, $customer_group_id);
					
					// Calculate Qualifying Tax Rates
					if(isset($product_tax_rates)){
						
						foreach($product_tax_rates as $product_tax_rate){							
							
							// Check if Tax Should be Applied to Their Geo Zone
							if(($product_tax_rate['based'] == 'shipping' && $product_tax_rate['geo_zone_id'] == $customer_shipping_geozone) ||
								($product_tax_rate['based'] == 'payment' && $product_tax_rate['geo_zone_id'] == $customer_billing_geozone) ||
								($product_tax_rate['based'] == 'store' && $product_tax_rate['geo_zone_id'] == $store_geozone)){					

								if (array_key_exists($product_tax_rate['name'],$order_taxes)){
									// Add To Existing Tax
									$order_taxes[$product_tax_rate['name']] += (float)($product_tax_rate['rate']/100) * $order_product['total'];
								}else{
									// Add New Tax
									$order_taxes[$product_tax_rate['name']]  = (float)($product_tax_rate['rate']/100) * $order_product['total'];
								}
							}
						}
					}
				}
			}


			// Update Order Taxes
			$this->model_sale_order_product_edit->updateOrderTax($order_id,$order_taxes);
			
			// Update Subtotal & Total	
			$this->model_sale_order_product_edit->updateOrderTotal($order_id,$order_total);						
			
			// Get User Info
			$user_info = $this->model_sale_order_product_edit->getUserInfo($this->user->getId());						
			
			// Get Status Id
			$order_status_id =  $this->model_sale_order_product_edit->getOrderStatusID($order_id);
			
			// Add History
			$history_info = $product_sku." updated to ".$product_total." by ".$user_info['firstname']." ".$user_info['lastname']." (".$user_info['username'].")";
			$this->model_sale_order_product_edit->addHistory($order_id,$order_status_id,$history_info);
			
			// Reload Page
			$this->redirect($this->url->link('sale/order/update', 'token=' . $this->session->data['token'] . '&order_id='.$order_id, 'SSL'));
			
		} else {
			// Return JSON
			echo json_encode($order_product);
		}
	}
	
// Validate The Form--------------------------------------------------------------------------------------------------

	private function validateForm() {

		return TRUE;
		
	}
	
// End Validate The Form----------------------------------------------------------------------------------------------	

// Update Shipping----------------------------------------------------------------------------------------------------

	public function updateShipping() {
		
		// Load Model File With SQL Statements
		$this->load->model('sale/order_product_edit');

		// If Data Set Up Array
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
		
			// Update Shipping Title Text and Value
			$this->model_sale_order_product_edit->updateShippingTotal($this->request->post);
			
			// Get Order Sub Total
			$sub_total = $this->model_sale_order_product_edit->getOrderTotal($this->request->post['order_id']);
		
			// Update Order Totals
			$total = $this->model_sale_order_product_edit->updateOrderTotal($this->request->post['order_id'],$sub_total);
			
			// Get User Info
			$user_info = $this->model_sale_order_product_edit->getUserInfo($this->user->getId());						
			
			// Get Status Id
			$order_status_id =  $this->model_sale_order_product_edit->getOrderStatusID($this->request->post['order_id']);
			
			// Add History
			$history_info = " Shipping Changed to ".$this->request->post['title']." ".$this->request->post['value']." by ".$user_info['firstname']." ".$user_info['lastname']." (".$user_info['username'].")";
			$this->model_sale_order_product_edit->addHistory($this->request->post['order_id'],$order_status_id,$history_info);			
			
			// Get All Order Totals
			echo json_encode($this->model_sale_order_product_edit->getOrderTotals($this->request->post['order_id']));
		}
		
		echo "";
	}
	
// End Update Shipping------------------------------------------------------------------------------------------------	

// Add Line Item------------------------------------------------------------------------------------------------------

	public function addLineItem() {
		
		// Load Model File With SQL Statements
		$this->load->model('sale/order_product_edit');

		// If Data Set Up Array
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {			
			
			// Create New Item Array
			$new_item = array(
				'order_id'			=>	$this->request->post['order_id'],
				'code'				=>	'line_item',
				'title'				=>	$this->request->post['description'],				
				'value'				=>	$this->request->post['price'],							
				'sort_order'		=>	8,												
			);
		
			// Add New Line Item
			$this->model_sale_order_product_edit->addLineItem($new_item);	
			
			// Get Order Sub Total
			$sub_total = $this->model_sale_order_product_edit->getOrderTotal($this->request->post['order_id']);					
		
			// Update Order Totals
			$total = $this->model_sale_order_product_edit->updateOrderTotal($this->request->post['order_id'],$sub_total);
			
			// Get User Info
			$user_info = $this->model_sale_order_product_edit->getUserInfo($this->user->getId());						
			
			// Get Status Id
			$order_status_id =  $this->model_sale_order_product_edit->getOrderStatusID($this->request->post['order_id']);
			
			// Add History
			$history_info = $this->request->post['description']." added for ".$this->request->post['price']." by ".$user_info['firstname']." ".$user_info['lastname']." (".$user_info['username'].")";
			$this->model_sale_order_product_edit->addHistory($this->request->post['order_id'],$order_status_id,$history_info);				
			
			// Get All Order Totals
			echo json_encode($this->model_sale_order_product_edit->getOrderTotals($this->request->post['order_id']));
		}
		
		echo "";
	}
	
// End Add Line Item--------------------------------------------------------------------------------------------------	

// Remove Line Item---------------------------------------------------------------------------------------------------

	public function removeLineItem() {
		
		// Load Model File With SQL Statements
		$this->load->model('sale/order_product_edit');

		// If Data Set Up Array
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {			
			
			// Remove Line Item
			$this->model_sale_order_product_edit->removeLineItem($this->request->post['order_total_id']);	
			
			// Get Order Sub Total
			$sub_total = $this->model_sale_order_product_edit->getOrderTotal($this->request->post['order_id']);					
		
			// Update Order Totals
			$total = $this->model_sale_order_product_edit->updateOrderTotal($this->request->post['order_id'],$sub_total);
			
			// Get User Info
			$user_info = $this->model_sale_order_product_edit->getUserInfo($this->user->getId());						
			
			// Get Status Id
			$order_status_id =  $this->model_sale_order_product_edit->getOrderStatusID($this->request->post['order_id']);
			
			// Add History
			$history_info = "Line Item Removed by ".$user_info['firstname']." ".$user_info['lastname']." (".$user_info['username'].") New Total: ".$total;
			$this->model_sale_order_product_edit->addHistory($this->request->post['order_id'],$order_status_id,$history_info);				
			
			// Get All Order Totals
			echo json_encode($this->model_sale_order_product_edit->getOrderTotals($this->request->post['order_id']));
		}
		
		echo "";
	}
	
// End Remove Line Item-----------------------------------------------------------------------------------------------	
	
}
?>