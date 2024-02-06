<?php
class ControllerExtensionModuleCiformbuilder extends Controller {
	private $error = array();

	public function __construct($registery) {
		parent::__construct($registery);

		if(VERSION <= '2.3.0.2') {
			$this->module_token = 'token';
			$this->ci_token = $this->session->data['token'];

			$this->extension_path = 'extension/extension';
		} else {
			$this->module_token = 'user_token';
			$this->ci_token = $this->session->data['user_token'];

			$this->extension_path = 'marketplace/extension';
		}
	}

	public function index() {
		$this->load->language('extension/module/ciformbuilder');

		$this->document->setTitle($this->language->get('heading_title_page'));

		if(VERSION >= '3.0.0.0') {
			$this->load->model('setting/module');
		} else {
			$this->load->model('extension/module');
		}

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			if (!isset($this->request->get['module_id'])) {
				if(VERSION >= '3.0.0.0') {
					$this->model_setting_module->addModule('ciformbuilder', $this->request->post);
				} else {
					$this->model_extension_module->addModule('ciformbuilder', $this->request->post);
				}
			} else {
				if(VERSION >= '3.0.0.0') {
					$this->model_setting_module->editModule($this->request->get['module_id'], $this->request->post);
				} else {
					$this->model_extension_module->editModule($this->request->get['module_id'], $this->request->post);
				}
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link($this->extension_path, $this->module_token .'=' . $this->ci_token . '&type=module', true));
		}

		$data['heading_title'] = $this->language->get('heading_title_page');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_product'] = $this->language->get('entry_product');
		$data['entry_limit'] = $this->language->get('entry_limit');
		$data['entry_width'] = $this->language->get('entry_width');
		$data['entry_height'] = $this->language->get('entry_height');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_form'] = $this->language->get('entry_form');

		$data['help_product'] = $this->language->get('help_product');

		$data['button_setting'] = $this->language->get('button_setting');
		$data['button_page_form'] = $this->language->get('button_page_form');
		$data['button_page_request'] = $this->language->get('button_page_request');
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', $this->module_token .'=' . $this->ci_token, true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link($this->extension_path, $this->module_token .'=' . $this->ci_token . '&type=module', true)
		);

		if (!isset($this->request->get['module_id'])) {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title_page'),
				'href' => $this->url->link('extension/module/ciformbuilder', $this->module_token .'=' . $this->ci_token, true)
			);
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title_page'),
				'href' => $this->url->link('extension/module/ciformbuilder', $this->module_token .'=' . $this->ci_token . '&module_id=' . $this->request->get['module_id'], true)
			);
		}

		if (!isset($this->request->get['module_id'])) {
			$data['action'] = $this->url->link('extension/module/ciformbuilder', $this->module_token .'=' . $this->ci_token, true);
		} else {
			$data['action'] = $this->url->link('extension/module/ciformbuilder', $this->module_token .'=' . $this->ci_token . '&module_id=' . $this->request->get['module_id'], true);
		}

		$data['cancel'] = $this->url->link($this->extension_path, $this->module_token .'=' . $this->ci_token . '&type=module', true);

		$data['page_form_href'] = str_replace('&amp;', '&', $this->url->link('extension/ciformbuilder/page_form', $this->module_token .'=' . $this->ci_token, true));

		$data['page_request_href'] = str_replace('&amp;', '&', $this->url->link('extension/ciformbuilder/page_request', $this->module_token .'=' . $this->ci_token, true));

		if($this->config->has('module_ciformbuilder_setting_status')) {
			$data['setting'] = str_replace('&amp;', '&', $this->url->link('extension/module/ciformbuilder_setting', $this->module_token .'=' . $this->ci_token, true));
		} else {
			$data['setting'] = str_replace('&amp;', '&', $this->url->link($this->extension_path, $this->module_token .'=' . $this->ci_token .'&type=module', true));
		}

		if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			if(VERSION >= '3.0.0.0') {
				$module_info = $this->model_setting_module->getModule($this->request->get['module_id']);
			} else {
				$module_info = $this->model_extension_module->getModule($this->request->get['module_id']);
			}
		}

		$data[$this->ci_token] = $this->session->data[$this->module_token];

		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($module_info)) {
			$data['name'] = $module_info['name'];
		} else {
			$data['name'] = '';
		}

		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($module_info)) {
			$data['status'] = $module_info['status'];
		} else {
			$data['status'] = '';
		}

		if (isset($this->request->post['page_form_id'])) {
			$data['page_form_id'] = $this->request->post['page_form_id'];
		} elseif (!empty($module_info)) {
			$data['page_form_id'] = $module_info['page_form_id'];
		} else {
			$data['page_form_id'] = '';
		}

		$this->load->model('extension/ciformbuilder/page_form');
		$data['pageforms'] = $this->model_extension_ciformbuilder_page_form->getPageForms();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		if(VERSION <= '2.3.0.2') {
			$this->response->setOutput($this->load->view('extension/module/ciformbuilder.tpl', $data));
		} else {
			$file_variable = 'template_engine';
			$file_type = 'template';
			$this->config->set($file_variable, $file_type);
			$this->response->setOutput($this->load->view('extension/module/ciformbuilder', $data));
		}
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/ciformbuilder')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ($this->request->post['status']) {
			if (!$this->config->get('module_ciformbuilder_setting_status')) {
				$this->error['warning'] = $this->language->get('error_main_setting');
			}
		}

		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}

		if (empty($this->request->post['page_form_id'])) {
			$this->error['warning'] = $this->language->get('error_form');
		}

		return !$this->error;
	}
}