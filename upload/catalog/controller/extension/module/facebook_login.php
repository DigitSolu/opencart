<?php
class ControllerExtensionModuleFacebookLogin extends Controller {
	public function index() {
	}
	public function login() {

		$this->load->model('account/customer');
		if ($this->customer->isLogged()) {			
			$json['status'] = "Already Logged in";
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
		}

		$this->load->language('account/login');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			// Unset guest
			unset($this->session->data['guest']);

			// Added strpos check to pass McAfee PCI compliance test (http://forum.opencart.com/viewtopic.php?f=10&t=12043&p=151494#p151295)
			if (isset($this->request->post['redirect']) && $this->request->post['redirect'] != $this->url->link('account/logout', '', true) && (strpos($this->request->post['redirect'], $this->config->get('config_url')) !== false || strpos($this->request->post['redirect'], $this->config->get('config_ssl')) !== false)) {
				$this->response->redirect(str_replace('&amp;', '&', $this->request->post['redirect']));
			} else {

				$this->response->redirect($this->url->link('account/account', '', true));
			}
		}

	
		$json['success'] = "success";
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));

	}	


	protected function validate() {
		$customer_info = $this->model_account_customer->getCustomerByEmail($this->request->post['email']);
		if (!$customer_info) {
			$this->request->post['password']=rand(10000,99999);
			$this->model_account_customer->addCustomer($this->request->post);
		}

		if (!$this->customer->login($this->request->post['email'], $this->request->post['password'],true)) {
			return false;
		} else {
			$this->model_account_customer->deleteLoginAttempts($this->request->post['email']);
		}
		return true;
	}
}