<?php
//==============================================================================
// Checkout Survey v155.1
//
// Author: Clear Thinking, LLC
// E-mail: johnathan@getclearthinking.com
// Website: http://www.getclearthinking.com
//==============================================================================

class ControllerTotalCheckoutSurvey extends Controller {
	private $type = 'total';
	private $name = 'checkout_survey';
	
	public function index() {}
	
	public function setResponse() {
		for ($i = 0; $i < $this->request->post['count']; $i++) {
			unset($this->session->data[$this->name . '_' . $i]);
		}
		
		foreach ($this->request->post['responses'] as $response) {
			if (empty($response['value'])) continue;
			$this->session->data[$response['name']] = (isset($this->session->data[$response['name']])) ? $this->session->data[$response['name']] . '; ' . $response['value'] : $response['value'];
		}
	}
}
?>