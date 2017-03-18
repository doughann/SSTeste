<?php


class DDWLibController extends Controller {

	public function getShippingMethods() {
		$shippingMethods = array();
		$this->load->model('setting/extension');
		$extensions = $this->model_setting_extension->getInstalled('shipping');
		
		$files = glob(DIR_APPLICATION.'controller/shipping/*.php');

		if ($files) {
			foreach ($files as $file) {
				$extension = basename($file, '.php');
				$this->language->load('shipping/' . $extension);

				if ($this->config->get($extension . '_status')) {
					$shippingMethods[] = array(
						'code'       => $extension,
						'name'       => $this->language->get('heading_title'),
						'status'     => $this->config->get($extension . '_status') ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
						'sort_order' => $this->config->get($extension . '_sort_order')
					);
				}
			}
		}
		return $shippingMethods;
	}

	/**
	 * @var $blockedDates Array Of DDWBlockedDate
	 * @var $date Timestamp
	 * @return boolean
	 */
	private function _is_date_blocked($blockedDates, $date) {
		$blocked = false;
		
		/* Weekdays Blocked check */
		$current_week_day = date("w", $date);
		if (in_array($current_week_day, explode(",",$this->ddwModel->ddw->weekdays))) return true;
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
		}
		return $blocked;
	}

	public function get_blocked_dates() {
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
		if ($this->ddwModel->ddw->cut_off_time_enabled == 1)
		{
			$hours = date('H');
			$minutes = date('i');
			if ($hours >= $this->ddwModel->ddw->cut_off_time_hours)
				$min_days ++;
			if ($hours == $this->ddwModel->ddw->cut_off_time_hours && $minutes > $this->ddwModel->ddw->cut_off_time_minutes)
				$min_days ++;
		}

		/* Determine if min days needs to exclude any blocked days
			This is done by looping from today to min days and incrementing MinDays each time any of the min days falls
			on a blocked day
		 */
		for ($i=0; $i<$min_days; $i++) {
			$loop_date = strtotime("+$i day", strtotime($start_date));
			if ($this->_is_date_blocked($blockedDates, $loop_date)) $min_days++;
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
		if ($this->ddwModel->ddw->max_days == 0) $this->ddwModel->ddw->max_days = 365;
		
		for ($i=$min_days;$i<$this->ddwModel->ddw->max_days+$min_days;$i++) {
			$loop_date = strtotime("+$i day", strtotime($start_date));
			if ($this->_is_date_blocked($blockedDates, $loop_date)) {
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

		$return['enabled'] = $this->ddwModel->ddw->enabled;
		$return['min_date'] = date('Y-m-d');
		$return['max_date'] = date('Y-m-d', strtotime("+".$this->ddwModel->ddw->max_days." days"));
		$return['calendar_blocked_dates'] = $calendar_blocked_dates;
		$return['required'] = $this->ddwModel->ddw->required;
		print json_encode($return);
	}

	public function render_widget(&$sibling) {
		$sibling->load->model('deliverydateswizard/deliverydateswizard');
		$sibling->ddwModel = $sibling->model_deliverydateswizard_deliverydateswizard;
		$sibling->ddwModel->load("");

		$sibling->data['min_date'] = date('Y-m-d');
		$sibling->data['max_date'] = date('Y-m-d', strtotime("+".$sibling->ddwModel->ddw->max_days." days"));

		//if (defined('HTTP_CATALOG')) { //widget being loaded from admin
		if (stripos($_SERVER['REQUEST_URI'], '/admin/') !== false) { //widget being loaded from admin
			$sibling->template = 'module/deliverydateswizard/orderentrysystem/shipping_method.tpl';
			$sibling->data['token'] = $this->session->data['token'];
		} else { //normal front end
			if (file_exists(DIR_TEMPLATE . $sibling->config->get('config_template') . '/template/module/deliverydateswizard/shipping_method.tpl')) {
				$sibling->template = $sibling->config->get('config_template') . '/template/module/deliverydateswizard/shipping_method.tpl';
			} else {
				$sibling->template = 'default/template/module/deliverydateswizard/shipping_method.tpl';
			}
			$sibling->data['token'] = "";
		}

		/* Translations */
		$translations = $sibling->ddwModel->get_all_translations();
		foreach($translations as $key=>$translation) {
			$texts[$translation->name][$translation->shipping_method_code] = $translation->text;
		}
		if (!empty($texts))
			$sibling->data['ddw_texts'] = $texts;
		else
			$sibling->data['ddw_texts'] = array();

		/* render the delivery times widget */
		$language_id = $sibling->config->get('config_language_id');
		$delivery_dates = $sibling->ddwModel->get_all_delivery_times();
		$delivery_dates_render = array();
		
		if ($delivery_dates) {
			foreach($delivery_dates as $ddw_time) {
				$delivery_dates_render[$ddw_time->shipping_method_code][] = $ddw_time;
			}
			$sibling->data['ddw_delivery_times'] = $delivery_dates_render;
		} else {
			$sibling->data['ddw_delivery_times'] = array();
		}

		$is_https = isset($_SERVER['HTTPS']);
		if (defined('HTTP_CATALOG')) {
			if ($is_https) $sibling->data['catalog_url'] = rtrim(HTTPS_CATALOG, "/")."/";
			else $sibling->data['catalog_url'] = rtrim(HTTP_CATALOG, "/")."/";
		} else {
			if ($is_https) $sibling->data['catalog_url'] = rtrim(HTTPS_SERVER, "/")."/";
			else $sibling->data['catalog_url'] = rtrim(HTTP_SERVER, "/")."/";
		}

		/* render widget */
		$sibling->data['ddw_calendar'] = $sibling->render();
	}


}