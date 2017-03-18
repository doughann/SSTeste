<?php
################################################################################################
#	Order Product Edit
#	Jesse W. Wallace
#	3/11/13
#	OpenCart 1.5.6
#	v1.2.9 Alpha
################################################################################################
class ModelSaleOrderProductEdit extends Model {

// Get Product ID 
	public function getOrderProductInfo($order_product_id) {
		
		// Setup SQL Statement
		$sql = "SELECT * FROM " . DB_PREFIX . "order_product WHERE order_product_id = ".(int)$order_product_id;
		
		// Get ID
		$query = $this->db->query($sql);		
		
		// Return
		return $query->row;
		
	}
	
	public function getOrderInfo($order_id) {
		
		// Setup SQL Statement
		$sql = "SELECT * FROM `" . DB_PREFIX . "order` WHERE order_id = ".(int)$order_id;
		
		// Get ID
		$query = $this->db->query($sql);		
		
		// Return
		return $query->row;
		
	}	
	
// Get Product SKU 
	public function getProductSKU($product_id) {
		
		// Setup SQL Statement
		$sql = "SELECT model FROM " . DB_PREFIX . "product WHERE product_id = ".(int)$product_id;
		
		// Get ID
		$query = $this->db->query($sql);		
		
		// Return
		return $query->row['model'];
		
	}	

// Get Current Option Value 
	public function getOrderProductOptionValue($order_product_id,$product_option_id) {
		
		// Setup SQL Statement
		$sql = "SELECT name, value, product_option_value_id FROM " . DB_PREFIX . "order_option WHERE order_product_id = ".(int)$order_product_id ." AND product_option_id = ".(int)$product_option_id;
		
		// Get ID
		$query = $this->db->query($sql);		
		
		// Return
		return $query->rows;
		
	}	
	
// Get Product Options 
	public function getProductOptions($product_id, $language_id) {
		
		
		// Setup SQL Statement
		$sql = "SELECT " . DB_PREFIX . "product_option.product_id as product_id
			," . DB_PREFIX . "product_option.product_option_id as product_option_id		
			," . DB_PREFIX . "product_option.option_id as option_id
			," . DB_PREFIX . "product_option.required as required
			,`" . DB_PREFIX . "option`.type as type
			,`" . DB_PREFIX . "option`.sort_order as sort_order
			," . DB_PREFIX . "option_description.name as name
			FROM `" . DB_PREFIX . "product_option` LEFT JOIN `" . DB_PREFIX . "option`
			ON " . DB_PREFIX . "product_option.option_id = `" . DB_PREFIX . "option`.option_id LEFT JOIN " . DB_PREFIX . "option_description
			ON " . DB_PREFIX . "product_option.option_id = " . DB_PREFIX . "option_description.option_id
			WHERE product_id = ".(int)$product_id." and language_id = ". (int)$language_id ."
			ORDER BY sort_order ASC";
		
		// Get Options
		$query = $this->db->query($sql);
		
		// Return
		return $query->rows;
		
	}
	
// Get Product Option Values
	public function getProductOptionValues($product_option_id, $language_id) {
		
		// Setup SQL Statement
		$sql = "SELECT " . DB_PREFIX . "product_option_value.*
			," . DB_PREFIX . "option_value.sort_order as sort_order
			," . DB_PREFIX . "option_value_description.name as name
			FROM " . DB_PREFIX . "product_option_value LEFT JOIN " . DB_PREFIX . "option_value
			ON " . DB_PREFIX . "product_option_value.option_value_id = " . DB_PREFIX . "option_value.option_value_id LEFT JOIN " . DB_PREFIX . "option_value_description
			ON " . DB_PREFIX . "product_option_value.option_value_id = " . DB_PREFIX . "option_value_description.option_value_id
			WHERE product_option_id = ".(int)$product_option_id." and language_id = ". (int)$language_id ."
			ORDER BY " . DB_PREFIX . "option_value.sort_order ASC";
				
		// Get Options
		$query = $this->db->query($sql);
		
		// Return
		return $query->rows;
		
	}		
	
// Get Base Price
	public function getBasePrice($product_id, $qty, $customer_group_id) {
		
		// Setup SQL Statement
		$sql = "SELECT price FROM " . DB_PREFIX . "product
			WHERE product_id = ".(int)$product_id;
				
		// Get Get Base Price
		$query = $this->db->query($sql);
		$price = $query->row['price'];
		
		// Get Date
		$current_date = date("Y-m-d");
		
		// Check For Qty Discount
		$sql = "SELECT price FROM " . DB_PREFIX . "product_discount WHERE product_id = ".(int)$product_id."
			AND customer_group_id = ".(int)$customer_group_id." AND quantity <= ".(int)$qty." AND date_start <= '" .$current_date . "' AND date_end >= '" .$current_date . "'
			ORDER BY quantity DESC, priority ASC LIMIT 1";					
		
		// Check For Discount
		$query = $this->db->query($sql);
		
		if($query->row){
			$price = $query->row['price'];
		}
		
		// Return
		return $price;
		
	}

// Get Option Increase
	public function getOptionIncrease($product_option_value_id) {
		
		// Setup SQL Statement
		$sql = "SELECT * FROM " . DB_PREFIX . "product_option_value
		WHERE product_option_value_id = ". (int)$product_option_value_id;
				
		// Get Options
		$query = $this->db->query($sql);
		
		// Return
		return $query->row;
		
	}			
	
// Get Option ID
	public function getOptionID($product_option_id) {
		
		// Setup SQL Statement
		$sql = "SELECT option_id FROM " . DB_PREFIX . "product_option
			WHERE product_option_id = ". (int)$product_option_id;
				
		// Get Options
		$query = $this->db->query($sql);
		
		// Get ID
		return $query->row['option_id'];
		
	}			
	
// Get Option Info
	public function getOptionInfo($option_id) {
		
		// Setup SQL Statement
		$sql = "SELECT * FROM `" . DB_PREFIX . "option`
			LEFT JOIN `" . DB_PREFIX . "option_description` ON " . DB_PREFIX . "option.option_id = " . DB_PREFIX . "option_description.option_id
			WHERE " . DB_PREFIX . "option.option_id = ". (int)$option_id;
				
		// Get Options
		$query = $this->db->query($sql);
		
		// Return
		return $query->row;
		
	}		
	
// Get Option Value
	public function getOptionValue($option_value_id) {
		
		// Setup SQL Statement
		$sql = "SELECT * FROM " . DB_PREFIX . "option_value_description
			WHERE option_value_id = ". (int)$option_value_id;
				
		// Get Options
		$query = $this->db->query($sql);
		
		// Return
		return $query->row;
		
	}
	
// Update Order Options
	public function updateOrderOption($order_option, $order_id, $order_product_id) {
		
		// Delete All Options For an Order Product
		$sql = "DELETE FROM " . DB_PREFIX . "order_option WHERE order_id = ". (int)$order_id ." AND order_product_id = ".(int)$order_product_id;
		
		// Delete
		$this->db->query($sql);	
		
		// Readd New Options
		foreach ($order_option as $option){
			$sql = "INSERT INTO " . DB_PREFIX . "order_option SET 
			order_id = ". (int)$order_id .",
			order_product_id = ". (int)$order_product_id .",
			product_option_id = ". (int)$option['product_option_id'] .",
			product_option_value_id = ". (int)$option['product_option_value_id'] .",
			name = '". $this->db->escape($option['name']) ."',
			value = '". $this->db->escape($option['value']) ."',
			type = '". $this->db->escape($option['type'])."'";
			
			$this->db->query($sql);
		}
	}
		
// Update Order Product
	public function updateOrderProduct($order_product) {
		
		// Setup SQL
		$sql = "UPDATE " . DB_PREFIX . "order_product SET 
			name = '".$this->db->escape($order_product['name'])."',
			model = '".$this->db->escape($order_product['model'])."',
			quantity = ".(int)$order_product['quantity'].",
			price = ". $order_product['price'].",
			total = ". $order_product['total'].",
			tax = ". $order_product['tax'].",
			reward = ". (int)$order_product['reward']."
			WHERE order_product_id = ". (int)$order_product['order_product_id'];
		
		// Update
		$this->db->query($sql);	
		
		
	}
	
// Get Order Total
	public function getOrderTotal($order_id) {
		
		// Setup SQL
		$sql = "SELECT SUM(total) FROM " . DB_PREFIX . "order_product 
			WHERE order_id = ". (int)$order_id;
		
		// Get Total
		$query = $this->db->query($sql);	
		
		return $query->row['SUM(total)'];				
	}
	
// Get All Order Product IDs
	public function getOrderProductIDs($order_id) {	
	
		// Setup SQL
		$sql = "SELECT product_id, total FROM " . DB_PREFIX . "order_product 
			WHERE order_id = ". (int)$order_id;
		
		// Get Total
		$query = $this->db->query($sql);	
		
		return $query->rows;
	}
	
// Get GeoZone
	public function getGeoZone($order_id, $geoType) {
		
if($geoType == "store"){
			// Get Store Zone
			$sql = "SELECT value FROM `" . DB_PREFIX ."setting` WHERE `group` = 'config' AND `key` = 'config_zone_id'";
			$query = $this->db->query($sql);
			$zone_id = $query->row['value'];
		}else{
			// Query Orders
			$sql = "SELECT * FROM `" . DB_PREFIX . "order` WHERE order_id = ". (int)$order_id;
			$query = $this->db->query($sql);			
			
			// Get Shipping Zone
			if($geoType == "shipping"){	
				$zone_id = $query->row['shipping_zone_id'];
			}
			
			// Get Billing Zone
			if($geoType == "billing"){
				$zone_id = $query->row['payment_zone_id'];				
			}
		}
		
		// Get Country ID
		$sql = "SELECT country_id FROM " . DB_PREFIX . "zone WHERE zone_id = " . (int)$zone_id;
		$query = $this->db->query($sql);
		$country_id = $query->row['country_id'];
		
		// Get Geo Zones
		$sql = "SELECT geo_zone_id FROM " . DB_PREFIX ."zone_to_geo_zone WHERE zone_id = ". (int)$zone_id ." OR zone_id = 0 AND country_id = ".(int)$country_id . " LIMIT 1";
		$query = $this->db->query($sql);
		
		// Return
		if ($query->row){
			return $query->row['geo_zone_id'];
		}else{
			return 0;
		}
	}
	
// Get Product Tax Class
	public function getProductTaxClass($product_id) {
		
		// Setup SQL Statement
		$sql = "SELECT tax_class_id FROM " . DB_PREFIX . "product
			WHERE product_id = ". (int)$product_id;
				
		// Get Class
		$query = $this->db->query($sql);
		
		// Return
		return $query->row['tax_class_id'];	
		
	}
	
// Get Product Tax Rates
	public function getProductTaxRates($product_tax_class,$customer_group_id) {
		
		// Setup SQL Statement
		$sql = "SELECT * FROM " . DB_PREFIX . "tax_rule 
		LEFT JOIN " . DB_PREFIX . "tax_rate ON " . DB_PREFIX . "tax_rule.tax_rate_id = " . DB_PREFIX . "tax_rate.tax_rate_id 
		LEFT JOIN " . DB_PREFIX . "tax_rate_to_customer_group ON " . DB_PREFIX . "tax_rate.tax_rate_id = " . DB_PREFIX . "tax_rate_to_customer_group.tax_rate_id 
		WHERE tax_class_id = ". (int)$product_tax_class ." AND customer_group_id = ". (int)$customer_group_id ." ORDER BY priority";
				
		// Get Class
		$query = $this->db->query($sql);
		
		// Return
		return $query->rows;	
		
	}
	
// Update Order Tax
	public function updateOrderTax($order_id, $taxes) {
		
		// Setup SQL
		$sql = "DELETE FROM " . DB_PREFIX . "order_total
			WHERE order_id = ". (int)$order_id ." AND code = 'tax'";
		
		// Delete Old Tax Values
		$this->db->query($sql);		
		
		// Get Tax Sort Order
		$tax_sort_order = $this->db->query("SELECT value FROM " . DB_PREFIX . "setting WHERE `group` = 'tax' AND `key` = 'tax_sort_order'");
		$tax_sort_order = $tax_sort_order->row['value'];
		
		// Write Tax Items
		foreach ($taxes as $tax_name => $tax_amount){
			
			// Round And Create Text Total
			$tax_amount_text = $this->currency->format($tax_amount, $this->config->get('config_currency'));
		
			// Setup SQL
			$sql = "INSERT INTO " . DB_PREFIX . "order_total SET
				`order_id` = ".(int)$order_id.",
				`code` = '".$this->db->escape('tax')."',			
				`title` = '".$this->db->escape($tax_name)."',
				`text` = '".$this->db->escape($tax_amount_text)."',
				`value` = ". (float)$tax_amount.",
				`sort_order` = ". (int)$tax_sort_order;
		
			// Insert
			$this->db->query($sql);									
		}		
	}
	
// Update Order Total
	public function updateOrderTotal($order_id, $total) {
		
		// Round And Create Text Total
		$total_text = $this->currency->format($total, $this->config->get('config_currency'));
		
		// Update Sub Total
		$sql = "UPDATE " . DB_PREFIX . "order_total SET 
			`text` = '".$this->db->escape($total_text)."',
			`value` = ". (float)$total."
			WHERE order_id = ". (int)$order_id." AND code = 'sub_total'";
		$this->db->query($sql);	
		
		// Get Additional Fees & Taxes
		$add_fees = $this->db->query("SELECT SUM(value) FROM `" . DB_PREFIX . "order_total` WHERE `code` <> 'sub_total' AND `code` <> 'total' AND order_id = ". (int)$order_id);
		$add_fees = $add_fees->row['SUM(value)'];
		$total = $total + $add_fees;
				
		// Round And Create Text Total
		$total_text = $this->currency->format($total, $this->config->get('config_currency'));		
		
		// Update Total
		$sql = "UPDATE " . DB_PREFIX . "order_total SET 
			`text` = '".$this->db->escape($total_text)."',
			`value` = ". (float)$total."
			WHERE order_id = ". (int)$order_id." AND code = 'total'";
		$this->db->query($sql);	
		
		// Update Order Total
		$sql = "UPDATE `" . DB_PREFIX . "order` SET 
			`total` = ". (float)$total."
			WHERE order_id = ". (int)$order_id;
		$this->db->query($sql);
		
		// Return Total
		return $total;
	}
	
// Update Shipping Total
	public function updateShippingTotal($data) {
		
		// Round And Create Text Total			
		$data['text'] = $this->currency->format($data['value'], $this->config->get('config_currency'));
		
		// Update Shipping Total
		$sql = "UPDATE " . DB_PREFIX . "order_total SET 
			`title` = '".$this->db->escape($data['title'])."',
			`text` = '".$this->db->escape($data['text'])."',
			`value` = ". (float)$data['value']."
			WHERE order_total_id = ". (int)$data['order_total_id'];
		$this->db->query($sql);
		
		// Update Order Shipping Method & Code
		$shipping_code = str_replace("-","_",strtolower(str_replace(" ",".",$data['title'])));
		
		$sql = "UPDATE `" . DB_PREFIX . "order` SET 
			`shipping_method` = '".$this->db->escape($data['title'])."',
			`shipping_code` = '".$this->db->escape($shipping_code)."'
			WHERE order_id = ". (int)$data['order_id'];
		$this->db->query($sql);
	
	}

// Get Order Totals
	public function getOrderTotals($order_id) {
		
		// Setup SQL
		$sql = "SELECT * FROM " . DB_PREFIX . "order_total 
			WHERE order_id = ". (int)$order_id."
			ORDER BY sort_order ASC";
		
		// Get Total
		$query = $this->db->query($sql);	
		
		return $query->rows;	
	
	}	

// Add Line Item
	public function addLineItem($data) {
		
		// Round And Create Text Total
		$data['text'] = $this->currency->format($data['value'], $this->config->get('config_currency'));
		
		// Setup SQL
		$sql = "INSERT INTO " . DB_PREFIX . "order_total SET
			`order_id` = ".(int)$data['order_id'].",
			`code` = '".$this->db->escape($data['code'])."',			
			`title` = '".$this->db->escape($data['title'])."',
			`text` = '".$this->db->escape($data['text'])."',
			`value` = ". (float)$data['value'].",
			`sort_order` = ". (int)$data['sort_order'];
		
		// Insert
		$this->db->query($sql);		
	}	
	
// Remove Line Item
	public function removeLineItem($order_total_id) {

		// Setup SQL
		$sql = "DELETE FROM " . DB_PREFIX . "order_total
			WHERE order_total_id = ". (int)$order_total_id;
		
		// Delete
		$this->db->query($sql);		
	}
	
// Get User Info
	public function getUserInfo($user_id) {

		// Setup SQL
		$sql = "SELECT * FROM " . DB_PREFIX . "user
			WHERE user_id = ". (int)$user_id;
		
		// Get Info
		$query = $this->db->query($sql);	
		
		return $query->row;	
	}
	
// Get Order Status
	public function getOrderStatusID($order_id) {

		// Setup SQL
		$sql = "SELECT order_status_id FROM `" . DB_PREFIX . "order`
			WHERE order_id = ". (int)$order_id;
		
		// Get Info
		$query = $this->db->query($sql);	
		
		return $query->row['order_status_id'];	
	}		

// Add History
	public function addHistory($order_id,$order_status_id,$history_info) {

		// Setup SQL
		$sql = "INSERT INTO " . DB_PREFIX . "order_history SET
			`order_id` = ".(int)$order_id.",
			`order_status_id` = '".(int)$order_status_id."',			
			`comment` = '".$this->db->escape($history_info)."',
			`date_added` = Now()";
		
		// Insert
		$this->db->query($sql);			
	}		
}
?>