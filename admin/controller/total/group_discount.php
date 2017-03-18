<?php 
class ControllerTotalGroupDiscount extends Controller { 
	private $error = array(); 
	 
	public function index() { 
		$this->load->language('total/group_discount'); 

		$this->document->setTitle($this->language->get('page_title'));
        $this->data['lang'] = $this->language;
		
        $this->load->model('setting/setting');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->model_setting_setting->editSetting('group_discount', $this->request->post);
		
			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->redirect($this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL'));
		}
        
        $this->load->model('group_discount/group_discount');
        $this->data['products'] = $this->model_group_discount_group_discount->getProducts();
		
        $this->data['discounts'] = $this->config->get('group_discount');
        if(empty($this->data['discounts']))
        {
            $this->data['discounts'] = array();
        }
        
        $this->data['category_discounts'] = $this->config->get('category_discount');
        if(empty($this->data['category_discounts']))
        {
            $this->data['category_discounts'] = array();
        }

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

   		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_total'),
			'href'      => $this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('page_title'),
			'href'      => $this->url->link('total/group_discount', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('total/group_discount', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['group_discount_status'])) {
			$this->data['group_discount_status'] = $this->request->post['group_discount_status'];
		} else {
			$this->data['group_discount_status'] = $this->config->get('group_discount_status');
		}

		if (isset($this->request->post['group_discount_sort_order'])) {
			$this->data['group_discount_sort_order'] = $this->request->post['group_discount_sort_order'];
		} else {
			$this->data['group_discount_sort_order'] = $this->config->get('group_discount_sort_order');
		}
        
        $this->load->model('catalog/category');
        $this->data['categories'] = $this->model_catalog_category->getCategories(array());
        $this->data['token'] = $this->session->data['token'];
		
		$this->template = 'total/group_discount.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}
	
	public function get_options()
	{
		$this->load->language('total/group_discount');
		$productId = isset($this->request->post['productId'])?$this->request->post['productId']:0;
		if($productId>0){
			$this->response->setOutput($this->renderProductOptions($this->language, $productId, $this->request->post['productRow'], $this->request->post['productType']));
		}
	}
	
	public function get_options_category()
	{
		$this->load->language('total/group_discount');
		$productId = isset($this->request->post['productId'])?$this->request->post['productId']:0;
		if($productId>0){
			$this->response->setOutput($this->renderProductOptions($this->language, $productId, $this->request->post['productRow'], $this->request->post['productType'], array(), 'category_discount'));
		}
	}
	
	public function renderProductOptions($language, $productId, $productRow, $productType, $defaults = array(), $discountType = 'group_discount')
	{
		$this->getProductOptions($productId);
		$this->data['lang'] = $language;
		$this->data['productType'] =  $productType;
		$this->data['productRow'] =  $productRow;
		$this->data['defaults'] = $defaults;
		$this->data['discountType'] = $discountType;
		$this->template = 'total/group_discount_ajax.tpl';
		return $this->render();
	}
	
	private function getProductOptions($productId)
	{
		$this->data['options'] = array();
		foreach ($this->_getProductOptions($productId) as $option) {
			if ($option['type'] == 'select' || $option['type'] == 'radio' || $option['type'] == 'checkbox') {
				$option_value_data = array();

				if(is_array($option['option_value']))
				foreach ($option['option_value'] as $option_value) {
					$option_value_data[] = array(
							'product_option_value_id' => $option_value['product_option_value_id'],
							'option_value_id'         => $option_value['option_value_id'],
							'name'                    => $option_value['name']
					);
				}
					
				$this->data['options'][] = array(
						'product_option_id' => $option['product_option_id'],
						'option_id'         => $option['option_id'],
						'name'              => $option['name'],
						'type'              => $option['type'],
						'option_value'      => $option_value_data,
						'required'          => $option['required']
				);
			} elseif ($option['type'] == 'text' || $option['type'] == 'textarea' || $option['type'] == 'date' || $option['type'] == 'datetime' || $option['type'] == 'time') {
				$this->data['options'][] = array(
						'product_option_id' => $option['product_option_id'],
						'option_id'         => $option['option_id'],
						'name'              => $option['name'],
						'type'              => $option['type'],
						'option_value'      => $option['option_value'],
						'required'          => $option['required']
				);
			}
		}
	}
	
	private function _getProductOptions($product_id) {
		$product_option_data = array();
	
		$product_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE po.product_id = '" . (int)$product_id . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY o.sort_order");
	
		foreach ($product_option_query->rows as $product_option) {
			if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
				$product_option_value_data = array();
					
				$product_option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_id = '" . (int)$product_id . "' AND pov.product_option_id = '" . (int)$product_option['product_option_id'] . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY ov.sort_order");
	
				foreach ($product_option_value_query->rows as $product_option_value) {
					$product_option_value_data[] = array(
							'product_option_value_id' => $product_option_value['product_option_value_id'],
							'option_value_id'         => $product_option_value['option_value_id'],
							'name'                    => $product_option_value['name'],
							'image'                   => $product_option_value['image'],
							'quantity'                => $product_option_value['quantity'],
							'subtract'                => $product_option_value['subtract'],
							'price'                   => $product_option_value['price'],
							'price_prefix'            => $product_option_value['price_prefix'],
							'weight'                  => $product_option_value['weight'],
							'weight_prefix'           => $product_option_value['weight_prefix']
					);
				}
					
				$product_option_data[] = array(
						'product_option_id' => $product_option['product_option_id'],
						'option_id'         => $product_option['option_id'],
						'name'              => $product_option['name'],
						'type'              => $product_option['type'],
						'option_value'      => $product_option_value_data,
						'required'          => $product_option['required']
				);
			} else {
				$product_option_data[] = array(
						'product_option_id' => $product_option['product_option_id'],
						'option_id'         => $product_option['option_id'],
						'name'              => $product_option['name'],
						'type'              => $product_option['type'],
						'option_value'      => $product_option['option_value'],
						'required'          => $product_option['required']
				);
			}
		}
	
		return $product_option_data;
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'total/group_discount')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
