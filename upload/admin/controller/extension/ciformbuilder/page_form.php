<?php
class ControllerExtensionCiformbuilderPageForm extends Controller {
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

	public function test() {
		phpinfo();
	}

	public function index() {
		$this->load->language('extension/ciformbuilder/page_form');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/ciformbuilder/page_form');

		$this->getList();
	}

	public function add() {
		$this->load->language('extension/ciformbuilder/page_form');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/ciformbuilder/page_form');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_extension_ciformbuilder_page_form->addPageForm($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_page_form_id'])) {
				$url .= '&filter_page_form_id=' . urlencode(html_entity_decode($this->request->get['filter_page_form_id'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_page_form_title'])) {
				$url .= '&filter_page_form_title=' . urlencode(html_entity_decode($this->request->get['filter_page_form_title'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . urlencode(html_entity_decode($this->request->get['filter_status'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('extension/ciformbuilder/page_form', $this->module_token .'=' . $this->ci_token . $url, true));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('extension/ciformbuilder/page_form');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/ciformbuilder/page_form');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_extension_ciformbuilder_page_form->editPageForm($this->request->get['page_form_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_page_form_id'])) {
				$url .= '&filter_page_form_id=' . urlencode(html_entity_decode($this->request->get['filter_page_form_id'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_page_form_title'])) {
				$url .= '&filter_page_form_title=' . urlencode(html_entity_decode($this->request->get['filter_page_form_title'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . urlencode(html_entity_decode($this->request->get['filter_status'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('extension/ciformbuilder/page_form', $this->module_token .'=' . $this->ci_token . $url, true));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('extension/ciformbuilder/page_form');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/ciformbuilder/page_form');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $page_form_id) {
				$this->model_extension_ciformbuilder_page_form->deletePageForm($page_form_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_page_form_id'])) {
				$url .= '&filter_page_form_id=' . urlencode(html_entity_decode($this->request->get['filter_page_form_id'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_page_form_title'])) {
				$url .= '&filter_page_form_title=' . urlencode(html_entity_decode($this->request->get['filter_page_form_title'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . urlencode(html_entity_decode($this->request->get['filter_status'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('extension/ciformbuilder/page_form', $this->module_token .'=' . $this->ci_token . $url, true));
		}

		$this->getList();
	}

	protected function getList() {
		$this->document->addStyle('view/stylesheet/formbuilder/formbuilder.css');

		if (isset($this->request->get['filter_page_form_id'])) {
			$filter_page_form_id = $this->request->get['filter_page_form_id'];
		} else {
			$filter_page_form_id = '';
		}

		if (isset($this->request->get['filter_page_form_title'])) {
			$filter_page_form_title = $this->request->get['filter_page_form_title'];
		} else {
			$filter_page_form_title = '';
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'p.sort_order,pd.title';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_page_form_id'])) {
			$url .= '&filter_page_form_id=' . urlencode(html_entity_decode($this->request->get['filter_page_form_id'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_page_form_title'])) {
			$url .= '&filter_page_form_title=' . urlencode(html_entity_decode($this->request->get['filter_page_form_title'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . urlencode(html_entity_decode($this->request->get['filter_status'], ENT_QUOTES, 'UTF-8'));
		}

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
			'href' => $this->url->link('extension/ciformbuilder/page_form', $this->module_token .'=' . $this->ci_token . $url, true)
		);

		$data['add'] = $this->url->link('extension/ciformbuilder/page_form/add', $this->module_token .'=' . $this->ci_token . $url, true);
		$data['delete'] = $this->url->link('extension/ciformbuilder/page_form/delete', $this->module_token .'=' . $this->ci_token . $url, true);
		$data['copy'] = $this->url->link('extension/ciformbuilder/page_form/copy', $this->module_token .'=' . $this->ci_token . $url, true);

		$data['buttons'] = $this->model_extension_ciformbuilder_setting->getButtons('ciformbuilder');

		$data['page_forms'] = array();

		$filter_data = array(
			'filter_page_form_id'  		=> $filter_page_form_id,
			'filter_title'  			=> $filter_page_form_title,
			'filter_status'  			=> $filter_status,
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$page_form_total = $this->model_extension_ciformbuilder_page_form->getTotalPageForms();

		$results = $this->model_extension_ciformbuilder_page_form->getPageForms($filter_data);

		$data['column_link'] = $this->language->get('column_link');

		$catalog_url = $this->request->server['HTTPS'] ? HTTPS_CATALOG : HTTP_CATALOG;

		$this->load->model('extension/ciformbuilder/form_status');

		foreach ($results as $result) {
			$data['page_forms'][] = array(
				'page_form_id'  => $result['page_form_id'],
				'title' 		=> $result['title'],
				'link' 			=> $catalog_url .'index.php?route=extension/ciformbuilder/form&page_form_id='. $result['page_form_id'],
				'status_class' 		=> ($result['status']) ? 'label-success' : 'label-danger',
				'status' 		=> ($result['status']) ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
				'sort_order' 	=> $result['sort_order'],
				'setupstatustotal' => $this->model_extension_ciformbuilder_form_status->getTotalEnabledPageFormStatuses($result['page_form_id']),
				'edit'       	=> $this->url->link('extension/ciformbuilder/page_form/edit', $this->module_token .'=' . $this->ci_token . '&page_form_id=' . $result['page_form_id'] . $url, true),
				'addstatus'       	=> $this->url->link('extension/ciformbuilder/page_form/addStatus', $this->module_token .'=' . $this->ci_token . '&page_form_id=' . $result['page_form_id'] . $url, true)
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['column_id'] = $this->language->get('column_id');
		$data['column_title'] = $this->language->get('column_title');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_sort_order'] = $this->language->get('column_sort_order');
		$data['column_action'] = $this->language->get('column_action');


		$data['entry_page_form_id'] = $this->language->get('entry_page_form_id');
		$data['entry_page_form_title'] = $this->language->get('entry_page_form_title');
		$data['entry_status'] = $this->language->get('entry_status');

		$data['alert_event'] = $this->language->get('alert_event');

		$data['button_code'] = $this->language->get('button_code');
		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_view_form'] = $this->language->get('button_view_form');
		$data['button_copy'] = $this->language->get('button_copy');
		$data['button_filter'] = $this->language->get('button_filter');
		$data['button_status'] = $this->language->get('button_status');

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

		$data['buttons'] ? $this->response->redirect($this->url->link('extension/ciformbuilder/about', $this->module_token .'=' . $this->ci_token, true)) : '';

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_id'] = $this->url->link('extension/ciformbuilder/page_form', $this->module_token .'=' . $this->ci_token . '&sort=p.page_form_id' . $url, true);
		$data['sort_title'] = $this->url->link('extension/ciformbuilder/page_form', $this->module_token .'=' . $this->ci_token . '&sort=pd.title' . $url, true);
		$data['sort_sort_order'] = $this->url->link('extension/ciformbuilder/page_form', $this->module_token .'=' . $this->ci_token . '&sort=p.sort_order' . $url, true);
		$data['sort_status'] = $this->url->link('extension/ciformbuilder/page_form', $this->module_token .'=' . $this->ci_token . '&sort=p.status' . $url, true);

		$url = '';

		if (isset($this->request->get['filter_page_form_id'])) {
			$url .= '&filter_page_form_id=' . urlencode(html_entity_decode($this->request->get['filter_page_form_id'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_page_form_title'])) {
			$url .= '&filter_page_form_title=' . urlencode(html_entity_decode($this->request->get['filter_page_form_title'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . urlencode(html_entity_decode($this->request->get['filter_status'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $page_form_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('extension/ciformbuilder/page_form', $this->module_token .'=' . $this->ci_token . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($page_form_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($page_form_total - $this->config->get('config_limit_admin'))) ? $page_form_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $page_form_total, ceil($page_form_total / $this->config->get('config_limit_admin')));

		$data['filter_page_form_id'] = $filter_page_form_id;
		$data['filter_page_form_title'] = $filter_page_form_title;
		$data['filter_status'] = $filter_status;
		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['config_submission_status'] = $this->config->get('module_ciformbuilder_setting_submission_status');

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$data['ci_token'] = $this->ci_token;
		$data['module_token'] = $this->module_token;

		if(VERSION <= '2.3.0.2') {
			$this->response->setOutput($this->load->view('extension/ciformbuilder/page_list.tpl', $data));
		} else {
			$file_variable = 'template_engine';
			$file_type = 'template';
			$this->config->set($file_variable, $file_type);
			$this->response->setOutput($this->load->view('extension/ciformbuilder/page_list', $data));
		}
	}

	protected function getForm() {
		$this->document->addStyle('view/stylesheet/formbuilder/formbuilder.css');

		// Color Picker
		$this->document->addStyle('view/javascript/jquery/ciformbuilder/colorpicker/css/colorpicker.css');

		$this->document->addScript('view/javascript/jquery/ciformbuilder/colorpicker/js/colorpicker.js');

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_form'] = !isset($this->request->get['page_form_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		$data['text_please_select'] = $this->language->get('text_please_select');
		$data['text_choose'] = $this->language->get('text_choose');
		$data['text_select'] = $this->language->get('text_select');
		$data['text_radio'] = $this->language->get('text_radio');
		$data['text_checkbox'] = $this->language->get('text_checkbox');
		$data['text_input'] = $this->language->get('text_input');
		$data['text_text'] = $this->language->get('text_text');
		$data['text_textarea'] = $this->language->get('text_textarea');
		$data['text_email'] = $this->language->get('text_email');
		$data['text_password'] = $this->language->get('text_password');
		$data['text_confirm_password'] = $this->language->get('text_confirm_password');
		$data['text_file'] = $this->language->get('text_file');
		$data['text_date'] = $this->language->get('text_date');
		$data['text_datetime'] = $this->language->get('text_datetime');
		$data['text_time'] = $this->language->get('text_time');
		$data['text_telephone'] = $this->language->get('text_telephone');
		$data['text_firstname'] = $this->language->get('text_firstname');
		$data['text_lastname'] = $this->language->get('text_lastname');
		$data['text_country'] = $this->language->get('text_country');
		$data['text_zone'] = $this->language->get('text_zone');
		$data['text_localisation'] = $this->language->get('text_localisation');
		$data['alert_admin_email'] = $this->language->get('alert_admin_email');
		$data['text_postcode'] = $this->language->get('text_postcode');
		$data['text_address'] = $this->language->get('text_address');
		$data['text_number'] = $this->language->get('text_number');
		$data['text_email_exists'] = $this->language->get('text_email_exists');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_default'] = $this->language->get('text_default');
		$data['text_lang_setting'] = $this->language->get('text_lang_setting');
		$data['text_type_setting'] = $this->language->get('text_type_setting');
		$data['text_image_setting'] = $this->language->get('text_image_setting');
		$data['text_required_setting'] = $this->language->get('text_required_setting');
		$data['text_dynamic_setting'] = $this->language->get('text_dynamic_setting');
		$data['text_value_setting'] = $this->language->get('text_value_setting');
		$data['text_show'] = $this->language->get('text_show');
		$data['text_hide'] = $this->language->get('text_hide');
		$data['text_radio_toggle'] = $this->language->get('text_radio_toggle');
		$data['text_checkbox_switch'] = $this->language->get('text_checkbox_switch');
		$data['text_checkbox_toggle'] = $this->language->get('text_checkbox_toggle');
		$data['text_multi_select'] = $this->language->get('text_multi_select');
		$data['text_color_picker'] = $this->language->get('text_color_picker');
		$data['text_google_map'] = $this->language->get('text_google_map');
		$data['text_small'] = $this->language->get('text_small');
		$data['text_medium'] = $this->language->get('text_medium');
		$data['text_large'] = $this->language->get('text_large');
		$data['text_keyword'] = $this->language->get('text_keyword');

		$data['valid_field_type'] = $this->language->get('valid_field_type');
		$data['valid_field_info'] = $this->language->get('valid_field_info');
		$data['valid_select_type'] = $this->language->get('valid_select_type');
		$data['valid_input_type'] = $this->language->get('valid_input_type');
		$data['valid_file_type'] = $this->language->get('valid_file_type');
		$data['valid_date_type'] = $this->language->get('valid_date_type');
		$data['valid_localisation_type'] = $this->language->get('valid_localisation_type');

		$data['text_select_value'] = $this->language->get('text_select_value');
		$data['text_radio_value'] = $this->language->get('text_radio_value');
		$data['text_checkbox_value'] = $this->language->get('text_checkbox_value');
		$data['text_text_value'] = $this->language->get('text_text_value');
		$data['text_multiple_text'] = $this->language->get('text_multiple_text');
		$data['text_textarea_value'] = $this->language->get('text_textarea_value');
		$data['text_number_value'] = $this->language->get('text_number_value');
		$data['text_telephone_value'] = $this->language->get('text_telephone_value');
		$data['text_firstname_value'] = $this->language->get('text_firstname_value');
		$data['text_lastname_value'] = $this->language->get('text_lastname_value');
		$data['text_email_value'] = $this->language->get('text_email_value');
		$data['text_email_exists_value'] = $this->language->get('text_email_exists_value');
		$data['text_password_value'] = $this->language->get('text_password_value');
		$data['text_password_value'] = $this->language->get('text_password_value');
		$data['text_confirm_value'] = $this->language->get('text_confirm_value');
		$data['text_file_value'] = $this->language->get('text_file_value');
		$data['valid_date_type'] = $this->language->get('valid_date_type');
		$data['text_date_value'] = $this->language->get('text_date_value');
		$data['text_time_value'] = $this->language->get('text_time_value');
		$data['text_datetime_value'] = $this->language->get('text_datetime_value');
		$data['text_country_value'] = $this->language->get('text_country_value');
		$data['text_zone_value'] = $this->language->get('text_zone_value');
		$data['text_postcode_value'] = $this->language->get('text_postcode_value');
		$data['text_address_value'] = $this->language->get('text_address_value');
		$data['text_form_attributes'] = $this->language->get('text_form_attributes');
		$data['text_header_type'] = $this->language->get('text_header_type');
		$data['text_header'] = $this->language->get('text_header');
		$data['text_paragraph'] = $this->language->get('text_paragraph');
		$data['text_hrline'] = $this->language->get('text_hrline');
		$data['text_no_product'] = $this->language->get('text_no_product');
		$data['text_all_product'] = $this->language->get('text_all_product');
		$data['text_choose_product'] = $this->language->get('text_choose_product');
		$data['text_display_message'] = $this->language->get('text_display_message');
		$data['text_left'] = $this->language->get('text_left');
		$data['text_center'] = $this->language->get('text_center');
		$data['text_right'] = $this->language->get('text_right');
		$data['text_image'] = $this->language->get('text_image');
		$data['text_icon'] = $this->language->get('text_icon');

		$data['entry_thumb_type'] = $this->language->get('entry_thumb_type');
		$data['entry_icon_class'] = $this->language->get('entry_icon_class');
		$data['entry_icon_size'] = $this->language->get('entry_icon_size');
		$data['entry_label_display'] = $this->language->get('entry_label_display');
		$data['entry_field_status'] = $this->language->get('entry_field_status');
		$data['entry_label_align'] = $this->language->get('entry_label_align');
		$data['entry_image_align'] = $this->language->get('entry_image_align');
		$data['entry_number_input'] = $this->language->get('entry_number_input');
		$data['entry_title'] = $this->language->get('entry_title');
		$data['entry_keyword'] = $this->language->get('entry_keyword');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['entry_logo'] = $this->language->get('entry_logo');
		$data['entry_show_guest'] = $this->language->get('entry_show_guest');
		$data['entry_captcha'] = $this->language->get('entry_captcha');
		$data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$data['entry_store'] = $this->language->get('entry_store');
		$data['entry_information'] = $this->language->get('entry_information');
		$data['entry_description'] = $this->language->get('entry_description');
		$data['entry_bottom_description'] = $this->language->get('entry_bottom_description');
		$data['entry_meta_title'] = $this->language->get('entry_meta_title');
		$data['entry_meta_description'] = $this->language->get('entry_meta_description');
		$data['entry_meta_keyword'] = $this->language->get('entry_meta_keyword');
		$data['entry_customer_email_status'] = $this->language->get('entry_customer_email_status');
		$data['entry_field_attachment'] = $this->language->get('entry_field_attachment');
		$data['entry_customer_subject'] = $this->language->get('entry_customer_subject');
		$data['entry_customer_message'] = $this->language->get('entry_customer_message');
		$data['entry_admin_email_status'] = $this->language->get('entry_admin_email_status');
		$data['entry_admin_email'] = $this->language->get('entry_admin_email');
		$data['entry_admin_subject'] = $this->language->get('entry_admin_subject');
		$data['entry_admin_message'] = $this->language->get('entry_admin_message');
		$data['entry_success_title'] = $this->language->get('entry_success_title');
		$data['entry_success_description'] = $this->language->get('entry_success_description');
		$data['entry_field_display_message'] = $this->language->get('entry_field_display_message');

		$data['entry_required'] = $this->language->get('entry_required');
		$data['entry_field_name'] = $this->language->get('entry_field_name');
		$data['entry_field_help'] = $this->language->get('entry_field_help');
		$data['entry_field_value'] = $this->language->get('entry_field_value');
		$data['entry_field_error'] = $this->language->get('entry_field_error');
		$data['entry_field_placeholder'] = $this->language->get('entry_field_placeholder');
		$data['entry_field_dvalue'] = $this->language->get('entry_field_dvalue');
		$data['entry_default_value'] = $this->language->get('entry_default_value');
		$data['entry_type'] = $this->language->get('entry_type');
		$data['entry_option_value'] = $this->language->get('entry_option_value');
		$data['entry_top'] = $this->language->get('entry_top');
		$data['entry_top'] = $this->language->get('entry_top');
		$data['entry_bottom'] = $this->language->get('entry_bottom');
		$data['entry_fieldset_title'] = $this->language->get('entry_fieldset_title');
		$data['entry_submit_button'] = $this->language->get('entry_submit_button');

		$data['entry_file_ext_allowed'] = $this->language->get('entry_file_ext_allowed');
		$data['entry_file_mime_allowed'] = $this->language->get('entry_file_mime_allowed');
		$data['entry_mail_alert_email'] = $this->language->get('entry_mail_alert_email');
		$data['entry_map'] = $this->language->get('entry_map');
		$data['entry_class'] = $this->language->get('entry_class');
		$data['entry_image'] = $this->language->get('entry_image');
		$data['entry_auto_fill_value'] = $this->language->get('entry_auto_fill_value');
		$data['entry_file_limit'] = $this->language->get('entry_file_limit');
		$data['entry_width'] = $this->language->get('entry_width');
		$data['entry_producttype'] = $this->language->get('entry_producttype');
		$data['entry_pbutton_title'] = $this->language->get('entry_pbutton_title');
		$data['entry_product'] = $this->language->get('entry_product');
		$data['entry_popup_size'] = $this->language->get('entry_popup_size');
		$data['entry_reset_button'] = $this->language->get('entry_reset_button');
		$data['entry_form_status'] = $this->language->get('entry_form_status');
		$data['entry_color'] = $this->language->get('entry_color');
		$data['entry_image_width'] = $this->language->get('entry_image_width');
		$data['entry_image_height'] = $this->language->get('entry_image_height');
		$data['entry_input_group_button_text'] = $this->language->get('entry_input_group_button_text');

		$data['entry_action'] = $this->language->get('entry_action');

		$data['const_names'] = $this->language->get('const_names');
		$data['const_short_codes'] = $this->language->get('const_short_codes');
		$data['const_logo'] = $this->language->get('const_logo');
		$data['const_store_name'] = $this->language->get('const_store_name');
		$data['const_store_link'] = $this->language->get('const_store_link');
		$data['const_product_id'] = $this->language->get('const_product_id');
		$data['const_product_name'] = $this->language->get('const_product_name');
		$data['const_product_link'] = $this->language->get('const_product_link');
		$data['const_name'] = $this->language->get('const_name');
		$data['const_product_model'] = $this->language->get('const_product_model');
		$data['const_product_image'] = $this->language->get('const_product_image');

		$data['help_multiple_text_placeholder'] = $this->language->get('help_multiple_text_placeholder');
		$data['help_mail_alert_email'] = $this->language->get('help_mail_alert_email');
		$data['help_product'] = $this->language->get('help_product');
		$data['help_field_name'] = $this->language->get('help_field_name');
		$data['help_field_help'] = $this->language->get('help_field_help');
		$data['help_field_value'] = $this->language->get('help_field_value');
		$data['help_field_error'] = $this->language->get('help_field_error');
		$data['help_field_placeholder'] = $this->language->get('help_field_placeholder');
		$data['help_field_dvalue'] = $this->language->get('help_field_dvalue');
		$data['help_required'] = $this->language->get('help_required');
		$data['help_sort_order'] = $this->language->get('help_sort_order');
		$data['help_type'] = $this->language->get('help_type');
		$data['help_top'] = $this->language->get('help_top');
		$data['help_bottom'] = $this->language->get('help_bottom');
		$data['help_keyword'] = $this->language->get('help_keyword');
		$data['help_file_mime_allowed'] = $this->language->get('help_file_mime_allowed');
		$data['help_file_ext_allowed'] = $this->language->get('help_file_ext_allowed');
		$data['help_width'] = $this->language->get('help_width');

		$data['help_fieldset_title'] = $this->language->get('help_fieldset_title');
		$data['help_submit_button'] = $this->language->get('help_submit_button');
		$data['help_customer_email_status'] = $this->language->get('help_customer_email_status');
		$data['help_field_map'] = $this->language->get('help_field_map');

		$data['tab_general'] = $this->language->get('tab_general');
		$data['tab_page'] = $this->language->get('tab_page');
		$data['tab_link'] = $this->language->get('tab_link');
		$data['tab_fields'] = $this->language->get('tab_fields');
		$data['tab_field'] = $this->language->get('tab_field');
		$data['tab_email'] = $this->language->get('tab_email');
		$data['tab_success_page'] = $this->language->get('tab_success_page');
		$data['tab_error_page'] = $this->language->get('tab_error_page');
		$data['tab_customer_email'] = $this->language->get('tab_customer_email');
		$data['tab_admin_email'] = $this->language->get('tab_admin_email');
		$data['tab_css'] = $this->language->get('tab_css');
		$data['tab_link_setting'] = $this->language->get('tab_link_setting');
		$data['tab_information'] = $this->language->get('tab_information');
		$data['tab_product'] = $this->language->get('tab_product');
		$data['tab_seo'] = $this->language->get('tab_seo');

		$data['leg_link'] = $this->language->get('leg_link');
		$data['leg_information'] = $this->language->get('leg_information');
		$data['leg_product'] = $this->language->get('leg_product');
		$data['leg_setting_info'] = $this->language->get('leg_setting_info');
		$data['leg_captcha'] = $this->language->get('leg_captcha');
		$data['leg_upload_info'] = $this->language->get('leg_upload_info');
		$data['leg_logo'] = $this->language->get('leg_logo');
		$data['leg_status_setting'] = $this->language->get('leg_status_setting');
		$data['leg_status_email'] = $this->language->get('leg_status_email');

		$data['button_valinfo'] = $this->language->get('button_valinfo');
		$data['button_add_field'] = $this->language->get('button_add_field');
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_remove'] = $this->language->get('button_remove');
		$data['button_option_value_add'] = $this->language->get('button_option_value_add');
		// Term Condition code start
		$data['legend_terms'] = $this->language->get('legend_terms');
		$data['entry_termcondition'] = $this->language->get('entry_termcondition');
		$data['entry_termcondition_info'] = $this->language->get('entry_termcondition_info');
		$data['entry_termcondition_text'] = $this->language->get('entry_termcondition_text');
		$data['text_none'] = $this->language->get('text_none');
		$data['help_termcondition_info'] = $this->language->get('help_termcondition_info');
		$data['help_termcondition_text'] = $this->language->get('help_termcondition_text');
		// Term Condition code end

		// Google Analytic code start
		$data['leg_google_analytic'] = $this->language->get('leg_google_analytic');
		$data['entry_google_analytic'] = $this->language->get('entry_google_analytic');
		$data['entry_google_analytic_status'] = $this->language->get('entry_google_analytic_status');
		// Google Analytic code end

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['title'])) {
			$data['error_title'] = $this->error['title'];
		} else {
			$data['error_title'] = '';
		}

		if (isset($this->error['meta_title'])) {
			$data['error_meta_title'] = $this->error['meta_title'];
		} else {
			$data['error_meta_title'] = '';
		}

		if (isset($this->error['success_title'])) {
			$data['error_success_title'] = $this->error['success_title'];
		} else {
			$data['error_success_title'] = '';
		}

		if (isset($this->error['customer_subject'])) {
			$data['error_customer_subject'] = $this->error['customer_subject'];
		} else {
			$data['error_customer_subject'] = '';
		}

		if (isset($this->error['customer_message'])) {
			$data['error_customer_message'] = $this->error['customer_message'];
		} else {
			$data['error_customer_message'] = '';
		}

		if (isset($this->error['admin_email'])) {
			$data['error_admin_email'] = $this->error['admin_email'];
		} else {
			$data['error_admin_email'] = '';
		}

		if (isset($this->error['admin_subject'])) {
			$data['error_admin_subject'] = $this->error['admin_subject'];
		} else {
			$data['error_admin_subject'] = '';
		}

		if (isset($this->error['admin_message'])) {
			$data['error_admin_message'] = $this->error['admin_message'];
		} else {
			$data['error_admin_message'] = '';
		}

		if (isset($this->error['field_name'])) {
			$data['error_field_name'] = $this->error['field_name'];
		} else {
			$data['error_field_name'] = array();
		}

		if (isset($this->error['value_name'])) {
			$data['error_value_name'] = $this->error['value_name'];
		} else {
			$data['error_value_name'] = array();
		}

		if (isset($this->error['keyword'])) {
			$data['error_keyword'] = $this->error['keyword'];
		} else {
			$data['error_keyword'] = '';
		}

		$url = '';

		if (isset($this->request->get['filter_page_form_id'])) {
			$url .= '&filter_page_form_id=' . urlencode(html_entity_decode($this->request->get['filter_page_form_id'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_page_form_title'])) {
			$url .= '&filter_page_form_title=' . urlencode(html_entity_decode($this->request->get['filter_page_form_title'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . urlencode(html_entity_decode($this->request->get['filter_status'], ENT_QUOTES, 'UTF-8'));
		}

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
			'href' => $this->url->link('extension/ciformbuilder/page_form', $this->module_token .'=' . $this->ci_token . $url, true)
		);

		if (!isset($this->request->get['page_form_id'])) {
			$data['action'] = $this->url->link('extension/ciformbuilder/page_form/add', $this->module_token .'=' . $this->ci_token . $url, true);
		} else {
			$data['action'] = $this->url->link('extension/ciformbuilder/page_form/edit', $this->module_token .'=' . $this->ci_token . '&page_form_id=' . $this->request->get['page_form_id'] . $url, true);
		}

		$data['cancel'] = $this->url->link('extension/ciformbuilder/page_form', $this->module_token .'=' . $this->ci_token . $url, true); $data['buttons'] = $this->model_extension_ciformbuilder_setting->getButtons('ciformbuilder');

		if (isset($this->request->get['page_form_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$page_form_info = $this->model_extension_ciformbuilder_page_form->getPageForm($this->request->get['page_form_id']);
		}

		$data['ci_token'] = $this->ci_token;
		$data['module_token'] = $this->module_token;

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();


		if (isset($this->request->post['top'])) {
			$data['top'] = $this->request->post['top'];
		} elseif (!empty($page_form_info)) {
			$data['top'] = $page_form_info['top'];
		} else {
			$data['top'] = '1';
		}

		if (isset($this->request->post['bottom'])) {
			$data['bottom'] = $this->request->post['bottom'];
		} elseif (!empty($page_form_info)) {
			$data['bottom'] = $page_form_info['bottom'];
		} else {
			$data['bottom'] = '1';
		}

		if(VERSION <= '2.3.0.2') {
			if (isset($this->request->post['keyword'])) {
				$data['keyword'] = $this->request->post['keyword'];
			} elseif (!empty($page_form_info)) {
				$data['keyword'] = $page_form_info['keyword'];
			} else {
				$data['keyword'] = '';
			}
		} else {
			if (isset($this->request->post['page_form_seo_url'])) {
				$data['page_form_seo_url'] = $this->request->post['page_form_seo_url'];
			} elseif (!empty($page_form_info)) {
				$data['page_form_seo_url'] = $this->model_extension_ciformbuilder_page_form->getPageFormSeoUrls($this->request->get['page_form_id']);
			} else {
				$data['page_form_seo_url'] = array();
			}
		}

		if (isset($this->request->post['sort_order'])) {
			$data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($page_form_info)) {
			$data['sort_order'] = $page_form_info['sort_order'];
		} else {
			$data['sort_order'] = '0';
		}

		// Logo
		if (isset($this->request->post['logo'])) {
			$data['logo'] = $this->request->post['logo'];
		} elseif (!empty($page_form_info)) {
			$data['logo'] = $page_form_info['logo'];
		} else {
			$data['logo'] = '';
		}

		$this->load->model('tool/image');

		if (isset($this->request->post['logo']) && is_file(DIR_IMAGE . $this->request->post['logo'])) {
			$data['thumb_logo'] = $this->model_tool_image->resize($this->request->post['logo'], 50, 50);
		} elseif (!empty($page_form_info) && is_file(DIR_IMAGE . $page_form_info['logo'])) {
			$data['thumb_logo'] = $this->model_tool_image->resize($page_form_info['logo'], 50, 50);
		} else {
			$data['thumb_logo'] = $this->model_tool_image->resize('no_image.png', 50, 50);
		}

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 50, 50);


		if (isset($this->request->post['css'])) {
			$data['css'] = $this->request->post['css'];
		} elseif (!empty($page_form_info)) {
			$data['css'] = $page_form_info['css'];
		} else {
			$data['css'] = '';
		}

		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($page_form_info)) {
			$data['status'] = $page_form_info['status'];
		} else {
			$data['status'] = '1';
		}

		if (isset($this->request->post['reset_button'])) {
			$data['reset_button'] = $this->request->post['reset_button'];
		} elseif (!empty($page_form_info)) {
			$data['reset_button'] = $page_form_info['reset_button'];
		} else {
			$data['reset_button'] = '1';
		}

		if (isset($this->request->post['popup_size'])) {
			$data['popup_size'] = $this->request->post['popup_size'];
		} elseif (!empty($page_form_info)) {
			$data['popup_size'] = $page_form_info['popup_size'];
		} else {
			$data['popup_size'] = 'medium';
		}


		if (isset($this->request->post['customer_email_status'])) {
			$data['customer_email_status'] = $this->request->post['customer_email_status'];
		} elseif (!empty($page_form_info)) {
			$data['customer_email_status'] = $page_form_info['customer_email_status'];
		} else {
			$data['customer_email_status'] = '';
		}

		if (isset($this->request->post['admin_email_status'])) {
			$data['admin_email_status'] = $this->request->post['admin_email_status'];
		} elseif (!empty($page_form_info)) {
			$data['admin_email_status'] = $page_form_info['admin_email_status'];
		} else {
			$data['admin_email_status'] = '';
		}

		if (isset($this->request->post['admin_email'])) {
			$data['admin_email'] = $this->request->post['admin_email'];
		} elseif (!empty($page_form_info)) {
			$data['admin_email'] = $page_form_info['admin_email'];
		} else {
			$data['admin_email'] = $this->config->get('config_email');
		}

		if (isset($this->request->post['show_guest'])) {
			$data['show_guest'] = $this->request->post['show_guest'];
		} elseif (!empty($page_form_info)) {
			$data['show_guest'] = $page_form_info['show_guest'];
		} else {
			$data['show_guest'] = '1';
		}

		if (isset($this->request->post['captcha'])) {
			$data['captcha'] = $this->request->post['captcha'];
		} elseif (!empty($page_form_info)) {
			$data['captcha'] = $page_form_info['captcha'];
		} else {
			$data['captcha'] = '';
		}

		if (isset($this->request->post['producttype'])) {
			$data['producttype'] = $this->request->post['producttype'];
		} else if (!empty($page_form_info)) {
			$data['producttype'] = $page_form_info['producttype'];
		} else {
			$data['producttype'] = 'no';
		}

		if (isset($this->request->post['page_form_product'])) {
			$page_form_products = $this->request->post['page_form_product'];
		} elseif (isset($this->request->get['page_form_id'])) {
			$page_form_products = $this->model_extension_ciformbuilder_page_form->getPageFormProducts($this->request->get['page_form_id']);
		} else {
			$page_form_products = array();
		}

		$data['page_form_products'] = array();
		$this->load->model('catalog/product');
		foreach ($page_form_products as $product_id) {
			$product_info = $this->model_catalog_product->getProduct($product_id);

			if ($product_info) {
				$data['page_form_products'][] = array(
					'product_id' 	=> $product_info['product_id'],
					'name'        	=> $product_info['name'],
				);
			}
		}


		$this->load->model('setting/store');

		$data['stores'] = array();
		$data['stores'][] = array(
			'store_id' => 0,
			'name'     => $this->language->get('text_default')
		);

		$stores = $this->model_setting_store->getStores(); $data['buttons'] ? $this->response->redirect($this->url->link('extension/ciformbuilder/about', $this->module_token .'=' . $this->ci_token, true)) : '';
		foreach ($stores as $store) {
			$data['stores'][] = array(
				'store_id' => $store['store_id'],
				'name'     => $store['name']
			);
		}


		if (isset($this->request->post['page_form_store'])) {
			$data['page_form_store'] = $this->request->post['page_form_store'];
		} elseif (isset($this->request->get['page_form_id'])) {
			$data['page_form_store'] = $this->model_extension_ciformbuilder_page_form->getPageFormStores($this->request->get['page_form_id']);
		} else {
			$data['page_form_store'] = array(0);
		}

		$this->load->model('catalog/information');
		$data['informations'] = $this->model_catalog_information->getInformations();

		if (isset($this->request->post['page_form_information'])) {
			$data['page_form_information'] = $this->request->post['page_form_information'];
		} elseif (isset($this->request->get['page_form_id'])) {
			$data['page_form_information'] = $this->model_extension_ciformbuilder_page_form->getPageFormInformations($this->request->get['page_form_id']);
		} else {
			$data['page_form_information'] = array(0);
		}

		if(VERSION > '2.0.3.1') {
			$this->load->model('customer/customer_group');
			$data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();
		} else{
			$this->load->model('sale/customer_group');
			$data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();
		}

		if (isset($this->request->post['page_form_customer_group'])) {
			$data['page_form_customer_group'] = $this->request->post['page_form_customer_group'];
		} elseif (isset($this->request->get['page_form_id'])) {
			$data['page_form_customer_group'] = $this->model_extension_ciformbuilder_page_form->getPageFormCustomerGroups($this->request->get['page_form_id']);
		} else {
			$data['page_form_customer_group'] = array($this->config->get('config_customer_group_id'));
		}

		if (isset($this->request->post['page_form_description'])) {
			$data['page_form_description'] = $this->request->post['page_form_description'];
		} elseif (isset($this->request->get['page_form_id'])) {
			$data['page_form_description'] = $this->model_extension_ciformbuilder_page_form->getPageFormDescriptions($this->request->get['page_form_id']);
		} else {
			$data['page_form_description'] = array();
		}

		if (isset($this->request->post['page_form_field'])) {
			$fields = $this->request->post['page_form_field'];
		} elseif (isset($this->request->get['page_form_id'])) {
			$fields = $this->model_extension_ciformbuilder_page_form->getPageFormOptions($this->request->get['page_form_id']);
		} else {
			$fields = [];
		}

		$data['fields'] = [];
		foreach($fields as $field) {
			if (!empty($field['image']) && is_file(DIR_IMAGE . $field['image'])) {
				$field['thumb'] = $this->model_tool_image->resize($field['image'], 50, 50);
			} else {
				$field['thumb'] = $this->model_tool_image->resize('no_image.png', 50, 50);
			}

			if(!empty($field['option_value'])) {
				foreach($field['option_value'] as &$option_value) {
					if (!empty($option_value['image']) && is_file(DIR_IMAGE . $option_value['image'])) {
						$option_value['thumb'] = $this->model_tool_image->resize($option_value['image'], 50, 50);
					} else {
						$option_value['thumb'] = $this->model_tool_image->resize('no_image.png', 50, 50);
					}
				}
			}

			$data['fields'][] = $field;
		}

		if (isset($this->request->post['file_ext_allowed'])) {
			$data['file_ext_allowed'] = $this->request->post['file_ext_allowed'];
		} elseif (!empty($page_form_info)) {
			$data['file_ext_allowed'] = $page_form_info['file_ext_allowed'];
		} else {
			$data['file_ext_allowed'] = $this->config->get('config_file_ext_allowed');
		}

		if (isset($this->request->post['file_mime_allowed'])) {
			$data['file_mime_allowed'] = $this->request->post['file_mime_allowed'];
		} elseif (!empty($page_form_info)) {
			$data['file_mime_allowed'] = $page_form_info['file_mime_allowed'];
		} else {
			$data['file_mime_allowed'] = $this->config->get('config_file_mime_allowed');
		}

		if (isset($this->request->post['mail_alert_email'])) {
			$data['mail_alert_email'] = $this->request->post['mail_alert_email'];
		} elseif (!empty($page_form_info)) {
			$data['mail_alert_email'] = $page_form_info['mail_alert_email'];
		} else {
			$data['mail_alert_email'] = $this->config->get('config_mail_alert_email');
		}

		if (isset($this->request->post['mail_alert_email_status'])) {
			$data['mail_alert_email_status'] = $this->request->post['mail_alert_email_status'];
		} elseif (!empty($page_form_info)) {
			$data['mail_alert_email_status'] = $page_form_info['mail_alert_email_status'];
		} else {
			$data['mail_alert_email_status'] = '';
		}

		if (isset($this->request->post['customer_field_attachment'])) {
			$data['customer_field_attachment'] = $this->request->post['customer_field_attachment'];
		} elseif (!empty($page_form_info)) {
			$data['customer_field_attachment'] = $page_form_info['customer_field_attachment'];
		} else {
			$data['customer_field_attachment'] = '';
		}

		if (isset($this->request->post['admin_field_attachment'])) {
			$data['admin_field_attachment'] = $this->request->post['admin_field_attachment'];
		} elseif (!empty($page_form_info)) {
			$data['admin_field_attachment'] = $page_form_info['admin_field_attachment'];
		} else {
			$data['admin_field_attachment'] = '';
		}

		$data['set_widths'] = array();
		for($w = 1; $w <= 12; $w++) {
			$data['set_widths'][] = array(
				'value'			=> $w,
				'text'			=> $this->language->get('text_width_'. $w),
			);
		}

		$this->load->model('extension/ciformbuilder/form_status');
		if (isset($this->request->get['page_form_id'])) {
			$data['form_statuses'] = $this->model_extension_ciformbuilder_form_status->getEnabledPageFormStatuses($this->request->get['page_form_id']);
		} else {
			$data['form_statuses'] = array();
		}

		if (isset($this->request->post['default_form_status'])) {
			$data['default_form_status'] = $this->request->post['default_form_status'];
		} elseif (!empty($page_form_info)) {
			$data['default_form_status'] = $page_form_info['default_form_status'];
		} else {
			$data['default_form_status'] = '';
		}		

		// Term Condition code start
		if (isset($this->request->post['termcondition'])) {
			$data['termcondition'] = $this->request->post['termcondition'];
		} elseif (!empty($page_form_info['termcondition'])) {
			$data['termcondition'] = json_decode($page_form_info['termcondition'],true);
		} else {
			$data['termcondition']['status'] = 0;
			$data['termcondition']['information_id'] = '';
		}
		// Term Condition code end

		// Google Analytic code start
		if (isset($this->request->post['google_analytic'])) {
			$data['google_analytic'] = $this->request->post['google_analytic'];
		} elseif (!empty($page_form_info['google_analytic'])) {
			$data['google_analytic'] = json_decode($page_form_info['google_analytic'],true);
		} else {
			$data['google_analytic']['status'] = 0;
			$data['google_analytic']['code'] = '';
		}
		// Google Analytic code end

		$data['config_language_id'] = $this->config->get('config_language_id');

		$data['config_submission_status'] = $this->config->get('module_ciformbuilder_setting_submission_status');

		$data['auto_fill_values'] = [];
		$data['auto_fill_values'][] = [
			'value'		=> '',
			'text'		=> $this->language->get('autofill_default_value'),
		];

		$data['auto_fill_values'][] = [
			'value'		=> 'Name',
			'text'		=> $this->language->get('autofill_name'),
		];
		$data['auto_fill_values'][] = [
			'value'		=> 'firstname',
			'text'		=> $this->language->get('autofill_firstname'),
		];

		$data['auto_fill_values'][] = [
			'value'		=> 'lastname',
			'text'		=> $this->language->get('autofill_lastname'),
		];

		$data['auto_fill_values'][] = [
			'value'		=> 'email',
			'text'		=> $this->language->get('autofill_email'),
		];

		$data['auto_fill_values'][] = [
			'value'		=> 'telephone',
			'text'		=> $this->language->get('autofill_telephone'),
		];

		$data['auto_fill_values'][] = [
			'value'		=> 'company',
			'text'		=> $this->language->get('autofill_company'),
		];

		$data['auto_fill_values'][] = [
			'value'		=> 'address_1',
			'text'		=> $this->language->get('autofill_address_1'),
		];

		$data['auto_fill_values'][] = [
			'value'		=> 'address_2',
			'text'		=> $this->language->get('autofill_address_2'),
		];

		$data['auto_fill_values'][] = [
			'value'		=> 'city',
			'text'		=> $this->language->get('autofill_city'),
		];

		$data['auto_fill_values'][] = [
			'value'		=> 'postcode',
			'text'		=> $this->language->get('autofill_postcode'),
		];

		$data['auto_fill_values'][] = [
			'value'		=> 'country_id',
			'text'		=> $this->language->get('autofill_country_id'),
		];

		$data['auto_fill_values'][] = [
			'value'		=> 'country',
			'text'		=> $this->language->get('autofill_country'),
		];

		$data['auto_fill_values'][] = [
			'value'		=> 'zone_id',
			'text'		=> $this->language->get('autofill_zone_id'),
		];

		$data['auto_fill_values'][] = [
			'value'		=> 'zone',
			'text'		=> $this->language->get('autofill_zone'),
		];


		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		if(VERSION <= '2.3.0.2') {
			$this->response->setOutput($this->load->view('extension/ciformbuilder/page_form.tpl', $data));
		} else {
			$file_variable = 'template_engine';
			$file_type = 'template';
			$this->config->set($file_variable, $file_type);
			$this->response->setOutput($this->load->view('extension/ciformbuilder/page_form', $data));
		}
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'extension/ciformbuilder/page_form')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!isset($this->request->post['css'])) {
			// Array Limit Exceeded

			$this->error['warning'] = sprintf($this->language->get('error_max_input_vars'), ini_get('max_input_vars'), ini_get('max_input_vars'));

			return !$this->error;
		}

		foreach ($this->request->post['page_form_description'] as $language_id => $page_form_value) {
			if ((utf8_strlen($page_form_value['title']) < 2) || (utf8_strlen($page_form_value['title']) > 255)) {
				$this->error['title'][$language_id] = $this->language->get('error_title');
			}

			if ((utf8_strlen($page_form_value['meta_title']) < 3) || (utf8_strlen($page_form_value['meta_title']) > 255)) {
				$this->error['meta_title'][$language_id] = $this->language->get('error_meta_title');
			}

			if ((utf8_strlen($page_form_value['success_title']) < 2) || (utf8_strlen($page_form_value['success_title']) > 255)) {
				$this->error['success_title'][$language_id] = $this->language->get('error_success_title');
			}

			if(!empty($this->request->post['customer_email_status'])) {
				if ((utf8_strlen($page_form_value['customer_subject']) < 2) || (utf8_strlen($page_form_value['customer_subject']) > 255)) {
					$this->error['customer_subject'][$language_id] = $this->language->get('error_customer_subject');
				}

				$page_form_value['customer_message'] = str_replace('&lt;p&gt;&lt;br&gt;&lt;/p&gt;', '', $page_form_value['customer_message']);
				if ((utf8_strlen($page_form_value['customer_message']) < 25)) {
					$this->error['customer_message'][$language_id] = $this->language->get('error_customer_message');
				}
			}

			if(!empty($this->request->post['admin_email_status'])) {
				if(empty($this->request->post['admin_email'])) {
					$this->error['admin_email'] = $this->language->get('error_admin_email');
					$this->error['warning'] = $this->language->get('error_admin_email');
				}

				if ((utf8_strlen($page_form_value['admin_subject']) < 2) || (utf8_strlen($page_form_value['admin_subject']) > 255)) {
					$this->error['admin_subject'][$language_id] = $this->language->get('error_admin_subject');
				}

				$page_form_value['admin_message'] = str_replace('&lt;p&gt;&lt;br&gt;&lt;/p&gt;', '', $page_form_value['admin_message']);
				if ((utf8_strlen($page_form_value['admin_message']) < 25)) {
					$this->error['admin_message'][$language_id] = $this->language->get('error_admin_message');
				}
			}
		}

		if (isset($this->request->post['page_form_field'])) {
			foreach ($this->request->post['page_form_field'] as $row => $description) {
				if(isset($description['description'])) {
					foreach ($description['description'] as $language_id => $value) {
						if ((utf8_strlen($value['field_name']) < 1) || (utf8_strlen($value['field_name']) > 128)) {
							$this->error['field_name'][$row][$language_id] = $this->language->get('error_field_name');
						}
					}
				}

				if(isset($description['option_value']) && !in_array($description['type'], array('select', 'multi_select', 'radio', 'radio_toggle', 'checkbox', 'checkbox_switch', 'checkbox_toggle')) ) {
					unset($this->request->post['page_form_field'][$row]['option_value']);
					unset($description['option_value']);
				}

				if(isset($description['option_value'])) {
					foreach ($description['option_value'] as $option_value_row => $option_value) {
						foreach ($option_value['page_form_option_value_description'] as $language_id => $option_value_description) {
							if ((utf8_strlen($option_value_description['name']) < 1) || (utf8_strlen($option_value_description['name']) > 128)) {
								$this->error['value_name'][$row][$option_value_row][$language_id] = $this->language->get('error_value_name');
							}
						}
					}
				}
			}
		}

		if(VERSION <= '2.3.0.2') {
			if (utf8_strlen($this->request->post['keyword']) > 0) {
				$this->load->model('catalog/url_alias');

				$url_alias_info = $this->model_catalog_url_alias->getUrlAlias($this->request->post['keyword']);

				if ($url_alias_info && isset($this->request->get['page_form_id']) && $url_alias_info['query'] != 'page_form_id=' . $this->request->get['page_form_id']) {
					$this->error['keyword'] = sprintf($this->language->get('error_keyword'));
				}

				if ($url_alias_info && !isset($this->request->get['page_form_id'])) {
					$this->error['keyword'] = sprintf($this->language->get('error_keyword'));
				}
			}
		} else {
			if ($this->request->post['page_form_seo_url']) {
				$this->load->model('design/seo_url');

				foreach ($this->request->post['page_form_seo_url'] as $store_id => $language) {
					foreach ($language as $language_id => $keyword) {
						if (!empty($keyword)) {
							if (count(array_keys($language, $keyword)) > 1) {
								$this->error['keyword'][$store_id][$language_id] = $this->language->get('error_unique');
							}

							$seo_urls = $this->model_design_seo_url->getSeoUrlsByKeyword($keyword);

							foreach ($seo_urls as $seo_url) {
								if (($seo_url['store_id'] == $store_id) && (!isset($this->request->get['page_form_id']) || (($seo_url['query'] != 'page_form_id=' . $this->request->get['page_form_id'])))) {
									$this->error['keyword'][$store_id][$language_id] = $this->language->get('error_keyword');

									break;
								}
							}
						}
					}
				}
			}
		}


		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'extension/ciformbuilder/page_form')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_title'])) {
			if (isset($this->request->get['filter_title'])) {
				$filter_title = $this->request->get['filter_title'];
			} else {
				$filter_title = '';
			}

			$this->load->model('extension/ciformbuilder/page_form');

			$filter_data = array(
				'filter_title' => $filter_title,
				'start'        => 0,
				'limit'        => 5
			);

			$results = $this->model_extension_ciformbuilder_page_form->getPageForms($filter_data);

			foreach ($results as $result) {
				$json[] = array(
					'page_form_id'       => $result['page_form_id'],
					'title'              => strip_tags(html_entity_decode($result['title'], ENT_QUOTES, 'UTF-8')),
				);
			}
		}

		$sort_order = array();

		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['title'];
		}

		array_multisort($sort_order, SORT_ASC, $json);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function copy() {
		$this->load->language('extension/ciformbuilder/page_form');

		$this->load->model('extension/ciformbuilder/page_form');

		$this->document->setTitle($this->language->get('heading_title'));

		if (isset($this->request->post['selected']) && $this->validateCopy()) {
			foreach ($this->request->post['selected'] as $page_form_id) {
				$this->model_extension_ciformbuilder_page_form->copyPageForm($page_form_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_page_form_id'])) {
				$url .= '&filter_page_form_id=' . urlencode(html_entity_decode($this->request->get['filter_page_form_id'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_page_form_title'])) {
				$url .= '&filter_page_form_title=' . urlencode(html_entity_decode($this->request->get['filter_page_form_title'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . urlencode(html_entity_decode($this->request->get['filter_status'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('extension/ciformbuilder/page_form', $this->module_token .'=' . $this->ci_token . $url, true));
		}

		$this->getList();
	}

	protected function validateCopy() {
		if (!$this->user->hasPermission('modify', 'extension/ciformbuilder/page_form')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	public function addStatus() {
		if (isset($this->request->get['page_form_id'])) {
			$page_form_id = (int)$this->request->get['page_form_id'];
		} else {
			$page_form_id = 0;
		}

		$this->load->model('extension/ciformbuilder/page_form');
		$this->load->language('extension/ciformbuilder/page_form');
		$this->document->setTitle($this->language->get('heading_title_status'));

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			$this->model_extension_ciformbuilder_page_form->addStatus($this->request->get['page_form_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/ciformbuilder/page_form', $this->module_token .'=' . $this->ci_token, true));
		}

		
		$this->document->addStyle('view/stylesheet/formbuilder/formbuilder.css');

		$data['heading_title'] = $this->language->get('heading_title_status');

		$data['tab_status_email'] = $this->language->get('tab_status_email');

		$data['leg_status_setting'] = $this->language->get('leg_status_setting');

		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['leg_status_email'] = $this->language->get('leg_status_email');
		$data['entry_customer_subject'] = $this->language->get('entry_customer_subject');
		$data['entry_customer_subject'] = $this->language->get('entry_customer_subject');
		$data['entry_customer_message'] = $this->language->get('entry_customer_message');

		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');

		$data['button_save'] = $this->language->get('button_save');

		$data['const_names'] = $this->language->get('const_names');
		$data['const_short_codes'] = $this->language->get('const_short_codes');
		$data['const_logo'] = $this->language->get('const_logo');
		$data['const_store_name'] = $this->language->get('const_store_name');
		$data['const_store_link'] = $this->language->get('const_store_link');
		$data['const_product_id'] = $this->language->get('const_product_id');
		$data['const_product_name'] = $this->language->get('const_product_name');
		$data['const_product_link'] = $this->language->get('const_product_link');
		$data['const_name'] = $this->language->get('const_name');

		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['form_status'])) {

			$data['error_form_status'] = $this->error['form_status'];

		} else {

			$data['error_form_status'] = array();

		}

		$url = '';

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', $this->module_token .'=' . $this->ci_token, true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/ciformbuilder/page_form', $this->module_token .'=' . $this->ci_token . $url, true)
		);

		$data['action'] = $this->url->link('extension/ciformbuilder/page_form/addStatus', $this->module_token .'=' . $this->ci_token . '&page_form_id=' . $this->request->get['page_form_id'] . $url, true);

		$data['cancel'] = $this->url->link('extension/ciformbuilder/page_form', $this->module_token .'=' . $this->ci_token . $url, true);

		if (isset($this->request->get['page_form_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$page_form_info = $this->model_extension_ciformbuilder_page_form->getPageForm($this->request->get['page_form_id']);
		}
		
		$data['text_assign_status'] = sprintf($this->language->get('text_assign_status'), $page_form_info['title']);

		$data['ci_token'] = $this->ci_token;
		$data['module_token'] = $this->module_token;

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		$this->document->addStyle('view/javascript/jquery/ciformbuilder/dropzone/dist/dropzone.css');

		$this->document->addScript('view/javascript/jquery/ciformbuilder/dropzone/dist/dropzone.js');

		$data['page_form_id'] = isset($this->request->get['page_form_id']) ? $this->request->get['page_form_id'] : '';

		$this->load->model('extension/ciformbuilder/form_status');

		$data['form_statuses'] = $this->model_extension_ciformbuilder_form_status->getFormStatuses();

		if (isset($this->request->post['page_form_status_email'])) {

			$page_form_status_emails = $this->request->post['page_form_status_email'];

		} elseif (isset($this->request->get['page_form_id'])) {

			$page_form_status_emails = $this->model_extension_ciformbuilder_form_status->getPageFormStatus($this->request->get['page_form_id']);

		} else {

			$page_form_status_emails = array();

		}

		$this->load->model('tool/upload');

		$data['page_form_status_email'] = array();

		foreach($page_form_status_emails as $status_id => $page_form_status_email){

			//$attachments = $page_form_status_email['attachment'] ? json_decode($page_form_status_email['attachment'],true) : array();

			$attachments = isset($page_form_status_email['attachment']) ? $page_form_status_email['attachment'] : '';

			$upload_data = array();

			if($attachments){

				foreach($attachments as $code){

					$upload_info = $this->model_tool_upload->getUploadByCode($code);

					if ($upload_info) {

						$upload_data[] = array(

							'name'			=> $upload_info['name'],

							'code'			=> $code,

							'src'			=> HTTPS_CATALOG.'upload/'.$upload_info['name'],

							'href'  		=> HTTPS_CATALOG.'index.php?route=page/form/download&code=' . $code,

						);

					}

				}

			}

			$data['page_form_status_email'][$status_id] = array(

				'desc' => $page_form_status_email['desc'],

				'status' => $page_form_status_email['status'],

				'sort_order' => $page_form_status_email['sort_order'],

				'attachment' => $upload_data,

			);

		}

		$data['config_language_id'] = $this->config->get('config_language_id');

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		if(VERSION <= '2.3.0.2') {
			$this->response->setOutput($this->load->view('extension/ciformbuilder/page_form_status.tpl', $data));
		} else {
			$file_variable = 'template_engine';
			$file_type = 'template';
			$this->config->set($file_variable, $file_type);
			$this->response->setOutput($this->load->view('extension/ciformbuilder/page_form_status', $data));
		}
  	}
}