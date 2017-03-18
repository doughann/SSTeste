<?php
class ControllerModuleDeliverydate extends Controller {
	private $error = array(); 
	
	public function index() {   
		$this->load->language('module/deliverydate');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			if (isset($this->request->post['special_day'])) {
				$this->request->post['special_day'] = serialize($this->request->post['special_day']);
			}
			if (isset($this->request->post['deliverydate_custom'])) {
				$this->request->post['deliverydate_custom_same_day'] = serialize($this->request->post['deliverydate_custom_same_day']);
			}
			if (isset($this->request->post['range_hour'])) {
				$this->request->post['range_hour'] = serialize($this->request->post['range_hour']);
			}
			if (isset($this->request->post['deliverydate_no_display_day'])) {
				$this->request->post['deliverydate_no_display_day'] = implode(";" , $this->request->post['deliverydate_no_display_day']);
			}
			$this->model_setting_setting->editSetting('deliverydate', $this->request->post);		
					
			$this->session->data['success'] = $this->language->get('text_success');
						
			$this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}
				
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['button_add_special_day'] = $this->language->get('button_add_special_day');
        $this->data['button_add_range_hour'] = $this->language->get('button_add_range_hour');
		$this->data['button_remove'] = $this->language->get('button_remove');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		$this->data['text_from'] = $this->language->get('text_from');
		$this->data['text_to'] = $this->language->get('text_to');
        $this->data['text_custom'] = $this->language->get('text_custom');
		
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_interval'] = $this->language->get('entry_interval');
		$this->data['entry_unavailable_after'] = $this->language->get('entry_unavailable_after');
		$this->data['entry_display_same_day'] = $this->language->get('entry_display_same_day');
        $this->data['entry_custom_same_day'] = $this->language->get('entry_custom_same_day');
		$this->data['entry_no_display_days'] = $this->language->get('entry_no_display_days');
		$this->data['entry_special_day'] = $this->language->get('entry_special_day');
        $this->data['entry_range_hour'] = $this->language->get('entry_range_hour');
        $this->data['entry_required'] = $this->language->get('entry_required');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

 		if (isset($this->error['text'])) {
			$this->data['error_text'] = $this->error['text'];
		} else {
			$this->data['error_text'] = '';
		}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->data['breadcrumbs'][] = array(
       		'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
       		'text'      => $this->language->get('text_module'),
      		'separator' => ' :: '
   		);

   		$this->data['breadcrumbs'][] = array(
       		'href'      => $this->url->link('module/deliverydate', 'token=' . $this->session->data['token'], 'SSL'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);

		$this->data['action'] = $this->url->link('module/deliverydate', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['deliverydate_status'])) {
			$this->data['deliverydate_status'] = $this->request->post['deliverydate_status'];
		} else {
			$this->data['deliverydate_status'] = $this->config->get('deliverydate_status');
		}
		
		if (isset($this->request->post['deliverydate_interval_days'])) {
			$this->data['deliverydate_interval_days'] = $this->request->post['deliverydate_interval_days'];
		} else {
			$this->data['deliverydate_interval_days'] = $this->config->get('deliverydate_interval_days');
		}

		$this->data['no_display_days'] = $this->getDisplayDays();

		if (isset($this->request->post['deliverydate_no_display_day'])) {
			$this->data['deliverydate_no_display_day'] = $this->request->post['deliverydate_no_display_day'];
		} elseif ( $this->config->get('deliverydate_no_display_day') ) {
			$this->data['deliverydate_no_display_day'] = explode( ';' , $this->config->get('deliverydate_no_display_day') );
		} else {
			$this->data['deliverydate_no_display_day'] = array();
		}

		if (isset($this->request->post['deliverydate_unavailable_after'])) {
      		$this->data['deliverydate_unavailable_after'] = $this->request->post['deliverydate_unavailable_after'];
    	} else {
      		$this->data['deliverydate_unavailable_after'] = $this->config->get('deliverydate_unavailable_after');
    	}

		if (isset($this->request->post['deliverydate_custom'])) {
       		$this->data['deliverydate_custom'] = $this->request->post['deliverydate_custom'];
		} else {
			$this->data['deliverydate_custom'] = $this->config->get('deliverydate_custom');
		}

		if (isset($this->request->post['deliverydate_required'])) {
       		$this->data['deliverydate_required'] = $this->request->post['deliverydate_required'];
		} else {
			$this->data['deliverydate_required'] = $this->config->get('deliverydate_required');
		}

		if (isset($this->request->post['deliverydate_same_day'])) {
      		$this->data['deliverydate_same_day'] = $this->request->post['deliverydate_same_day'];
    	} else {
      		$this->data['deliverydate_same_day'] = $this->config->get('deliverydate_same_day');
    	}

		$this->load->model('localisation/language');

		$this->data['languages'] = $this->model_localisation_language->getLanguages();

        if ($this->config->get('deliverydate_custom'))
        {
		    $custom_same_day = unserialize($this->config->get('deliverydate_custom_same_day'));
        }

		if (isset($this->request->post['deliverydate_custom_same_day'])) {
			$this->data['deliverydate_custom_same_day'] = $this->request->post['deliverydate_custom_same_day'];
		} elseif (isset($custom_same_day)) {
			$this->data['deliverydate_custom_same_day'] = $custom_same_day;
		} else {
			$this->data['deliverydate_custom_same_day'] = array();
		}

        if ($this->config->get('special_day'))
        {
		    $special_day_info = unserialize($this->config->get('special_day'));
        }

		if (isset($this->request->post['special_day'])) {
			$this->data['special_days'] = $this->request->post['special_day'];
		} elseif (isset($special_day_info)) {
			$this->data['special_days'] = $special_day_info;
		} else {
			$this->data['special_days'] = array();
		}

		$range_hour_info = unserialize($this->config->get('range_hour'));

		if (isset($this->request->post['range_hour'])) {
			$this->data['range_hours'] = $this->request->post['range_hour'];
		} elseif (isset($range_hour_info)) {
			$this->data['range_hours'] = $range_hour_info;
		} else {
			$this->data['range_hours'] = array();
		}

		$this->template = 'module/deliverydate.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render());
	}

	public function getDisplayDays(){
	 return array(
			'0' => $this->language->get('text_day_0'),
			'1' => $this->language->get('text_day_1'),
			'2' => $this->language->get('text_day_2'),
			'3' => $this->language->get('text_day_3'),
			'4' => $this->language->get('text_day_4'),
			'5' => $this->language->get('text_day_5'),
			'6' => $this->language->get('text_day_6')
		);
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/deliverydate')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

        if (isset($this->request->post['deliverydate_custom'])) {
    		foreach ($this->request->post['deliverydate_custom_same_day'] as $language_id => $value) {
    			if ((strlen(utf8_decode($value['text'])) < 2) || (strlen(utf8_decode($value['text'])) > 255)) {
    				$this->error['text'][$language_id] = $this->language->get('error_text');
    			}
    		}
        }

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}	
	}
}
?>