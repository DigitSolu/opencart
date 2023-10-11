<?php
class ControllerExtensionModuleDelaccount extends Controller {

	private $error = array();
	private $prefix;

	public function __construct($registry) {
		parent::__construct($registry);
		$this->prefix = (version_compare(VERSION, '3.0', '>=')) ? 'module_' : '';
	}

	public function index() {
		if (!$this->customer->isLogged() || !$this->config->get($this->prefix . 'delaccount_status')) {
			$this->response->redirect($this->url->link('account/login', '', true));
		}

		$this->load->language('extension/module/delaccount');

		$this->document->setTitle($this->language->get('heading_title_delete'));
		$this->document->addLink($this->url->link('extension/module/delaccount', '', true), 'canonical');

		$this->load->model('extension/module/delaccount');

		$data['heading_title'] = $this->language->get('heading_title_delete');
		
		$customer_email = $this->customer->getEmail();

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

			$code = token(40);
			$this->model_extension_module_delaccount->editCode($customer_email, $code);

			$data['store_name'] = $this->config->get('config_name');
			if ($this->request->server['HTTPS']) {
				$data['store_url'] = $this->config->get('config_ssl');
			} else {
				$data['store_url'] = $this->config->get('config_url');
			}
			$data['logo'] = $data['store_url'] . 'image/' . $this->config->get('config_logo');

			$data['confirm_link'] = $this->url->link('extension/module/delaccount/confirm', 'code='.$code, true);

			$mail = new Mail($this->config->get('config_mail_engine'));
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
			$mail->smtp_username = $this->config->get('config_mail_smtp_username');
			$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail->smtp_port = $this->config->get('config_mail_smtp_port');
			$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

			$mail->setTo($customer_email);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setReplyTo($this->config->get('config_email'));
			$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
			$mail->setSubject(html_entity_decode($this->language->get('text_delete'), ENT_QUOTES, 'UTF-8'));
			$mail->setHTML($this->load->view('mail/delete_account', $data));
			$mail->send();

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('account/account', '', true));
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', '', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_delete'),
			'href' => $this->url->link('extension/module/delaccount', '', true)
		);

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['action'] = $this->url->link('extension/module/delaccount', '', true);

		$data['back'] = $this->url->link('account/account', '', true);

		$data['content'] = html_entity_decode($this->config->get($this->prefix . 'delaccount_content_delete')[$this->config->get('config_language_id')], ENT_QUOTES, 'UTF-8');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('extension/module/delaccount', $data));
	}

	public function confirm() {

		if (!$this->config->get($this->prefix . 'delaccount_status') || empty($this->request->get['code'])) {
			$this->response->redirect($this->url->link('account/login', '', true));
		}

		if (isset($this->request->get['code'])) {
			$code = $this->request->get['code'];
		} else {
			$code = '';
		}

		$this->load->language('extension/module/delaccount');
		
		$this->load->model('extension/module/delaccount');
		$this->load->model('account/customer');

		$customer_info = $this->model_account_customer->getCustomerByCode($code);

		if ($customer_info) {

			$this->document->setTitle($this->language->get('heading_title_delete'));

			$order = $this->config->get($this->prefix . 'delaccount_order');
			$review = $this->config->get($this->prefix . 'delaccount_review');

			$this->model_extension_module_delaccount->deleteAccount($customer_info['customer_id'], $order, $review);

			$mail = new Mail($this->config->get('config_mail_engine'));
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
			$mail->smtp_username = $this->config->get('config_mail_smtp_username');
			$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail->smtp_port = $this->config->get('config_mail_smtp_port');
			$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

			$mail->setTo($customer_info['email']);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setReplyTo($this->config->get('config_email'));
			$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
			$mail->setSubject(html_entity_decode($this->language->get('text_delete'), ENT_QUOTES, 'UTF-8'));
			$mail->setText($this->language->get('text_data_delete'));
			$mail->send();

			$this->session->data['success'] = $this->language->get('text_data_delete');

		} else {
			exit($this->language->get('error_code'));
		}

		$this->response->redirect($this->url->link('account/login', '', true));

	}

	protected function validate() {
		$this->load->model('account/customer');
		$customer_info = $this->model_account_customer->getCustomer($this->customer->getId());

		if ($customer_info && !$customer_info['status']) {
			$this->error['warning'] = $this->language->get('error_approved');
		}

		return !$this->error;
	}

}
