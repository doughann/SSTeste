<?php
//==============================================================================
// Checkout Survey v155.1
//
// Author: Clear Thinking, LLC
// E-mail: johnathan@getclearthinking.com
// Website: http://www.getclearthinking.com
//==============================================================================

class ModelTotalCheckoutSurvey extends Model {
	private $type = 'total';
	private $name = 'checkout_survey';
	
	public function getTotal(&$total_data, &$total, &$taxes) {
		$data = $this->getSettings();
		$language = $this->session->data['language'];
		
		if (!$data['status']) return;
		
		for ($i = 0; $i < count($data['type']); $i++) {
			if (!empty($this->session->data[$this->name . '_' . $i])) {
				$total_data[] = array(
					'code'			=> $this->name,
					'title'			=> (!defined('VERSION') || VERSION < 1.5) ? $data['lineitem'][$language][$i] . ':' : $data['lineitem'][$language][$i],
					'text'			=> $this->session->data[$this->name . '_' . $i],
					'value'			=> '',
					'sort_order'	=> $data['sort_order']
				);
			}
		}
	}
	
	public function getSettings() {
		$settings = array();
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE `group` = '" . $this->db->escape($this->name) . "' ORDER BY `key` ASC");
		foreach ($query->rows as $setting) {
			$settings[str_replace($this->name . '_', '', $setting['key'])] = (is_string($setting['value']) && strpos($setting['value'], 'a:') === 0) ? unserialize($setting['value']) : $setting['value'];
		}
		return $settings;
	}
}
?>