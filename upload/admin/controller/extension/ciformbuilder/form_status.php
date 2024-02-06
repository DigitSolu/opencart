<?php
class ControllerExtensionCiformbuilderFormStatus extends Controller {
	private $error = array();
	private $module_token = '';
	private $ci_token = '';

	public function __construct($registry) {
		parent :: __construct($registry);
		if(VERSION <= '2.3.0.2') {
			$this->module_token = 'token';
			$this->ci_token = $this->session->data['token'];
		} else {
			$this->module_token = 'user_token';
			$this->ci_token = $this->session->data['user_token'];
		}

		$this->load->model('extension/ciformbuilder/setting');
	}

	public function index() {
		$this->load->language('extension/ciformbuilder/form_status');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/ciformbuilder/form_status');
		
		$this->getList();
	}

	public function add() {
		$this->load->language('extension/ciformbuilder/form_status');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/ciformbuilder/form_status');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_extension_ciformbuilder_form_status->addFormStatus($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('extension/ciformbuilder/form_status', $this->module_token .'=' . $this->ci_token . $url, true));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('extension/ciformbuilder/form_status');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/ciformbuilder/form_status');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_extension_ciformbuilder_form_status->editFormStatus($this->request->get['form_status_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('extension/ciformbuilder/form_status', $this->module_token .'=' . $this->ci_token . $url, true));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('extension/ciformbuilder/form_status');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/ciformbuilder/form_status');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $form_status_id) {
				$this->model_extension_ciformbuilder_form_status->deleteFormStatus($form_status_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('extension/ciformbuilder/form_status', $this->module_token .'=' . $this->ci_token . $url, true));
		}

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'name';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', $this->module_token .'=' . $this->ci_token, true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/ciformbuilder/form_status', $this->module_token .'=' . $this->ci_token . $url, true)
		);

		$data['add'] = $this->url->link('extension/ciformbuilder/form_status/add', $this->module_token .'=' . $this->ci_token . $url, true);
		$data['delete'] = $this->url->link('extension/ciformbuilder/form_status/delete', $this->module_token .'=' . $this->ci_token . $url, true);

		$data['form_statuses'] = array();

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$form_status_total = $this->model_extension_ciformbuilder_form_status->getTotalFormStatuses();

		$results = $this->model_extension_ciformbuilder_form_status->getFormStatuses($filter_data);

		foreach ($results as $result) {
			$data['form_statuses'][] = array(
				'form_status_id' => $result['form_status_id'],
				'shortcode' => $result['shortcode'],
				'sort_order' => $result['sort_order'],
				'status'     => $result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
				'name'            => $result['name'] . (($result['form_status_id'] == $this->config->get('config_form_status_id')) ? $this->language->get('text_default') : null),
				'edit'            => $this->url->link('extension/ciformbuilder/form_status/edit', $this->module_token .'=' . $this->ci_token . '&form_status_id=' . $result['form_status_id'] . $url, true)
			);
		}

		$data['buttons'] = $this->model_extension_ciformbuilder_setting->getButtons('ciformbuilder');

		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_list'] = $this->language->get('text_list');
		$data['column_name'] = $this->language->get('column_name');
		$data['column_shortcode'] = $this->language->get('column_shortcode');
		$data['column_action'] = $this->language->get('column_action');

		$data['text_no_results'] = $this->language->get('text_no_results');

		$data['button_add'] = $this->language->get('button_add');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['button_delete'] = $this->language->get('button_delete');

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

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_name'] = $this->url->link('extension/ciformbuilder/form_status', $this->module_token .'=' . $this->ci_token . '&sort=name' . $url, true);
		$data['sort_status'] = $this->url->link('extension/ciformbuilder/form_status', $this->module_token .'=' . $this->ci_token . '&sort=p.status' . $url, true);
		$data['sort_sort_order'] = $this->url->link('extension/ciformbuilder/form_status', $this->module_token .'=' . $this->ci_token . '&sort=p.sort_order' . $url, true);
		$data['sort_shortcode'] = $this->url->link('extension/ciformbuilder/form_status', $this->module_token .'=' . $this->ci_token . '&sort=p.shortcode' . $url, true);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $form_status_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('extension/ciformbuilder/form_status', $this->module_token .'=' . $this->ci_token . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($form_status_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($form_status_total - $this->config->get('config_limit_admin'))) ? $form_status_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $form_status_total, ceil($form_status_total / $this->config->get('config_limit_admin'))); if($this->model_extension_ciformbuilder_setting->getButtons('ciformbuilder')) { $this->response->redirect( $this->url->link('extension/ciformbuilder/about', $this->module_token .'=' . $this->ci_token, true)); }

		$data['sort'] = $sort;
		$data['order'] = $order;
		$data['column_status'] = $this->language->get('column_status');
		$data['column_sort_order'] = $this->language->get('column_sort_order');

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$data['ci_token'] = $this->ci_token;

		$data['module_token'] = $this->module_token;

		if(VERSION <= '2.3.0.2') {
			$this->response->setOutput($this->load->view('extension/ciformbuilder/form_status_list.tpl', $data));
		} else {
			$file_variable = 'template_engine';
			$file_type = 'template';
			$this->config->set($file_variable, $file_type);
			$this->response->setOutput($this->load->view('extension/ciformbuilder/form_status_list', $data));
		}
	}

	protected function getForm() {
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_form'] = !isset($this->request->get['form_status_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		$data['entry_shortcode'] = $this->language->get('entry_shortcode');
		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['entry_status'] = $this->language->get('entry_status');


		$data['entry_bgcolor'] = $this->language->get('entry_bgcolor');
		$data['entry_textcolor'] = $this->language->get('entry_textcolor');

		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$this->document->addStyle('view/javascript/jquery/ciformbuilder/colorpicker/css/colorpicker.css');
		$this->document->addScript('view/javascript/jquery/ciformbuilder/colorpicker/js/colorpicker.js');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = array();
		}
		
		if (isset($this->error['shortcode'])) {
			$data['error_shortcode'] = $this->error['shortcode'];
		} else {
			$data['error_shortcode'] = '';
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', $this->module_token .'=' . $this->ci_token, true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/ciformbuilder/form_status', $this->module_token .'=' . $this->ci_token . $url, true)
		);

		if (!isset($this->request->get['form_status_id'])) {
			$data['action'] = $this->url->link('extension/ciformbuilder/form_status/add', $this->module_token .'=' . $this->ci_token . $url, true);
		} else {
			$data['action'] = $this->url->link('extension/ciformbuilder/form_status/edit', $this->module_token .'=' . $this->ci_token . '&form_status_id=' . $this->request->get['form_status_id'] . $url, true);
		}

		$data['cancel'] = $this->url->link('extension/ciformbuilder/form_status', $this->module_token .'=' . $this->ci_token . $url, true);

		if($this->model_extension_ciformbuilder_setting->getButtons('ciformbuilder')) {
			$this->response->redirect($this->url->link('extension/ciformbuilder/about', $this->module_token .'=' . $this->ci_token, true));
		}

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();
		
		if (isset($this->request->get['form_status_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$status_info = $this->model_extension_ciformbuilder_form_status->getFormStatus($this->request->get['form_status_id']);
		}

		if (isset($this->request->post['form_status'])) {
			$data['form_status'] = $this->request->post['form_status'];
		} elseif (isset($this->request->get['form_status_id'])) {
			$data['form_status'] = $this->model_extension_ciformbuilder_form_status->getFormStatusDescriptions($this->request->get['form_status_id']);
		} else {
			$data['form_status'] = array();
		}
		
		if (isset($this->request->post['shortcode'])) {
			$data['shortcode'] = $this->request->post['shortcode'];
		} elseif (!empty($status_info)) {
			$data['shortcode'] = $status_info['shortcode'];
		} else {
			$data['shortcode'] = '';
		}
		
		if (isset($this->request->post['sort_order'])) {
			$data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($status_info)) {
			$data['sort_order'] = $status_info['sort_order'];
		} else {
			$data['sort_order'] = '';
		}
		
		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($status_info)) {
			$data['status'] = $status_info['status'];
		} else {
			$data['status'] = 1;
		}

		if (isset($this->request->post['bgcolor'])) {
			$data['bgcolor'] = $this->request->post['bgcolor'];
		} elseif (!empty($status_info)) {
			$data['bgcolor'] = $status_info['bgcolor'];
		} else {
			$data['bgcolor'] = '';
		}

		if (isset($this->request->post['textcolor'])) {
			$data['textcolor'] = $this->request->post['textcolor'];
		} elseif (!empty($status_info)) {
			$data['textcolor'] = $status_info['textcolor'];
		} else {
			$data['textcolor'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$data['ci_token'] = $this->ci_token;

		$data['module_token'] = $this->module_token;

		if(VERSION <= '2.3.0.2') {
			$this->response->setOutput($this->load->view('extension/ciformbuilder/form_status_form.tpl', $data));
		} else {
			$file_variable = 'template_engine';
			$file_type = 'template';
			$this->config->set($file_variable, $file_type);
			$this->response->setOutput($this->load->view('extension/ciformbuilder/form_status_form', $data));
		}
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'extension/ciformbuilder/form_status')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['form_status'] as $language_id => $value) {
			if ((utf8_strlen($value['name']) < 3) || (utf8_strlen($value['name']) > 32)) {
				$this->error['name'][$language_id] = $this->language->get('error_name');
			}
		}

		if ((utf8_strlen($this->request->post['shortcode']) < 3) || (utf8_strlen($this->request->post['shortcode']) > 32)) {
			$this->error['shortcode'] = $this->language->get('error_shortcode');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'extension/ciformbuilder/form_status')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}
