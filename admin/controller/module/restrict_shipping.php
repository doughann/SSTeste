<?php
//==============================================================================
// Restrict Shipping Methods v203.1
// 
// Author: Clear Thinking, LLC
// E-mail: johnathan@getclearthinking.com
// Website: http://www.getclearthinking.com
// 
// All code within this file is copyright Clear Thinking, LLC.
// You may not copy or reuse code within this file without written permission.
//==============================================================================

class ControllerModuleRestrictShipping extends Controller {
	private $type = 'module';
	private $name = 'restrict_shipping';
	
	public function index() {
		$data = array(
			'type'				=> $this->type,
			'name'				=> $this->name,
			'autobackup'		=> true,
			'save_type'			=> 'keepediting',
			'token'				=> $this->session->data['token'],
			'permission'		=> $this->user->hasPermission('modify', $this->type . '/' . $this->name),
			'exit'				=> $this->url->link('extension/' . $this->type . '&token=' . $this->session->data['token'], '', 'SSL'),
		);
		
		$this->loadSettings($data);
		
		//------------------------------------------------------------------------------
		// Data Arrays
		//------------------------------------------------------------------------------
		$data['rule_options'] = array(
			'adjustments'				=> array('setting_override', 'total_value'),
			'cart_criteria'				=> array('length', 'width', 'height', 'quantity', 'stock', 'total', 'volume', 'weight'),
			'datetime_criteria'			=> array('day', 'date', 'time'),
			'location_criteria'			=> array('city', 'distance', 'geo_zone', 'location_comparison', 'postcode'),
			'order_criteria'			=> array('currency', 'customer_group', 'language', 'past_orders', 'shipping_rate', 'store'),
			'product_criteria'			=> array('category', 'manufacturer', 'product', 'product_group'),
			'rule_sets'					=> array('rule_set'),
		);
		
		$data['setting_override_array'] = array(
			array((version_compare(VERSION, '2.0') < 0 ? 'group' : 'code') => 'config', 'key' => 'config_address', 'value' => $this->config->get('config_address')),
		);
		
		$data['currency_array'] = array($this->config->get('config_currency') => '');
		$this->load->model('localisation/currency');
		foreach ($this->model_localisation_currency->getCurrencies() as $currency) {
			$data['currency_array'][$currency['code']] = $currency['code'];
		}
		
		$data['customer_group_array'] = array(0 => $data['text_guests']);
		$this->load->model('sale/customer_group');
		foreach ($this->model_sale_customer_group->getCustomerGroups() as $customer_group) {
			$data['customer_group_array'][$customer_group['customer_group_id']] = $customer_group['name'];
		}
		
		$data['geo_zone_array'] = array(0 => $data['text_everywhere_else']);
		$this->load->model('localisation/geo_zone');
		foreach ($this->model_localisation_geo_zone->getGeoZones() as $geo_zone) {
			$data['geo_zone_array'][$geo_zone['geo_zone_id']] = $geo_zone['name'];
		}
		
		$this->load->model('localisation/language');
		$data['language_array'] = array($this->config->get('config_language') => '');
		$data['language_flags'] = array();
		foreach ($this->model_localisation_language->getLanguages() as $language) {
			$data['language_array'][$language['code']] = $language['name'];
			$data['language_flags'][$language['code']] = $language['image'];
		}
		
		$data['store_array'] = array(0 => $this->config->get('config_name'));
		$store_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "store ORDER BY name");
		foreach ($store_query->rows as $store) {
			$data['store_array'][$store['store_id']] = $store['name'];
		}
		
		$data['tax_class_array'] = array(0 => $data['text_none']);
		$this->load->model('localisation/tax_class');
		foreach ($this->model_localisation_tax_class->getTaxClasses() as $tax_class) {
			$data['tax_class_array'][$tax_class['tax_class_id']] = $tax_class['title'];
		}
		
		$data['total_value_array'] = array();
		foreach (array('prediscounted', 'nondiscounted', 'taxed', 'total') as $total_value) {
			$data['total_value_array'][$total_value] = $data['text_' . $total_value . '_subtotal'];
		}
		
		$data['quantity_unit'] = $data['text_items'];
		$data['stock_unit'] = $data['text_items'];
		$left_symbol = $this->currency->getSymbolLeft();
		$data['total_unit'] = ($left_symbol) ? $left_symbol : $this->currency->getSymbolRight();
		$length = $this->db->query("SELECT * FROM " . DB_PREFIX . "length_class_description WHERE length_class_id = " . (int)$this->config->get('config_length_class_id'));
		$data['length_unit'] = $length->row['unit'];
		$data['width_unit'] = $length->row['unit'];
		$data['height_unit'] = $length->row['unit'];
		$data['volume_unit'] = $length->row['unit'] . '&sup3;';
		$weight = $this->db->query("SELECT * FROM " . DB_PREFIX . "weight_class_description WHERE weight_class_id = " . (int)$this->config->get('config_weight_class_id'));
		$data['weight_unit'] = $weight->row['unit'];
		
		$data['typeaheads'] = array('category', 'manufacturer', 'product');
		if (!empty($data['saved']['autocomplete_preloading'])) {
			$data['all_preload'] = '';
			foreach ($data['typeaheads'] as $typeahead_type) {
				$data[$typeahead_type . '_preload'] = array();
				if ($typeahead_type == 'customer') {
					$data_query = $this->db->query("SELECT customer_id, CONCAT(firstname, ' ', lastname, ' (', email, ')') as name FROM " . DB_PREFIX . "customer");
				} elseif ($typeahead_type == 'manufacturer') {
					$data_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "manufacturer");
				} else {
					$data_query = $this->db->query("SELECT * FROM " . DB_PREFIX . $typeahead_type . "_description");
				}
				foreach ($data_query->rows as $row) {
					if ($typeahead_type == 'category') {
						$category_id = $row['category_id'];
						$parent_exists = true;
						while ($parent_exists) {
							$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_description WHERE category_id = (SELECT parent_id FROM " . DB_PREFIX . "category WHERE category_id = " . (int)$category_id . ")");
							if (!empty($query->row['name'])) {
								$category_id = $query->row['category_id'];
								$row['name'] = $query->row['name'] . ' > ' . $row['name'];
							} else {
								$parent_exists = false;
							}
						}
					}
					$row_name = str_replace(array("\n", '"'), array(' ', '&quot;'), html_entity_decode($row['name'], ENT_NOQUOTES, 'UTF-8'));
					$data['all_preload'][] = '"' . $row_name . ' [' . $typeahead_type . ':' . $row[$typeahead_type . '_id'] . ']",';
					$data[$typeahead_type . '_preload'][] = '"' . $row_name . ' [' . $typeahead_type . ':' . $row[$typeahead_type . '_id'] . ']",';
				}
				natcasesort($data[$typeahead_type . '_preload']);
				$data[$typeahead_type . '_preload'] = implode('', $data[$typeahead_type . '_preload']);
			}
			natcasesort($data['all_preload']);
			$data['all_preload'] = implode('', $data['all_preload']);
		}
		
		$data['product_groups'] = array();
		$data['rule_sets'] = array();
		foreach ($data['saved'] as $key => $setting) {
			if (preg_match('/product_group_(\d+)_name/', $key)) {
				$data['product_groups'][str_replace(array('product_group_', '_name'), '', $key)] = $setting;
			} elseif (preg_match('/rule_set_(\d+)_name/', $key)) {
				$data['rule_sets'][str_replace(array('rule_set_', '_name'), '', $key)] = $setting;
			}
		}
		
		foreach (array('shipping', 'payment') as $extension_type) {
			$data[$extension_type . '_extension_array'] = array();
			$extension_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension WHERE `type` = '" . $this->db->escape($extension_type) . "' ORDER BY `code` ASC");
			foreach ($extension_query->rows as $extension) {
				if ($extension['code'] == $this->name) continue;
				$this->load->language($extension_type . '/' . $extension['code']);
				$data[$extension_type . '_extension_array'][$extension['code']] = $this->language->get('heading_title');
			}
		}
		
		//------------------------------------------------------------------------------
		// Extensions Settings
		//------------------------------------------------------------------------------
		$data['settings'] = array();
		
		$data['settings'][] = array(
			'type'		=> 'tabs',
			'tabs'		=> array('extension_settings', 'restrictions', 'product_groups', 'rule_sets'),
		);
		$data['settings'][] = array(
			'key'		=> 'extension_settings',
			'type'		=> 'heading',
			'buttons'	=> 'backup_restore',
		);
		$data['settings'][] = array(
			'key'		=> 'status',
			'type'		=> 'select',
			'options'	=> array(0 => $data['text_disabled'], 1 => $data['text_enabled']),
		);
		if ($this->type == 'shipping') {
			$data['settings'][] = array(
				'key'		=> 'heading',
				'type'		=> 'multilingual_text',
				'default'	=> $data['heading_title'],
			);
		}
		if ($this->type != 'module') {
			$data['settings'][] = array(
				'key'		=> 'sort_order',
				'type'		=> 'text',
				'default'	=> ($this->type == 'shipping' ? 1 : 3),
				'class'		=> 'short',
				'attributes'=> array('maxlength' => 2),
			);
			$data['settings'][] = array(
				'key'		=> 'tax_class_id',
				'type'		=> 'select',
				'options'	=> $data['tax_class_array'],
			);
		}
		if ((isset($data['rule_options']['location_criteria']) && in_array('distance', $data['rule_options']['location_criteria'])) || strpos($this->name, 'distance_based') === 0) {
			$data['settings'][] = array(
				'key'		=> 'distance_units',
				'type'		=> 'select',
				'options'	=> array('mi' => $data['text_miles'], 'km' => $data['text_kilometers']),
			);
		}
		$data['settings'][] = array(
			'key'		=> 'testing_mode',
			'type'		=> 'select',
			'options'	=> array(0 => $data['text_disabled'], 1 => $data['text_enabled']),
		);
				
		// Admin Panel Settings
		$data['settings'][] = array(
			'key'		=> 'admin_panel_settings',
			'type'		=> 'heading',
		);
		$data['settings'][] = array(
			'key'		=> 'autosave',
			'type'		=> 'select',
			'options'	=> array(0 => $data['text_disabled'], 1 => $data['text_enabled']),
		);
		if (!empty($data['typeaheads'])) {
			$data['settings'][] = array(
				'key'		=> 'autocomplete_preloading',
				'type'		=> 'select',
				'options'	=> array(0 => $data['text_disabled'], 1 => $data['text_enabled']),
			);
		}
		$data['settings'][] = array(
			'key'		=> 'display',
			'type'		=> 'select',
			'options'	=> array('expanded' => $data['text_expanded'], 'collapsed' => $data['text_collapsed']),
		);
		$data['settings'][] = array(
			'key'		=> 'tooltips',
			'type'		=> 'select',
			'options'	=> array(0 => $data['text_disabled'], 1 => $data['text_enabled']),
			'default'	=> 1,
		);
		
		//------------------------------------------------------------------------------
		// Restrictions
		//------------------------------------------------------------------------------
		$data['settings'][] = array(
			'key'		=> 'restrictions',
			'type'		=> 'tab',
		);
		$data['settings'][] = array(
			'type'		=> 'html',
			'content'	=> '<div class="text-info text-center" style="padding-bottom: 5px">' . $data['help_restrictions'] . '</div>',
		);
		$data['settings'][] = array(
			'key'		=> 'restrictions',
			'type'		=> 'heading',
			'buttons'	=> 'expand_collapse',
		);
		$data['settings'][] = array(
			'key'		=> 'restriction',
			'type'		=> 'table_start',
			'columns'	=> array('action', 'name', 'method', 'rules'),
		);
		
		$table = 'restriction';
		$sortby = 'name';
		foreach ($this->getTableRowNumbers($data, $table, $sortby) as $num => $rules) {
			$prefix = $table . '_' . $num . '_';
			$data['settings'][] = array(
				'type'		=> 'row_start',
			);
			$data['settings'][] = array(
				'key'		=> 'expand_collapse',
				'type'		=> 'button',
			);
			$data['settings'][] = array(
				'key'		=> 'copy',
				'type'		=> 'button',
			);
			$data['settings'][] = array(
				'key'		=> 'delete',
				'type'		=> 'button',
			);
			$data['settings'][] = array(
				'type'		=> 'column',
			);
			$data['settings'][] = array(
				'key'		=> $prefix . 'name',
				'type'		=> 'text',
			);
			$data['settings'][] = array(
				'type'		=> 'column',
			);
			$data['settings'][] = array(
				'key'		=> $prefix . 'methods',
				'type'		=> 'checkboxes',
				'options'	=> $data['shipping_extension_array'],
			);
			$data['settings'][] = array(
				'type'		=> 'column',
			);
			$data['settings'][] = array(
				'key'		=> $prefix . 'rule',
				'type'		=> 'rule',
				'rules'		=> $rules,
			);
			$data['settings'][] = array(
				'type'		=> 'row_end',
			);
		}
		
		$data['settings'][] = array(
			'type'		=> 'table_end',
			'buttons'	=> 'add_row',
			'text'		=> 'button_add_restriction',
		);

		//------------------------------------------------------------------------------
		// Product Groups
		//------------------------------------------------------------------------------
		$data['settings'][] = array(
			'key'		=> 'product_groups',
			'type'		=> 'tab',
		);
		$data['settings'][] = array(
			'type'		=> 'html',
			'content'	=> '<div class="text-info text-center" style="padding-bottom: 5px">' . $data['help_product_groups'] . '</div>',
		);
		$data['settings'][] = array(
			'key'		=> 'product_groups',
			'type'		=> 'heading',
			'buttons'	=> 'expand_collapse',
		);
		
		$table = 'product_group';
		$sortby = 'sort_order';
		$data['settings'][] = array(
			'key'		=> $table,
			'type'		=> 'table_start',
			'columns'	=> array('action', 'sort_order', 'name', 'group_members', ''),
		);
		foreach ($this->getTableRowNumbers($data, $table, $sortby) as $num => $rules) {
			$prefix = $table . '_' . $num . '_';
			$data['settings'][] = array(
				'type'		=> 'row_start',
			);
			$data['settings'][] = array(
				'key'		=> 'expand_collapse',
				'type'		=> 'button',
			);
			$data['settings'][] = array(
				'key'		=> 'copy',
				'type'		=> 'button',
			);
			$data['settings'][] = array(
				'key'		=> 'delete',
				'type'		=> 'button',
			);
			$data['settings'][] = array(
				'type'		=> 'column',
			);
			$data['settings'][] = array(
				'key'		=> $prefix . 'sort_order',
				'type'		=> 'text',
				'class'		=> 'short',
				'attributes'=> array('maxlength' => 2),
			);
			$data['settings'][] = array(
				'type'		=> 'column',
			);
			$data['settings'][] = array(
				'key'		=> $prefix . 'name',
				'type'		=> 'text',
			);
			$data['settings'][] = array(
				'type'		=> 'column',
			);
			$data['settings'][] = array(
				'key'		=> $prefix . 'member',
				'type'		=> 'typeahead',
			);
			$data['settings'][] = array(
				'type'		=> 'row_end',
			);
		}
		$data['settings'][] = array(
			'type'		=> 'table_end',
			'buttons'	=> 'add_row',
			'text'		=> 'button_add_product_group',
		);
		
		//------------------------------------------------------------------------------
		// Rule Sets
		//------------------------------------------------------------------------------
		$data['settings'][] = array(
			'key'		=> 'rule_sets',
			'type'		=> 'tab',
		);
		$data['settings'][] = array(
			'type'		=> 'html',
			'content'	=> '<div class="text-info text-center" style="padding-bottom: 5px">' . $data['help_rule_sets'] . '</div>',
		);
		$data['settings'][] = array(
			'key'		=> 'rule_sets',
			'type'		=> 'heading',
			'buttons'	=> 'expand_collapse',
		);
		
		$table = 'rule_set';
		$sortby = 'sort_order';
		$data['settings'][] = array(
			'key'		=> $table,
			'type'		=> 'table_start',
			'columns'	=> array('action', 'sort_order', 'name', 'rules'),
		);
		foreach ($this->getTableRowNumbers($data, $table, $sortby) as $num => $rules) {
			$prefix = $table . '_' . $num . '_';
			$data['settings'][] = array(
				'type'		=> 'row_start',
			);
			$data['settings'][] = array(
				'key'		=> 'expand_collapse',
				'type'		=> 'button',
			);
			$data['settings'][] = array(
				'key'		=> 'copy',
				'type'		=> 'button',
			);
			$data['settings'][] = array(
				'key'		=> 'delete',
				'type'		=> 'button',
			);
			$data['settings'][] = array(
				'type'		=> 'column',
			);
			$data['settings'][] = array(
				'key'		=> $prefix . 'sort_order',
				'type'		=> 'text',
				'class'		=> 'short',
				'attributes'=> array('maxlength' => 2),
			);
			$data['settings'][] = array(
				'type'		=> 'column',
			);
			$data['settings'][] = array(
				'key'		=> $prefix . 'name',
				'type'		=> 'text',
			);
			$data['settings'][] = array(
				'type'		=> 'column',
			);
			$data['settings'][] = array(
				'key'		=> $prefix . 'rule',
				'type'		=> 'rule',
				'rules'		=> $rules,
			);
			$data['settings'][] = array(
				'type'		=> 'row_end',
			);
		}
		
		$data['settings'][] = array(
			'type'		=> 'table_end',
			'buttons'	=> 'add_row',
			'text'		=> 'button_add_rule_set',
		);
		
		//------------------------------------------------------------------------------
		// end settings
		//------------------------------------------------------------------------------
		
		$this->document->setTitle($data['heading_title']);
		
		if (version_compare(VERSION, '2.0') < 0) {
			$this->data = $data;
			$this->template = $this->type . '/' . $this->name . '.tpl';
			$this->children = array(
				'common/header',	
				'common/footer',
			);
			$this->response->setOutput($this->render());
		} else {
			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');
			$this->response->setOutput($this->load->view($this->type . '/' . $this->name . '.tpl', $data));
		}
	}
	
	//==============================================================================
	// Setting functions
	//==============================================================================
	private $encryption_key = '';
	private $columns = 7;
	
	private function getTableRowNumbers($data, $table, $sorting) {
		$groups = array();
		$rules = array();
		
		foreach ($data['saved'] as $key => $setting) {
			if (preg_match('/' . $table . '_(\d+)_' . $sorting . '/', $key, $matches)) {
				$groups[$setting][] = $matches[1];
			}
			if (preg_match('/' . $table . '_(\d+)_rule_(\d+)_type/', $key, $matches)) {
				$rules[$matches[1]][] = $matches[2];
			}
		}
		
		if (empty($groups)) {
			$groups = array('' => array('1'));
		}
		ksort($groups, SORT_STRING);
		
		$rows = array();
		foreach ($groups as $group) {
			foreach ($group as $num) {
				$rows[$num] = (empty($rules[$num])) ? array() : $rules[$num];
			}
		}
		
		return $rows;
	}
	
	public function loadSettings(&$data) {
		$backup_type = (empty($data)) ? 'manual' : 'auto';
		if ($backup_type == 'manual' && !$this->user->hasPermission('modify', $this->type . '/' . $this->name)) {
			return;
		}
		
		// Load saved settings
		$data['saved'] = array();
		$settings_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE `" . (version_compare(VERSION, '2.0.1') < 0 ? 'group' : 'code') . "` = '" . $this->db->escape($this->name) . "' ORDER BY `key` ASC");
		
		foreach ($settings_query->rows as $setting) {
			$key = str_replace($this->name . '_', '', $setting['key']);
			$value = (is_string($setting['value']) && strpos($setting['value'], 'a:') === 0) ? unserialize($setting['value']) : $setting['value'];
			
			if (is_array($value)) {
				foreach ($value as $num => $value_array) {
					foreach ($value_array as $k => $v) {
						$data['saved'][$key . '_' . $num . '_' . $k] = $v;
					}
				}
			} else {
				$data['saved'][$key] = $value;
			}
		}
		
		// Load language and check max_input _vars
		$data = array_merge($data, $this->load->language($this->type . '/' . $this->name));
		
		if (ini_get('max_input_vars') && ((ini_get('max_input_vars') - count($data['saved'])) < 50)) {
			$data['warning'] = $data['standard_warning'];
		}
		
		// Set save type
		if (!empty($data['saved']['autosave'])) {
			$data['save_type'] = 'auto';
		}
		
		// Skip auto-backup if not needed
		if ($backup_type == 'auto' && empty($data['autobackup'])) {
			return;
		}
		
		// Create settings auto-backup file
		$manual_filepath = DIR_LOGS . $this->name . '_backup' . $this->encryption_key . '.txt';
		$auto_filepath = DIR_LOGS . $this->name . '_autobackup' . $this->encryption_key . '.txt';
		$filepath = ($backup_type == 'auto') ? $auto_filepath : $manual_filepath;
		if (file_exists($filepath)) unlink($filepath);
		
		if ($this->columns == 3) {
			file_put_contents($filepath, 'EXTENSION	SETTING	VALUE' . "\n", FILE_APPEND|LOCK_EX);
		} elseif ($this->columns == 5) {
			file_put_contents($filepath, 'EXTENSION	SETTING	NUMBER	SUB-SETTING	VALUE' . "\n", FILE_APPEND|LOCK_EX);
		} else {
			file_put_contents($filepath, 'EXTENSION	SETTING	NUMBER	SUB-SETTING	SUB-NUMBER	SUB-SUB-SETTING	VALUE' . "\n", FILE_APPEND|LOCK_EX);
		}
		
		foreach ($data['saved'] as $key => $value) {
			$parts = explode('|', preg_replace(array('/_(\d+)_/', '/_(\d+)/'), array('|$1|', '|$1'), $key));
			
			$line = $this->name . "\t" . $parts[0] . "\t";
			for ($i = 1; $i < $this->columns - 2; $i++) {
				$line .= (isset($parts[$i]) ? $parts[$i] : '') . "\t";
			}
			$line .= str_replace(array("\t", "\n"), array('    ', '\n'), $value) . "\n";
			
			file_put_contents($filepath, $line, FILE_APPEND|LOCK_EX);
		}
		
		$data['autobackup_time'] = date('Y-M-d @ g:i a');
		$data['backup_time'] = (file_exists($manual_filepath)) ? date('Y-M-d @ g:i a', filemtime($manual_filepath)) : '';
		
		if ($backup_type == 'manual') {
			echo $data['autobackup_time'];
		}
	}
	
	public function saveSettings() {
		if (!$this->user->hasPermission('modify', $this->type . '/' . $this->name)) {
			echo 'PermissionError';
			return;
		}
		
		$modules = array();
		foreach ($this->request->post as $key => $value) {
			if (strpos($key, 'module_') === 0) {
				$parts = explode('_', $key, 3);
				$modules[$parts[1]][$parts[2]] = $value;
			} else {
				$this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE `" . (version_compare(VERSION, '2.0.1') < 0 ? 'group' : 'code') . "` = '" . $this->db->escape($this->name) . "'AND `key` = '" . $this->db->escape($this->name . '_' . $key) . "'");
				$this->db->query("
					INSERT INTO " . DB_PREFIX . "setting SET
					`store_id` = 0,
					`" . (version_compare(VERSION, '2.0.1') < 0 ? 'group' : 'code') . "` = '" . $this->db->escape($this->name) . "',
					`key` = '" . $this->db->escape($this->name . '_' . $key) . "',
					`value` = '" . $this->db->escape(stripslashes(is_array($value) ? implode(';', $value) : $value)) . "',
					`serialized` = 0
				");
			}
		}
		
		if (version_compare(VERSION, '2.0.1') < 0) {
			$module_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE `" . (version_compare(VERSION, '2.0.1') < 0 ? 'group' : 'code') . "` = '" . $this->db->escape($this->name) . "'AND `key` = '" . $this->db->escape($this->name . '_module') . "'");
			if ($module_query->num_rows) {
				foreach (unserialize($module_query->row['value']) as $key => $value) {
					foreach ($value as $k => $v) {
						if (!isset($modules[$key][$k])) $modules[$key][$k] = $v;
					}
				}
			}
			
			if (isset($modules[0])) {
				$index = 1;
				while (isset($modules[$index])) {
					$index++;
				}
				$modules[$index] = $modules[0];
				unset($modules[0]);
			}
			
			$this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE `" . (version_compare(VERSION, '2.0.1') < 0 ? 'group' : 'code') . "` = '" . $this->db->escape($this->name) . "'AND `key` = '" . $this->db->escape($this->name . '_module') . "'");
			$this->db->query("
				INSERT INTO " . DB_PREFIX . "setting SET
				`store_id` = 0,
				`" . (version_compare(VERSION, '2.0.1') < 0 ? 'group' : 'code') . "` = '" . $this->db->escape($this->name) . "',
				`key` = '" . $this->db->escape($this->name . '_module') . "',
				`value` = '" . $this->db->escape(serialize($modules)) . "',
				`serialized` = 1
			");
		} else {
			foreach ($modules as $module_id => $module) {
				if ($module_id) {
					$this->db->query("
						UPDATE " . DB_PREFIX . "module SET
						`name` = '" . $this->db->escape($module['name']) . "',
						`code` = '" . $this->db->escape($this->name) . "',
						`setting` = '" . $this->db->escape(serialize($module)) . "'
						WHERE module_id = " . (int)$module_id . "
					");
				} else {
					$this->db->query("
						INSERT INTO " . DB_PREFIX . "module SET
						`name` = '" . $this->db->escape($module['name']) . "',
						`code` = '" . $this->db->escape($this->name) . "',
						`setting` = '" . $this->db->escape(serialize($module)) . "'
					");
				}
			}
		}
	}
	
	public function deleteSetting() {
		$this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE `" . (version_compare(VERSION, '2.0.1') < 0 ? 'group' : 'code') . "` = '" . $this->db->escape($this->name) . "' AND `key` = '" . $this->db->escape($this->name . '_' . str_replace('[]', '', $this->request->get['setting'])) . "'");
	}
	
	//==============================================================================
	// Backup functions
	//==============================================================================
	public function backupSettings() {
		$data = array();
		$this->loadSettings($data);
	}
	
	public function viewBackup() {
		if (!$this->user->hasPermission('access', $this->type . '/' . $this->name)) {
			echo 'You do not have permission to view this file.';
			return;
		}
		if (!file_exists(DIR_LOGS . $this->name . '_backup.txt')) {
			echo 'Backup file "' . DIR_LOGS . $this->name . '_backup.txt" does not exist';
			return;
		}
		
		$contents = trim(file_get_contents(DIR_LOGS . $this->name . '_backup' . $this->encryption_key . '.txt'));
		$lines = explode("\n", $contents);
		
		$html = '<table border="1" style="font-family: monospace" cellspacing="0" cellpadding="5">';
		foreach ($lines as $line) {
			$html .= '<tr><td>' . implode('</td><td>', explode("\t", $line)) . '</td></tr>';
		}
		echo str_replace('<td></td>', '<td style="background: #DDD"></td>', $html);
	}
	
	public function downloadBackup() {
		$file = DIR_LOGS . $this->name . '_backup' . $this->encryption_key . '.txt';
		if (!$this->user->hasPermission('access', $this->type . '/' . $this->name) || !file_exists($file)) {
			return;
		}
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Content-Description: File Transfer');
		header('Content-Disposition: attachment; filename=' . $this->name . '_backup.' . date('Y-n-d') . '.txt');
		header('Content-Length: ' . filesize($file));
		header('Content-Transfer-Encoding: binary');
		header('Content-Type: application/octet-stream');
		header('Expires: 0');
		header('Pragma: public');
		readfile($file);
	}
	
	public function restoreSettings() {
		$data = $this->load->language($this->type . '/' . $this->name);
		
		if (!$this->user->hasPermission('modify', $this->type . '/' . $this->name)) {
			$this->session->data['error'] = $data['standard_error'];
			$this->response->redirect(str_replace(array('&amp;', "\n", "\r"), array('&', '', ''), $this->url->link($this->type . '/' . $this->name, 'token=' . $this->session->data['token'], 'SSL')));
		}
		
		if ($this->request->post['from'] == 'auto') {
			$filepath = DIR_LOGS . $this->name . '_autobackup' . $this->encryption_key . '.txt';
		} elseif ($this->request->post['from'] == 'manual') {
			$filepath = DIR_LOGS . $this->name . '_backup' . $this->encryption_key . '.txt';
		} elseif ($this->request->post['from'] == 'file') {
			$filepath = $this->request->files['backup_file']['tmp_name'];
			if (empty($filepath)) {
				$this->response->redirect(str_replace(array('&amp;', "\n", "\r"), array('&', '', ''), $this->url->link($this->type . '/' . $this->name, 'token=' . $this->session->data['token'], 'SSL')));
			}
		}
		
		$contents = str_replace("\r\n", "\n", trim(file_get_contents($filepath)));
		
		if (strpos($contents, 'EXTENSION') !== 0) {
			$this->session->data['error'] = $data['error_invalid_file_data'];
			$this->response->redirect(str_replace(array('&amp;', "\n", "\r"), array('&', '', ''), $this->url->link($this->type . '/' . $this->name, 'token=' . $this->session->data['token'], 'SSL')));
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE `" . (version_compare(VERSION, '2.0.1') < 0 ? 'group' : 'code') . "` = '" . $this->db->escape($this->name) . "'");
		
		foreach (explode("\n", $contents) as $row) {
			if (empty($row) || strpos($row, 'EXTENSION') === 0) continue;
			
			$col = explode("\t", $row);
			$value = str_replace('\n', "\n", array_pop($col));
			$key = implode('_', array_diff($col, array('')));
			
			$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET `store_id` = 0, `" . (version_compare(VERSION, '2.0.1') < 0 ? 'group' : 'code') . "` = '" . $this->db->escape($this->name) . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape($value) . "', `serialized` = 0");
		}
		
		$this->session->data['success'] = $data['text_settings_restored'];
		$this->response->redirect(str_replace(array('&amp;', "\n", "\r"), array('&', '', ''), $this->url->link($this->type . '/' . $this->name, 'token=' . $this->session->data['token'], 'SSL')));
	}
	
	//==============================================================================
	// Ajax functions
	//==============================================================================
	public function loadDropdown() {
		$data = $this->load->language($this->type . '/' . $this->name);
		echo '<option value="">' . $data['standard_select'] . '</option>';
		$options = array();
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE `" . (version_compare(VERSION, '2.0.1') < 0 ? 'group' : 'code') . "` = '" . $this->name . "' AND `key` LIKE '" . $this->name . "_" . $this->request->get['type'] . "%'");
		foreach ($query->rows as $row) {
			if (strpos($row['key'], '_name')) {
				$num = str_replace(array($this->name . '_' . $this->request->get['type'] . '_', '_name'), '', $row['key']);
				foreach ($query->rows as $subrow) {
					if (strpos($subrow['key'], $num . '_sort_order') && $row['value']) {
						$options['<option value="' . $num . '">' . $row['value'] . '</option>'] = $subrow['value'];
						break;
					}
				}
			}
		}
		asort($options);
		foreach ($options as $option => $sort_order) {
			echo $option;
		}
	}
	
	public function typeahead() {
		$search = (strpos($this->request->get['q'], '[')) ? substr($this->request->get['q'], 0, strpos($this->request->get['q'], ' [')) : $this->request->get['q'];
		
		if ($this->request->get['type'] == 'all') {
			$tables = array('attribute_group_description', 'attribute_description', 'category_description', 'manufacturer', 'option_description', 'option_value_description', 'product_description');
		} elseif ($this->request->get['type'] == 'customer') {
			$tables = array('customer');
		} elseif ($this->request->get['type'] == 'manufacturer') {
			$tables = array('manufacturer');
		} else {
			$tables = array($this->request->get['type'] . '_description');
		}
		
		$results = array();
		foreach ($tables as $table) {
			if ($table == 'customer') {
				$query = $this->db->query("SELECT customer_id, CONCAT(firstname, ' ', lastname, ' (', email, ')') as name FROM " . DB_PREFIX . $table . " WHERE CONCAT(firstname, ' ', lastname, ' (', email, ')') LIKE '%" . $this->db->escape($search) . "%' ORDER BY name ASC LIMIT 0,100");
			} else {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . $table . " WHERE name LIKE '%" . $this->db->escape($search) . "%' ORDER BY name ASC LIMIT 0,100");
			}
			$results = array_merge($results, $query->rows);
		}
		
		if (empty($results)) {
			$variations = array();
			for ($i = 0; $i < strlen($search); $i++) {
				$variations[] = substr_replace($search, '_', $i, 1);
				$variations[] = substr_replace($search, '', $i, 1);
				if ($i != strlen($search)-1) {
					$transpose = $search;
					$transpose[$i] = $search[$i+1];
					$transpose[$i+1] = $search[$i];
					$variations[] = $transpose;
				}
			}
			foreach ($tables as $table) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . $table . " WHERE name LIKE '%" . implode("%' OR name LIKE '%", $variations) . "%' ORDER BY name ASC LIMIT 0,100");
				$results = array_merge($results, $query->rows);
			}
		}
		
		$items = array();
		foreach ($results as $result) {
			if (key($result) == 'category_id') {
				$category_id = reset($result);
				$parent_exists = true;
				while ($parent_exists) {
					$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_description WHERE category_id = (SELECT parent_id FROM " . DB_PREFIX . "category WHERE category_id = " . (int)$category_id . ")");
					if (!empty($query->row['name'])) {
						$category_id = $query->row['category_id'];
						$result['name'] = $query->row['name'] . ' > ' . $result['name'];
					} else {
						$parent_exists = false;
					}
				}
			}
			$items[] = html_entity_decode($result['name'], ENT_NOQUOTES, 'UTF-8') . ' [' . key($result) . ':' . reset($result) . ']';
		}
		
		natcasesort($items);
		echo '["' . implode('","', str_replace(array('"', '_id'), array('&quot;', ''), $items)) . '"]';
	}
}
?>