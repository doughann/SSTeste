<?php

include("../system/library/deliverydateswizard/types.php");
include("../system/library/deliverydateswizard/controller.php");
include("../system/library/deliverydateswizard/model.php");

class ControllerModuleDeliveryDatesWizard extends DDWLibController {

	/** @var ModelDeliveryDatesWizardDeliveryDatesWizard */
	protected $model;

	private function _is_installed() {
		$this->load->model("deliverydateswizard/deliverydateswizard");
		$model = $this->model_deliverydateswizard_deliverydateswizard;
		return $model->is_installed();
	}

	public function __construct($registry) {
		parent::__construct($registry);
		if ($this->_is_installed()) {
			$this->load->model("deliverydateswizard/deliverydateswizard");
			$this->model = $this->model_deliverydateswizard_deliverydateswizard;

			if (isset($this->request->post['shipping_method_code'])) {
				$this->model->load($this->request->post['shipping_method_code']);
			} else {
				$this->model->load("");
			}
		}
	}

	public function install() {
		$this->load->model("deliverydateswizard/deliverydateswizard");
		$model = $this->model_deliverydateswizard_deliverydateswizard;
		$model->install();
	}

	public function uninstall()
	{
		$this->load->model("deliverydateswizard/deliverydateswizard");
		$model = $this->model_deliverydateswizard_deliverydateswizard;
		$model->uninstall();
	}


	private function _getDaysToBlockDisplay($return_html=false) {
		$this->template = 'module/deliverydateswizard/daystoblock.tpl';

		if (isset($this->model->ddw->datesCollection))
			$this->data['datesCollection'] = $this->model->ddw->datesCollection;
		else
			$this->data['datesCollection'] = array();
		if (!$return_html) {
			$this->data['daysToBlockDisplay'] = $this->render();
		} else {
			return $this->render();
		}
	}

	private function _render_widget_delivery_times($return_html=false) {
		$delivery_times = array(); //this for the template view
		$this->template = 'module/deliverydateswizard/widget_delivery_times.tpl';

		$this->data['admin_url'] = $this->get_admin_url();

		/* Arrange the delivery time slots for the languages tabs output */
		if (isset($this->model->ddw->delivery_times) AND count($this->model->ddw->delivery_times) > 0) {
			foreach($this->model->ddw->delivery_times as $key=>$item) {
				$delivery_times[$item->language_id][] = $item;
			}
			$this->data['delivery_times'] = $delivery_times;
		}

		if (!$return_html) {
			$this->data['widget_delivery_times'] = $this->render();
		} else {
			return $this->render();
		}
	}


	/**
	 * @return DDWClockList
	 */
	private function _getCutOffTimeLists() {
		$clockList = new DDWClockList();
		$i = 0;

		for ($i=0;$i<24;$i++) {
			$clockList->hours[] = str_pad($i, 2, "0", STR_PAD_LEFT);
		}

		for ($i=0;$i<60;$i++) {
			$clockList->minutes[] = str_pad($i, 2, "0", STR_PAD_LEFT);
		}
		return $clockList;
	}

	public function get_admin_url() {
		if(isset($_SERVER['HTTPS'])) return rtrim(HTTPS_SERVER, "/")."/";
		else return rtrim(HTTP_SERVER, "/")."/";
	}

	public function index() {
		$this->load->model('setting/setting');
		$this->load->model('design/layout');
		$this->language->load('module/deliverydateswizard');
		$this->load->model('localisation/language');

		$this->data['breadcrumbs'] = array();
		$this->data['languages'] = $this->model_localisation_language->getLanguages();

		$this->document->setTitle($this->language->get('heading_title'));
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);
		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);
		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/postcodeblocker', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		/* Render admin widgets */
		$this->_getDaysToBlockDisplay();
		$this->_render_widget_delivery_times();

		$this->data['clockList'] = $this->_getCutOffTimeLists();

		$this->data['admin_url'] = $this->get_admin_url();
		$this->data['token'] = $this->session->data['token'];
		$this->data['ajaxURL'] = html_entity_decode($this->url->link('module/deliverydateswizard/ajax', 'token=' . $this->session->data['token'], 'SSL'));
		$this->data['shipping_methods'] = $this->getShippingMethods();
		
		$this->data['layouts'] = $this->model_design_layout->getLayouts();
		$this->template = 'module/deliverydateswizard/edit.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		$this->response->setOutput($this->render());
	}

	public function getSettings() {
		if (isset($this->request->post["shipping_method_code"])) $shipping_method_code = $this->request->post["shipping_method_code"];
		else $shipping_method_code = "";
		$this->model->load($shipping_method_code);
		return $this->model->ddw;
	}

	public function saveSettings() {
		$ddw_id = -1;
		if (!isset($this->request->post['weekdays'])) $this->request->post['weekdays'] = array();

		$this->model->load($this->request->post['shipping_method_code']);

		$this->model->ddw->shipping_method_code = $this->request->post['shipping_method_code'];
		$this->model->ddw->required = (int)$this->request->post['required'];
		$this->model->ddw->enabled = (int)$this->request->post['enabled'];
		$this->model->ddw->weekdays = $this->request->post['weekdays'];
		$this->model->ddw->min_days = (int)$this->request->post['min_days'];
		$this->model->ddw->max_days = (int)$this->request->post['max_days'];
		$this->model->ddw->cut_off_time_enabled = $this->request->post['cut_off_time_enabled'];
		$this->model->ddw->cut_off_time_hours = $this->request->post['cut_off_time_hours'];
		$this->model->ddw->cut_off_time_minutes = $this->request->post['cut_off_time_minutes'];

		/* Get Dates Collection from Ajax Object */
		if (isset($this->request->post['datesCollection'])) {
			foreach($this->request->post['datesCollection'] as $obj) {
				$ddwDate = new DDWDate();
				$ddwDate->date_start = $obj['date_start'];
				$ddwDate->date_end = $obj['date_end'];
				$ddwDate->type = $obj['type'];
				$ddwDate->recurring = $obj['recurring'];
				$this->model->ddw->dates[] = $ddwDate;
			}
		}

		$this->model->ddw->delivery_times = array();  //constructor will have loaded current times from db, reset that
		if (isset($this->request->post['delivery_times']['collection'])) {
			foreach($this->request->post['delivery_times']['collection'] as $k=>$time_slot) {
				$obj_time_slot = new DDW_Time_Slot();
				$obj_time_slot->language_id = $time_slot['language_id'];
				$obj_time_slot->text = $time_slot['text'];
				$this->model->ddw->delivery_times[] = $obj_time_slot;
			}
		}
		$save_success = $this->model->save();

		/* Save the front end language translations */
		if ($save_success) {
			if (isset($this->request->post['text'])) {
				$text = html_entity_decode(urldecode($this->request->post['text']));
				$params = array();
				parse_str($text, $params);
				$this->load->model('localisation/language');
				$this->data['languages'] = $this->model_localisation_language->getLanguages();

				if (count($params) > 0) {
					$ddw_text = new DDW_Text();
					foreach($params as $key=>$value) {
						$ddw_text_item = new DDW_Text_Object();
						$ddw_text_item->text = utf8_encode($value);
						$ddw_text_item->ddw_id = $this->model->ddw->ddw_id;
						$key_parts = explode("_", $key);
						$ddw_text->text_collection[$key_parts{1}][$key_parts{0}] = $ddw_text_item;
					}
				}
				$this->model->ddw->translations = $ddw_text;
				$this->model_setting_setting->editSetting('ddw_translations_'.$this->model->ddw->ddw_id, $this->model->ddw->translations);
			}
		}


		/* Send Results back to JS */
		$json_result = new JsonResult();
		$json_result->result_code = JsonResultCode::Ok;
		$json_result->data = $this->model->ddw->shipping_method_code;
		return $json_result;
	}

	public function refresh_date_list() {
		print $this->_getDaysToBlockDisplay(true);
	}

	public function updateDDWDate() {
		$ddwDate = new DDWDate();
		$ddwDate->ddwd_id = $this->request->post['ddwd_id'];
		$ddwDate->ddw_id = $this->request->post['ddw_id'];
		$ddwDate->date_start = $this->request->post['date_start'];
		$ddwDate->date_end = $this->request->post['date_end'];
		$ddwDate->type = $this->request->post['type'];
		$ddwDate->recurring = (int)$this->request->post['recurring'];
		$this->model->updateDDWDate($ddwDate);
	}

	public function deleteDDWDate() {
		$this->model->deleteDDWDateById((int)$this->request->post['ddwd_id']);
	}

	public function order_entry_system_update() {
		$this->session->data['ddw_delivery_date'] = "";
		$this->session->data['ddw_time_slot'] = "";
		if (isset($this->request->post['ddw_delivery_date'])) $this->session->data['ddw_delivery_date'] = $this->request->post['ddw_delivery_date'];
		if (isset($this->request->post['ddw_time_slot'])) $this->session->data['ddw_time_slot'] = $this->request->post['ddw_time_slot'];
	}

	public function ajax() {
		switch($this->request->request['action']) {
			case "loadSettings":
				print json_encode($this->getSettings());
				break;
			case "saveSettings":
				print json_encode($this->saveSettings());
				break;
			case "refresh_date_list" :
				$this->refresh_date_list();
				break;
			case "updateDDWDate" :
				$this->updateDDWDate();
				break;
			case "deleteDDWDate" :
				$this->deleteDDWDate();
				break;

		}
	}

}