<?php  
class ControllerCommonFooter extends Controller {

				function getRemarketingCode ($products, $view, $categories_name = "")
				{
					//Comprove if google remarketing code if correct
					$google_remarketing_code = explode("'REPLACE_WITH_VALUE'", $this->config->get('google_remarketing_code'));

					if (count($google_remarketing_code) != 4)
					{
						$this->data['google_remarketing_code'] = "<script type='text/javascript'> $( document ).ready(function() { alert('Error: The Google Dynamic Remarketing code is not correct') });</script>";
						return true;
					}

					$product_data = array();
					$total_products = '""';
					if ($products != "")
					{
						//-- GETS ALL INFO PRODUCTS IN CART
						foreach ($products as $product) 
						{
							$option_data = array();
							if (isset($product['option']))
							{
								foreach ($product['option'] as $option) {
									if ($option['type'] != 'file') {
										$value = $option['option_value'];	
									} else {
										$value = $this->encryption->decrypt($option['option_value']);
									}	
									
									$option_data[] = array(
										'product_option_id'       => $option['product_option_id'],
										'product_option_value_id' => $option['product_option_value_id'],
										'option_id'               => $option['option_id'],
										'option_value_id'         => $option['option_value_id'],								   
										'name'                    => $option['name'],
										'value'                   => $value,
										'type'                    => $option['type']
									);					
								}
							}

							//Fix to undefined variables 2014/09/29
					 			if (!isset($product['download'])) $product['download'] = '';
					 			if (!isset($product['price'])) $product['price'] = 0;
					 			if (!isset($product['tax_class_id'])) $product['tax_class_id'] = '';

				 			if (!isset($product['total']))
				 			{
				 				if (!empty($product['special']))
				 					$product['total'] = $this->tax->calculate($product['special'], $product['tax_class_id'], $this->config->get('config_tax'));
				 				else
				 					$product['total'] = $this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax'));
				 			}
				 			else
							{
								if (!empty($product['special']))
									$product['total'] = $this->tax->calculate($product['special'], $product['tax_class_id'], $this->config->get('config_tax'));
								else
									$product['total'] = $this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax'));
							}

							if (!isset($product['product_id']))
								$product['product_id'] = '';
							if (!isset($product['name']))
								$product['name'] = '';
							if (!isset($product['model']))
								$product['model'] = '';
							if (!isset($product['download']))
								$product['download'] = '';
							if (!isset($product['quantity']))
								$product['quantity'] = '';
							if (!isset($product['subtract']))
								$product['subtract'] = '';
							if (!isset($product['price']))
								$product['price'] = '';
							if (!isset($product['total']))
								$product['total'] = '';
							if (!isset($product['tax_class_id']))
								$product['tax_class_id'] = '';
							if (!isset($product['reward']))
								$product['reward'] = '';

							$product_data[] = array(
								'product_id' => $this->config->get('google_remarketing_id_preffix').$product['product_id'].$this->config->get('google_remarketing_id_suffix'),
								'name'       => $product['name'],
								'model'      => $product['model'],
								'option'     => $option_data,
								'download'   => $product['download'],
								'quantity'   => $product['quantity'],
								'subtract'   => $product['subtract'],
								'price'      => $product['price'],
								'total'      => $product['total'],
								'tax'        => $this->tax->getTax($product['price'], $product['tax_class_id']),
								'reward'     => $product['reward']
							); 
						}
						$total_price = 0;
						foreach ($product_data as $key => $product)
						{
							if ($key == 0) $total_price = 0;
							$total_price += $product['total'];
						}
						if ($total_price == 0) $total_price = '';
						else $total_products = number_format($total_price, 2, '.', '');
					}

					

					//-- GETS STRING JQUERY ARRAY ID TO GOOGLE CODE
					$array_id = '""';
					if (!empty($product_data))
					{
						$array_id = '';
						if (count($product_data) > 1)
							$array_id = '[';

						foreach ($product_data as $product)
						{
							$array_id .= '"';
							$array_id .= $product['product_id'];
							$array_id .= '",';
						}
						$array_id = substr($array_id, 0, -1);
						
						if (count($product_data) > 1)
							$array_id .= ']';

					}

					//Add obtained data to google remarketing code.
					$temp_google_remarketing_code_1 = explode("{", $this->config->get('google_remarketing_code'));
					$temp_google_remarketing_code_2 = explode("}", $this->config->get('google_remarketing_code'));

					$google_remarketing_code_1 = $temp_google_remarketing_code_1[0].'{';
					$google_remarketing_code_2 = '}'.$temp_google_remarketing_code_2[1];

					$params = "";

					if ($view != "category")
					{
						$params .= "\n"."\t\t"."ecomm_prodid: ".$array_id.','."\n";
						$params .= "\t\t"."ecomm_totalvalue: ".$total_products.','."\n";
					}
					else
					{
						$categories_name_temp = '""';
						if (!empty($categories_name))
						{
							if (count($categories_name) == 1)
								$categories_name_temp = '"'.$categories_name[0].'"';
							else
							{
								$categories_name_temp = "[";
								foreach ($categories_name as $key=>$cat_name)
								{
									$categories_name_temp .= '"'.$cat_name.'"';
									if (($key+1) != count($categories_name))
										$categories_name_temp .= ",";
								}
								$categories_name_temp .= "]";
							}
						}
						$params .= "\n"."\t\t"."ecomm_pcat: ".$categories_name_temp.','."\n";
					}

					$params .= "\t\t".'ecomm_pagetype: "'.$view.'"'."\n";

					$this->data['google_remarketing_code'] = html_entity_decode($google_remarketing_code_1.$params.$google_remarketing_code_2);
					return true;
				}
			
	protected function index() {
		$this->language->load('common/footer');

		$this->data['text_information'] = $this->language->get('text_information');
		$this->data['text_service'] = $this->language->get('text_service');
		$this->data['text_extra'] = $this->language->get('text_extra');
		$this->data['text_contact'] = $this->language->get('text_contact');
		$this->data['text_return'] = $this->language->get('text_return');
		$this->data['text_sitemap'] = $this->language->get('text_sitemap');
		$this->data['text_manufacturer'] = $this->language->get('text_manufacturer');
		$this->data['text_voucher'] = $this->language->get('text_voucher');
		$this->data['text_affiliate'] = $this->language->get('text_affiliate');
		$this->data['text_special'] = $this->language->get('text_special');
		$this->data['text_account'] = $this->language->get('text_account');
		$this->data['text_order'] = $this->language->get('text_order');
		$this->data['text_wishlist'] = $this->language->get('text_wishlist');
		$this->data['text_newsletter'] = $this->language->get('text_newsletter');

		$this->load->model('catalog/information');

		$this->data['informations'] = array();

		foreach ($this->registry->get('config')->get('config_template') === 'journal2' ? array() : $this->model_catalog_information->getInformations() as $result) {
			if ($result['bottom']) {
				$this->data['informations'][] = array(
					'title' => $result['title'],
					'href'  => $this->url->link('information/information', 'information_id=' . $result['information_id'])
				);
			}
		}

		$this->data['contact'] = $this->url->link('information/contact');
		$this->data['return'] = $this->url->link('account/return/insert', '', 'SSL');
		$this->data['sitemap'] = $this->url->link('information/sitemap');
		$this->data['manufacturer'] = $this->url->link('product/manufacturer');
		$this->data['voucher'] = $this->url->link('account/voucher', '', 'SSL');
		$this->data['affiliate'] = $this->url->link('affiliate/account', '', 'SSL');
		$this->data['special'] = $this->url->link('product/special');
		$this->data['account'] = $this->url->link('account/account', '', 'SSL');
		$this->data['order'] = $this->url->link('account/order', '', 'SSL');
		$this->data['wishlist'] = $this->url->link('account/wishlist', '', 'SSL');
		$this->data['newsletter'] = $this->url->link('account/newsletter', '', 'SSL');		

		$this->data['powered'] = sprintf($this->language->get('text_powered'), $this->config->get('config_name'), date('Y', time()));

		// Whos Online
		if ($this->config->get('config_customer_online')) {
			$this->load->model('tool/online');

			if (isset($this->request->server['REMOTE_ADDR'])) {
				$ip = $this->request->server['REMOTE_ADDR'];	
			} else {
				$ip = ''; 
			}

			if (isset($this->request->server['HTTP_HOST']) && isset($this->request->server['REQUEST_URI'])) {
				$url = 'http://' . $this->request->server['HTTP_HOST'] . $this->request->server['REQUEST_URI'];	
			} else {
				$url = '';
			}

			if (isset($this->request->server['HTTP_REFERER'])) {
				$referer = $this->request->server['HTTP_REFERER'];	
			} else {
				$referer = '';
			}

			$this->model_tool_online->whosonline($ip, $this->customer->getId(), $url, $referer);
		}		

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/footer.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/common/footer.tpl';
		} else {
			$this->template = 'default/template/common/footer.tpl';
		}


				$this->load->model('setting/setting');

				//IS ENABLED??

				if($this->config->get('google_remarketing_status'))
				{
					//DYNAMIC TYPE 
					if($this->config->get('google_remarketing_type') == 0)
					{
						//-- CASE HOME VIEW
						if (!isset($this->request->get["route"]))
						{					
							$this->getRemarketingCode('', 'home');
						}

						
						//-- CASE HOME VIEW
						elseif (isset($this->request->get["route"]) && $this->request->get["route"] == "common/home")
						{							
							$this->getRemarketingCode('', 'home');
						}


						//-- CASE PRODUCT VIEW
						elseif (isset($this->request->get["route"]) && $this->request->get["route"] == "product/product")
						{		
							$product_info = $this->model_catalog_product->getProduct($this->request->get["product_id"]);
							$product_info_temp = array();
							$product_info_temp[] = $product_info;
							$this->getRemarketingCode($product_info_temp, 'product');
						}

						//-- CASE CART VIEW
						elseif (isset($this->request->get["route"]) && $this->request->get["route"] == "checkout/checkout" || $this->request->get["route"] == "checkout/cart" || $this->request->get["route"] == "supercheckout/supercheckout")
						{		
							$this->getRemarketingCode($this->cart->getProducts(), 'cart');					
						}

						
						//-- CASE PURCHASE VIEW
						elseif (isset($this->request->get["route"]) && $this->request->get["route"] == "checkout/success")
						{
							if($this->cart->countProducts() > 0)
							{
								$this->getRemarketingCode($this->cart->getProducts(), 'purchase');
								$this->cart->clear();
							}
						}
						

						//-- CASE CATEGORY VIEW
						elseif (isset($this->request->get["route"]) && $this->request->get["route"] == "product/category")
						{
							$category_names = array();
							if (isset($this->request->get['path'])) {

								$path = '';

								$parts = explode('_', (string)$this->request->get['path']);

								foreach ($parts as $path_id) {
									if (!$path) {
										$path = $path_id;
									} else {
										$path .= '_' . $path_id;
									}

									$category_info = $this->model_catalog_category->getCategory($path_id);

									if ($category_info) {
										$category_names[] = $category_info['name'];
									}
								}
							}

							$this->getRemarketingCode('', 'category', $category_names);
						}

						//-- CASE MANUFACTURER VIEW
						elseif (isset($this->request->get["route"]) && $this->request->get["route"] == "product/manufacturer/info")
						{
							$manufacturers_names = array();
							if (isset($this->request->get['manufacturer_id'])) {
								$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($this->request->get['manufacturer_id']);

								$manufacturers_names[] = $manufacturer_info['name'];
							}
							$this->getRemarketingCode('', 'category', $manufacturers_names);
						}

						//-- CASE SEARCHRESULT VIEW
						elseif (isset($this->request->get["route"]) && $this->request->get["route"] == "product/search")
						{
							
							//-- GET ALL PRODUCTS FILTERED
								if (isset($this->request->get['search']) || isset($this->request->get['tag'])) {
									if (isset($this->request->get['search'])) {
										$search = $this->request->get['search'];
									} else {
										$search = '';
									} 

									if (isset($this->request->get['tag'])) {
										$tag = $this->request->get['tag'];
									} elseif (isset($this->request->get['search'])) {
										$tag = $this->request->get['search'];
									} else {
										$tag = '';
									} 

									if (isset($this->request->get['description'])) {
										$description = $this->request->get['description'];
									} else {
										$description = '';
									} 

									if (isset($this->request->get['category_id'])) {
										$category_id = $this->request->get['category_id'];
									} else {
										$category_id = 0;
									} 

									if (isset($this->request->get['sub_category'])) {
										$sub_category = $this->request->get['sub_category'];
									} else {
										$sub_category = '';
									} 

									if (isset($this->request->get['sort'])) {
										$sort = $this->request->get['sort'];
									} else {
										$sort = 'p.sort_order';
									} 

									if (isset($this->request->get['order'])) {
										$order = $this->request->get['order'];
									} else {
										$order = 'ASC';
									}

									if (isset($this->request->get['page'])) {
										$page = $this->request->get['page'];
									} else {
										$page = 1;
									}

									if (isset($this->request->get['limit'])) {
										$limit = $this->request->get['limit'];
									} else {
										$limit = $this->config->get('config_catalog_limit');
									}

									if (isset($this->request->get['search'])) {
										$this->document->setTitle($this->language->get('heading_title') .  ' - ' . $this->request->get['search']);
									} else {
										$this->document->setTitle($this->language->get('heading_title'));
									}
									$data = array(
										'filter_name'         => $search, 
										'filter_tag'          => $tag, 
										'filter_description'  => $description,
										'filter_category_id'  => $category_id, 
										'filter_sub_category' => $sub_category, 
										'sort'                => $sort,
										'order'               => $order,
										'start'               => ($page - 1) * $limit,
										'limit'               => $limit
									);

									$results = $this->model_catalog_product->getProducts($data);
					
									$this->getRemarketingCode($results, 'searchresults');
								}
							//-- END GET ALL PRODUCTS FILTERED
								else
								{
									$this->getRemarketingCode('', 'searchresults');
								}
						}
						

						//-- CASE OTHER VIEWS
						else
						{
							$this->getRemarketingCode('', 'other');
						}
					
						
					} //END DYNAMIC TYPE
					else //STANDARD TYPE
					{
						$this->data['google_remarketing_code'] = html_entity_decode($this->config->get('google_remarketing_code'));
					}
				}				
			
		$this->render();
	}
}
?>