<?php


class DDWModel extends Model {

	/** @var DDW */
	public $ddw;

	public function __construct($registry) {
		parent::__construct($registry);
		$this->ddw = new DDW();
		$this->load->model('setting/setting');
	}

	public function getBlockedDates($shipping_method_code) {
		$ddwBlockedDates = array();

		$sql = "SELECT * FROM ".DB_PREFIX."deliverydateswizard_dates ddwd
				INNER JOIN ".DB_PREFIX."deliverydateswizard ddw ON ddwd.ddw_id = ddw.ddw_id
				WHERE ddw.shipping_method_code LIKE '$shipping_method_code'
			   ";
		$query = $this->db->query($sql);
		if ($query->num_rows) {
			foreach($query->rows as $row) {
				$ddwBlockedDate = new DDWBlockedDate();
				$ddwBlockedDate->date_start = $row['date_start'];
				$ddwBlockedDate->date_end = $row['date_end'];
				$ddwBlockedDate->type = $row['type'];
				$ddwBlockedDate->recurring = $row['recurring'];
				$ddwBlockedDates[] = $ddwBlockedDate;
			}
			return $ddwBlockedDates;
		} return false;
	}

	public function exists($shippingMethodCode) {
	}

	public function load($shipping_method_code) {
		$sql = "SELECT * FROM ".DB_PREFIX."deliverydateswizard
				WHERE shipping_method_code LIKE '".$shipping_method_code."' LIMIT 1";
		$query = $this->db->query($sql);
		if ($query->num_rows) {
			$row = $query->rows[0];
			$this->ddw->ddw_id = $row['ddw_id'];
			$this->ddw->shipping_method_code = $row['shipping_method_code'];
			$this->ddw->required = $row['required'];
			$this->ddw->enabled = $row['enabled'];
			$this->ddw->weekdays = $row['weekdays'];
			$this->ddw->min_days = $row['min_days'];
			$this->ddw->max_days = $row['max_days'];
			$this->ddw->cut_off_time_enabled = $row['cut_off_time_enabled'];
			$this->ddw->cut_off_time_hours = str_pad($row['cut_off_time_hours'], 2, "0", STR_PAD_LEFT);;
			$this->ddw->cut_off_time_minutes = str_pad($row['cut_off_time_minutes'], 2, "0", STR_PAD_LEFT);;

			/* Load the Blocked Dates */
			$sql = "SELECT * FROM ".DB_PREFIX."deliverydateswizard_dates
					WHERE ddw_id = ".(int)$this->ddw->ddw_id."";
			$query = $this->db->query($sql);
			$this->ddw->datesCollection = array();

			if ($query->num_rows) {
				foreach($query->rows as $row) {
					$ddwDate = new DDWDate();
					$ddwDate->ddw_id = $this->ddw->ddw_id;
					$ddwDate->ddwd_id = $row['ddwd_id'];
					$ddwDate->date_start = $row['date_start'];
					$ddwDate->date_end = $row['date_end'];
					$ddwDate->recurring = $row['recurring'];
					switch($row['type']) {
						case "single":
							$ddwDate->type = DDWDateType::Single;
							break;
						case "range":
							$ddwDate->type = DDWDateType::Range;
							break;
					}
					$this->ddw->datesCollection[] = $ddwDate;
				}
			}

			/* Load the delivery time slots */
			$this->ddw->delivery_times = array();
			$sql = "SELECT * FROM ".DB_PREFIX."deliverydateswizard_times WHERE ddw_id = ".$this->ddw->ddw_id." ORDER BY position ASC";
			$query = $this->db->query($sql);

			if ($query->num_rows) {
				foreach($query->rows as $row) {
					$ddw_time = new DDW_Time_Slot();
					$ddw_time->language_id = $row['language_id'];
					$ddw_time->text = $row['text'];
					$ddw_time->ddw_id = $this->ddw->ddw_id;
					$this->ddw->delivery_times[] = $ddw_time;
				}
			}
			/* Load the translations */
			$this->load->model('setting/setting');
			$this->ddw->translations = $this->model_setting_setting->getSetting('ddw_translations_'.$this->ddw->ddw_id);

			foreach($this->ddw->translations as &$translation)
				foreach($translation as &$trans_lang)
					foreach($trans_lang as &$text)
						$text->text = utf8_decode($text->text);
		}
	}

	public function save() {
		$this->load->model('setting/setting');

		/* Update existing */
		if ($this->ddw->ddw_id != "") {
			$sql = "UPDATE ".DB_PREFIX."deliverydateswizard
					SET
					required = '".$this->ddw->required."',
					enabled = '".$this->ddw->enabled."',
					weekdays = '".implode(",", $this->ddw->weekdays)."',
					min_days = ".(int)$this->ddw->min_days.",
					max_days = ".(int)$this->ddw->max_days.",
					cut_off_time_enabled = ".(int)$this->ddw->cut_off_time_enabled.",
					cut_off_time_hours = ".(int)$this->ddw->cut_off_time_hours.",
					cut_off_time_minutes = ".(int)$this->ddw->cut_off_time_minutes."
					WHERE ddw_id = ".(int)$this->ddw->ddw_id;
			$this->db->query($sql);
		} else {
			$sql = "INSERT INTO ".DB_PREFIX."deliverydateswizard (
						shipping_method_code,
						required,
						enabled,
						weekdays,
						min_days,
						max_days,
						cut_off_time_enabled,
						cut_off_time_hours,
						cut_off_time_minutes
					) VALUES (
						'".$this->ddw->shipping_method_code."',
						'".$this->ddw->required."',
						'".$this->ddw->enabled."',
						'".implode(",", $this->ddw->weekdays)."',
						".(int)$this->ddw->min_days.",
						".(int)$this->ddw->max_days.",
						".(int)$this->ddw->cut_off_time_enabled.",
						".(int)$this->ddw->cut_off_time_hours.",
						".(int)$this->ddw->cut_off_time_minutes."
					)";
			$this->db->query($sql);
			$this->ddw->ddw_id = $this->db->getLastId();
		}

		if (isset($this->ddw->dates) AND count($this->ddw->dates > 0)) {
			foreach($this->ddw->dates as $ddwDate) {
				/* @var $ddwDate DDWDate */
				$sql = "INSERT INTO ".DB_PREFIX."deliverydateswizard_dates (
					ddw_id,
					date_start,
					date_end,
					type,
					recurring
				) VALUES (
					'".$this->ddw->ddw_id."',
					'".$ddwDate->formatDateString($ddwDate->date_start)."',
					'".$ddwDate->formatDateString($ddwDate->date_end, "23:59:59")."',
					'".$ddwDate->type."',
					'".$ddwDate->recurring."'
				)";
				$this->db->query($sql);
			}
		}

		/* Save the delivery time slots */
		$sql = "DELETE FROM ".DB_PREFIX."deliverydateswizard_times WHERE ddw_id = ".$this->ddw->ddw_id;
		$this->db->query($sql);

		$position = 0;

		if (isset($this->ddw->delivery_times) AND count($this->ddw->delivery_times) > 0) {
			foreach($this->ddw->delivery_times as $time) {
				$sql = "INSERT INTO ".DB_PREFIX."deliverydateswizard_times (
					ddw_id,
					language_id,
					text,
					position
				) VALUES (
					'".$this->ddw->ddw_id."',
					'".$time->language_id."',
					'".$time->text."',
					".$position."
				)";
				$position++;
				$this->db->query($sql);
			}
		}
		/* end */
		if (isset($this->ddw->ddw_id))
			return $this->ddw->ddw_id;
		else return false;
	}

	public function updateDDWDate(DDWDate $ddwDate) {
		$sql =  "UPDATE ".DB_PREFIX."deliverydateswizard_dates
				 SET
					 date_start = '".$ddwDate->formatDateString($ddwDate->date_start)."',
					 date_end = '".$ddwDate->formatDateString($ddwDate->date_end, "23:59:59")."',
					 type = '".$ddwDate->type."',
					 recurring = '".$ddwDate->recurring."'
				 WHERE
				    ddwd_id = ".$ddwDate->ddwd_id;
		$this->db->query($sql);
	}

	public function deleteDDWByShippingCode($shipping_method_code) {
		$sql = "
			DELETE ".DB_PREFIX."deliverydateswizard_dates
			FROM ".DB_PREFIX."deliverydateswizard_dates
			INNER JOIN ".DB_PREFIX."deliverydateswizard ON ".DB_PREFIX."deliverydateswizard_dates.ddw_id = ".DB_PREFIX."deliverydateswizard.ddw_id
			WHERE ".DB_PREFIX."deliverydateswizard.shipping_method_code LIKE '".$shipping_method_code."'
		";
		$this->db->query($sql);

		$sql =  "DELETE FROM ".DB_PREFIX."deliverydateswizard WHERE shipping_method_code LIKE '".$shipping_method_code."'";
		$this->db->query($sql);
	}

	public function deleteDDWDateById($ddwd_id) {
		$sql =  "DELETE FROM ".DB_PREFIX."deliverydateswizard_dates WHERE ddwd_id=".$ddwd_id;
		$this->db->query($sql);
	}

	public function get_order_delivery_date($order_id) {
		$sql = "SELECT ddw_delivery_date FROM ".DB_PREFIX."order WHERE order_id=".(int)$order_id." LIMIT 1";
		$query = $this->db->query($sql);
		if ($query->num_rows) {
			return $query->rows[0]['ddw_delivery_date'];
		}
		return false;
	}

	public function get_order_delivery_time($order_id) {
		$sql = "SELECT ddw_time_slot FROM ".DB_PREFIX."order WHERE order_id=".(int)$order_id." LIMIT 1";
		$query = $this->db->query($sql);
		if ($query->num_rows) {
			return $query->rows[0]['ddw_time_slot'];
		}
		return false;
	}

	public function get_all_delivery_times() {
		$delivery_times = array();
		
		$sql = "SELECT
		            ddwt.*,
		            ddw.shipping_method_code
				FROM ".DB_PREFIX."deliverydateswizard_times ddwt
		        INNER JOIN ".DB_PREFIX."deliverydateswizard ddw ON ddwt.ddw_id = ddw.ddw_id
		        ORDER BY ddwt.position
		        ";
		$query = $this->db->query($sql);
		if ($query->num_rows) {
			foreach($query->rows as $row) {
				$ddw_time = new DDW_Time_Slot();
				$ddw_time->language_id = $row['language_id'];
				$ddw_time->ddw_id = $row['ddw_id'];
				$ddw_time->shipping_method_code = $row['shipping_method_code'];
				$ddw_time->text = $row['text'];
				$delivery_times[] = $ddw_time; 
			}
			return $delivery_times;
		}
		return false;
	}

	public function get_all_translations() {
		$text_collection = array();
		$sql = "SELECT `group`, `value`
				FROM ".DB_PREFIX."setting WHERE `group` LIKE 'ddw_translations_%'";
		$query = $this->db->query($sql);
		if ($query->num_rows) {
			foreach($query->rows as $key=>$row) {
				$key = str_replace("ddw_translations_", "", $row['group']);
				$obj = $this->model_setting_setting->getSetting('ddw_translations_'.$key);

				$sql = "SELECT * FROM ".DB_PREFIX."deliverydateswizard WHERE ddw_id=".$key;
				$query2 = $this->db->query($sql);
				if ($query2->num_rows) {
					$shipping_method_code = $query2->rows[0]['shipping_method_code'];
					foreach($obj as $obj_add) {
						foreach($obj_add as $key2=>&$obj_add_2) {
							foreach($obj_add_2 as $key3=>&$obj_add_3) {
								$obj_add_3->name = $key3;
								$obj_add_3->language_id = $key2;
								$obj_add_3->shipping_method_code = $shipping_method_code;
								$obj_add_3->text = utf8_decode($obj_add_3->text);
								$text_collection[] = $obj_add_3;
							}

						}
					}
				}
			}
		}
		return $text_collection;
	}

	/**
	 * @param integer $order_id
	 * @return DDW_Order
	 */
	public function get_order_ddw($order_id) {
		$sql = "SELECT ddw_delivery_date, ddw_time_slot FROM ".DB_PREFIX."order WHERE order_id=".(int)$order_id." LIMIT 1";
		$query = $this->db->query($sql);
		if ($query->num_rows) {
			$ddw_order = new DDW_Order();
			$ddw_order->ddw_delivery_date = $query->rows[0]['ddw_delivery_date'];
			$ddw_order->ddw_time_slot = $query->rows[0]['ddw_time_slot'];
			return $ddw_order;
		}
		return false;
	}

	/*public function update_order_date($order_id, $ddw_delivery_date) {
		$this->log->write($order_id.":".$ddw_delivery_date);
		$sql = "UPDATE ".DB_PREFIX."order SET ddw_delivery_date = '$ddw_delivery_date' WHERE order_id = ".(int)$order_id;
		$this->db->query($sql);
	}*/



}