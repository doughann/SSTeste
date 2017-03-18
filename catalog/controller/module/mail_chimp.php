<?php
class ControllerModuleMailchimp extends Controller {
	protected function index($setting) {

		static $module_index = 0;

		$this->data['module_index'] = $module_index++;

		if (isset($setting['list']) && strlen(trim($setting['list'])) > 0) {
			$c_lang = $this->config->get('config_language_id');

			$this->language->load('module/mail_chimp');

			$texts = $setting['texts'][$c_lang];

			$this->data['heading_title']   = html_entity_decode($texts['heading_title'], ENT_QUOTES, 'UTF-8');
			$this->data['html']            = html_entity_decode($texts['description'], ENT_QUOTES, 'UTF-8');
			$this->data['text_submit']     = $this->language->get('text_submit');
			$this->data['language_id']     = $c_lang;
			$this->data['list_id']         = $setting['list'];
			$this->data['popup']           = $setting['popup_frm'];
			$this->data['success_message'] = html_entity_decode($texts['success_message'], ENT_QUOTES, 'UTF-8');
			$this->data['button_text']     = html_entity_decode($texts['button_text'], ENT_QUOTES, 'UTF-8');

			if (intval($setting['popup_frm']) == 1) {
				$this->data['md5code'] = md5(implode('-', array(
					$setting['list'],
					$this->data['module_index'],
				)));

				if (isset($_COOKIE['md5code']) && $_COOKIE['md5code'] == $this->data['md5code']) {
					return '';
				}
			}

			if (isset($setting['list_fields'])) {
				foreach ($setting['list_fields'] as $tag => $listnfo) {
					$this->data['list_fields'][$tag] = $listnfo[$c_lang];
				}
			}

			$this->data['setting'] = $setting;

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/mailchimp.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/module/mailchimp.tpl';
			} else {
				$this->template = 'default/template/module/mailchimp.tpl';
			}

			$this->render();
		}

	}

	public function add2list() {
		$mailchimp_api_key = trim($this->config->get('mailchimp_api'));
		$mailchimp_list_id = trim($this->request->post['list_id']);
		unset($this->request->post['list_id']);

		if (strlen($mailchimp_list_id) > 1 && strlen($mailchimp_api_key) > 1) {

			require_once DIR_SYSTEM . 'library/Drewm/MailChimp.php';
			$MailChimp = new \Drewm\MailChimp($mailchimp_api_key);

			$result = $MailChimp->call('lists/subscribe', array(
				'id'                => $mailchimp_list_id,
				'email'             => array('email' => trim($this->request->post['EMAIL'])),
				'merge_vars'        => $this->request->post,
				'double_optin'      => false,
				'update_existing'   => true,
				'replace_interests' => false,
				'send_welcome'      => false,
			));

			if (isset($result['email']) && !empty($result['email']) && isset($this->request->post['md5code'])) {
				$this->storeCookie('md5code', $this->request->post['md5code']);
			}

			echo json_encode($result);

		}
	}

	function storeCookie($name, $value) {
		$expire_cookie = strtotime('+30 Days');
		$server        = str_replace('www.', '', $this->request->server['HTTP_HOST']);
		setcookie($name, $value, $expire_cookie, '/', '.' . $server);
		setcookie($name, $value, $expire_cookie, '/', $server);
		setcookie($name, $value, $expire_cookie, '/');
	}

	public function subscribeMailChimp($email = null, $fname = null, $lname = null, $customer_group_id = 0) {

		$mailchimp_api_key = trim($this->config->get('mailchimp_api'));
		$mailchimp_list_id = trim($this->config->get('mailchimp_list_' . $customer_group_id));

		if (strlen($mailchimp_list_id) > 1 && strlen($mailchimp_api_key) > 1) {
			if (!empty($email)) {
				require_once DIR_SYSTEM . 'library/Drewm/MailChimp.php';
				$MailChimp = new \Drewm\MailChimp($mailchimp_api_key);

				$result = $MailChimp->call('lists/subscribe', array(
					'id'                => $mailchimp_list_id,
					'email'             => array('email' => trim($email)),
					'merge_vars'        => array('FNAME' => trim($fname), 'LNAME' => trim($lname)),
					'double_optin'      => false,
					'update_existing'   => true,
					'replace_interests' => false,
					'send_welcome'      => false,
				));
			}
		}
	}

}
?>