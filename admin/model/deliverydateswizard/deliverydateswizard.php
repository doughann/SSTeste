<?php

class ModelDeliveryDatesWizardDeliveryDatesWizard extends DDWModel {

	public function is_installed() {
		$installed = true;
		$sql = "SELECT table_name FROM information_schema.tables WHERE table_schema = '".DB_PREFIX."deliverydateswizard' OR table_name = '".DB_PREFIX."deliverydateswizard';";
		$query = $this->db->query($sql);
		if ($query->num_rows == 0) $installed = false;

		$sql = "SELECT table_name FROM information_schema.tables WHERE table_schema = '".DB_PREFIX."deliverydateswizard_times' OR table_name = '".DB_PREFIX."deliverydateswizard_times';";
		$query = $this->db->query($sql);
		if ($query->num_rows == 0) $installed = false;

		return $installed;
	}

	public function addColumn($tableName, $name, $type, $log) {
		$col = $this->db->query("SELECT `$name` FROM `$tableName`");
		if (!array_key_exists($name, $col->row)) $this->db->query("ALTER TABLE `$tableName` ADD `$name` $type");
	}

	public function install() {
		$this->load->model('setting/setting');

		$sql = "
			CREATE TABLE IF NOT EXISTS `".DB_PREFIX."deliverydateswizard` (
			  `ddw_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			  `shipping_method_code` varchar(128) NOT NULL,
			  `required` int(1) unsigned NOT NULL DEFAULT '0',
			  `enabled` int(1) unsigned NOT NULL DEFAULT '0',
			  `weekdays` varchar(32) NOT NULL DEFAULT '',
			  `min_days` tinyint(3) unsigned NOT NULL DEFAULT '0',
			  `max_days` SMALLINT(4) unsigned NOT NULL DEFAULT '0',
			  `cut_off_time_enabled` tinyint(1) NOT NULL DEFAULT '0',
			  `cut_off_time_hours` tinyint(3) unsigned NOT NULL DEFAULT '0',
			  `cut_off_time_minutes` tinyint(3) unsigned NOT NULL DEFAULT '0',
			  PRIMARY KEY (`ddw_id`)
			)
		";
		$this->db->query($sql);

		$sql = "
			CREATE TABLE IF NOT EXISTS `".DB_PREFIX."deliverydateswizard_dates` (
			  `ddwd_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `ddw_id` int(10) unsigned NOT NULL,
			  `date_start` datetime NOT NULL,
			  `date_end` datetime NOT NULL,
			  `type` varchar(12) NOT NULL,
			  `recurring` int(1) unsigned NOT NULL DEFAULT '0',
			  PRIMARY KEY (`ddwd_id`)
			)
		";
		$this->db->query($sql);

		$sql = "
			CREATE TABLE IF NOT EXISTS `".DB_PREFIX."deliverydateswizard_times` (
				`ddw_id` int(10) unsigned NOT NULL,
  				`language_id` int(10) unsigned NOT NULL,
  				`text` varchar(64) NOT NULL,
  				`position` int(10) NOT NULL
			)
		";
		$this->db->query($sql);

		$this->addColumn(DB_PREFIX.'order', 'ddw_delivery_date', 'DATETIME NULL DEFAULT NULL', false);
		$this->addColumn(DB_PREFIX.'order', 'ddw_time_slot', 'VARCHAR(64) NOT NULL', false);

		/* Create translations */
		$this->load->model('localisation/language');
		$languages = $this->model_localisation_language->getLanguages();

		$ddw_text = new DDW_Text();
		foreach($languages as $language) {
			$ddw_text->text_collection[$language{'language_id'}]['textlabel'] = "Delivery Date:";
			$ddw_text->text_collection[$language{'language_id'}]['textselect'] = "Select delivery date";
			$ddw_text->text_collection[$language{'language_id'}]['textselecteddate'] = "delivery date chosen:";
			$ddw_text->text_collection[$language{'language_id'}]['textrequirederror'] = "Please select a delivery date and time";
		}
		$this->model_setting_setting->editSetting('ddw_translations', $ddw_text);
	}

	private function dropTable($table_name)
	{
		$sql = 'DROP TABLE IF EXISTS `'.DB_PREFIX.$table_name.'`';
		$this->db->query($sql);
	}

	public function uninstall()
	{
		$this->dropTable('deliverydateswizard');
		$this->dropTable('deliverydateswizard_dates');
		$this->dropTable('deliverydateswizard_time');
	}

}