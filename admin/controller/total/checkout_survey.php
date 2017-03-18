<?php
//==============================================================================
// Checkout Survey v155.1
//
// Author: Clear Thinking, LLC
// E-mail: johnathan@getclearthinking.com
// Website: http://www.getclearthinking.com
//==============================================================================

class ControllerTotalCheckoutSurvey extends Controller {
	private $error = array();
	private $type = 'total';
	private $name = 'checkout_survey';
	
	public function index() {
		$this->data['type'] = $this->type;
		$this->data['name'] = $this->name;
		
		$token = $this->data['token'] = (isset($this->session->data['token'])) ? $this->session->data['token'] : '';
		$version = $this->data['version'] = (!defined('VERSION')) ? 140 : (int)substr(str_replace('.', '', VERSION), 0, 3);
		
		$this->data = array_merge($this->data, $this->load->language($this->type . '/' . $this->name));
		$this->data['exit'] = $this->makeURL($this->type . '/' . $this->name, 'token=' . $token, 'SSL');
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validate()) {
			$this->save(false);
			$this->session->data['success'] = $this->data['standard_success'];
			$this->redirect($this->data['exit']);
		}
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE `group` = '" . $this->db->escape($this->name) . "' ORDER BY `key` ASC");
		foreach ($query->rows as $setting) {
			$value = isset($this->request->post[$setting['key']]) ? $this->request->post[$setting['key']] : $setting['value'];
			$this->data[$setting['key']] = (is_string($value) && strpos($value, 'a:') === 0) ? unserialize($value) : $value;
		}
		
		// non-standard
		$this->load->model('localisation/language');
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		// end
		
		$breadcrumbs = array();
		$breadcrumbs[] = array(
			'href'		=> $this->makeURL('common/home', 'token=' . $token, 'SSL'),
			'text'		=> $this->data['text_home'],
			'separator' => false
		);
		$breadcrumbs[] = array(
			'href'		=> $this->makeURL('extension/' . $this->type, 'token=' . $token, 'SSL'),
			'text'		=> $this->data['standard_' . $this->type],
			'separator' => ' :: '
		);
		$breadcrumbs[] = array(
			'href'		=> $this->makeURL($this->type . '/' . $this->name, 'token=' . $token, 'SSL'),
			'text'		=> $this->data['heading_title'],
			'separator' => ' :: '
		);
		
		$this->data['error_warning'] = isset($this->error['warning']) ? $this->error['warning'] : '';
		$this->data['success'] = (isset($this->session->data['success'])) ? $this->session->data['success'] : '';
		unset($this->session->data['success']);
		
		$this->template = $this->type . '/' . $this->name . '.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		
		if ($version < 150) {
			$this->document->title = $this->data['heading_title'];
			$this->document->breadcrumbs = $breadcrumbs;
			$this->response->setOutput($this->render(true), $this->config->get('config_compression'));
		} else {
			$this->document->setTitle($this->data['heading_title']);
			$this->data['breadcrumbs'] = $breadcrumbs;
			$this->response->setOutput($this->render());
		}
	}
	
	public function save($ajax = true) {
		if ($this->validate()) {
			if (!defined('VERSION') || VERSION < 1.5 || strpos(VERSION, '1.5.0') === 0) {
				foreach ($this->request->post as $key => $value) {
					if (is_array($value)) $this->request->post[$key] = serialize($value);
				}
			}
			$this->load->model('setting/setting');
			$this->model_setting_setting->editSetting($this->name, $this->request->post);
			if ($ajax) echo 'success';
		}
	}
	
	private function makeURL($route, $args = '', $connection = 'NONSSL') {
		if (!defined('VERSION') || VERSION < 1.5) {
			$url = ($connection == 'NONSSL') ? HTTP_SERVER : HTTPS_SERVER;
			$url .= 'index.php?route=' . $route;
			$url .= ($args) ? '&' . ltrim($args, '&') : '';
			return $url;
		} else {
			return $this->url->link($route, $args, $connection);
		}
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', $this->type . '/' . $this->name)) {
			$this->error['warning'] = $this->data['standard_error'];
		}
		return ($this->error) ? false : true;
	}
}
?>