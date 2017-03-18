<?php 

                /* Add DDW Front end Dependencies */
                include_once(rtrim(DIR_SYSTEM, "/")."/library/deliverydateswizard/bootstrap.php");
            
            
class ControllerCheckoutShippingMethod extends Controller {

                /** @var ModelDeliveryDatesWizardDeliveryDatesWizard */
                public $ddwModel;
            
            
  	public function index() {
		$this->language->load('checkout/checkout');
		
		$this->load->model('account/address');
		
		if ($this->customer->isLogged() && isset($this->session->data['shipping_address_id'])) {					
			$shipping_address = $this->model_account_address->getAddress($this->session->data['shipping_address_id']);		
		} elseif (isset($this->session->data['guest'])) {
			$shipping_address = $this->session->data['guest']['shipping'];
		}
		
		if (!empty($shipping_address)) {
			// Shipping Methods
			$quote_data = array();
			
			$this->load->model('setting/extension');
			
			$results = $this->model_setting_extension->getExtensions('shipping');
			
			foreach ($results as $result) {
				if ($this->config->get($result['code'] . '_status')) {
					$this->load->model('shipping/' . $result['code']);
					
					$quote = $this->{'model_shipping_' . $result['code']}->getQuote($shipping_address); 
		
					if ($quote) {
// Clear Thinking: restrict_shipping.xml
				if ($this->config->get('restrict_shipping_status') && isset($this->session->data['restrict_shipping'])) {
					foreach ($quote['quote'] as $index => $restricting_quote) {
						foreach ($this->session->data['restrict_shipping'] as $extension => $rules) {
							if ($extension != $result['code']) continue;
							foreach ($rules as $comparison => $values) {
								$adjusted_title = explode('(', $restricting_quote['title']);
								$adjusted_title = strtolower(html_entity_decode(trim($adjusted_title[0]), ENT_QUOTES, 'UTF-8'));
								if (($comparison == 'is' && in_array($adjusted_title, $values)) || ($comparison == 'not' && !in_array($adjusted_title, $values))) {
									unset($quote['quote'][$index]);
								}
							}
						}
					}
					if (empty($quote['quote'])) {
						continue;
					}
				}
				// end: restrict_shipping.xml
						$quote_data[$result['code']] = array( 
							'title'      => $quote['title'],
							'quote'      => $quote['quote'], 
							'sort_order' => $quote['sort_order'],
							'error'      => $quote['error']
						);
					}
				}
			}
	
			$sort_order = array();
		  
			foreach ($quote_data as $key => $value) {
				$sort_order[$key] = $value['sort_order'];
			}
	
			array_multisort($sort_order, SORT_ASC, $quote_data);
			
			$this->session->data['shipping_methods'] = $quote_data;
		}
					
		$this->data['text_shipping_method'] = $this->language->get('text_shipping_method');
		$this->data['text_comments'] = $this->language->get('text_comments');
	
		$this->data['button_continue'] = $this->language->get('button_continue');
		
		if (empty($this->session->data['shipping_methods'])) {
			$this->data['error_warning'] = sprintf($this->language->get('error_no_shipping'), $this->url->link('information/contact'));
		} else {
			$this->data['error_warning'] = '';
		}	
					
		if (isset($this->session->data['shipping_methods'])) {
			$this->data['shipping_methods'] = $this->session->data['shipping_methods']; 
		} else {
			$this->data['shipping_methods'] = array();
		}
		
		if (isset($this->session->data['shipping_method']['code'])) {
			$this->data['code'] = $this->session->data['shipping_method']['code'];
		} else {
			$this->data['code'] = '';
		}
		
		if (isset($this->session->data['okazaki'])) {
			$this->data['okazaki'] = $this->session->data['okazaki'];
			} else {
				$this->data['okazaki'] = '';
		}
		
		if (isset($this->session->data['anjochiryu'])) {
			$this->data['anjochiryu'] = $this->session->data['anjochiryu'];
			} else {
				$this->data['anjochiryu'] = '';
		}
		
		if (isset($this->session->data['toyota'])) {
			$this->data['toyota'] = $this->session->data['toyota'];
			} else {
				$this->data['toyota'] = '';
		}
		
		if (isset($this->session->data['nishiotakahamahekinan'])) {
			$this->data['nishiotakahamahekinan'] = $this->session->data['nishiotakahamahekinan'];
			} else {
				$this->data['nishiotakahamahekinan'] = '';
		}
		
		if (isset($this->session->data['comment'])) {
			$this->data['comment'] = $this->session->data['comment'];
		} else {
			$this->data['comment'] = '';
		}

                $ddw_controller = new DDWLibController($this->registry);
                $ddw_controller->render_widget($this);
            
            
			
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/shipping_method.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/checkout/shipping_method.tpl';
		} else {
			$this->template = 'default/template/checkout/shipping_method.tpl';
		}
		
		$this->response->setOutput($this->render());
  	}

                /**
                 * @var $blockedDates Array Of DDWBlockedDate
                 * @var $date Timestamp
                 * @return boolean
                 */
                private function _isDateBlocked($blockedDates, $date) {
                    $blocked = false;

                    /* Weekdays Blocked check */
                    $current_week_day = date("w", $date);
                    if (in_array($current_week_day, explode(",",$this->ddwModel->ddw->weekdays))) return true;
                    else return false;

                    if (!is_array($blockedDates)) return false;

                    foreach($blockedDates as $key=>$blocked_date) {
                        if ($blocked_date->type == DDWDateType::Single) {
                            $y = date('Y', strtotime($blocked_date->date_start));
                            $m = date('m', strtotime($blocked_date->date_start));
                            $d = date('d', strtotime($blocked_date->date_start));
                            $blocked_date->date_end = date('Y-m-d H:i:s',strtotime("$y-$m-$d 23:59:59"));
                        }

                        $timestamp_start = strtotime($blocked_date->date_start);
                        $timestamp_end = strtotime($blocked_date->date_end);

                        if ($date >= $timestamp_start && $date <= $timestamp_end) {
                            $blocked = true;
                        }

                        /* Recurring block check */
                        if ($blocked_date->recurring) {
                            $recurring_timestamp_start = date('Y-m-d H:i:s', strtotime(
                                date('Y')."-".date("m", $timestamp_start)."-".date("d", $timestamp_start)." 00:00:00"
                            ));
                            $recurring_timestamp_end = date('Y-m-d H:i:s', strtotime(
                                date('Y')."-".date("m", $timestamp_end)."-".date("d", $timestamp_end)." 23:59:00"
                            ));
                            if ($date >= $recurring_timestamp_start && $date <= $recurring_timestamp_end) $blocked = true;
                        }

                        return $blocked;
                    }
                }

                public function DDW_GetBlockedDates() {
                        $min_days = 0;
                        $start_date = date('Y-m-d');
                        $calendar_blocked_dates = array(); //of DDWCalendarBlockedDates

                        if (!isset($this->request->post['shipping_method_code'])) $shipping_method_code = "";
                        else $shipping_method_code = $this->request->post['shipping_method_code'];

                        $this->load->model('deliverydateswizard/deliverydateswizard');
                        $this->ddwModel = $this->model_deliverydateswizard_deliverydateswizard;
                        $this->ddwModel->load($shipping_method_code);

                        /* If no settings defined, load settings for "all" */
                        if ($this->ddwModel->ddw->shipping_method_code == "") {
                            $this->ddwModel->load("");
                                $shipping_method_code = "";
                        }

                        $min_days = $this->ddwModel->ddw->min_days;
                        $blockedDates = $this->ddwModel->getBlockedDates($shipping_method_code);

                        /* Determine if cut off time requires Min Days to be blocked from today onwards  */
                        if ($this->ddwModel->ddw->cut_off_time_enabled == 1) {
                            $hours = date("H");
                            $minutes = date("i");
                            if ($hours >= $this->ddwModel->ddw->cut_off_time_hours && $minutes > $this->ddwModel->ddw->cut_off_time_minutes)
                                $min_days ++;
                        }

                        /* Block all days up to min_days from order date (today) */
                        for ($i=0; $i<$min_days; $i++) {
                            $loop_date = strtotime("+$i day", strtotime($start_date));
                            $calendarBlockedDate = new DDWCalendarBlockedDate();
                            $calendarBlockedDate->date = date('Y-m-d', $loop_date);
                            $calendarBlockedDate->blocked = true;
                            $calendar_blocked_dates[] = $calendarBlockedDate;
                        }

                        /* Loop through dates */
                        for ($i=0;$i<30;$i++) {
                            $loop_date = strtotime("+$i day", strtotime($start_date));
                            if ($this->_isDateBlocked($blockedDates, $loop_date)) {
                                $calendarBlockedDate = new DDWCalendarBlockedDate();
                                $calendarBlockedDate->date = date('Y-m-d', $loop_date);
                                $calendarBlockedDate->blocked = true;
                                $calendar_blocked_dates[] = $calendarBlockedDate;
                            }
                        }

                        /* If blocked */
                        if ($this->ddwModel->ddw->enabled != 1) {
                            $calendar_blocked_dates = array();
                            $calendar_blocked_dates['enabled'] = false;
                        }

                        print json_encode($calendar_blocked_dates);
                    }
            
            
	
	public function validate() {
		$this->language->load('checkout/checkout');
		
		$json = array();		
		
		// Validate if shipping is required. If not the customer should not have reached this page.
		if (!$this->cart->hasShipping()) {
			$json['redirect'] = $this->url->link('checkout/checkout', '', 'SSL');
		}
		
		// Validate if shipping address has been set.		
		$this->load->model('account/address');

		if ($this->customer->isLogged() && isset($this->session->data['shipping_address_id'])) {					
			$shipping_address = $this->model_account_address->getAddress($this->session->data['shipping_address_id']);		
		} elseif (isset($this->session->data['guest'])) {
			$shipping_address = $this->session->data['guest']['shipping'];
		}
		
		if (empty($shipping_address)) {								
			$json['redirect'] = $this->url->link('checkout/checkout', '', 'SSL');
		}
		
		// Validate cart has products and has stock.	
		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$json['redirect'] = $this->url->link('checkout/cart');				
		}	
		
		// Validate minimum quantity requirments.			
		$products = $this->cart->getProducts();
				
		foreach ($products as $product) {
			$product_total = 0;
				
			foreach ($products as $product_2) {
				if ($product_2['product_id'] == $product['product_id']) {
					$product_total += $product_2['quantity'];
				}
			}		
			
			if ($product['minimum'] > $product_total) {
				$json['redirect'] = $this->url->link('checkout/cart');
				
				break;
			}				
		}
				
		if (!$json) {
			if (!isset($this->request->post['shipping_method'])) {
				$json['error']['warning'] = $this->language->get('error_shipping');
			} else {
				$shipping = explode('.', $this->request->post['shipping_method']);
				$okazaki_temp  = '';
				if (isset($this->session->data['okazaki'])){
					$okazaki_temp = $this->session->data['okazaki'];
				}
				if ($this->request->post['shipping_method'] == 'okazaki' && $okazaki_temp == '') {						
					$json['error']['warning'] = 'Please select a date';			
				}
				$anjochiryu_temp  = '';
				if (isset($this->session->data['anjochiryu'])){
					$anjochiryu_temp = $this->session->data['anjochiryu'];
				}
				if ($this->request->post['shipping_method'] == 'anjochiryu' && $anjochiryu_temp == '') {						
					$json['error']['warning'] = 'Please select a date';			
				}
				$toyota_temp  = '';
				if (isset($this->session->data['toyota'])){
					$toyota_temp = $this->session->data['toyota'];
				}
				if ($this->request->post['shipping_method'] == 'toyota' && $toyota_temp == '') {						
					$json['error']['warning'] = 'Please select a date';			
				}
				$nishiotakahamahekinan_temp  = '';
				if (isset($this->session->data['nishiotakahamahekinan'])){
					$nishiotakahamahekinan_temp = $this->session->data['nishiotakahamahekinan'];
				}
				if ($this->request->post['shipping_method'] == 'nishiotakahamahekinan' && $nishiotakahamahekinan_temp == '') {						
					$json['error']['warning'] = 'Please select a date';			
				}
				if (!isset($shipping[0]) || !isset($shipping[1]) || !isset($this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]])) {			
					$json['error']['warning'] = $this->language->get('error_shipping');
				}
			}
			
			if (!$json) {
				$shipping = explode('.', $this->request->post['shipping_method']);
					
				$this->session->data['shipping_method'] = $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];
				/*$this->session->data['okazaki'] = $this->request->post['okazaki'];
				$this->session->data['anjochiryu'] = $this->request->post['anjochiryu'];
				$this->session->data['toyota'] = $this->request->post['toyota'];
				$this->session->data['nishiotakahamahekinan'] = $this->request->post['nishiotakahamahekinan'];*/
				
				$this->session->data['comment'] = strip_tags($this->request->post['comment']);

                $this->session->data['ddw_date'] = $this->request->post['DDW_date'];
                $this->session->data['ddw_time_slot'] = $this->request->post['DDW_time_slot'];
            
            
			}							
		}
		
		$this->response->setOutput(json_encode($json));	
	}
}
?>