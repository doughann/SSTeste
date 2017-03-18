<?php

/* Application Specific Data Structures */

function getCalledClass(){
	$arr = array();
	$arrTraces = debug_backtrace();
	foreach ($arrTraces as $arrTrace){
		if(!array_key_exists("class", $arrTrace)) continue;
		if(count($arr)==0) $arr[] = $arrTrace['class'];
		else if(get_parent_class($arrTrace['class'])==end($arr)) $arr[] = $arrTrace['class'];
	}
	return end($arr);
}

abstract class Enumerable {
	public static function collection() {
		if (version_compare(PHP_VERSION, '5.3.0', '<'))
			$oClass = new ReflectionClass (getCalledClass());
		else
			$oClass = new ReflectionClass (get_called_class());
		return $oClass->getConstants();
	}
}

abstract class DDWDateType extends Enumerable {
	const Single = "single";
	const Range = "range";
}

class DDW_Time_Slot {
	public
		$language_id = -1,
		$text = "",
		$ddw_id = -1,
		$shipping_method_code = "";
}

class DDWClockList {
	public $hours = array();
	public $minutes = array();
}

class DDWDate {
	public  $ddwd_id,
			$ddw_id,
			$date_start,
			$date_end,
			/** @var DDWDateType */
			$type = DDWDateType::Range,
			$recurring = false;

	public function formatDateString($strDate, $time="00:00:00") {
		//$objDate = DateTime::createFromFormat('Y-m-d H:i:s', $strDate.' '.$time);
		//return $objDate->format('Y-m-d H:i:s');
		$objDate   = date('Y-m-d', strtotime($strDate))." $time";
		return $objDate;
	}

	/**
	 * Removes the timestamp from a date time string
     * @return string
	*/
	public function unformatDateString($strDateTime) {
		$objDate   = date('Y-m-d', strtotime($strDateTime));
		return $objDate;
		//$objDate = DateTime::createFromFormat('Y-m-d H:i:s', $strDateTime);
		//return $objDate->format('Y-m-d');
	}
}

class DDWBlockedDate {
	public
		$date_start,
		$date_end,
		$type = DDWDateType::Range,
		$recurring = false;
}

class DDWCalendarBlockedDate {
	public
		$date = "",
		$blocked = false;
}

class DDW {
	public  $ddw_id = "",
			$shipping_method_code = '',
			$required = 0,
			$enabled = 1,
			$min_days = 0,
			$max_days = 0,
			$cut_off_time_enabled = false,
			$cut_off_time_hours = 0,
			$cut_off_time_minutes = 0,
			$weekdays = "",
			/** @var DDWDate[] */
			$datesCollection = array(),
			/** @var DDW_Text[] */
			$translations,
			/** @var DDW_Time_Slot[] */
			$delivery_times = array();
}

class DDW_Text_Object {
	public
		$text = "",
		$ddw_id = "";
}

class DDW_Text {
	public
			/** @var $text_collection string[] */
			$text_collection = array();
}


class DDW_Order {
	public
		$ddw_delivery_date = "",
		$ddw_time_slot = "";
}

/* Ajax Structures for handling data exchange between client and server */

abstract class JsonResultCode {
	const Undefined = -1;
	const GeneralError = 0;
	const Ok = 1;
}

abstract class JsonError {
	public $text = "";
	public $form_element_id = "";
	public $extra;
}

class JsonResult {
	public $errors = array(); //of JsonError
	public $result_code = JsonResultCode::Undefined;
	public $data; //mixed, it is up to the calling javascript to handle this data
}