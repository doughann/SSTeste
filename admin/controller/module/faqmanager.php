<?php
class ControllerModuleFaqmanager extends Controller {
	private $error = array(); 
	 
	public function index() {   
		$this->load->language('module/faqmanager');
		
		$this->load->model('tool/image');

		$this->document->setTitle($this->language->get('doc_title'));
		
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('faqmanager', $this->request->post);		
			
			$this->session->data['success'] = $this->language->get('text_success');
						
			$this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}
		
				
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_content_top'] = $this->language->get('text_content_top');
		$this->data['text_content_bottom'] = $this->language->get('text_content_bottom');		
		$this->data['text_column_left'] = $this->language->get('text_column_left');
		$this->data['text_column_right'] = $this->language->get('text_column_right');
		$this->data['text_browse'] = $this->language->get('text_browse');
		$this->data['text_clear'] = $this->language->get('text_clear');
		$this->data['text_title'] = $this->language->get('text_title');
		$this->data['text_link'] = $this->language->get('text_link');
		$this->data['text_image_manager'] = $this->language->get('text_image_manager');
		
		
		$this->data['entry_title_description'] = $this->language->get('entry_title_description');
		$this->data['entry_image'] = $this->language->get('entry_image');
		$this->data['entry_layout'] = $this->language->get('entry_layout');
		$this->data['entry_position'] = $this->language->get('entry_position');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_module'] = $this->language->get('button_add_module');
		$this->data['button_add_section'] = $this->language->get('button_add_section');
		$this->data['button_remove'] = $this->language->get('button_remove');
		
		$this->data['tab_module'] = $this->language->get('tab_module');
		$this->data['question_title'] = $this->language->get('question_title');
		
		$this->data['token'] = $this->session->data['token'];

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		

  		$this->data['breadcrumbs'] = array();

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
			'href'      => $this->url->link('module/faqmanager', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('module/faqmanager', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

		$modules = array();
		
		if (isset($this->request->post['faqmanager_module'])) {
			$modules = $this->request->post['faqmanager_module'];
		} elseif ($this->config->get('faqmanager_module')) { 
			$modules = $this->config->get('faqmanager_module');
		}

		if ($modules){
			foreach($modules as $key => $value){
				$module_number = $key;
				
				foreach($value['sections'] as $key => $value){
					$section_number = $key;
					
				}				
			}
		}

		$this->data['modules'] = $modules;	
				
		$this->load->model('design/layout');
		
		$this->data['layouts'] = $this->model_design_layout->getLayouts();
		
		$this->load->model('localisation/language');
		
		$this->data['languages'] = $this->model_localisation_language->getLanguages();

		$this->template = 'module/faqmanager.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}
	
	private function validate() {
		
		if (!$this->user->hasPermission('modify', 'module/faqmanager')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (isset($this->request->post['faqmanager_module'])) {
			foreach ($this->request->post['faqmanager_module'] as $key => $value) {
				$module_number = $key;
				
			}
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>