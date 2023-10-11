<?php
class ControllerExtensionModuledelaccount extends Controller {

	private $error = array();
	private $token_var;
	private $extension_var;
	private $prefix;

	public function __construct($registry) {
		parent::__construct($registry);
		$this->token_var = (version_compare(VERSION, '3.0', '>=')) ? 'user_token' : 'token';
		$this->extension_var = (version_compare(VERSION, '3.0', '>=')) ? 'marketplace' : 'extension';
		$this->prefix = (version_compare(VERSION, '3.0', '>=')) ? 'module_' : '';
	}

	public function index() {
		$data = $this->load->language('extension/module/delaccount');

		$heading_title = $this->language->get('heading_title');
		$this->document->setTitle($heading_title);

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting($this->prefix . 'delaccount', $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');

			if (isset($this->request->post['apply'])) {
				$this->response->redirect($this->url->link('extension/module/delaccount', $this->token_var . '=' . $this->session->data[$this->token_var], true));
			} else {
				$this->response->redirect($this->url->link($this->extension_var . '/extension', $this->token_var . '=' . $this->session->data[$this->token_var] . '&type=module', true));
			}
		}

		$this->document->addStyle('view/javascript/summernote/summernote.css');
		$this->document->addScript('view/javascript/summernote/summernote.js');
		$this->document->addScript('view/javascript/summernote/opencart.js');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', $this->token_var . '=' . $this->session->data[$this->token_var], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link($this->extension_var . '/extension', $this->token_var . '=' . $this->session->data[$this->token_var] . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $heading_title,
			'href' => $this->url->link('extension/module/delaccount', $this->token_var . '=' . $this->session->data[$this->token_var], true)
		);

		$data['prefix'] = $this->prefix;
		$data['token_var'] = $this->token_var;
		$data[$this->token_var] = $this->session->data[$this->token_var];
		$data['action'] = $this->url->link('extension/module/delaccount', $this->token_var . '=' . $this->session->data[$this->token_var], true);
		$data['cancel'] = $this->url->link($this->extension_var . '/extension', $this->token_var . '=' . $this->session->data[$this->token_var] . '&type=module', true);

		$this->load->model('localisation/language');
		$languages = $this->model_localisation_language->getLanguages();
		$data['languages'] = $languages;

		if (isset($this->request->post[$this->prefix . 'delaccount_status'])) {
			$data[$this->prefix . 'delaccount_status'] = $this->request->post[$this->prefix . 'delaccount_status'];
		} else {
			$data[$this->prefix . 'delaccount_status'] = $this->config->get($this->prefix . 'delaccount_status');
		}
		if (isset($this->request->post[$this->prefix . 'delaccount_order'])) {
			$data[$this->prefix . 'delaccount_order'] = $this->request->post[$this->prefix . 'delaccount_order'];
		} else {
			$data[$this->prefix . 'delaccount_order'] = $this->config->get($this->prefix . 'delaccount_order');
		}
		if (isset($this->request->post[$this->prefix . 'delaccount_review'])) {
			$data[$this->prefix . 'delaccount_review'] = $this->request->post[$this->prefix . 'delaccount_review'];
		} else {
			$data[$this->prefix . 'delaccount_review'] = $this->config->get($this->prefix . 'delaccount_review');
		}
		if (isset($this->request->post[$this->prefix . 'delaccount_content_delete'])) {
			$data[$this->prefix . 'delaccount_content_delete'] = $this->request->post[$this->prefix . 'delaccount_content_delete'];
		} else {
			$data[$this->prefix . 'delaccount_content_delete'] = $this->config->get($this->prefix . 'delaccount_content_delete');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/delaccount', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/delaccount')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}
}