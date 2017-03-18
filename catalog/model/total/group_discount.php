<?php
class ModelTotalGroupDiscount extends Model {
	public function getTotal(&$total_data, &$total, &$taxes) {
		$this->load->language('total/group_discount');
		
        $discounts = $this->config->get('group_discount');
        $sortOrder = $this->config->get('group_discount_sort_order');
        if(empty($sortOrder)){
            $sortOrder = 0;
        }
        if(!is_array($discounts)){
            $discounts = array();
        }

        $subTotal = $this->cart->getSubTotal();

        $products = $this->cart->getProducts();
        
        $productList = array();
        foreach($products as $productId => $productInfo)
        {
            for($i=0;$i<$productInfo['quantity'];$i++)
            {
                $productList[] = $productId;
            }
        }
        usort($discounts, array($this, 'sortDiscounts'));

        $discountPrice = 0;
        foreach($discounts as $discount)
        {
        	$keyA = $this->buildKey($discount, 'productA');
        	$keyB = $this->buildKey($discount, 'productB');
        	$keyC = $this->buildKey($discount, 'productC');
        	$keyD = $this->buildKey($discount, 'productD');
        	
        	$optionA = isset($discount['productAoption'])?$discount['productAoption']:array();
        	$optionB = isset($discount['productBoption'])?$discount['productBoption']:array();
        	$optionC = isset($discount['productCoption'])?$discount['productCoption']:array();
        	$optionD = isset($discount['productDoption'])?$discount['productDoption']:array();
        	
            do{
                $found = false;
                if(isset($discount['productD'])&&$discount['productD']>0)
                {
                    if(in_array($keyA, $productList)
                    &&in_array($keyB, $productList)
                    &&in_array($keyC, $productList)
                    &&in_array($keyD, $productList))
                    {
                        unset($productList[array_search($keyA, $productList)]);
                        unset($productList[array_search($keyB, $productList)]);
                        unset($productList[array_search($keyC, $productList)]);
                        unset($productList[array_search($keyD, $productList)]);
                        $price = $this->getPrice($products[$keyA]['product_id'], $optionA);
                        $price += $this->getPrice($products[$keyB]['product_id'], $optionB);
                        $price += $this->getPrice($products[$keyC]['product_id'], $optionC);
                        $price += $this->getPrice($products[$keyD]['product_id'], $optionD);
                        $discountPrice += $this->getDiscount($discount, $price);
                        $found = true;
                    }
                }
                elseif($discount['productC']>0)
                {
                    if(in_array($keyA, $productList)
                    &&in_array($keyB, $productList)
                    &&in_array($keyC, $productList))
                    {
                        unset($productList[array_search($keyA, $productList)]);
                        unset($productList[array_search($keyB, $productList)]);
                        unset($productList[array_search($keyC, $productList)]);
                        $price = $this->getPrice($products[$keyA]['product_id'], $optionA);
                        $price += $this->getPrice($products[$keyB]['product_id'], $optionB);
                        $price += $this->getPrice($products[$keyC]['product_id'], $optionC);
                        $discountPrice += $this->getDiscount($discount, $price);
                        $found = true;
                    }
                }
                else
                {
                    if(in_array($keyA, $productList)
                    &&in_array($keyB, $productList))
                    {
                        unset($productList[array_search($keyA, $productList)]);
                        unset($productList[array_search($keyB, $productList)]);
                        $price = $this->getPrice($products[$keyA]['product_id'], $optionA);
                        $price += $this->getPrice($products[$keyB]['product_id'], $optionB);
                        $discountPrice += $this->getDiscount($discount, $price);
                        $found = true;
                    }
                }
            }while($found);
        }
        
        $discountPrice += $this->getCategoryDiscount($products, $productList);

		if ($discountPrice>0){

			$total_data[] = array(
				'code'       => 'group_discount',
				'title'      => $this->language->get('text_discount') ,
				'text'       => '<span style="color: #990000; font-weight:bold">-' . $this->currency->format($discountPrice) . '</span>',
				'value'      => $discountPrice,
				'sort_order' => $sortOrder
			);

			$total -= $discountPrice;
		}
	}
	
	private function buildKey($discount, $productType){
		if(!isset($discount[$productType])||$discount[$productType]==''){
			return '::';
		}
		$options = false;
		if(isset($discount[$productType . 'option'])){
			$options = $discount[$productType . 'option'];
			ksort($options);
			$options = base64_encode(serialize($options));
		}
		if(version_compare(VERSION, '1.5.6', 'lt')){
			return $this->buildKeyLs156($discount[$productType], $options);
		}
		return $this->buildKey156($discount[$productType], $options);
	}
	
	private function buildKeyProductId($productId)
	{
		$options = false;
		if(version_compare(VERSION, '1.5.6', 'lt')){
			return $this->buildKeyLs156($productId, $options);
		}
		return $this->buildKey156($productId, $options);
	}
	
	private function buildKey156($product, $options)
	{
		$result = $product . ':';
		if($options)
		{
			$result .= $options;
		}
		$result .=  ':';
		return $result;
	}
	
	private function buildKeyLs156($product, $options)
	{
		$result = $product;
		if($options)
		{
			$result .= ':' . $options;
		}
		return $result;
	}
	
    private function getNumber($item){
        if(isset($item['productD'])){
            return 2;
        }
        if(isset($item['productC'])){
            return 1;
        }
        return 0;
    }

    function sortDiscounts($a, $b){
        $result = $this->getNumber($a) - $this->getNumber($b);
        if($result>0){
            return 1;
        }
        return $result<0?-1:0;
    }

    private function getDiscount($discount, $price)
    {
        if($discount['discountType']=='percent')
        {
            $discount = $price * ($discount['discount']/100);
        }
        else
        {
            $discount = $discount['discount'];
        }

        return $discount;
    }

    private function formatPrice($price)
    {
        return $this->currency->format($price);
    }
    
    public function getPrice($product_id, $options = array(), $profile_id = 0)
    {
    	$product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.date_available <= NOW() AND p.status = '1'");
    
    	if ($product_query->num_rows) {
    		$option_price = 0;
    		$option_data = array();
    		foreach ($options as $product_option_id => $option_value) {
    			$option_query = $this->db->query("SELECT po.product_option_id, po.option_id, od.name, o.type FROM " . DB_PREFIX . "product_option po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE po.product_option_id = '" . (int)$product_option_id . "' AND po.product_id = '" . (int)$product_id . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "'");
    
    			if ($option_query->num_rows) {
    				if ($option_query->row['type'] == 'select' || $option_query->row['type'] == 'radio' || $option_query->row['type'] == 'image') {
    					$option_value_query = $this->db->query("SELECT pov.option_value_id, ovd.name, pov.quantity, pov.subtract, pov.price, pov.price_prefix, pov.points, pov.points_prefix, pov.weight, pov.weight_prefix FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_option_value_id = '" . (int)$option_value . "' AND pov.product_option_id = '" . (int)$product_option_id . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
    
    					if ($option_value_query->num_rows) {
    						if ($option_value_query->row['price_prefix'] == '+') {
    							$option_price += $option_value_query->row['price'];
    						} elseif ($option_value_query->row['price_prefix'] == '-') {
    							$option_price -= $option_value_query->row['price'];
    						}
    
    					}
    				} elseif ($option_query->row['type'] == 'checkbox' && is_array($option_value)) {
    					foreach ($option_value as $product_option_value_id) {
    						$option_value_query = $this->db->query("SELECT pov.option_value_id, ovd.name, pov.quantity, pov.subtract, pov.price, pov.price_prefix, pov.points, pov.points_prefix, pov.weight, pov.weight_prefix FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_option_value_id = '" . (int)$product_option_value_id . "' AND pov.product_option_id = '" . (int)$product_option_id . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
    							
    						if ($option_value_query->num_rows) {
    							if ($option_value_query->row['price_prefix'] == '+') {
    								$option_price += $option_value_query->row['price'];
    							} elseif ($option_value_query->row['price_prefix'] == '-') {
    								$option_price -= $option_value_query->row['price'];
    							}
    
    							$option_data[] = array(
    									'product_option_id'       => $product_option_id,
    									'product_option_value_id' => $product_option_value_id,
    									'option_id'               => $option_query->row['option_id'],
    									'option_value_id'         => $option_value_query->row['option_value_id'],
    									'name'                    => $option_query->row['name'],
    									'option_value'            => $option_value_query->row['name'],
    									'type'                    => $option_query->row['type'],
    									'quantity'                => $option_value_query->row['quantity'],
    									'subtract'                => $option_value_query->row['subtract'],
    									'price'                   => $option_value_query->row['price'],
    									'price_prefix'            => $option_value_query->row['price_prefix'],
    									'points'                  => $option_value_query->row['points'],
    									'points_prefix'           => $option_value_query->row['points_prefix'],
    									'weight'                  => $option_value_query->row['weight'],
    									'weight_prefix'           => $option_value_query->row['weight_prefix']
    							);
    						}
    					}
    				} elseif ($option_query->row['type'] == 'text' || $option_query->row['type'] == 'textarea' || $option_query->row['type'] == 'file' || $option_query->row['type'] == 'date' || $option_query->row['type'] == 'datetime' || $option_query->row['type'] == 'time') {
    					$option_data[] = array(
    							'product_option_id'       => $product_option_id,
    							'product_option_value_id' => '',
    							'option_id'               => $option_query->row['option_id'],
    							'option_value_id'         => '',
    							'name'                    => $option_query->row['name'],
    							'option_value'            => $option_value,
    							'type'                    => $option_query->row['type'],
    							'quantity'                => '',
    							'subtract'                => '',
    							'price'                   => '',
    							'price_prefix'            => '',
    							'points'                  => '',
    							'points_prefix'           => '',
    							'weight'                  => '',
    							'weight_prefix'           => ''
    					);
    				}
    			}
    		}
    
    		if ($this->customer->isLogged()) {
    			$customer_group_id = $this->customer->getCustomerGroupId();
    		} else {
    			$customer_group_id = $this->config->get('config_customer_group_id');
    		}
    			
    		$price = $product_query->row['price'];
    			
    		// Product Specials
    		$product_special_query = $this->db->query("SELECT price FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product_id . "' AND customer_group_id = '" . (int)$customer_group_id . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY priority ASC, price ASC LIMIT 1");
    
    		if ($product_special_query->num_rows) {
    			$price = $product_special_query->row['price'];
    		}
    		
    		return $price + $option_price;
    	}
    	return 0;
    }

    private function prepareCategories($productId){
        $productCategories = $this->model_catalog_product->getCategories($productId);
        $result = array();
        foreach($productCategories as $category){
            $result[] = $category['category_id'];
        }
        return $result;
    }

    function getCategoryDiscount($products, $productList){
        $this->load->model('catalog/product');
        
        $categoryDiscounts = $this->config->get('category_discount');
        if(empty($categoryDiscounts))
        {
            $categoryDiscounts = array();
        }

        $discountPrice = 0;
        $productsTested = array();
        while(true){
            $productId = 0;
            foreach($productList as $_productId){
                if(!in_array($_productId, $productsTested)){
                    $productId = $_productId;
                    unset($productList[array_search($productId, $productList)]);
                    break;
                }
            }
            if(!$productId){
                break;
            }
            $categories = $this->prepareCategories($productId);
            $discount = false;
            foreach($categoryDiscounts as $_discount){
                if(in_array($_discount['categoryId'], $categories)){
                    $discount = $_discount;
                    break;
                }
            }
            $found = false;
            if($discount){
            	
            	$keyA = $productId;
            	$keyB = $this->buildKey($discount, 'productB');
            	$keyC = $this->buildKey($discount, 'productC');
            	$keyD = $this->buildKey($discount, 'productD');
            	 
            	$optionA = array();
            	$optionB = isset($discount['productBoption'])?$discount['productBoption']:array();
            	$optionC = isset($discount['productCoption'])?$discount['productCoption']:array();
            	$optionD = isset($discount['productDoption'])?$discount['productDoption']:array();
            	
                if(isset($discount['productD'])&&$discount['productD']>0)
                {
                    if(in_array($keyB, $productList)
                    &&in_array($keyC, $productList)
                    &&in_array($keyD, $productList))
                    {
                        unset($productList[array_search($keyB, $productList)]);
                        unset($productList[array_search($keyC, $productList)]);
                        unset($productList[array_search($keyD, $productList)]);
                        $price = $this->getPrice($products[$keyA]['product_id'], $optionA);
                        $price += $this->getPrice($products[$keyB]['product_id'], $optionB);
                        $price += $this->getPrice($products[$keyC]['product_id'], $optionC);
                        $price += $this->getPrice($products[$keyD]['product_id'], $optionD);
                        $discountPrice += $this->getDiscount($discount, $price);
                        $found = true;
                    }
                }
                elseif($discount['productC']>0)
                {
                    if(in_array($keyB, $productList)
                    &&in_array($keyC, $productList))
                    {
                        unset($productList[array_search($keyB, $productList)]);
                        unset($productList[array_search($keyC, $productList)]);
                        $price = $this->getPrice($products[$keyA]['product_id'], $optionA);
                        $price += $this->getPrice($products[$keyB]['product_id'], $optionB);
                        $price += $this->getPrice($products[$keyC]['product_id'], $optionC);
                        $discountPrice += $this->getDiscount($discount, $price);
                        $found = true;
                    }
                }
                elseif(in_array($keyB, $productList))
                {
                    unset($productList[array_search($keyB, $productList)]);
                    $price = $this->getPrice($products[$keyA]['product_id'], $optionA);
                    $price += $this->getPrice($products[$keyB]['product_id'], $optionB);
                    $discountPrice += $this->getDiscount($discount, $price);
                    $found = true;
                }
            }
            if(!$found){
                $productsTested[] = $productId;
                $productList[] = $productId;
            }
        }

        return $discountPrice;
    }
}
