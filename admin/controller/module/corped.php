<?php
class ControllerModuleCorped extends Controller {
	private $error = array(); 
	
	public function index() {   
		$this->load->language('module/corped');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
				
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			$this->model_setting_setting->editSetting('corped', $this->request->post);		
					
			$this->session->data['success'] = $this->language->get('text_success');
						
			$this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}
		
                
                /*
                Inicio carrega variaveis de linguagem
#################################################################################################
                 */
		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_module'] = $this->language->get('button_add_module');
		$this->data['button_remove'] = $this->language->get('button_remove');
		 /*
                Fim carrega variaveis de linguagem
#################################################################################################
                 */
                
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

                /*
                Inicio migalha de pão
#################################################################################################
                 */
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
			'href'      => $this->url->link('module/corped', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
                 /*
                Fim migalha de pão
#################################################################################################
                 */
		
		$this->data['action'] = $this->url->link('module/corped', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
		
		
		if (isset($this->request->post['corped_status'])) {
			$this->data['corped_status'] = $this->request->post['corped_status'];
		} elseif ($this->config->get('corped_status')) { 
			$this->data['corped_status'] = $this->config->get('corped_status');
		}	
					
		
		$this->template = 'module/corped.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);		
		$this->response->setOutput($this->render());
	}
	
        public function install() {
            $query = $this->db->query("ALTER TABLE  " . DB_PREFIX . "order_status ADD `cor` VARCHAR( 6 ) NOT NULL AFTER  `name`");	
	}
        public function uninstall() { 
            $query = $this->db->query("ALTER TABLE  " . DB_PREFIX . "order_status DROP `cor`");
        }    
}
?>