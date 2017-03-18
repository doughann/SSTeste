<?php
class ModelSettingExtension extends Model {
	function getExtensions($type) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension WHERE `type` = '" . $this->db->escape($type) . "'");
// Clear Thinking: restrict_shipping.xml
				if ($type == 'shipping' && $this->config->get('restrict_shipping_status')) {
					$this->load->model('module/restrict_shipping');
					$query->rows = $this->model_module_restrict_shipping->restrict($query->rows);
				}
				// end: restrict_shipping.xml

		return $query->rows;
	}
}
?>