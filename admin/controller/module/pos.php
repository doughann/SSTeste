<?php

define ('POS_VERSION', '3.0.5');

class ControllerModulePos extends Controller {
	private $error = array(); 
	
	public function index() {   
		$this->language->load('module/pos');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
		$this->load->model('pos/pos');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('POS', $this->request->post);	
					
			$this->session->data['success'] = $this->language->get('text_success');
						
			$this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}
				
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_order_payment_type'] = $this->language->get('text_order_payment_type');
		$this->data['text_action'] = $this->language->get('text_action');
		$this->data['text_type_already_exist'] = $this->language->get('text_type_already_exist');
		$this->data['text_payment_type_setting'] = $this->language->get('text_payment_type_setting');
		$this->data['text_display_setting'] = $this->language->get('text_display_setting');
		$this->data['text_display_once_login'] = $this->language->get('text_display_once_login');
		$this->data['column_exclude'] = $this->language->get('column_exclude');
		$this->data['text_select_all'] = $this->language->get('text_select_all');
		$this->data['text_unselect_all'] = $this->language->get('text_unselect_all');
				
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_type'] = $this->language->get('button_add_type');
		$this->data['button_remove'] = $this->language->get('button_remove');
		
		$this->data['token'] = $this->session->data['token'];
		
		$this->load->model('user/user');
		$this->data['users'] = $this->model_user_user->getUsers();
		$this->load->model('user/user_group');
		$this->data['user_groups'] = $this->model_user_user_group->getUserGroups();

		$excluded_groups = array();
		if ($this->config->get('excluded_groups')) {
			$excluded_groups = $this->config->get('excluded_groups');
		}
		$this->data['excluded_groups'] = $excluded_groups;
		
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
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/pos', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('module/pos', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['payment_types'] = array();
		
		if (isset($this->request->post['POS_payment_types'])) {
			$this->data['payment_types'] = $this->request->post['POS_payment_types'];
		} elseif ($this->config->get('POS_payment_types')) {
			$this->data['payment_types'] = $this->config->get('POS_payment_types');
		}

		if (isset($this->request->post['display_once_login'])) {
			$this->data['display_once_login'] = $this->request->post['display_once_login'];
		} else {
			$this->data['display_once_login'] = $this->config->get('display_once_login');
		}
		
		$this->template = 'pos/settings.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}
		
	protected function validate() {
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}

	public function install() {
		/*
		if (!$this->checkVqmod()) {
			// not existing
			$this->language->load('module/pos');
			$this->session->data['error'] = $this->language->get('text_vqmod_not_installed');
			$this->load->model('setting/extension');
			// remove from the extension table
			$this->model_setting_extension->uninstall('module', 'pos');
			return false;
		}
		*/
		
		// create tables
		$this->load->model('pos/pos');
		$this->model_pos_pos->createModuleTables();

		// create vqmod files
		$this->createFile();
		
		// copy language file is English not set to default
		$this->copyLangFile();

		// add default settings
		$this->load->model('setting/setting');
		$this->model_setting_setting->editSetting('POS', array('POS_payment_types'=>array('cash'=>'Cash', 'credit_card'=>'Credit Card'), 'display_once_login'=>'0'));
		
		// add permission for report
		$this->load->model('user/user_group');
		$this->model_user_user_group->addPermission($this->user->getId(), 'access', 'report/order_payment');
		$this->model_user_user_group->addPermission($this->user->getId(), 'modify', 'report/order_payment');
	}

	public function uninstall() {
		// $this->load->model('pos/pos');
		// $this->model_pos_pos->deleteModuleTables();

		// remove the files
		// $this->deleteFile();

		// $this->load->model('setting/setting');
		// $this->model_setting_setting->deleteSetting('POS');
	}
	
	private function checkVqmod() {
		return file_exists(DIR_APPLICATION . '/../vqmod');
	}

	private function createFile() {
	}

	private function deleteFile() {
		unlink(DIR_APPLICATION . '../vqmod/xml/pos.xml');
		unlink(DIR_APPLICATION . '../vqmod/xml/pos_redirect.xml');
	}
	
	private function copyLangFile() {
		$supported_languages = array();
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "language`"); 
		foreach ($query->rows as $result) {
			$supported_languages[$result['code']] = $result;
		}
		$directory = $supported_languages[$this->config->get('config_admin_language')]['directory'];
		if ($directory != 'english') {
			copy(DIR_LANGUAGE . 'english/pos/pos.php', DIR_LANGUAGE . $directory . '/pos/pos.php');
		}
	}

	public function addOrderPayment() {
		$this->load->model('pos/pos');
		if ($this->request->server['REQUEST_METHOD'] == 'GET') {
			$this->model_pos_pos->addOrderPayment($this->request->get);
			$json = array();
			$this->response->setOutput(json_encode($json));
		}
	}

	public function deleteOrderPayment() {
		$this->load->model('pos/pos');
		if ($this->request->server['REQUEST_METHOD'] == 'GET') {
			$this->model_pos_pos->deleteOrderPayment($this->request->get);
			$this->response->setOutput(json_encode(array()));
		}
	}
	
	public function modifyOrderComment() {
		$this->load->model('pos/pos');
		if ($this->request->server['REQUEST_METHOD'] == 'GET') {
			$this->model_pos_pos->modifyOrderComment($this->request->get);
			$this->response->setOutput(json_encode(array()));
		}
	}
	
	public function main() {
		$this->language->load('module/pos');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			if (isset($this->request->post['selected'])) {
				// selected orders to be deleted
				$this->load->model('sale/order');
				foreach ($this->request->post['selected'] as $order_id) {
					$this->model_sale_order->deleteOrder($order_id);
				}
			}
		}
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_terminal'] = $this->language->get('text_terminal');
		$this->data['text_register_mode'] = $this->language->get('text_register_mode');
		$this->data['text_date_added'] = $this->language->get('text_date_added');
		$this->data['text_date_modified'] = $this->language->get('text_date_modified');
		$this->data['text_customer'] = $this->language->get('text_customer');
		$this->data['text_product_quantity'] = $this->language->get('text_product_quantity');
		$this->data['text_items_in_cart']  = $this->language->get('text_items_in_cart');
		$this->data['text_amount_due']  = $this->language->get('text_amount_due');
		$this->data['text_change']  = $this->language->get('text_change');
		$this->data['text_payment_zero_amount']  = $this->language->get('text_payment_zero_amount');
		$this->data['text_quantity_zero']  = $this->language->get('text_quantity_zero');
		$this->data['text_comments'] = $this->language->get('text_comments');
		$this->data['text_order_sucess'] = $this->language->get('text_order_sucess');
		$this->data['text_load_order'] = $this->language->get('text_load_order');
		$this->data['text_filter_order_list'] = $this->language->get('text_filter_order_list');
		$this->data['text_load_order_list'] = $this->language->get('text_load_order_list');

		$this->data['text_product_name'] = $this->language->get('text_product_name');
		$this->data['text_product_upc'] = $this->language->get('text_product_upc');
		$this->data['text_no_order_selected'] = $this->language->get('text_no_order_selected');
		$this->data['text_confirm_delete_order'] = $this->language->get('text_confirm_delete_order');
		$this->data['text_not_available'] = $this->language->get('text_not_available');
		$this->data['text_del_payment_confirm'] = $this->language->get('text_del_payment_confirm');
		$this->data['text_autocomplete'] = $this->language->get('text_autocomplete');
		$this->data['text_customer_no_address'] = $this->language->get('text_customer_no_address');

		$this->data['column_payment_type']  = $this->language->get('column_payment_type');
		$this->data['column_payment_amount']  = $this->language->get('column_payment_amount');
		$this->data['column_payment_note']  = $this->language->get('column_payment_note');
		$this->data['column_payment_action']  = $this->language->get('column_payment_action');

		$this->data['button_add_payment']  = $this->language->get('button_add_payment');

		$this->data['button_existing_order'] = $this->language->get('button_existing_order'); 
		$this->data['button_new_order'] = $this->language->get('button_new_order'); 
		$this->data['button_complete_order'] = $this->language->get('button_complete_order');
		$this->data['button_print_invoice'] = $this->language->get('Print Invoice');
		$this->data['button_full_screen'] = $this->language->get('button_full_screen');
		$this->data['button_normal_screen'] = $this->language->get('button_normal_screen');
		$this->data['button_discount'] = $this->language->get('button_discount');
		$this->data['button_cut'] = $this->language->get('button_cut');
		$this->data['button_delete'] = $this->language->get('button_delete');
		$this->data['button_add_product'] = $this->language->get('button_add_product');
		
		$this->data['text_decimal_point'] = $this->language->get('decimal_point');
		$this->data['text_thousand_point'] = $this->language->get('thousand_point');
		
		$this->data['print_wait_title'] = $this->language->get('print_wait_title');
		$this->data['print_wait_message'] = $this->language->get('print_wait_message');
		// add for Invoice Print begin
		$this->data['print_invoice_message'] = $this->language->get('print_invoice_message');
		// add for Invoice Print end
		// add for Browse begin
		$this->data['text_top_category_id'] = '0';
		$this->data['text_top_category_name'] = $this->language->get('text_top_category_name');
		// add for Browse end
		
		$this->data['user'] = $this->user->getUserName();
		$text_week_0 = $this->language->get('text_week_0');
		$text_week_1 = $this->language->get('text_week_1');
		$text_week_2 = $this->language->get('text_week_2');
		$text_week_3 = $this->language->get('text_week_3');
		$text_week_4 = $this->language->get('text_week_4');
		$text_week_5 = $this->language->get('text_week_5');
		$text_week_6 = $this->language->get('text_week_6');
		$this->data['text_weeks'] = array($text_week_0, $text_week_1, $text_week_2, $text_week_3, $text_week_4, $text_week_5, $text_week_6);
		
		$text_month_1 = $this->language->get('text_month_1');
		$text_month_2 = $this->language->get('text_month_2');
		$text_month_3 = $this->language->get('text_month_3');
		$text_month_4 = $this->language->get('text_month_4');
		$text_month_5 = $this->language->get('text_month_5');
		$text_month_6 = $this->language->get('text_month_6');
		$text_month_7 = $this->language->get('text_month_7');
		$text_month_8 = $this->language->get('text_month_8');
		$text_month_9 = $this->language->get('text_month_9');
		$text_month_10 = $this->language->get('text_month_10');
		$text_month_11 = $this->language->get('text_month_11');
		$text_month_12 = $this->language->get('text_month_12');
		$this->data['text_months'] = array($text_month_1, $text_month_2, $text_month_3, $text_month_4, $text_month_5, $text_month_6, $text_month_7, $text_month_8, $text_month_9, $text_month_10, $text_month_11, $text_month_12);
		
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
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/pos/main', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('module/pos', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['payment_types'] = array();
		
		$default_country_id = $this->config->get('config_country_id');
		$default_zone_id = $this->config->get('config_zone_id');
		$this->data['shipping_country_id'] = $default_country_id;
		$this->data['shipping_zone_id'] = $default_zone_id;
		$this->data['payment_country_id'] = $default_country_id;
		$this->data['payment_zone_id'] = $default_zone_id;
		$this->data['currency_code'] = $this->config->get('config_currency');
		$this->data['currency_value'] = '1.0';
		$this->data['store_id'] = $this->getStoreId();
		$this->data['customer_id'] = 0;
		$this->data['customer_group_id'] = 1;
		$this->data['text_select'] = $this->language->get('text_select');
				
		if (isset($this->request->post['POS_payment_types'])) {
			$this->data['payment_types'] = $this->request->post['POS_payment_types'];
		} elseif ($this->config->get('POS_payment_types')) {
			$this->data['payment_types'] = $this->config->get('POS_payment_types');
		}
		
		$this->getOrderList();
		
		if (isset($this->request->get['order_id'])) {
			$this->getOrderProducts($this->request->get['order_id']);
			$this->data['display_order_content'] = 'block';
			$this->data['display_order_header'] = 'block';
			$this->data['display_orders'] = 'none';
		} elseif (isset($this->request->get['action']) || isset($this->request->get['page'])) {
			$this->data['display_order_content'] = 'none';
			$this->data['display_order_header'] = 'none';
			$this->data['display_orders'] = 'block';
		} elseif (!empty($this->data['orders'])) {
			$existingOrders = $this->data['orders'];
			$this->getOrderProducts($existingOrders[0]['order_id']);
			$this->data['display_order_content'] = 'block';
			$this->data['display_order_header'] = 'none';
			$this->data['display_orders'] = 'none';
			// add for Blank Page begin
			foreach ($this->data as $key => $value) {
				if (!(substr($key, 0, 5) == 'text_' || substr($key, 0, 7) == 'button_' ||
					substr($key, 0, 6) == 'entry_' || substr($key, 0, 7) == 'column_' ||
					$key == 'breadcrumbs' || substr($key, 0, 8) == 'display_' || substr($key, 0, 4) == 'tab_' ||
					$key == 'user' || $key == 'orders' || $key == 'token' || substr($key, 0, 6) == 'print_' ||
					$key == 'customer_countries' || $key == 'store_id' || $key == 'pagination' || $key == 'order_statuses' ||
					$key == 'quote_statuses' || $key == 'ccx_types')) {
					if (is_array($value)) {
						$this->data[$key] = array();
					} else {
						$this->data[$key] = '';
					}
				}
			}
			$this->data['text_order_ready'] = $this->language->get('text_order_ready');
			$this->data['text_order_blank'] = $this->language->get('text_order_blank');
			$this->data['totals'] = array(array('code'=>'total', 'title'=>'Total', 'text'=>$this->currency->format(0), 'value'=>0));
			// add for Blank Page end 
		} else {
			$this->data['display_order_content'] = 'none';
			$this->data['display_order_header'] = 'none';
			$this->data['display_orders'] = 'block';
		}
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$this->data['store_url'] = HTTPS_CATALOG;
		} else {
			$this->data['store_url'] = HTTP_CATALOG;
		}

		
		$this->data['full_screen_mode'] = 1;

		$this->template = 'pos/main.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}
	
	public function getOrderList() {
		$this->language->load('sale/order');
		$this->load->model('sale/order');
		
		if (isset($this->request->get['filter_order_id'])) {
			$filter_order_id = $this->request->get['filter_order_id'];
		} else {
			$filter_order_id = null;
		}

		if (isset($this->request->get['filter_customer'])) {
			$filter_customer = $this->request->get['filter_customer'];
		} else {
			$filter_customer = null;
		}

		if (isset($this->request->get['filter_order_status_id'])) {
			$filter_order_status_id = $this->request->get['filter_order_status_id'];
		} else {
			$filter_order_status_id = null;
		}
		
		if (isset($this->request->get['filter_total'])) {
			$filter_total = $this->request->get['filter_total'];
		} else {
			$filter_total = null;
		}
		
		if (isset($this->request->get['filter_date_added'])) {
			$filter_date_added = $this->request->get['filter_date_added'];
		} else {
			$filter_date_added = null;
		}
		
		if (isset($this->request->get['filter_date_modified'])) {
			$filter_date_modified = $this->request->get['filter_date_modified'];
		} else {
			$filter_date_modified = null;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'o.order_id';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
				
		$url = '';

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		
		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		}
		
		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}
					
		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}
		
		if (isset($this->request->get['filter_date_modified'])) {
			$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['orders'] = array();

		$data = array(
			'filter_order_id'        => $filter_order_id,
			'filter_customer'	     => $filter_customer,
			'filter_order_status_id' => $filter_order_status_id,
			'filter_total'           => $filter_total,
			'filter_date_added'      => $filter_date_added,
			'filter_date_modified'   => $filter_date_modified,
			'sort'                   => $sort,
			'order'                  => $order,
			'start'                  => ($page - 1) * 14,
			'limit'                  => 14
		);

		$this->load->model('pos/pos');
		$order_total = $this->model_sale_order->getTotalOrders($data);
		$results = $this->model_sale_order->getOrders($data);
		$data['filter_user_id'] = $this->user->getId();

    	foreach ($results as $result) {
			$action = array();
						
			$action[] = array(
				'text' => $this->language->get('text_select'),
				'href' => $this->url->link('module/pos/main', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'] . $url, 'SSL')
			);
			
			$this->data['orders'][] = array(
				'order_id'      => $result['order_id'],
				'customer'      => $result['customer'],
				'status'        => $result['status'],
				'total'         => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
				'date_added'    => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'date_modified' => date($this->language->get('date_format_short'), strtotime($result['date_modified'])),
				'selected'      => isset($this->request->post['selected']) && in_array($result['order_id'], $this->request->post['selected']),
				'action'        => $action
			);
		}

		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_missing'] = $this->language->get('text_missing');
		$this->data['text_wait'] = $this->language->get('text_wait');

		$this->data['column_order_id'] = $this->language->get('column_order_id');
    	$this->data['column_customer'] = $this->language->get('column_customer');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_total'] = $this->language->get('column_total');
		$this->data['column_date_added'] = $this->language->get('column_date_added');
		$this->data['column_date_modified'] = $this->language->get('column_date_modified');
		$this->data['column_action'] = $this->language->get('column_action');

		$this->data['button_invoice'] = $this->language->get('button_invoice');
		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');
		$this->data['button_filter'] = $this->language->get('button_filter');
		$this->data['entry_option'] = $this->language->get('entry_option');

		$this->data['token'] = $this->session->data['token'];
		
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		$url = '';

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		
		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		}
		
		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}
					
		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}
		
		if (isset($this->request->get['filter_date_modified'])) {
			$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['sort_order'] = $this->url->link('module/pos/main', 'token=' . $this->session->data['token'] . '&sort=o.order_id' . $url, 'SSL');
		$this->data['sort_customer'] = $this->url->link('module/pos/main', 'token=' . $this->session->data['token'] . '&sort=customer' . $url, 'SSL');
		$this->data['sort_status'] = $this->url->link('module/pos/main', 'token=' . $this->session->data['token'] . '&sort=status' . $url, 'SSL');
		$this->data['sort_total'] = $this->url->link('module/pos/main', 'token=' . $this->session->data['token'] . '&sort=o.total' . $url, 'SSL');
		$this->data['sort_date_added'] = $this->url->link('module/pos/main', 'token=' . $this->session->data['token'] . '&sort=o.date_added' . $url, 'SSL');
		$this->data['sort_date_modified'] = $this->url->link('module/pos/main', 'token=' . $this->session->data['token'] . '&sort=o.date_modified' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		
		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		}
		
		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}
					
		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}
		
		if (isset($this->request->get['filter_date_modified'])) {
			$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = 14;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('module/pos/main', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_order_id'] = $filter_order_id;
		$this->data['filter_customer'] = $filter_customer;
		$this->data['filter_order_status_id'] = $filter_order_status_id;
		$this->data['filter_total'] = $filter_total;
		$this->data['filter_date_added'] = $filter_date_added;
		$this->data['filter_date_modified'] = $filter_date_modified;

		$this->load->model('localisation/order_status');

    	$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
  	}
	
	private function getOrderIdText($order_id) {
		$order_id_text = ''.$order_id;
		$order_id_len = strlen($order_id_text);
		if ($order_id_len < 7) {
			for ($i = 0; $i < 7-$order_id_len; $i++) {
				$order_id_text = '0'.$order_id_text;
			}
		}
		return $order_id_text;
	}
	
	private function getOrderProducts($order_id) {
		// unset the shipping method before load it again
		unset($this->session->data['shipping_method']);
		
		$this->load->model('sale/order');
		$this->load->model('pos/pos');

		$order_info = $this->model_sale_order->getOrder($order_id);

		$this->language->load('sale/order');

		$this->data['text_order_id'] = $this->language->get('text_order_id');
		$this->data['text_invoice_no'] = $this->language->get('text_invoice_no');
		$this->data['text_invoice_date'] = $this->language->get('text_invoice_date');
		$this->data['text_store_name'] = $this->language->get('text_store_name');
		$this->data['text_store_url'] = $this->language->get('text_store_url');		
		$this->data['text_default'] = $this->language->get('text_default');
		$this->data['text_customer'] = $this->language->get('text_customer');
		$this->data['text_customer_group'] = $this->language->get('text_customer_group');
		$this->data['text_total'] = $this->language->get('text_total');
		$this->data['text_reward'] = $this->language->get('text_reward');		
		$this->data['text_order_status'] = $this->language->get('text_order_status');
		$this->data['text_comment'] = $this->language->get('text_comment');
		$this->data['text_date_added'] = $this->language->get('text_date_added');
		$this->data['text_date_modified'] = $this->language->get('text_date_modified');			
    	$this->data['entry_firstname'] = $this->language->get('entry_firstname');
    	$this->data['entry_lastname'] = $this->language->get('entry_lastname');
    	$this->data['entry_email'] = $this->language->get('entry_email');
    	$this->data['entry_telephone'] = $this->language->get('entry_telephone');
    	$this->data['entry_fax'] = $this->language->get('entry_fax');
		$this->data['entry_company'] = $this->language->get('entry_company');
		$this->data['entry_company_id'] = $this->language->get('entry_company_id');
		$this->data['entry_tax_id'] = $this->language->get('entry_tax_id');
		$this->data['entry_address_1'] = $this->language->get('entry_address_1');
		$this->data['entry_address_2'] = $this->language->get('entry_address_2');
		$this->data['entry_city'] = $this->language->get('entry_city');
		$this->data['entry_postcode'] = $this->language->get('entry_postcode');
		$this->data['entry_zone'] = $this->language->get('entry_zone');
		$this->data['entry_country'] = $this->language->get('entry_country');
		$this->data['text_shipping_method'] = $this->language->get('text_shipping_method');
		$this->data['text_payment_method'] = $this->language->get('text_payment_method');	
		$this->data['text_download'] = $this->language->get('text_download');
		$this->data['text_wait'] = $this->language->get('text_wait');
		$this->data['text_generate'] = $this->language->get('text_generate');
		$this->data['text_voucher'] = $this->language->get('text_voucher');
		$this->data['text_add_product_prompt'] = $this->language->get('text_add_product_prompt');
		$this->data['text_select'] = $this->language->get('text_select');
		$this->data['text_no_product'] = $this->language->get('text_no_product');
		$this->data['text_order_ready'] = $this->language->get('text_order_ready');
		$this->data['text_none'] = $this->language->get('text_none');
						
		$this->data['column_product'] = $this->language->get('column_product');
		$this->data['column_model'] = $this->language->get('column_model');
		$this->data['column_quantity'] = $this->language->get('column_quantity');
		$this->data['column_price'] = $this->language->get('column_price');
		$this->data['column_total'] = $this->language->get('column_total');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_voucher'] = $this->language->get('button_add_voucher');
		$this->data['entry_to_name'] = $this->language->get('entry_to_name');
		$this->data['entry_to_email'] = $this->language->get('entry_to_email');
		$this->data['entry_from_name'] = $this->language->get('entry_from_name');
		$this->data['entry_from_email'] = $this->language->get('entry_from_email');
		$this->data['entry_theme'] = $this->language->get('entry_theme');	
		$this->data['entry_message'] = $this->language->get('entry_message');
		$this->data['entry_amount'] = $this->language->get('entry_amount');
		$this->data['text_product'] = $this->language->get('text_product');
		$this->data['entry_product'] = $this->language->get('entry_product');
		$this->data['entry_option'] = $this->language->get('entry_option');
		$this->data['entry_quantity'] = $this->language->get('entry_quantity');
		$this->data['button_add_product'] = $this->language->get('button_add_product');
		$this->data['button_upload'] = $this->language->get('button_upload');
		
		$this->data['tab_product_search'] = $this->language->get('tab_product_search');
		$this->data['tab_product_browse'] = $this->language->get('tab_product_browse');
		$this->data['tab_product_details'] = $this->language->get('tab_product_details');
		// add for Browse begin
		$this->data['browse_items'] = $this->getCategoryItems(0, $order_info['currency_code'], $order_info['currency_value']);
		// add for Brose end

		$this->data['token'] = $this->session->data['token'];

		$this->data['order_id'] = $order_id;
		$this->data['order_id_text'] = $this->getOrderIdText($order_id);
		
		$this->data['store_id'] = $order_info['store_id'];
		$this->data['invoice'] = $this->url->link('sale/order/invoice', 'token=' . $this->session->data['token'] . '&order_id=' . (int)$order_id, 'SSL');
		
		if ($order_info['invoice_no']) {
			$this->data['invoice_no'] = $order_info['invoice_prefix'] . $order_info['invoice_no'];
		} else {
			$this->data['invoice_no'] = '';
		}
		
		$this->data['store_name'] = $order_info['store_name'];
		$this->data['store_url'] = $order_info['store_url'];
		$this->data['firstname'] = $order_info['firstname'];
		$this->data['lastname'] = $order_info['lastname'];
		
		if ($order_info['customer_id'] > 0) {
			$this->data['customer'] = $order_info['customer'];
			$this->data['customer_id'] = $order_info['customer_id'];
		} else {
			$this->data['customer'] = $order_info['firstname'].' '.$order_info['lastname'];
			$this->data['customer_id'] = 0;
		}
		$this->getCustomer($order_info['customer_id']);

		$this->load->model('sale/customer_group');

		$customer_group_info = $this->model_sale_customer_group->getCustomerGroup($order_info['customer_group_id']);
		$this->data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();
		$this->data['customer_group_id'] = $order_info['customer_group_id'];

		if ($customer_group_info) {
			$this->data['customer_group'] = $customer_group_info['name'];
		} else {
			$this->data['customer_group'] = '';
		}

		$this->data['email'] = $order_info['email'];
		$this->data['telephone'] = $order_info['telephone'];
		$this->data['fax'] = $order_info['fax'];
		$this->data['comment'] = $order_info['comment'];
		$this->data['total'] = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value']);
		
		if ($order_info['total'] < 0) {
			$this->data['credit'] = $order_info['total'];
		} else {
			$this->data['credit'] = 0;
		}
		
		$this->load->model('sale/customer');
					
		$this->data['credit_total'] = $this->model_sale_customer->getTotalTransactionsByOrderId($order_id); 
		
		$this->data['reward'] = $order_info['reward'];
					
		$this->data['reward_total'] = $this->model_sale_customer->getTotalCustomerRewardsByOrderId($order_id);

		$this->data['affiliate_firstname'] = $order_info['affiliate_firstname'];
		$this->data['affiliate_lastname'] = $order_info['affiliate_lastname'];
		
		if ($order_info['affiliate_id']) {
			$this->data['affiliate'] = $this->url->link('sale/affiliate/update', 'token=' . $this->session->data['token'] . '&affiliate_id=' . $order_info['affiliate_id'], 'SSL');
			$this->data['affiliate_id'] = $order_info['affiliate_id'];
		} else {
			$this->data['affiliate'] = '';
			$this->data['affiliate_id'] = 0;
		}
		
		$this->data['commission'] = $this->currency->format($order_info['commission'], $order_info['currency_code'], $order_info['currency_value']);
					
		$this->load->model('sale/affiliate');
		
		$this->data['commission_total'] = $this->model_sale_affiliate->getTotalTransactionsByOrderId($order_id); 

		$this->load->model('localisation/order_status');

		$order_status_info = $this->model_localisation_order_status->getOrderStatus($order_info['order_status_id']);

		if ($order_status_info) {
			$this->data['order_status'] = $order_status_info['name'];
		} else {
			$this->data['order_status'] = '';
		}
		
		$this->data['ip'] = $order_info['ip'];
		$this->data['forwarded_ip'] = $order_info['forwarded_ip'];
		$this->data['user_agent'] = $order_info['user_agent'];
		$this->data['accept_language'] = $order_info['accept_language'];
		$this->data['date_added'] = date($this->language->get('date_format_short'), strtotime($order_info['date_added'])) . ' '  .date($this->language->get('time_format'), strtotime($order_info['date_added']));
		$this->data['date_modified'] = date($this->language->get('date_format_short'), strtotime($order_info['date_modified'])) . ' '  .date($this->language->get('time_format'), strtotime($order_info['date_modified']));		
		$this->data['payment_firstname'] = $order_info['payment_firstname'];
		$this->data['payment_lastname'] = $order_info['payment_lastname'];
		$this->data['payment_company'] = $order_info['payment_company'];
		$this->data['payment_company_id'] = $order_info['payment_company_id'];
		$this->data['payment_tax_id'] = $order_info['payment_tax_id'];
		$this->data['payment_address_1'] = $order_info['payment_address_1'];
		$this->data['payment_address_2'] = $order_info['payment_address_2'];
		$this->data['payment_city'] = $order_info['payment_city'];
		$this->data['payment_postcode'] = $order_info['payment_postcode'];
		$this->data['payment_zone'] = $order_info['payment_zone'];
		$this->data['payment_zone_code'] = $order_info['payment_zone_code'];
		$this->data['payment_country'] = $order_info['payment_country'];			
		$this->data['payment_country_id'] = $order_info['payment_country_id'];			
		$this->data['payment_zone_id'] = $order_info['payment_zone_id'];			
		$this->data['shipping_firstname'] = $order_info['shipping_firstname'];
		$this->data['shipping_lastname'] = $order_info['shipping_lastname'];
		$this->data['shipping_company'] = $order_info['shipping_company'];
		$this->data['shipping_address_1'] = $order_info['shipping_address_1'];
		$this->data['shipping_address_2'] = $order_info['shipping_address_2'];
		$this->data['shipping_city'] = $order_info['shipping_city'];
		$this->data['shipping_postcode'] = $order_info['shipping_postcode'];
		$this->data['shipping_zone'] = $order_info['shipping_zone'];
		$this->data['shipping_zone_code'] = $order_info['shipping_zone_code'];
		$this->data['shipping_country'] = $order_info['shipping_country'];
		$this->data['shipping_country_id'] = $order_info['shipping_country_id'];
		$this->data['shipping_zone_id'] = $order_info['shipping_zone_id'];
		$this->data['shipping_code'] = $order_info['shipping_code'];
		$this->data['shipping_method'] = $order_info['shipping_method'];
		$this->data['payment_method'] = $order_info['payment_method'];
		$this->data['payment_code'] = $order_info['payment_code'];
		
		$this->data['products'] = array();

		$raw_products = $this->model_sale_order->getOrderProducts($order_id);
		// there is a bug in opencart code when write order back to db and read out, it maybe in different order
		// change the order in code to make constanct result by sorting by product id
		$products = array();
		while (count($raw_products) > 0) {
			$raw_index = 0;
			while (!isset($raw_products[$raw_index])) {
				$raw_index++;
			}
			$raw_product_min = $raw_products[$raw_index];

			$keys = array_keys($raw_products);
			foreach ($keys as $key) {
				if ($raw_product_min['order_product_id'] > $raw_products[$key]['order_product_id']) {
					$raw_index = $key;
					$raw_product_min = $raw_products[$key];
				}
			}
			array_push($products, $raw_product_min);
			unset($raw_products[$raw_index]);
		}

		$items_in_cart = 0;
		foreach ($products as $product) {
			$option_data = array();

			$options = $this->model_sale_order->getOrderOptions($order_id, $product['order_product_id']);
			$order_download = $this->model_sale_order->getOrderDownloads($order_id, $product['order_product_id']);

			$this->data['products'][] = array(
				'order_product_id' => $product['order_product_id'],
				'product_id'       => $product['product_id'],
				'name'    	 	   => $product['name'],
				'model'    		   => $product['model'],
				'option'   		   => $options,
				'download'		   => $order_download,
				'quantity'		   => $product['quantity'],
				'price'			   => $product['price'],
				'price_text'	   => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
				'total'			   => $product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0),
				'total_text'       => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']),
				'tax'			   => $product['tax'],
				'reward'		   => $product['reward'],
				'href'     		   => $this->url->link('catalog/product/update', 'token=' . $this->session->data['token'] . '&product_id=' . $product['product_id'], 'SSL'),
				'selected'		   => isset($this->request->post['selected']) && in_array($product['product_id'], $this->request->post['selected'])
			);
			
			$items_in_cart += $product['quantity'];
		}
		$this->data['items_in_cart'] = $items_in_cart;
		$this->data['currency_code'] = $order_info['currency_code'];
		$this->data['currency_value'] = $order_info['currency_value'];
		$this->data['currency_symbol'] = $this->currency->getSymbolLeft($order_info['currency_code']);
		if ($this->data['currency_symbol'] == '') {
			$this->data['currency_symbol'] = $this->currency->getSymbolRight($order_info['currency_code']);
		}
	
		$this->data['order_vouchers'] = $this->model_sale_order->getOrderVouchers($order_id);
		$this->load->model('sale/voucher_theme');
		$this->data['voucher_themes'] = $this->model_sale_voucher_theme->getVoucherThemes();
		$this->load->model('setting/store');
		$this->data['stores'] = $this->model_setting_store->getStores();
	
		$this->data['totals'] = $this->model_sale_order->getOrderTotals($order_id);
		// instead of using the last object in the array, use the total code
		$totalPaymentAmount = 0;
		foreach ($this->data['totals'] as $order_total_data) {
			if ($order_total_data['code'] == 'total') {
				$totalPaymentAmount = $order_total_data['value'];
				if ($order_info['currency_value']) $totalPaymentAmount = (float)$totalPaymentAmount*$order_info['currency_value'];
				break;
			}
		}

		$totalPaid = 0;
		$order_payments = $this->model_pos_pos->retrieveOrderPayments($order_id);
		if ($order_payments) {
			// reverse the order
			$order_payments = array_reverse($order_payments);
			foreach ($order_payments as $order_payment) {
				$totalPaid += $order_payment['tendered_amount'];
				$this->data['order_payments'][] = array (
					'order_payment_id' => $order_payment['order_payment_id'],
					'payment_type'     => $order_payment['payment_type'],
					'tendered_amount'  => $this->currency->format($order_payment['tendered_amount'], $order_info['currency_code'], 1),
					'payment_note'     => $order_payment['payment_note']
				);
			}
		}

		$this->data['payment_due_amount'] = $totalPaymentAmount - $totalPaid;
		$this->data['payment_change'] = 0;
		if ($this->data['payment_due_amount'] <  0) {
			$this->data['payment_change'] = 0 - $this->data['payment_due_amount'];
			$this->data['payment_due_amount'] = 0;
		}
		$this->data['payment_due_amount_text'] = $this->currency->format($this->data['payment_due_amount'], $order_info['currency_code'], 1);
		$this->data['payment_change_text'] = $this->currency->format($this->data['payment_change'], $order_info['currency_code'], 1);
		$this->data['downloads'] = array();

		foreach ($products as $product) {
			$results = $this->model_sale_order->getOrderDownloads($order_id, $product['order_product_id']);

			foreach ($results as $result) {
				$this->data['downloads'][] = array(
					'name'      => $result['name'],
					'filename'  => $result['mask'],
					'remaining' => $result['remaining']
				);
			}
		}
		$this->document->addScript('view/javascript/jquery/ajaxupload.js');
		
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		$this->data['order_status_id'] = $order_info['order_status_id'];
	}
	
	public function getProductDetails() {
		$product_id = $this->request->get['product_id'];
		$this->language->load('catalog/product');

		if ('' == $product_id) {
			return;
		}
		
		$this->load->model('catalog/product');
		
    	$product_info = $this->model_catalog_product->getProduct($product_id);
		if (empty($product_info)) return;
 
    	$this->data['text_enabled'] = $this->language->get('text_enabled');
    	$this->data['text_disabled'] = $this->language->get('text_disabled');
    	$this->data['text_none'] = $this->language->get('text_none');
    	$this->data['text_yes'] = $this->language->get('text_yes');
    	$this->data['text_no'] = $this->language->get('text_no');
		$this->data['text_plus'] = $this->language->get('text_plus');
		$this->data['text_minus'] = $this->language->get('text_minus');
		$this->data['text_default'] = $this->language->get('text_default');
		$this->data['text_image_manager'] = $this->language->get('text_image_manager');
		$this->data['text_browse'] = $this->language->get('text_browse');
		$this->data['text_clear'] = $this->language->get('text_clear');
		$this->data['text_option'] = $this->language->get('text_option');
		$this->data['text_option_value'] = $this->language->get('text_option_value');
		$this->data['text_select'] = $this->language->get('text_select');
		$this->data['text_percent'] = $this->language->get('text_percent');
		$this->data['text_amount'] = $this->language->get('text_amount');

		$this->data['entry_name'] = $this->language->get('entry_name');
		$this->data['entry_meta_description'] = $this->language->get('entry_meta_description');
		$this->data['entry_meta_keyword'] = $this->language->get('entry_meta_keyword');
		$this->data['entry_description'] = $this->language->get('entry_description');
		$this->data['entry_store'] = $this->language->get('entry_store');
		$this->data['entry_keyword'] = $this->language->get('entry_keyword');
    	$this->data['entry_model'] = $this->language->get('entry_model');
		$this->data['entry_sku'] = $this->language->get('entry_sku');
		$this->data['entry_upc'] = $this->language->get('entry_upc');
		$this->data['entry_ean'] = $this->language->get('entry_ean');
		$this->data['entry_jan'] = $this->language->get('entry_jan');
		$this->data['entry_isbn'] = $this->language->get('entry_isbn');
		$this->data['entry_mpn'] = $this->language->get('entry_mpn');
		$this->data['entry_location'] = $this->language->get('entry_location');
		$this->data['entry_minimum'] = $this->language->get('entry_minimum');
		$this->data['entry_manufacturer'] = $this->language->get('entry_manufacturer');
    	$this->data['entry_shipping'] = $this->language->get('entry_shipping');
    	$this->data['entry_date_available'] = $this->language->get('entry_date_available');
    	$this->data['entry_quantity'] = $this->language->get('entry_quantity');
		$this->data['entry_stock_status'] = $this->language->get('entry_stock_status');
    	$this->data['entry_price'] = $this->language->get('entry_price');
		$this->data['entry_tax_class'] = $this->language->get('entry_tax_class');
		$this->data['entry_points'] = $this->language->get('entry_points');
		$this->data['entry_option_points'] = $this->language->get('entry_option_points');
		$this->data['entry_subtract'] = $this->language->get('entry_subtract');
    	$this->data['entry_weight_class'] = $this->language->get('entry_weight_class');
    	$this->data['entry_weight'] = $this->language->get('entry_weight');
		$this->data['entry_dimension'] = $this->language->get('entry_dimension');
		$this->data['entry_length'] = $this->language->get('entry_length');
    	$this->data['entry_image'] = $this->language->get('entry_image');
    	$this->data['entry_download'] = $this->language->get('entry_download');
    	$this->data['entry_category'] = $this->language->get('entry_category');
		$this->data['entry_filter'] = $this->language->get('entry_filter');
		$this->data['entry_related'] = $this->language->get('entry_related');
		$this->data['entry_attribute'] = $this->language->get('entry_attribute');
		$this->data['entry_text'] = $this->language->get('entry_text');
		$this->data['entry_option'] = $this->language->get('entry_option');
		$this->data['entry_option_value'] = $this->language->get('entry_option_value');
		$this->data['entry_required'] = $this->language->get('entry_required');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$this->data['entry_date_start'] = $this->language->get('entry_date_start');
		$this->data['entry_date_end'] = $this->language->get('entry_date_end');
		$this->data['entry_priority'] = $this->language->get('entry_priority');
		$this->data['entry_tag'] = $this->language->get('entry_tag');
		$this->data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$this->data['entry_reward'] = $this->language->get('entry_reward');
		$this->data['entry_layout'] = $this->language->get('entry_layout');
		
		$this->language->load('module/pos');
		$this->data['column_attr_name'] = $this->language->get('column_attr_name');
		$this->data['column_attr_value'] = $this->language->get('column_attr_value');
		$this->data['entry_thumb'] = $this->language->get('entry_thumb');
		
		$this->data['tab_option'] = $this->language->get('tab_option');
				
		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}
		
		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		}

		$this->data['token'] = $this->session->data['token'];

		$this->data['name'] = '';
		$this->data['description'] = '';
		$this->load->model('localisation/language');
		$languages = $this->model_localisation_language->getLanguages();
		$descriptions = $this->model_catalog_product->getProductDescriptions($product_id);
		foreach ($languages as $language) {
			if ($language['code'] == $this->language->get('code')) {
				$this->data['name'] = $descriptions[$language['language_id']]['name'];
				$this->data['description'] = $descriptions[$language['language_id']]['description'];
			}
		}
		$this->data['model'] = $product_info['model'];
		$this->data['sku'] = $product_info['sku'];
		$this->data['upc'] = $product_info['upc'];
		// the following attributes are not in the previous version (eariler than 1.5.5.1)
		// and the current page details do not require them,
		$this->data['ean'] = isset($product_info['ean']) ? $product_info['ean'] : '';
		$this->data['jan'] = isset($product_info['jan']) ? $product_info['jan'] : '';
		$this->data['isbn'] = isset($product_info['isbn']) ? $product_info['isbn'] : '';
		$this->data['mpn'] = isset($product_info['mpn']) ? $product_info['mpn'] : '';
		$this->data['location'] = $product_info['location'];

		$this->load->model('setting/store');
		$this->data['stores'] = $this->model_setting_store->getStores();
		$this->data['product_store'] = $this->model_catalog_product->getProductStores($product_id);
		$this->data['keyword'] = $product_info['keyword'];
		$this->data['image'] = $product_info['image'];

		$this->load->model('tool/image');
		if ($product_info['image'] && file_exists(DIR_IMAGE . $product_info['image'])) {
			$this->data['thumb'] = $this->model_tool_image->resize($product_info['image'], 100, 100);
		} else {
			$this->data['thumb'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		}
     	$this->data['shipping'] = $product_info['shipping'];
		$this->data['price'] = $product_info['price'];

		$this->load->model('localisation/tax_class');
		$this->data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();
		$this->data['tax_class_id'] = $product_info['tax_class_id'];
		$this->data['date_available'] = date('Y-m-d', strtotime($product_info['date_available']));
    	$this->data['quantity'] = $product_info['quantity'];
      	$this->data['minimum'] = $product_info['minimum'];
      	$this->data['subtract'] = $product_info['subtract'];
      	$this->data['sort_order'] = $product_info['sort_order'];

		$this->load->model('localisation/stock_status');
		$stock_statuses = $this->model_localisation_stock_status->getStockStatuses();
		$this->data['stock_status'] = '';
		foreach ($stock_statuses as $stock_status) {
			if ($stock_status['stock_status_id'] == $product_info['stock_status_id']) {
				$this->data['stock_status'] = $stock_status['name'];
			}
		}

 		$this->data['status'] = $product_info['status'];
		$this->data['weight'] = $product_info['weight'];

		$this->load->model('localisation/weight_class');
		$this->data['weight_classes'] = $this->model_localisation_weight_class->getWeightClasses();
  		$this->data['weight_class_id'] = $product_info['weight_class_id'];
		$this->data['length'] = $product_info['length'];
		$this->data['width'] = $product_info['width'];
		$this->data['height'] = $product_info['height'];

		$this->load->model('localisation/length_class');
		$this->data['length_classes'] = $this->model_localisation_length_class->getLengthClasses();
  		$this->data['length_class_id'] = $product_info['length_class_id'];

		$this->load->model('catalog/manufacturer');
		$this->data['manufacturer_id'] = $product_info['manufacturer_id'];
		$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($product_info['manufacturer_id']);
		if ($manufacturer_info) {		
			$this->data['manufacturer'] = $manufacturer_info['name'];
		} else {
			$this->data['manufacturer'] = '';
		}	
		
		// Categories
		$this->load->model('catalog/category');
		$categories = $this->model_catalog_product->getProductCategories($product_id);
		$this->data['product_categories'] = array();
		
		foreach ($categories as $category_id) {
			$category_info = $this->model_catalog_category->getCategory($category_id);
			
			if ($category_info) {
				$this->data['product_categories'][] = array(
					'category_id' => $category_info['category_id'],
					'name'        => isset($category_info['path']) ? (($category_info['path'] ? $category_info['path'] . ' &gt; ' : '') . $category_info['name']) : ''
				);
			}
		}
		
		// the following model is not in the previous version (eariler than 1.5.5.1)
		// and the current page details do not require it,
		// Filters
		/*
		$this->load->model('catalog/filter');
		$filters = $this->model_catalog_product->getProductFilters($product_id);
		$this->data['product_filters'] = array();
		foreach ($filters as $filter_id) {
			$filter_info = $this->model_catalog_filter->getFilter($filter_id);
			
			if ($filter_info) {
				$this->data['product_filters'][] = array(
					'filter_id' => $filter_info['filter_id'],
					'name'      => $filter_info['group'] . ' &gt; ' . $filter_info['name']
				);
			}
		}
		*/
		
		// Attributes
		$this->load->model('catalog/attribute');
		$product_attributes = $this->model_catalog_product->getProductAttributes($product_id);
		$this->data['product_attributes'] = array();
		foreach ($product_attributes as $product_attribute) {
			$attribute_info = $this->model_catalog_attribute->getAttribute($product_attribute['attribute_id']);
			
			if ($attribute_info) {
				$this->data['product_attributes'][] = array(
					'attribute_id'                  => $product_attribute['attribute_id'],
					'name'                          => isset($attribute_info['name']) ? $attribute_info['name'] : '',
					'product_attribute_description' => $product_attribute['product_attribute_description']
				);
			}
		}		
		
		// Options
		$this->load->model('catalog/option');
		$product_options = $this->model_catalog_product->getProductOptions($product_id);			

		$this->data['product_options'] = array();
		foreach ($product_options as $product_option) {
			if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
				$product_option_value_data = array();
				
				foreach ($product_option['product_option_value'] as $product_option_value) {
					$product_option_value_data[] = array(
						'product_option_value_id' => $product_option_value['product_option_value_id'],
						'option_value_id'         => $product_option_value['option_value_id'],
						'quantity'                => $product_option_value['quantity'],
						'subtract'                => $product_option_value['subtract'],
						'price'                   => $product_option_value['price'],
						'price_prefix'            => $product_option_value['price_prefix'],
						'points'                  => $product_option_value['points'],
						'points_prefix'           => $product_option_value['points_prefix'],						
						'weight'                  => $product_option_value['weight'],
						'weight_prefix'           => $product_option_value['weight_prefix']	
					);
				}
				
				$this->data['product_options'][] = array(
					'product_option_id'    => $product_option['product_option_id'],
					'product_option_value' => $product_option_value_data,
					'option_id'            => $product_option['option_id'],
					'name'                 => $product_option['name'],
					'type'                 => $product_option['type'],
					'required'             => $product_option['required']
				);				
			} else {
				$this->data['product_options'][] = array(
					'product_option_id' => $product_option['product_option_id'],
					'option_id'         => $product_option['option_id'],
					'name'              => $product_option['name'],
					'type'              => $product_option['type'],
					'option_value'      => $product_option['option_value'],
					'required'          => $product_option['required']
				);				
			}
		}
		
		$this->data['option_values'] = array();
		
		foreach ($this->data['product_options'] as $product_option) {
			if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
				if (!isset($this->data['option_values'][$product_option['option_id']])) {
					$this->data['option_values'][$product_option['option_id']] = $this->model_catalog_option->getOptionValues($product_option['option_id']);
				}
			}
		}
		
		$this->load->model('sale/customer_group');
		$this->data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();
		
		$this->data['product_discounts'] = $this->model_catalog_product->getProductDiscounts($product_id);
		$this->data['product_specials'] = $this->model_catalog_product->getProductSpecials($product_id);
		
		// Images
		$product_images = $this->model_catalog_product->getProductImages($product_id);
		$this->data['product_images'] = array();
		foreach ($product_images as $product_image) {
			if ($product_image['image'] && file_exists(DIR_IMAGE . $product_image['image'])) {
				$image = $product_image['image'];
			} else {
				$image = 'no_image.jpg';
			}
			
			$this->data['product_images'][] = array(
				'image'      => $image,
				'thumb'      => $this->model_tool_image->resize($image, 100, 100),
				'sort_order' => $product_image['sort_order']
			);
		}

		$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);

		// Downloads
		$this->load->model('catalog/download');
		$product_downloads = $this->model_catalog_product->getProductDownloads($product_id);
		$this->data['product_downloads'] = array();
		foreach ($product_downloads as $download_id) {
			$download_info = $this->model_catalog_download->getDownload($download_id);
			
			if ($download_info) {
				$this->data['product_downloads'][] = array(
					'download_id' => $download_info['download_id'],
					'name'        => $download_info['name']
				);
			}
		}
		
		$products = $this->model_catalog_product->getProductRelated($product_id);
		$this->data['product_related'] = array();
		foreach ($products as $product_id) {
			$related_info = $this->model_catalog_product->getProduct($product_id);
			
			if ($related_info) {
				$this->data['product_related'][] = array(
					'product_id' => $related_info['product_id'],
					'name'       => $related_info['name']
				);
			}
		}

		$this->data['points'] = $product_info['points'];
		$this->data['product_reward'] = $this->model_catalog_product->getProductRewards($product_id);
		$this->data['product_layout'] = $this->model_catalog_product->getProductLayouts($product_id);

		$this->load->model('design/layout');
		$this->data['layouts'] = $this->model_design_layout->getLayouts();
		
		$this->template = 'pos/product_details.tpl';
				
		$this->response->setOutput($this->render());
	}
	
	private function getCustomer($customer_id) {
		$this->language->load('sale/customer');
    	$this->data['text_enabled'] = $this->language->get('text_enabled');
    	$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_select'] = $this->language->get('text_select');
		
    	$this->data['entry_password'] = $this->language->get('entry_password');
    	$this->data['entry_confirm'] = $this->language->get('entry_confirm');
		$this->data['entry_newsletter'] = $this->language->get('entry_newsletter');
    	$this->data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_default'] = $this->language->get('entry_default');
 
    	$this->data['button_add_address'] = $this->language->get('button_add_address');
    	$this->data['button_remove'] = $this->language->get('button_remove');
	
		$this->data['tab_general'] = $this->language->get('tab_general');
		$this->data['tab_address'] = $this->language->get('tab_address');

		$this->load->model('sale/customer');
		$customer_info = $this->model_sale_customer->getCustomer($customer_id);
			
		if (!empty($customer_info)) { 
			$this->data['customer_firstname'] = $customer_info['firstname'];
			$this->data['customer_lastname'] = $customer_info['lastname'];
      		$this->data['customer_email'] = $customer_info['email'];
			$this->data['customer_telephone'] = $customer_info['telephone'];
			$this->data['customer_fax'] = $customer_info['fax'];
			$this->data['customer_newsletter'] = $customer_info['newsletter'];
			$this->data['customer_customer_group_id'] = $customer_info['customer_group_id'];
			$this->data['customer_status'] = $customer_info['status'];
			$this->data['customer_addresses'] = $this->model_sale_customer->getAddresses($customer_id);
			$this->data['customer_address_id'] = $customer_info['address_id'];
			$this->data['hasAddress'] = 1;
			foreach ($this->data['customer_addresses'] as $address) {
				if ($customer_info['address_id'] == $address['address_id']) {
					$this->data['hasAddress'] = 2;
					break;
				}
			}
			$this->data['customer_password'] = '';
			$this->data['customer_confirm'] = '';
		} else {
      		$this->data['customer_firstname'] = '';
      		$this->data['customer_lastname'] = '';
      		$this->data['customer_email'] = '';
      		$this->data['customer_telephone'] = '';
      		$this->data['customer_fax'] = '';
      		$this->data['customer_newsletter'] = '';
      		$this->data['customer_customer_group_id'] = $this->config->get('config_customer_group_id');
      		$this->data['customer_status'] = 1;
			$this->data['customer_password'] = '';
			$this->data['customer_confirm'] = '';
			$this->data['customer_addresses'] = array();
      		$this->data['customer_address_id'] = '';
    	}

		$this->load->model('sale/customer_group');
		$this->data['customer_customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();

		$this->load->model('localisation/country');
		$this->data['customer_countries'] = $this->model_localisation_country->getCountries();
	}
	
	public function save_order() {
		$json = array();
		$this->load->library('user');
		$this->user = new User($this->registry);
		if ($this->user->isLogged() && $this->user->hasPermission('modify', 'sale/order')) {
			$this->load->model('sale/order');
			$this->model_sale_order->editOrder($this->request->get['order_id'], $this->request->post);
		} else {
			$json['error']['warning'] = $this->language->get('error_permission');
		}
		$this->response->setOutput(json_encode($json));
	}
	
	public function save_customer() {
		$json = array();
		$json['hasAddress'] = 1;
		$this->load->library('user');
		$this->user = new User($this->registry);
		if ($this->user->isLogged() && $this->user->hasPermission('modify', 'sale/customer')) {
			$data = array();
			$keys = array_keys($this->request->post);
			foreach ($keys as $key) {
				$value = $this->request->post[$key];
				if ($key == 'customer_address') {
					foreach ($value as $address) {
						if (isset($address['default']) && $address['default']) {
							$json['hasAddress'] = 2;
							break;
						}
					}
				}
				if (strpos($key, 'customer_') === 0) {
					$dataKey = substr($key, 9);
					$data[$dataKey] = $value;
				}
			}
			
			if (isset($this->request->get['customer_id'])) {
				$data['customer_id'] = $this->request->get['customer_id'];
			}
			if (!empty($data) && isset($data['customer_id'])) {
				$this->load->model('sale/customer');
				$this->model_sale_customer->editCustomer($data['customer_id'], $data);
				$customer_addresses = $this->model_sale_customer->getAddresses((int)$data['customer_id']);
				$this->language->load('module/pos');
				$json['success'] = $this->language->get('text_customer_success');
			}
		} else {
			$json['error']['warning'] = $this->language->get('error_permission');
		}
		$this->response->setOutput(json_encode($json));
	}
	
	private function getStoreId() {
		if (isset($this->request->get['store_id'])) {
			$store_id = $this->request->get['store_id'];
		} else {
			$store_id = 0;
			// get the default store id
			$this->load->model('setting/store');
			$stores = $this->model_setting_store->getStores();
			if (!empty($stores)) {
				$store_id = $stores[0]['store_id'];
			}
		}
		return $store_id;
	}
	
	public function createEmptyOrder() {
		// create an empty order with default / dummy customer data
		unset($this->session->data['shipping_method']);
		
		$data = array();
		
		$data['store_id'] = $this->getStoreId();
		
		$default_country_id = $this->config->get('config_country_id');
		$default_zone_id = $this->config->get('config_zone_id');
		$data['shipping_country_id'] = $default_country_id;
		$data['shipping_zone_id'] = $default_zone_id;
		$data['payment_country_id'] = $default_country_id;
		$data['payment_zone_id'] = $default_zone_id;
		$data['customer_id'] = 0;
		$data['customer_group_id'] = 1;
		$data['firstname'] = 'Instore';
		$data['lastname'] = "Dummy";
		$data['email'] = 'customer@instore.com';
		$data['telephone'] = '1600';
		$data['fax'] = '';
		$data['payment_firstname'] = 'Instore';
		$data['payment_lastname'] = "Dummy";
		$data['payment_company'] = '';
		$data['payment_company_id'] = '';
		$data['payment_tax_id'] = '';
		$data['payment_address_1'] = 'customer address';
		$data['payment_address_2'] = '';
		$data['payment_city'] = 'customer city';
		$data['payment_postcode'] = '1600';
		$data['payment_country_id'] = $default_country_id;
		$data['payment_zone_id'] = $default_zone_id;
		$data['payment_method'] = 'In Store';
		$data['payment_code'] = 'in_store';
		$data['shipping_firstname'] = 'Instore';
		$data['shipping_lastname'] = 'Dummy';
		$data['shipping_company'] = '';
		$data['shipping_address_1'] = 'customer address';
		$data['shipping_address_2'] = '';
		$data['shipping_city'] = 'customer city';
		$data['shipping_postcode'] = '1600';
		$data['shipping_country_id'] = $default_country_id;
		$data['shipping_zone_id'] = $default_zone_id;
		$data['shipping_method'] = 'Pickup From Store';
		$data['shipping_code'] = 'pickup.pickup';
		$data['comment'] = '';
		$data['order_status_id'] = 1;
		$data['affiliate_id'] = 0;
		$data['user_id'] = $this->user->getId();
				
		$this->load->model('pos/pos');
		$order_id = $this->model_pos_pos->addOrder($data);
		$context = 'token=' . $this->session->data['token'] . '&order_id=' . $order_id;
		$result = $this->url->link('module/pos/main', $context, 'SSL');
		$this->redirect($result);
	}

	public function detachCustomer() {
		$customer = array();
		
		$default_country_id = $this->config->get('config_country_id');
		$default_zone_id = $this->config->get('config_zone_id');
		$customer['customer_id'] = 0;
		$customer['customer_group_id'] = 1;
		$customer['firstname'] = 'Instore';
		$customer['lastname'] = "Dummy";
		$customer['email'] = 'customer@instore.com';
		$customer['telephone'] = '1600';
		$customer['fax'] = '';
		$customer['payment_firstname'] = 'Instore';
		$customer['payment_lastname'] = "Dummy";
		$customer['payment_company'] = '';
		$customer['payment_company_id'] = '';
		$customer['payment_tax_id'] = '';
		$customer['payment_address_1'] = 'customer address';
		$customer['payment_address_2'] = '';
		$customer['payment_city'] = 'customer city';
		$customer['payment_postcode'] = '1600';
		$customer['payment_country_id'] = $default_country_id;
		$customer['payment_zone_id'] = $default_zone_id;
		$customer['payment_method'] = 'In Store';
		$customer['payment_code'] = 'in_store';
		$customer['shipping_firstname'] = 'Instore';
		$customer['shipping_lastname'] = 'Dummy';
		$customer['shipping_company'] = '';
		$customer['shipping_address_1'] = 'customer address';
		$customer['shipping_address_2'] = '';
		$customer['shipping_city'] = 'customer city';
		$customer['shipping_postcode'] = '1600';
		$customer['shipping_country_id'] = $default_country_id;
		$customer['shipping_zone_id'] = $default_zone_id;
		
		$this->load->model('localisation/country');
		$this->load->model('localisation/zone');
		$country_info = $this->model_localisation_country->getCountry($default_country_id);
		if ($country_info) {
			$payment_country = $country_info['name'];
			$payment_address_format = $country_info['address_format'];			
		} else {
			$payment_country = '';	
			$payment_address_format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';					
		}
		$shipping_country = $payment_country;
		$shipping_address_format = $payment_address_format;
		$zone_info = $this->model_localisation_zone->getZone($default_zone_id);
		if ($zone_info) {
			$payment_zone = $zone_info['name'];
		} else {
			$payment_zone = '';			
		}			
		$shipping_zone = $payment_zone;
		
		$order_id = $this->request->get['order_id'];
		$this->db->query("UPDATE `" . DB_PREFIX . "order` SET customer_id = '0', customer_group_id = '1', firstname = '" . $this->db->escape($customer['firstname']) ."', lastname = '" . $this->db->escape($customer['lastname']) . "', email = '" . $this->db->escape($customer['email']) . "', telephone = '" . $this->db->escape($customer['telephone']) . "', fax = '" . $this->db->escape($customer['fax']) . "', payment_firstname = '" . $this->db->escape($customer['payment_firstname']) . "', payment_lastname = '" . $this->db->escape($customer['payment_lastname']) . "', payment_company = '" . $this->db->escape($customer['payment_company']) . "', payment_company_id = '" . $this->db->escape($customer['payment_company_id']) . "', payment_tax_id = '" . $this->db->escape($customer['payment_tax_id']) . "', payment_address_1 = '" . $this->db->escape($customer['payment_address_1']) . "', payment_address_2 = '" . $this->db->escape($customer['payment_address_2']) . "', payment_city = '" . $this->db->escape($customer['payment_city']) . "', payment_postcode = '" . $this->db->escape($customer['payment_postcode']) . "', payment_country = '" . $this->db->escape($payment_country) . "', payment_country_id = '" . (int)$customer['payment_country_id'] . "', payment_zone = '" . $this->db->escape($payment_zone) . "', payment_zone_id = '" . (int)$customer['payment_zone_id'] . "', payment_address_format = '" . $this->db->escape($payment_address_format) . "', shipping_firstname = '" . $this->db->escape($customer['shipping_firstname']) . "', shipping_lastname = '" . $this->db->escape($customer['shipping_lastname']) . "',  shipping_company = '" . $this->db->escape($customer['shipping_company']) . "', shipping_address_1 = '" . $this->db->escape($customer['shipping_address_1']) . "', shipping_address_2 = '" . $this->db->escape($customer['shipping_address_2']) . "', shipping_city = '" . $this->db->escape($customer['shipping_city']) . "', shipping_postcode = '" . $this->db->escape($customer['shipping_postcode']) . "', shipping_country = '" . $this->db->escape($shipping_country) . "', shipping_country_id = '" . (int)$customer['shipping_country_id'] . "', shipping_zone = '" . $this->db->escape($shipping_zone) . "', shipping_zone_id = '" . (int)$customer['shipping_zone_id'] . "', shipping_address_format = '" . $this->db->escape($shipping_address_format) . "', date_modified = NOW() WHERE order_id = '" . (int)$order_id . "'");
		$this->language->load('module/pos');
		$customer['success'] = $this->language->get('text_order_sucess');
		$this->response->setOutput(json_encode($customer));	
	}

	public function modifyOrder() {
		$this->language->load('module/pos');
		
		$json = array();
		
		$order_id = $this->request->post['order_id'];
		
		$product_id = '';
		if (isset($this->request->post['order_product'])) {
			$order_product = $this->request->post['order_product'];
			$product_id = $order_product['product_id'];
			if ($order_product['action'] == 'insert') {
      			$this->db->query("INSERT INTO " . DB_PREFIX . "order_product SET order_id = '" . (int)$order_id . "', product_id = '" . (int)$order_product['product_id'] . "', name = '" . $this->db->escape($order_product['name']) . "', model = '" . $this->db->escape($order_product['model']) . "', quantity = '" . (int)$order_product['quantity'] . "', price = '" . (float)$order_product['price'] . "', total = '" . (float)$order_product['total'] . "', tax = '" . (float)$order_product['tax'] . "', reward = '" . (int)$order_product['reward'] . "'");
			
				$order_product_id = $this->db->getLastId();
				
				$this->db->query("UPDATE " . DB_PREFIX . "product SET quantity = (quantity - " . (int)$order_product['quantity'] . ") WHERE product_id = '" . (int)$order_product['product_id'] . "' AND subtract = '1'");
				
				if (isset($order_product['option'])) {
					foreach ($order_product['option'] as $order_option) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "order_option SET order_id = '" . (int)$order_id . "', order_product_id = '" . (int)$order_product_id . "', product_option_id = '" . (int)$order_option['product_option_id'] . "', product_option_value_id = '" . (int)$order_option['product_option_value_id'] . "', name = '" . $this->db->escape($order_option['name']) . "', `value` = '" . $this->db->escape($order_option['option_value']) . "', `type` = '" . $this->db->escape($order_option['type']) . "'");
						
						$this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET quantity = (quantity - " . (int)$order_product['quantity'] . ") WHERE product_option_value_id = '" . (int)$order_option['product_option_value_id'] . "' AND subtract = '1'");
					}
				}
			} elseif ($order_product['action'] == 'modify') {
				$sqlQuery = "UPDATE " . DB_PREFIX . "order_product SET quantity = " . (int)$order_product['quantity'] . ", total = '" . (float)$order_product['total'] . "' WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$order_product['order_product_id'] . "'";
      			$this->db->query($sqlQuery);
				$this->db->query("UPDATE " . DB_PREFIX . "product SET quantity = (quantity - " . (int)$order_product['quantity_change'] . ") WHERE product_id = '" . (int)$order_product['product_id'] . "' AND subtract = '1'");
				
				if (isset($order_product['option'])) {
					foreach ($order_product['option'] as $order_option) {
						$this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET quantity = (quantity - " . (int)$order_product['quantity_change'] . ") WHERE product_option_value_id = '" . (int)$order_option['product_option_value_id'] . "' AND subtract = '1'");
					}
				}
			}
		} else {
			$action = $this->request->post['action'];
			if ($action != 'new' && $action != 'insert') {
				$product_id = $this->request->post['product_id'];
				$ex_order_product_id = $this->request->post['order_product_id'];
				if ($action == 'delete') {
					$this->db->query("UPDATE " . DB_PREFIX . "product SET quantity = (quantity + " . (int)$this->request->post['quantity'] . ") WHERE product_id = '" . (int)$this->request->post['product_id'] . "' AND subtract = '1'");
					
					if (!empty($this->request->post['option'])) {
						foreach ($this->request->post['option'] as $order_option) {
							$this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET quantity = (quantity + " . (int)$this->request->post['quantity'] . ") WHERE product_option_value_id = '" . (int)$order_option['product_option_value_id'] . "' AND subtract = '1'");
						}
					}
					$this->db->query("DELETE FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$ex_order_product_id . "'");
					$this->db->query("DELETE FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$ex_order_product_id . "'");
					$this->db->query("DELETE FROM " . DB_PREFIX . "order_download WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$ex_order_product_id . "'");
				} elseif ($action == 'modify') {
					$sqlQuery = "UPDATE " . DB_PREFIX . "order_product SET quantity = " . (int)$this->request->post['quantity'] . ", total = '" . (float)$this->request->post['total'] . "' WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$ex_order_product_id . "'";
					$this->db->query($sqlQuery);
					$this->db->query("UPDATE " . DB_PREFIX . "product SET quantity = (quantity - " . (int)$this->request->post['quantity_change'] . ") WHERE product_id = '" . (int)$this->request->post['product_id'] . "' AND subtract = '1'");
					
					if (!empty($this->request->post['option'])) {
						foreach ($this->request->post['option'] as $order_option) {
							$this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET quantity = (quantity - " . (int)$this->request->post['quantity_change'] . ") WHERE product_option_value_id = '" . (int)$order_option['product_option_value_id'] . "' AND subtract = '1'");
						}
					}
				}
			}
		}
		
		// Get the total
		$total = 0;
				
		$this->db->query("DELETE FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$order_id . "'");
		
		if (isset($this->request->post['order_total'])) {		
      		foreach ($this->request->post['order_total'] as $order_total) {	
      			$this->db->query("INSERT INTO " . DB_PREFIX . "order_total SET order_id = '" . (int)$order_id . "', code = '" . $this->db->escape($order_total['code']) . "', title = '" . $this->db->escape($order_total['title']) . "', text = '" . $this->db->escape($order_total['text']) . "', `value` = '" . (float)$order_total['value'] . "', sort_order = '" . (int)$order_total['sort_order'] . "'");
			}
			
			$total += $order_total['value'];
		}
		$this->db->query("UPDATE `" . DB_PREFIX . "order` SET total = '" . (float)$total . "', date_modified = NOW() WHERE order_id = '" . (int)$order_id . "'"); 
		
		if (isset($order_product_id)) {
			$json['order_product_id'] = $order_product_id;
		}
		$json['success'] = $this->language->get('text_order_sucess');
		if ($product_id != '') {
			$json['product_id'] = $product_id;
		}
		$this->response->setOutput(json_encode($json));	
	}
	
	public function saveOrderStatus() {
		$order_id = $this->request->post['order_id'];
		$order_status_id = $this->request->post['order_status_id'];
		$this->load->model('pos/pos');
		$this->model_pos_pos->saveOrderStatus($order_id, $order_status_id);

		$this->language->load('module/pos');
		$json['success'] = $this->language->get('text_order_sucess');
		$this->response->setOutput(json_encode($json));	
	}
	
	public function saveOrderCustomer() {
		$json = array();
		$order_id = $this->request->get['order_id'];
		$sql = "UPDATE `" . DB_PREFIX . "order` SET store_id = '" . $this->request->post['store_id'] . "', customer_id = '" . $this->request->post['customer_id'] . "', customer_group_id = '" . $this->request->post['customer_group_id'] . "', firstname = '" . $this->db->escape($this->request->post['firstname']) ."', lastname = '" . $this->db->escape($this->request->post['lastname']) . "', email = '" . $this->db->escape($this->request->post['email']) . "', telephone = '" . $this->db->escape($this->request->post['telephone']) . "', fax = '" . $this->db->escape($this->request->post['fax']) . "', date_modified = NOW()";
		if (((int)$this->request->post['customer_id']) > 0) {
			$json['hasAddress'] = 1;
			// switch to a real customer
			$this->load->model('sale/customer');
			$customer_info = $this->model_sale_customer->getCustomer((int)$this->request->post['customer_id']);
			$json['customer_info'] = $customer_info;
			$json['customer_addresses'] = $this->model_sale_customer->getAddresses((int)$this->request->post['customer_id']);
			foreach ($json['customer_addresses'] as $address) {
				if ($customer_info['address_id'] == $address['address_id']) {
					// update the order shipping address and payment address
					$sql .= ", payment_firstname = '" . $this->db->escape($address['firstname']) . "', payment_lastname = '" . $this->db->escape($address['lastname']) . "', payment_company = '" . $this->db->escape($address['company']) . "', payment_company_id = '" . $this->db->escape($address['company_id']) . "', payment_tax_id = '" . $this->db->escape($address['tax_id']) . "', payment_address_1 = '" . $this->db->escape($address['address_1']) . "', payment_address_2 = '" . $this->db->escape($address['address_2']) . "', payment_city = '" . $this->db->escape($address['city']) . "', payment_postcode = '" . $this->db->escape($address['postcode']) . "', payment_country = '" . $this->db->escape($address['country']) . "', payment_country_id = '" . (int)$address['country_id'] . "', payment_zone = '" . $this->db->escape($address['zone']) . "', payment_zone_id = '" . (int)$address['zone_id'] . "', shipping_firstname = '" . $this->db->escape($address['firstname']) . "', shipping_lastname = '" . $this->db->escape($address['lastname']) . "',  shipping_company = '" . $this->db->escape($address['company']) . "', shipping_address_1 = '" . $this->db->escape($address['address_1']) . "', shipping_address_2 = '" . $this->db->escape($address['address_2']) . "', shipping_city = '" . $this->db->escape($address['city']) . "', shipping_postcode = '" . $this->db->escape($address['postcode']) . "', shipping_country = '" . $this->db->escape($address['country']) . "', shipping_country_id = '" . (int)$address['country_id'] . "', shipping_zone = '" . $this->db->escape($address['zone']) . "', shipping_zone_id = '" . (int)$address['zone_id'] . "'";
					$json['hasAddress'] = 2;
					$json['country_id'] = $address['country_id'];
					$json['zone_id'] = $address['zone_id'];
					break;
				}
			}
			$this->load->model('localisation/country');
			$json['customer_countries'] = $this->model_localisation_country->getCountries();
		}
		$sql .= " WHERE order_id = '" . (int)$order_id . "'";
		$this->db->query($sql);
		$this->language->load('module/pos');
		$json['success'] = $this->language->get('text_order_sucess');
		$this->response->setOutput(json_encode($json));	
	}
	
	// add for Browse begin
	public function getCategoryTree() {
		// get the category tree in the catalog database
		$this->load->model('pos/pos');
		$categories = $this->model_pos_pos->getCategories();
		// convert the array to an tree-like array
		$category_tree = array();
		$parent_id_list = array();
		foreach ($categories as $category) {
			$parent_id_list[] = $category['parent_id'];
		}
		$this->convert2Tree($categories, $parent_id_list, $category_tree);
		
		$json = array();
		$json['category_tree'] = $category_tree;
		$this->response->setOutput(json_encode($json));
	}
	
	private function getCategoryItems($parent_category_id, $currency_code, $currency_value) {
		// get the direct sub-category and product in the given category
		$this->load->model('pos/pos');
		$sub_categories = $this->model_pos_pos->getSubCategories($parent_category_id);
		$products = $this->model_pos_pos->getProducts($parent_category_id);
		
		$this->language->load('module/pos');
		$browse_items = array();
		foreach ($sub_categories as $sub_category) {
			$browse_items[] = array('type' => 'C',
								'name' => $sub_category['name'],
								'image' => !empty($sub_category['image']) ? '../image/'.$sub_category['image'] : 'view/image/pos/no_image.jpg',
								'id' => $sub_category['category_id']);
		}
		foreach ($products as $product) {
			$browse_items[] = array('type' => 'P',
								'name' => $product['name'],
								'image' => !empty($product['image']) ? '../image/'.$product['image'] : 'view/image/pos/no_image.jpg',
								'price_text' => $this->currency->format($product['price'], $currency_code, $currency_value),
								'stock_text' => $product['quantity'], // . ' ' . $this->language->get('text_remaining'),
								'hasOptions' => $product['options'] ? '1' : '0',
								'id' => $product['product_id']);
		}
		
		return $browse_items;
	}
	
	public function getCategoryItemsAjax() {
		$parent_category_id = 0;
		if (isset($this->request->post['category_id'])) {
			$parent_category_id = $this->request->post['category_id'];
		}
		
		$json = array();
		$json['browse_items'] = $this->getCategoryItems($parent_category_id, $this->request->post['currency_code'], $this->request->post['currency_value']);
		// the above step already has model pos/pos include
		if (version_compare(VERSION, '1.5.5', '<')) {
			$category_path = $this->model_pos_pos->getCategoryFullPathOld($parent_category_id);
		} else {
			$category_path = $this->model_pos_pos->getCategoryFullPath($parent_category_id);
			if ($category_path) {
				$category_path = $category_path['name'];
			}
		}
		$json['path'] = array();
		if ($category_path) {
			$pathes = explode('!|||!', $category_path);
			$json['path'] = array();
			foreach ($pathes as $path) {
				$names = explode('|||', $path);
				$json['path'][] = array('id' => $names[0], 'name' => $names[1]);
			}
		}
		
		$this->response->setOutput(json_encode($json));
	}
	
	private function convert2Tree($categories, $parent_id_list, &$parent_category, $parent_id = 0) {
		// find the sub categories under the given parent category with id $parent_id
		foreach ($categories as $category) {
			if ($category['parent_id'] == $parent_id) {
				// add it into the parent category array
				$category_names = explode(' &gt; ', $category['name']);
				$category_name = $category_names[sizeof($category_names)-1];
				$sub_category = array();
				if (in_array($category['category_id'], $parent_id_list)) {
					// the category still has sub categories
					$this->convert2Tree($categories, $parent_id_list, $sub_category, $category['category_id']);
				}
				array_push($parent_category, array('id' => $category['category_id'], 'name' => $category_name, 'subs' => $sub_category));
			}
		}
	}
	
	public function getProductOptions() {
		$json = array();
		$option_data = array();
		
		$this->load->model('catalog/product');
		$this->load->model('catalog/option');
		$product_options = $this->model_catalog_product->getProductOptions($this->request->get['product_id']);
		
		foreach ($product_options as $product_option) {
			$option_info = $this->model_catalog_option->getOption($product_option['option_id']);
			
			if ($option_info) {				
				if ($option_info['type'] == 'select' || $option_info['type'] == 'radio' || $option_info['type'] == 'checkbox' || $option_info['type'] == 'image') {
					$option_value_data = array();
					
					foreach ($product_option['product_option_value'] as $product_option_value) {
						$option_value_info = $this->model_catalog_option->getOptionValue($product_option_value['option_value_id']);
				
						if ($option_value_info) {
							$option_value_data[] = array(
								'product_option_value_id' => $product_option_value['product_option_value_id'],
								'option_value_id'         => $product_option_value['option_value_id'],
								'name'                    => $option_value_info['name'],
								'price'                   => (float)$product_option_value['price'] ? $this->currency->format($product_option_value['price'], $this->config->get('config_currency')) : false,
								'price_prefix'            => $product_option_value['price_prefix']
							);
						}
					}
				
					$option_data[] = array(
						'product_option_id' => $product_option['product_option_id'],
						'option_id'         => $product_option['option_id'],
						'name'              => $option_info['name'],
						'type'              => $option_info['type'],
						'option_value'      => $option_value_data,
						'required'          => $product_option['required']
					);	
				} else {
					$option_data[] = array(
						'product_option_id' => $product_option['product_option_id'],
						'option_id'         => $product_option['option_id'],
						'name'              => $option_info['name'],
						'type'              => $option_info['type'],
						'option_value'      => $product_option['option_value'],
						'required'          => $product_option['required']
					);				
				}
			}
		}
		
		$json['option_data'] = $option_data;
		$this->response->setOutput(json_encode($json));
	}
	// add for Browse end
}
?>