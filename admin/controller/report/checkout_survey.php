<?php
//==============================================================================
// Checkout Survey v155.1
//
// Author: Clear Thinking, LLC
// E-mail: johnathan@getclearthinking.com
// Website: http://www.getclearthinking.com
//==============================================================================

class ControllerReportCheckoutSurvey extends Controller {
	private $type = 'report';
	private $name = 'checkout_survey';
	
	public function index() {
		$this->data['type'] = $this->type;
		$this->data['name'] = $this->name;
		
		$token = $this->data['token'] = (isset($this->session->data['token'])) ? $this->session->data['token'] : '';
		$version = $this->data['version'] = (!defined('VERSION')) ? 140 : (int)substr(str_replace('.', '', VERSION), 0, 3);
		
		$this->data = array_merge($this->data, $this->load->language($this->type . '/' . $this->name));
		
		$this->data['filters'] = array(
			'question'			=> 1,
			'order_status_id'	=> 0,
			'date_start'		=> date('Y-m-d', strtotime('-7 day')),
			'multilingual'		=> 0,
			'historical'		=> 0,
			'date_end'			=> date('Y-m-d', time())
		);
		
		$url = '';
		
		foreach ($this->data['filters'] as $key => $value) {
			if (isset($this->request->get[$key])) {
				$url .= '&' . $key . '=' . $this->request->get[$key];
				$this->data['filters'][$key] = $this->request->get[$key];
			}
		}
		
		$this->load->model($this->type . '/' . $this->name);
		$data = $this->{'model_'.$this->type.'_'.$this->name}->getSettings();
		$this->data['questions'] = $data['question'][$this->config->get('config_admin_language')];
		
		$admin_language = $this->config->get('config_admin_language');
		$index = $this->data['filters']['question'];
		
		$this->data['responses'] = array();
		$responses = $this->{'model_'.$this->type.'_'.$this->name}->getReport($this->data['filters']);
		
		// Historical Data
		if ($this->data['filters']['historical']) {
			for ($i = 0; $i < count($data['historical_response']); $i++) {
				if ($data['historical_question'][$i] != $data['question'][$admin_language][$index]) {
					continue;
				}
				$responses[] = array(
					'text'					=> $data['historical_response'][$i],
					'customer_responses'	=> (int)$data['historical_customer_responses'][$i],
					'customer_sales'		=> (float)$data['historical_customer_sales'][$i],
					'guest_responses'		=> (int)$data['historical_guest_responses'][$i],
					'guest_sales'			=> (float)$data['historical_guest_sales'][$i],
					'total_sales'			=> (float)$data['historical_customer_sales'][$i] + (float)$data['historical_guest_sales'][$i]
				);
			}
		}
		
		// Survey Responses
		foreach ($responses as $response) {
			foreach (explode('; ', $response['text']) as $r) {
				
				// Combine Multi-lingual Responses
				if ($this->data['filters']['multilingual']) {
					foreach ($data['responses'] as $responses_array) {
						foreach (explode('; ', $responses_array[$index]) as $multilingual_index => $multilingual_response) {
							if ($r != $multilingual_response) continue;
							$primary_language_responses = explode('; ', $data['responses'][$admin_language][$index]);
							$r = $primary_language_responses[$multilingual_index];
							break 2;
						}
					}
				}
				
				// Add Up Responses
				$initial = (isset($this->data['responses'][$r])) ? $this->data['responses'][$r] : array('customer_responses' => 0, 'customer_sales' => 0, 'guest_responses' => 0, 'guest_sales' => 0, 'total_sales' => 0);
				$this->data['responses'][$r] = array(
					'customer_responses'	=> (int)$initial['customer_responses'] + (int)$response['customer_responses'],
					'guest_responses'		=> (int)$initial['guest_responses'] + (int)$response['guest_responses'],
					'customer_sales'		=> (float)$initial['customer_sales'] + (float)$response['customer_sales'],
					'guest_sales'			=> (float)$initial['guest_sales'] + (float)$response['guest_sales'],
					'total_sales'			=> (float)$initial['total_sales'] + (float)$response['total_sales']
				);
			}
		}
		
		// Sort Data by "Total" Column
		$total_sales = array();
		foreach ($this->data['responses'] as $key => $value) $total_sales[$key] = $value['total_sales'];
		array_multisort($total_sales, SORT_DESC, $this->data['responses']);
		
		$this->load->model('localisation/order_status');
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		$breadcrumbs = array();
		$breadcrumbs[] = array(
			'href'		=> $this->makeURL('common/home', 'token=' . $token, 'SSL'),
			'text'		=> $this->data['text_home'],
			'separator' => false
		);
		$breadcrumbs[] = array(
			'href'		=> $this->makeURL($this->type . '/' . $this->name, 'token=' . $token . '&url=' . $url, 'SSL'),
			'text'      => $this->data['heading_title'],
			'separator' => ' :: '
		);
		
		$this->template = $this->type . '/' . $this->name . '.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		
		if ($version < 150) {
			$this->document->title = $this->data['heading_title'];
			$this->document->breadcrumbs = $breadcrumbs;
			$this->response->setOutput($this->render(true), $this->config->get('config_compression'));
		} else {
			$this->document->setTitle($this->data['heading_title']);
			$this->data['breadcrumbs'] = $breadcrumbs;
			$this->response->setOutput($this->render());
		}
	}
	
	private function makeURL($route, $args = '', $connection = 'NONSSL') {
		if (!defined('VERSION') || VERSION < 1.5) {
			$url = ($connection == 'NONSSL') ? HTTP_SERVER : HTTPS_SERVER;
			$url .= 'index.php?route=' . $route;
			$url .= ($args) ? '&' . ltrim($args, '&') : '';
			return $url;
		} else {
			return $this->url->link($route, $args, $connection);
		}
	}
}
?>