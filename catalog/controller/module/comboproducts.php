<?php
/*
 * Author: minhdqa
 * Mail: minhdqa@gmail.com
 */
class ControllerModuleComboProducts extends Controller {
    public function index() {
		$this->load->language('total/combo_products');
		$this->data = array();
		$this->data['combo_heading']= $this->language->get('text_combo');
		$path = '/template/module/combo_products.tpl';
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . $path)) {

       $this->template = $this->config->get('config_template') . $path;
		} else {

      $this->template = 'default'.$path;
		}



		//$this->render();
      $this->response->setOutput($this->render());
	//	 return $this->load->view($this->template, $this->data);
	}
}
?>
