<?php
class ControllerModuleMailChimp extends Controller {
	private $error = array();

	public function index() {
		$this->language->load('module/mail_chimp');

		$this->data['mailchimp_api'] = trim($this->config->get('mailchimp_api'));

		if (empty($this->data['mailchimp_api'])) {
			$this->session->data['error_apikey'] = $this->language->get('error_apikey');
			$this->redirect($this->url->link('mail_chimp/setting', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			//print_r($this->request->post);exit;
			$this->model_setting_setting->editSetting('mail_chimp', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			unset($this->session->data['mailchimp_list']);

			//$this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_select_list']         = $this->language->get('text_select_list');
		$this->data['text_hide']                = $this->language->get('text_hide');
		$this->data['text_show']                = $this->language->get('text_show');
		$this->data['text_enabled']             = $this->language->get('text_enabled');
		$this->data['text_disabled']            = $this->language->get('text_disabled');
		$this->data['text_content_top']         = $this->language->get('text_content_top');
		$this->data['text_content_bottom']      = $this->language->get('text_content_bottom');
		$this->data['text_column_left']         = $this->language->get('text_column_left');
		$this->data['text_column_right']        = $this->language->get('text_column_right');
		$this->data['entry_settings']           = $this->language->get('entry_settings');
		$this->data['entry_heading_title']      = $this->language->get('entry_heading_title');
		$this->data['entry_description']        = $this->language->get('entry_description');
		$this->data['entry_success_msg']        = $this->language->get('entry_success_msg');
		$this->data['entry_submit_button_text'] = $this->language->get('entry_submit_button_text');
		$this->data['entry_popup_frm']          = $this->language->get('entry_popup_frm');
		$this->data['entry_list']               = $this->language->get('entry_list');
		$this->data['entry_list_fields']        = $this->language->get('entry_list_fields');
		$this->data['entry_name']               = $this->language->get('entry_name');
		$this->data['entry_layout']             = $this->language->get('entry_layout');
		$this->data['entry_position']           = $this->language->get('entry_position');
		$this->data['entry_status']             = $this->language->get('entry_status');
		$this->data['entry_sort_order']         = $this->language->get('entry_sort_order');
		$this->data['button_save']              = $this->language->get('button_save');
		$this->data['button_cancel']            = $this->language->get('button_cancel');
		$this->data['button_add_module']        = $this->language->get('button_add_module');
		$this->data['button_remove']            = $this->language->get('button_remove');

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->error['list'])) {
			$this->data['error_list'] = $this->error['list'];
		} else {
			$this->data['error_list'] = array();
		}

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false,
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: ',
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/mail_chimp', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: ',
		);

		$this->data['action'] = $this->url->link('module/mail_chimp', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['token'] = $this->session->data['token'];

		$this->data['modules'] = array();

		if (isset($this->request->post['mail_chimp_module'])) {
			$this->data['modules'] = $this->request->post['mail_chimp_module'];
		} elseif ($this->config->get('mail_chimp_module')) {
			$this->data['modules'] = $this->config->get('mail_chimp_module');
		}

		$this->load->model('design/layout');
		$this->data['layouts'] = $this->model_design_layout->getLayouts();

		$this->load->model('localisation/language');
		$this->data['languages'] = $this->model_localisation_language->getLanguages();

		if (!isset($this->session->data['mailchimp_list'])) {
			$mailchimp_list = $this->getlist($this->data['mailchimp_api']);

			$this->data['mailchimp_list'] = array();

			foreach ($mailchimp_list['data'] as $id => $list) {
				$merge_vars = $this->getlistInfo($this->data['mailchimp_api'], $list['id']);

				if (isset($merge_vars['data']) && isset($merge_vars['data'][0]) && isset($merge_vars['data'][0]['id'])) {
					$this->data['mailchimp_list'][$list['id']] = array(
						'id'         => $merge_vars['data'][0]['id'],
						'name'       => $merge_vars['data'][0]['name'],
						'merge-vars' => $merge_vars['data'][0]['merge_vars'],
					);
				}

			}

			$this->session->data['mailchimp_list'] = $this->data['mailchimp_list'];
		} else {
			$this->data['mailchimp_list'] = $this->session->data['mailchimp_list'];
		}

		$this->template = 'module/mail_chimp.tpl';
		$this->children = array(
			'common/header',
			'common/footer',
		);

		$this->response->setOutput($this->render());
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'module/mail_chimp')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (isset($this->request->post['mail_chimp_module'])) {
			foreach ($this->request->post['mail_chimp_module'] as $key => $value) {
				if (strlen(trim($value['list'])) == 0) {
					$this->error['list'][$key] = $this->language->get('error_list');
				}
			}
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	public function getlist($api_key = null) {
		require_once DIR_SYSTEM . 'library/Drewm/MailChimp.php';

		if ($api_key) {
			$MailChimp = new \Drewm\MailChimp($api_key);
			return $MailChimp->call('lists/list');
		} else {
			$api_key   = $this->request->get['api_key'];
			$MailChimp = new \Drewm\MailChimp($api_key);
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($MailChimp->call('lists/list')));
		}
	}

	public function getlistInfo($api_key = null, $list_id = null) {
		require_once DIR_SYSTEM . 'library/Drewm/MailChimp.php';

		if ($api_key) {
			$MailChimp = new \Drewm\MailChimp($api_key);
			return $MailChimp->call('lists/merge-vars', array(
				'id' => array($list_id),
			));
		} else {
			$api_key   = $this->request->get['api_key'];
			$MailChimp = new \Drewm\MailChimp($api_key);
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(
				json_encode(
					$MailChimp->call(
						'lists/merge-vars',
						array(
							'id' => array($this->request->get['list_id']),
						)
					)
				)
			);
		}

	}
}
?>