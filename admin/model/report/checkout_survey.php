<?php
//==============================================================================
// Checkout Survey v155.1
//
// Author: Clear Thinking, LLC
// E-mail: johnathan@getclearthinking.com
// Website: http://www.getclearthinking.com
//==============================================================================

class ModelReportCheckoutSurvey extends Model {
	private $type = 'report';
	private $name = 'checkout_survey';
	
	public function getReport($filters = array()) {
		$data = $this->getSettings();
		
		$sql = "SELECT ot.text, COUNT(IF(o.customer_id != 0, 1, NULL)) AS customer_responses, COUNT(IF(o.customer_id = 0, 1, NULL)) AS guest_responses, SUM(IF(o.customer_id != 0, o.total, 0)) AS customer_sales, SUM(IF(o.customer_id = 0, o.total, 0)) AS guest_sales, SUM(o.total) AS total_sales";
		$sql .= " FROM `" . DB_PREFIX . "order_total` ot";
		$sql .= " LEFT JOIN `" . DB_PREFIX . "order` o ON (ot.order_id = o.order_id) WHERE (";
		
		$titles = array();
		foreach ($data['lineitem'] as $lineitem) {
			$titles[] = "ot.title = '" . $this->db->escape($lineitem[(int)$filters['question']]) . (!defined('VERSION') || VERSION < 1.5 ? ":" : "") . "'";
		}
		
		$sql .= implode(" OR ", $titles) . ")";
		$sql .= " AND (DATE(date_added) >= '" . $this->db->escape($filters['date_start']) . "'";
		$sql .= " AND DATE(date_added) <= '" . $this->db->escape($filters['date_end']) . "')";
		$sql .= " AND o.order_status_id" . (!empty($filters['order_status_id']) ? " = " . (int)$filters['order_status_id'] : " > 0");
		$sql .= " GROUP BY ot.text ORDER BY total_sales DESC";
		
		$query = $this->db->query($sql);
		return $query->rows;
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