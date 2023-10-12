<?php
class ControllerExtensionModuleFacebookLogin extends Controller {

	private $error = array();

	public function index() {
		$this->load->language('extension/module/facebook_login');

		$this->load->model('setting/setting');
		$this->load->model('design/layout');

		$this->document->setTitle($this->language->get('heading_title'));

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('module_facebook_login', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true),
			'separator' => false
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true),
			'separator' => ' :: '
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('bc_heading_title'),
			'href' => $this->url->link('extension/module/facebook_login', 'user_token=' . $this->session->data['user_token'], true),
			'separator' => ' :: '
		);

		$data['action'] = $this->url->link('extension/module/facebook_login', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

		$data['user_token'] = $this->session->data['user_token'];


		
		if (isset($this->request->post['module_facebook_login_facebook_app_id'])) {
			$data['module_facebook_login_facebook_app_id'] = $this->request->post['module_facebook_login_facebook_app_id'];
		} elseif ($this->config->get('module_facebook_login_facebook_app_id')) {
			$data['module_facebook_login_facebook_app_id'] = $this->config->get('module_facebook_login_facebook_app_id');
		} else {
			$data['module_facebook_login_facebook_app_id'] = '';
		}

		if (isset($this->request->post['module_facebook_login_facebook_secret_key'])) {
			$data['module_facebook_login_facebook_secret_key'] = $this->request->post['module_facebook_login_facebook_secret_key'];
		} elseif ($this->config->get('module_facebook_login_facebook_secret_key')) {
			$data['module_facebook_login_facebook_secret_key'] = $this->config->get('module_facebook_login_facebook_secret_key');
		} else {
			$data['module_facebook_login_facebook_secret_key'] = '';
		}

        if (isset($this->request->post['module_facebook_login_status'])) {
			$data['module_facebook_login_status'] = $this->request->post['module_facebook_login_status'];
		} else {
			$data['module_facebook_login_status'] = $this->config->get('module_facebook_login_status');
		}

        if (isset($this->error['error_facebook_app_id'])) {
            $data['error']['app_id'] = $this->error['error_facebook_app_id'];
        } else {
            $data['error']['app_id'] = '';
        }

        if (isset($this->error['error_facebook_secret_key'])) {
            $data['error']['secret_key'] = $this->error['error_facebook_secret_key'];
        } else {
            $data['error']['secret_key'] = '';
        }

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/facebook_login', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/facebook_login')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
        if (isset($this->request->post['module_facebook_login_status']) && $this->request->post['module_facebook_login_status'] == 1) {
            if (isset($this->request->post['module_facebook_login_facebook_app_id']) && empty($this->request->post['module_facebook_login_facebook_app_id']) ) {
                $this->error['error_facebook_app_id'] = $this->language->get('error_facebook_app_id');
            }
            if (isset($this->request->post['module_facebook_login_facebook_secret_key']) && empty($this->request->post['module_facebook_login_facebook_secret_key']) ) {
                $this->error['error_facebook_secret_key'] = $this->language->get('error_facebook_secret_key');
            }

        }
		return !$this->error;
	}

	
}