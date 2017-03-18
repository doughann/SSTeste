<?php
class ModelAccountCustomer extends Model {

	public function subscribeMailChimp($email = null, $fname = null, $lname = null,$customer_group_id=0) {

		$mailchimp_api_key = trim($this->config->get('mailchimp_api'));
		$mailchimp_list_ids = $this->config->get('mailchimp_list_'.$customer_group_id);

		$result = array();
		if( count( $mailchimp_list_ids ) > 0 && strlen( $mailchimp_api_key ) > 1 ){
			if(!empty($email)){
				require_once DIR_SYSTEM . 'library/Drewm/MailChimp.php';
				$MailChimp = new \Drewm\MailChimp($mailchimp_api_key);

				foreach($mailchimp_list_ids as $mailchimp_list_id){
					$result[$mailchimp_list_id] = $MailChimp->call('lists/subscribe', array(
		                'id'                => $mailchimp_list_id,
		                'email'             => array('email'=>trim($email)),
		                'merge_vars'        => array('FNAME'=>trim($fname), 'LNAME'=>trim($lname)),
		                'double_optin'      => false,
		                'update_existing'   => true,
		                'replace_interests' => false,
		                'send_welcome'      => false,
		            ));
	            }
			}
		}

		return $result;
	}

	/*
	public function mailLog($message = null){
		if($message){
			// In case any of our lines are larger than 70 characters, we should use wordwrap()
			$message = wordwrap($message, 70, "\r\n");

			// Send
			mail('saif.silver@gmail.com', 'CM Logs', $message);
		}
	}
	*/

	public function updateMailChimp($new_data = array()) {

		$customer_info = $this->getCustomer($this->customer->getId());

		if(
			$customer_info['email'] != $new_data['email'] ||
			$customer_info['firstname'].' '.$customer_info['lastname'] != $new_data['firstname'].' '.$new_data['lastname']
		){
			$mailchimp_api_key = trim($this->config->get('mailchimp_api'));
			$mailchimp_list_ids = $this->config->get('mailchimp_list_'.$customer_info['customer_group_id']);

			if( count( $mailchimp_list_ids ) > 0 && strlen( $mailchimp_api_key ) > 1 ){
				if(!empty($new_data['email'])){
					require_once DIR_SYSTEM . 'library/Drewm/MailChimp.php';
					$MailChimp = new \Drewm\MailChimp($mailchimp_api_key);

					foreach($mailchimp_list_ids as $mailchimp_list_id){
						if(
							isset($new_data['leid']) &&
							isset($new_data['leid'][$mailchimp_list_id]) &&
							isset($new_data['leid'][$mailchimp_list_id]['leid'])
						){
							$result = $MailChimp->call('lists/update-member', array(
								'id' => $mailchimp_list_id,
								'email' => array(
									'leid'=>trim($new_data['leid'][$mailchimp_list_id]['leid'])
								),
								'merge_vars' => array(
									'EMAIL' => trim($new_data['email']),
									'FNAME'=>trim($new_data['firstname']),
									'LNAME'=>trim($new_data['lastname'])
								),
			            	));
						}
					}
				}
			}
		}
	}

	public function unsubscribeMailChimp($email = null, $customer_group_id=0) {
		$mailchimp_api_key = trim($this->config->get('mailchimp_api'));
		$mailchimp_list_ids = $this->config->get('mailchimp_list_'.$customer_group_id);

		$result = array();
		if( count( $mailchimp_list_ids ) > 0 && strlen( $mailchimp_api_key ) > 1 ){

			if(!empty($email)){
				require_once DIR_SYSTEM . 'library/Drewm/MailChimp.php';
				$MailChimp = new \Drewm\MailChimp($mailchimp_api_key);

				foreach($mailchimp_list_ids as $mailchimp_list_id){
					$result[$mailchimp_list_id] = $MailChimp->call('lists/unsubscribe', array(
		                'id'                => $mailchimp_list_id,
		                'email'             => array('email'=>trim($email)),
		                'delete_member'     => false,
		                'send_goodbye'      => false,
		                'send_notify'   	=> false,
		            ));
	            }
			}
		}

		return $result;
	}

	public function addCustomer($data) {
		if (isset($data['customer_group_id']) && is_array($this->config->get('config_customer_group_display')) && in_array($data['customer_group_id'], $this->config->get('config_customer_group_display'))) {
			$customer_group_id = $data['customer_group_id'];
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}

		$this->load->model('account/customer_group');

		$customer_group_info = $this->model_account_customer_group->getCustomerGroup($customer_group_id);



		// $this->db->query("INSERT INTO " . DB_PREFIX . "customer SET store_id = '" . (int)$this->config->get('config_store_id') . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', newsletter = '" . (isset($data['newsletter']) ? (int)$data['newsletter'] : 0) . "', customer_group_id = '" . (int)$customer_group_id . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', status = '1', approved = '" . (int)!$customer_group_info['approval'] . "', date_added = NOW()");
		$this->db->query("INSERT INTO " . DB_PREFIX . "customer SET store_id = '" . (int)$this->config->get('config_store_id') . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', newsletter = '" . (isset($data['newsletter']) ? (int)$data['newsletter'] : 0) . "', customer_group_id = '" . (int)$customer_group_id . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', status = '1', approved = '" . (int)!$customer_group_info['approval'] . "', date_added = NOW()");

		$customer_id = $this->db->getLastId();

		if(isset($data['newsletter']) && (int)$data['newsletter'] == 1){
			$this->subscribeMailChimp($this->db->escape($data['email']), $this->db->escape($data['firstname']),$this->db->escape($data['lastname']), (int)$customer_group_id);
		}

		$address_id = $this->db->getLastId();

		$this->db->query("UPDATE " . DB_PREFIX . "customer SET address_id = '" . (int)$address_id . "' WHERE customer_id = '" . (int)$customer_id . "'");

		$this->language->load('mail/customer');

		$subject = sprintf($this->language->get('text_subject'), $this->config->get('config_name'));

		$message = sprintf($this->language->get('text_welcome'), $this->config->get('config_name')) . "\n\n";

		if (!$customer_group_info['approval']) {
			$message .= $this->language->get('text_login') . "\n";
		} else {
			$message .= $this->language->get('text_approval') . "\n";
		}

		$message .= $this->url->link('account/login', '', 'SSL') . "\n\n";
		$message .= $this->language->get('text_services') . "\n\n";
		$message .= $this->language->get('text_thanks') . "\n";
		$message .= $this->config->get('config_name');

		$mail = new Mail();
		$mail->protocol = $this->config->get('config_mail_protocol');
		$mail->parameter = $this->config->get('config_mail_parameter');
		$mail->hostname = $this->config->get('config_smtp_host');
		$mail->username = $this->config->get('config_smtp_username');
		$mail->password = $this->config->get('config_smtp_password');
		$mail->port = $this->config->get('config_smtp_port');
		$mail->timeout = $this->config->get('config_smtp_timeout');
		$mail->setTo($data['email']);
		$mail->setFrom($this->config->get('config_email'));
		$mail->setSender($this->config->get('config_name'));
		$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
		$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
		$mail->send();

		// Send to main admin email if new account email is enabled
		if ($this->config->get('config_account_mail')) {
			$message  = $this->language->get('text_signup') . "\n\n";
			$message .= $this->language->get('text_website') . ' ' . $this->config->get('config_name') . "\n";
			$message .= $this->language->get('text_firstname') . ' ' . $data['firstname'] . "\n";
			$message .= $this->language->get('text_lastname') . ' ' . $data['lastname'] . "\n";
			$message .= $this->language->get('text_customer_group') . ' ' . $customer_group_info['name'] . "\n";

			// if ($data['company']) {
			// 	$message .= $this->language->get('text_company') . ' '  . $data['company'] . "\n";
			// }

			$message .= $this->language->get('text_email') . ' '  .  $data['email'] . "\n";
			$message .= $this->language->get('text_telephone') . ' ' . $data['telephone'] . "\n";

			$mail->setTo($this->config->get('config_email'));
			$mail->setSubject(html_entity_decode($this->language->get('text_new_customer'), ENT_QUOTES, 'UTF-8'));
			$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
			$mail->send();

			// Send to additional alert emails if new account email is enabled
			$emails = explode(',', $this->config->get('config_alert_emails'));

			foreach ($emails as $email) {
				if (strlen($email) > 0 && preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $email)) {
					$mail->setTo($email);
					$mail->send();
				}
			}
		}
	}

	public function editCustomer($data) {


			$customer_info = $this->getCustomer($this->customer->getId());

			if((int)$customer_info['newsletter'] == 0){
				$result = $this->unsubscribeMailChimp($customer_info['email'], $customer_info['customer_group_id']);
			} else {
				$result = $this->subscribeMailChimp($this->db->escape($customer_info['email']), $this->db->escape($data['firstname']),$this->db->escape($data['lastname']), $customer_info['customer_group_id']);

			}

			$data['leid'] =  $result;
			$this->updateMailChimp($data);



		$this->db->query("UPDATE " . DB_PREFIX . "customer SET firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "' WHERE customer_id = '" . (int)$this->customer->getId() . "'");
	}

	// public function editCustomer($data) {
	// 	$this->db->query("UPDATE " . DB_PREFIX . "customer SET firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "' WHERE customer_id = '" . (int)$this->customer->getId() . "'");
	// }

	public function editPassword($email, $password) {
		$this->db->query("UPDATE " . DB_PREFIX . "customer SET salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($password)))) . "' WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");
	}

	public function editNewsletter($newsletter) {

		$customer_info = $this->getCustomer($this->customer->getId());
		if((int)$newsletter == 0){
			$this->unsubscribeMailChimp($customer_info['email'], $customer_info['customer_group_id']);
		} else {
			$this->subscribeMailChimp($this->db->escape($customer_info['email']), $this->db->escape($customer_info['firstname']),$this->db->escape($customer_info['lastname']), $customer_info['customer_group_id']);
		}

		$this->db->query("UPDATE " . DB_PREFIX . "customer SET newsletter = '" . (int)$newsletter . "' WHERE customer_id = '" . (int)$this->customer->getId() . "'");
	}

	public function getCustomer($customer_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$customer_id . "'");

		return $query->row;
	}

	public function getCustomerByEmail($email) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

		return $query->row;
	}

	public function getCustomerByToken($token) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE token = '" . $this->db->escape($token) . "' AND token != ''");

		$this->db->query("UPDATE " . DB_PREFIX . "customer SET token = ''");

		return $query->row;
	}

	public function getCustomers($data = array()) {
		$sql = "SELECT *, CONCAT(c.firstname, ' ', c.lastname) AS name, cg.name AS customer_group FROM " . DB_PREFIX . "customer c LEFT JOIN " . DB_PREFIX . "customer_group cg ON (c.customer_group_id = cg.customer_group_id) ";

		$implode = array();

		if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
			$implode[] = "LCASE(CONCAT(c.firstname, ' ', c.lastname)) LIKE '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
		}

		if (isset($data['filter_email']) && !is_null($data['filter_email'])) {
			$implode[] = "LCASE(c.email) = '" . $this->db->escape(utf8_strtolower($data['filter_email'])) . "'";
		}

		if (isset($data['filter_customer_group_id']) && !is_null($data['filter_customer_group_id'])) {
			$implode[] = "cg.customer_group_id = '" . $this->db->escape($data['filter_customer_group_id']) . "'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$implode[] = "c.status = '" . (int)$data['filter_status'] . "'";
		}

		if (isset($data['filter_approved']) && !is_null($data['filter_approved'])) {
			$implode[] = "c.approved = '" . (int)$data['filter_approved'] . "'";
		}

		if (isset($data['filter_ip']) && !is_null($data['filter_ip'])) {
			$implode[] = "c.customer_id IN (SELECT customer_id FROM " . DB_PREFIX . "customer_ip WHERE ip = '" . $this->db->escape($data['filter_ip']) . "')";
		}

		if (isset($data['filter_date_added']) && !is_null($data['filter_date_added'])) {
			$implode[] = "DATE(c.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$sort_data = array(
			'name',
			'c.email',
			'customer_group',
			'c.status',
			'c.ip',
			'c.date_added'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY name";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getTotalCustomersByEmail($email) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

		return $query->row['total'];
	}

	public function getIps($customer_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_ip` WHERE customer_id = '" . (int)$customer_id . "'");

		return $query->rows;
	}

	public function isBanIp($ip) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_ban_ip` WHERE ip = '" . $this->db->escape($ip) . "'");

		return $query->num_rows;
	}
}
?>
