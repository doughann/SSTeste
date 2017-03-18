<?php
class ControllerModuleBuyTogether extends Controller {
	private $error = array(); 
	
    public function install()
    {
    }
    
    public function uninstall()
    {
    }
    
	public function index() {   
		$this->language->load('module/buy_together');
        $this->data['lang'] = $this->language;
        
        $this->load->model('setting/setting');
        
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('buy_together', $this->request->post);        
            $this->session->data['success'] = $this->language->get('text_success');
            $this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
        }
        
        $options = $this->config->get('buy_together_options');
        if(empty($options)){
            $this->data['buy_together_display'] = 0;
            $this->data['buy_together_title_width'] = 17;
            $this->data['buy_together_image_width'] = $this->config->get('config_image_cart_width');
            $this->data['buy_together_image_height'] = $this->config->get('config_image_cart_height');            
        }
        else{
            $this->data['buy_together_display'] = $options['display'];
            $this->data['buy_together_title_width'] = $options['titleWidth'];
            $this->data['buy_together_image_width'] = $options['imageWidth'];
            $this->data['buy_together_image_height'] = $options['imageHeight'];
        }
        
        $modules = $this->config->get('buy_together_module');
        if(count($modules))
        {
            $this->data['module'] = $modules[0];    
        }
        else
        {
            $this->data['module'] = array(
                'layout_id' => 2,
                'position' => 'content_bottom',
                'status' => 1,
                'sort_order' => 10
            );
        }
        
        $this->data['action'] = $this->url->link('module/buy_together', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
        $this->breadcrumbs();
		$this->template = 'module/buy_together.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
        
		$this->response->setOutput($this->render());
	}
    
    private function breadcrumbs()
    {
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
            'href'      => $this->url->link('module/buy_together', 'token=' . $this->session->data['token'], 'SSL'),
              'separator' => ' :: '
           );
        
    }
	
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'module/buy_together')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>
