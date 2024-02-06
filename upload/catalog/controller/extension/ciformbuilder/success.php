<?php
class ControllerExtensionCiFormbuilderSuccess extends Controller {
	public function index() {
		$this->load->language('extension/ciformbuilder/success');

		$this->load->model('extension/ciformbuilder/form');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		if (isset($this->request->get['id'])) {
			$page_form_id = (int)$this->request->get['id'];
		} else {
			$page_form_id = 0;
		}

		$page_form_info = $this->model_extension_ciformbuilder_form->getPageForm($page_form_id);

		if ($page_form_info) {
			$page_title = ($page_form_info['title']) ? $page_form_info['title'] : '';
			$heading_title = ($page_form_info['success_title']) ? $page_form_info['success_title'] : $this->language->get('text_success');

			$this->document->setTitle($heading_title);

			$data['breadcrumbs'][] = array(
				'text' => $page_title,
				'href' => $this->url->link('extension/ciformbuilder/form', 'page_form_id=' .  $page_form_id)
			);

			$data['breadcrumbs'][] = array(
				'text' => $heading_title,
				'href' => $this->url->link('extension/ciformbuilder/success')
			);

			$data['page_form_id'] = $page_form_info['page_form_id'];

			$data['heading_title'] = $heading_title;

			$data['text_message'] = html_entity_decode($page_form_info['success_description'], ENT_QUOTES, 'UTF-8');


			$data['button_continue'] = $this->language->get('button_continue');

			$data['continue'] = $this->url->link('common/home', '', true);

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			if(VERSION < '2.2.0.0') {
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extension/ciformbuilder/page_oc2/success.tpl')) {
					$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/extension/ciformbuilder/page_oc2/success.tpl', $data));
				} else {
					$this->response->setOutput($this->load->view('default/template/extension/ciformbuilder/page_oc2/success.tpl', $data));
				}
			} else if(VERSION <= '2.3.0.2') {
				$this->response->setOutput($this->load->view('extension/ciformbuilder/page_oc2/success', $data));
			} else{
				$this->response->setOutput($this->load->view('extension/ciformbuilder/page_oc3/success', $data));
			}
		} else {
			$this->response->redirect($this->url->link('common/home', '', true));
		}
	}
}