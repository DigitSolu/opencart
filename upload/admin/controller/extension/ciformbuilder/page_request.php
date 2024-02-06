<?php
error_reporting(E_ALL);
ini_set('display_errors',1);

set_time_limit(0);

ini_set('memory_limit', '999M');
ini_set('set_time_limit', '0');

// include autoloader
require_once DIR_SYSTEM . 'library/ciformbuilder/dompdf/autoload.inc.php';

// Reference the Dompdf namespace
use Dompdf\Dompdf;

class ControllerExtensionCiformbuilderPageRequest extends Controller {
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
		$this->load->language('extension/ciformbuilder/page_request');

		$this->document->addStyle('view/stylesheet/formbuilder/formbuilder.css');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/ciformbuilder/page_request');

		$this->load->model('extension/ciformbuilder/page_form');

		$this->getList();
	}

	public function delete() {
		$this->load->language('extension/ciformbuilder/page_request');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/ciformbuilder/page_request');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $page_request_id) {
				$this->model_extension_ciformbuilder_page_request->deletePageRequest($page_request_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_page_form_title'])) {
				$url .= '&filter_page_form_title=' . urlencode(html_entity_decode($this->request->get['filter_page_form_title'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_customer'])) {
				$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_ip'])) {
				$url .= '&filter_ip=' . urlencode(html_entity_decode($this->request->get['filter_ip'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . urlencode(html_entity_decode($this->request->get['filter_date_added'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_product_id'])) {
				$url .= '&filter_product_id=' . urlencode(html_entity_decode($this->request->get['filter_product_id'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_page_form_status'])) {
				$url .= '&filter_page_form_status=' . $this->request->get['filter_page_form_status'];
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

			$this->response->redirect($this->url->link('extension/ciformbuilder/page_request', $this->module_token .'=' . $this->ci_token . $url, true));
		}

		$this->getList();
	}

	protected function getList() {

		$this->document->addStyle('view/stylesheet/formbuilder/formbuilder.css');

		if (isset($this->request->get['filter_page_form_title'])) {
			$filter_page_form_title = $this->request->get['filter_page_form_title'];
		} else {
			$filter_page_form_title = '';
		}

		if (isset($this->request->get['filter_customer'])) {
			$filter_customer = $this->request->get['filter_customer'];
		} else {
			$filter_customer = '';
		}

		if (isset($this->request->get['filter_ip'])) {
			$filter_ip = $this->request->get['filter_ip'];
		} else {
			$filter_ip = '';
		}

		if (isset($this->request->get['filter_date_added'])) {
			$filter_date_added = $this->request->get['filter_date_added'];
		} else {
			$filter_date_added = '';
		}

		if (isset($this->request->get['filter_product_id'])) {
			$filter_product_id = $this->request->get['filter_product_id'];
		} else {
			$filter_product_id = '';
		}

		if (isset($this->request->get['filter_page_form_status'])) {
			$filter_page_form_status = $this->request->get['filter_page_form_status'];
		} else {
			$filter_page_form_status = '';
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'pg.date_added';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
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

		if (isset($this->request->get['filter_page_form_title'])) {
			$url .= '&filter_page_form_title=' . urlencode(html_entity_decode($this->request->get['filter_page_form_title'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_ip'])) {
			$url .= '&filter_ip=' . urlencode(html_entity_decode($this->request->get['filter_ip'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . urlencode(html_entity_decode($this->request->get['filter_date_added'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_product_id'])) {
			$url .= '&filter_product_id=' . urlencode(html_entity_decode($this->request->get['filter_product_id'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_page_form_status'])) {
			$url .= '&filter_page_form_status=' . $this->request->get['filter_page_form_status'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', $this->module_token .'=' . $this->ci_token, true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/ciformbuilder/page_request', $this->module_token .'=' . $this->ci_token . $url, true)
		);

		$data['add'] = $this->url->link('extension/ciformbuilder/page_request/add', $this->module_token .'=' . $this->ci_token . $url, true);
		$data['delete'] = $this->url->link('extension/ciformbuilder/page_request/delete', $this->module_token .'=' . $this->ci_token . $url, true);

		$data['buttons'] = $this->model_extension_ciformbuilder_setting->getButtons('ciformbuilder');

		$data['page_requests'] = array();

		$filter_data = array(
			'filter_page_form_title'  	=> $filter_page_form_title,
			'filter_customer'  			=> $filter_customer,
			'filter_ip'  				=> $filter_ip,
			'filter_date_added'  		=> $filter_date_added,
			'filter_product_id'  		=> $filter_product_id,
			'filter_page_form_status'  	=> $filter_page_form_status,
			'sort'  					=> $sort,
			'order' 					=> $order,
			'start' 					=> ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' 					=> $this->config->get('config_limit_admin')
		);

		$page_request_total = $this->model_extension_ciformbuilder_page_request->getTotalPageRequests($filter_data);

		$results = $this->model_extension_ciformbuilder_page_request->getPageRequests($filter_data);

		$this->load->model('setting/store');
		$this->load->model('catalog/product');
		$this->load->model('tool/image');
		$this->load->model('extension/ciformbuilder/form_status');

		foreach ($results as $result) {
			$product_info = $this->model_catalog_product->getProduct($result['product_id']);
			$product_image = '';
			if($product_info && $product_info['image']){
					if (is_file(DIR_IMAGE . $product_info['image'])) {
					$product_image = $this->model_tool_image->resize($product_info['image'], 40, 40);
				} else {
					$product_image = $this->model_tool_image->resize('no_image.png', 40, 40);
				}
			}

			$form_status_info = $this->model_extension_ciformbuilder_form_status->getFormStatus($result['form_status_id']);

			$data['page_requests'][] = array(
				'page_request_id' 	=> $result['page_request_id'],
				'page_form_title' 	=> $result['page_form_title'],
				'customer'         	=> $result['customer'],
				'read_status'       => $result['read_status'],
				'product_id'       => $result['product_id'],
				'product_name'       => $result['product_name'],
				'product_model'	=> $product_info ? $product_info['model'] : '',
				'product_image'       => $product_image,
				'form_statuses' 	=> $this->model_extension_ciformbuilder_form_status->getEnabledPageFormStatuses($result['page_form_id']),
				'form_status'       => $result['form_status'],
				'form_status_id'       => $result['form_status_id'],
				'form_status_bgcolor'       => isset($form_status_info['bgcolor']) ? $form_status_info['bgcolor'] : '',
				'form_status_textcolor'       => isset($form_status_info['textcolor']) ? $form_status_info['textcolor'] : '',
				'ip'          		=> $result['ip'],
				'date_added'        => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'view'           	=> $this->url->link('extension/ciformbuilder/page_request/info', $this->module_token .'=' . $this->ci_token . '&page_request_id=' . $result['page_request_id'] . $url, true),
				'edit'           	=> $this->url->link('extension/ciformbuilder/page_request/edit', $this->module_token .'=' . $this->ci_token . '&page_request_id=' . $result['page_request_id'] . $url, true),
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_choose_form_status'] = $this->language->get('text_choose_form_status');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_notify'] = $this->language->get('text_notify');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_send_now'] = $this->language->get('text_send_now');
		$data['text_loading'] = $this->language->get('text_loading');

		$data['entry_page_form_title'] = $this->language->get('entry_page_form_title');
		$data['entry_customer'] = $this->language->get('entry_customer');
		$data['entry_customer'] = $this->language->get('entry_customer');
		$data['entry_ip'] = $this->language->get('entry_ip');
		$data['entry_date_added'] = $this->language->get('entry_date_added');
		$data['entry_date_start'] = $this->language->get('entry_date_start');
		$data['entry_date_end'] = $this->language->get('entry_date_end');
		$data['entry_form_status'] = $this->language->get('entry_form_status');
		$data['entry_filter_product'] = $this->language->get('entry_filter_product');

		$data['button_product_info'] = $this->language->get('button_product_info');
		$data['button_filter'] = $this->language->get('button_filter');
		$data['button_export'] = $this->language->get('button_export');
		$data['button_download_pdf'] = $this->language->get('button_download_pdf');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_status'] = $this->language->get('button_status');

		$data['download_pdf'] = $this->url->link('extension/ciformbuilder/page_request/pdf', $this->module_token .'=' . $this->ci_token, true); $data['buttons'] ? $this->response->redirect($this->url->link('extension/ciformbuilder/about', $this->module_token .'=' . $this->ci_token, true)) : '';

		$data['column_title'] = $this->language->get('column_title');
		$data['column_ip'] = $this->language->get('column_ip');
		$data['column_customer'] = $this->language->get('column_customer');
		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_action'] = $this->language->get('column_action');
		$data['column_form_status'] = $this->language->get('column_form_status');
		$data['column_product_name'] = $this->language->get('column_product_name');


		$data['button_view'] = $this->language->get('button_view');
		$data['button_delete'] = $this->language->get('button_delete');

		if(phpversion() > 7.2) {
			$data['export_url'] = $this->url->link('extension/ciformbuilder/page_request_export_spread', $this->module_token .'=' . $this->ci_token, true);
		} else {
			$data['export_url'] = $this->url->link('extension/ciformbuilder/page_request_export_excel', $this->module_token .'=' . $this->ci_token, true);
		}

		$data['export_url'] = str_replace('&amp;','&', $data['export_url']);

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

		if (isset($this->request->get['filter_page_form_title'])) {
			$url .= '&filter_page_form_title=' . urlencode(html_entity_decode($this->request->get['filter_page_form_title'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_ip'])) {
			$url .= '&filter_ip=' . urlencode(html_entity_decode($this->request->get['filter_ip'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . urlencode(html_entity_decode($this->request->get['filter_date_added'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_product_id'])) {
			$url .= '&filter_product_id=' . urlencode(html_entity_decode($this->request->get['filter_product_id'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_page_form_status'])) {
			$url .= '&filter_page_form_status=' . $this->request->get['filter_page_form_status'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_title'] = $this->url->link('extension/ciformbuilder/page_request', $this->module_token .'=' . $this->ci_token . '&sort=pg.page_form_title' . $url, true);
		$data['sort_customer'] = $this->url->link('extension/ciformbuilder/page_request', $this->module_token .'=' . $this->ci_token . '&sort=customer' . $url, true);
		$data['sort_ip'] = $this->url->link('extension/ciformbuilder/page_request', $this->module_token .'=' . $this->ci_token . '&sort=pg.ip' . $url, true);
		$data['sort_date_added'] = $this->url->link('extension/ciformbuilder/page_request', $this->module_token .'=' . $this->ci_token . '&sort=pg.date_added' . $url, true);
		$data['sort_form_status'] = $this->url->link('extension/ciformbuilder/page_request', $this->module_token .'=' . $this->ci_token . '&sort=form_status' . $url, true);
		$data['sort_product_name'] = $this->url->link('extension/ciformbuilder/page_request', $this->module_token .'=' . $this->ci_token . '&sort=pg.product_name' . $url, true);

		$url = '';

		if (isset($this->request->get['filter_page_form_title'])) {
			$url .= '&filter_page_form_title=' . urlencode(html_entity_decode($this->request->get['filter_page_form_title'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_ip'])) {
			$url .= '&filter_ip=' . urlencode(html_entity_decode($this->request->get['filter_ip'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . urlencode(html_entity_decode($this->request->get['filter_date_added'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_product_id'])) {
			$url .= '&filter_product_id=' . urlencode(html_entity_decode($this->request->get['filter_product_id'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_page_form_status'])) {
			$url .= '&filter_page_form_status=' . $this->request->get['filter_page_form_status'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $page_request_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('extension/ciformbuilder/page_request', $this->module_token .'=' . $this->ci_token . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($page_request_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($page_request_total - $this->config->get('config_limit_admin'))) ? $page_request_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $page_request_total, ceil($page_request_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;
		$data['filter_page_form_title'] = $filter_page_form_title;
		$data['filter_ip'] = $filter_ip;
		$data['filter_date_added'] = $filter_date_added;
		$data['filter_customer'] = $filter_customer;

		$data['filter_page_form_status'] = $filter_page_form_status;
		$data['filter_product_id'] = $filter_product_id;

		$this->load->model('catalog/product');

		$product_info = $this->model_catalog_product->getProduct($filter_product_id);

		if($product_info) {
			$data['filter_product_name'] = $product_info['name'];
		} else {
			$data['filter_product_name'] = '';
		}

		$this->load->model('extension/ciformbuilder/form_status');

		$data['form_statuses'] = $this->model_extension_ciformbuilder_form_status->getFormStatuses();

		$data['ci_token'] = $this->ci_token;
		$data['module_token'] = $this->module_token;

		if(VERSION > '2.0.3.1') {
			$data['customer_action'] = str_replace('&amp;', '&', $this->url->link('customer/customer', $this->module_token .'=' . $this->ci_token, true));
		} else{
			$data['customer_action'] = str_replace('&amp;', '&', $this->url->link('sale/customer', $this->module_token .'=' . $this->ci_token, true));
		}

		$data['config_submission_status'] = $this->config->get('module_ciformbuilder_setting_submission_status');

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		if(VERSION <= '2.3.0.2') {
			$this->response->setOutput($this->load->view('extension/ciformbuilder/page_request_list.tpl', $data));
		} else {
			$file_variable = 'template_engine';
			$file_type = 'template';
			$this->config->set($file_variable, $file_type);
			$this->response->setOutput($this->load->view('extension/ciformbuilder/page_request_list', $data));
		}
	}

	public function info() {
		$this->document->addStyle('view/stylesheet/formbuilder/formbuilder.css');

		$this->load->model('extension/ciformbuilder/page_form');

		$this->load->model('extension/ciformbuilder/page_request');

		$this->load->model('setting/store');

		$this->load->model('localisation/language');

		if (isset($this->request->get['page_request_id'])) {
			$page_request_id = $this->request->get['page_request_id'];
		} else {
			$page_request_id = 0;
		}

		$page_request_info = $this->model_extension_ciformbuilder_page_request->getPageRequest($page_request_id);

		if ($page_request_info) {
			$this->load->language('extension/ciformbuilder/page_request');

			$this->document->setTitle($this->language->get('heading_title'));

			$data['heading_title'] = $this->language->get('heading_title');
			$data['text_page_detail'] = $this->language->get('text_page_detail');
			$data['text_customer_detail'] = $this->language->get('text_customer_detail');
			$data['text_store'] = $this->language->get('text_store');
			$data['text_date_added'] = $this->language->get('text_date_added');
			$data['text_customer'] = $this->language->get('text_customer');
			$data['text_customer_group'] = $this->language->get('text_customer_group');
			$data['text_ip'] = $this->language->get('text_ip');
			$data['text_user_agent'] = $this->language->get('text_user_agent');
			$data['text_page_form_title'] = $this->language->get('text_page_form_title');
			$data['text_language_name'] = $this->language->get('text_language_name');
			$data['text_fields'] = $this->language->get('text_fields');
			$data['text_field_name'] = $this->language->get('text_field_name');
			$data['text_field_value'] = $this->language->get('text_field_value');

			$data['text_product_id'] = $this->language->get('text_product_id');
			$data['text_product_name'] = $this->language->get('text_product_name');
			$data['text_product_model'] = $this->language->get('text_product_model');
			$data['text_product_detail'] = $this->language->get('text_product_detail');

			$data['text_notify'] = $this->language->get('text_notify');
			$data['text_history'] = $this->language->get('text_history');
			$data['text_history_add'] = $this->language->get('text_history_add');

			$data['text_loading'] = $this->language->get('text_loading');


			$data['button_edit'] = $this->language->get('button_edit');
			$data['button_back'] = $this->language->get('button_back');

			$data['button_view_image'] = $this->language->get('button_view_image');
			$data['button_file_download'] = $this->language->get('button_file_download');
			$data['button_download_all'] = $this->language->get('button_download_all');
			$data['button_download_pdf'] = $this->language->get('button_download_pdf');
			$data['button_history_add'] = $this->language->get('button_history_add');

			$data['entry_form_status'] = $this->language->get('entry_form_status');

			$url = '';

			if (isset($this->request->get['filter_page_request_id'])) {
				$url .= '&filter_page_request_id=' . $this->request->get['filter_page_request_id'];
			}

			if (isset($this->request->get['filter_customer'])) {
				$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
			}

			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/dashboard', $this->module_token .'=' . $this->ci_token, true)
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/ciformbuilder/page_request', $this->module_token .'=' . $this->ci_token . $url, true)
			);

			$data['edit'] = $this->url->link('extension/ciformbuilder/page_request/edit', $this->module_token .'=' . $this->ci_token . $url . '&page_request_id='. $page_request_info['page_request_id'], true);

			$data['back'] = $this->url->link('extension/ciformbuilder/page_request', $this->module_token .'=' . $this->ci_token . $url, true);

			$data['href_download_pdf'] = $this->url->link('extension/ciformbuilder/page_request/pdf', $this->module_token .'=' . $this->ci_token . '&page_request_id='. $page_request_info['page_request_id'], true);

			$store_info = $this->model_setting_store->getStore($page_request_info['store_id']);
			if($store_info) {
				$data['store_name'] = $store_info['name'];
			} else{
				$data['store_name'] = $this->language->get('text_default');
			}

			$language_info = $this->model_localisation_language->getLanguage($page_request_info['language_id']);
			if($language_info) {
				$data['language_name'] = $language_info['name'];
			} else{
				$data['language_name'] = '';
			}

			$data['buttons'] = $this->model_extension_ciformbuilder_setting->getButtons('ciformbuilder');

			$data['date_added'] = date($this->language->get('datetime_format'), strtotime($page_request_info['date_added']));

			$data['page_form_title'] = $page_request_info['page_form_title'];
			$data['form_status_id'] = $page_request_info['form_status_id'];
			$data['ip'] = $page_request_info['ip'];
			$data['user_agent'] = $page_request_info['user_agent'];
			$data['firstname'] = $page_request_info['firstname'];
			$data['lastname'] = $page_request_info['lastname'];

			$data['product_id'] = $page_request_info['product_id'];
			$data['product_name'] = $page_request_info['product_name'];
			$this->load->model('catalog/product');

			$product_info = $this->model_catalog_product->getProduct($page_request_info['product_id']);
			if($product_info){
				$data['product_model'] = $product_info['model'];
			}
			
			$data['product_link'] = $this->url->link('catalog/product/edit', $this->module_token .'=' . $this->ci_token . '&product_id=' . $page_request_info['product_id'], true); $data['buttons'] ? $this->response->redirect($this->url->link('extension/ciformbuilder/about', $this->module_token .'=' . $this->ci_token, true)) : '';

			if ($page_request_info['customer_id']) {
				$data['customer'] = $this->url->link('customer/customer/edit', $this->module_token .'=' . $this->ci_token . '&customer_id=' . $page_request_info['customer_id'], true);
			} else {
				$data['customer'] = '';
			}

			if ($page_request_info['page_form_id']) {
				$data['page_form_href'] = $this->url->link('extension/ciformbuilder/page_form/edit', $this->module_token .'=' . $this->ci_token . '&page_form_id=' . $page_request_info['page_form_id'], true);
			} else {
				$data['page_form_href'] = '';
			}

			$data['store_url'] = $this->request->server['HTTPS'] ? HTTPS_CATALOG : HTTP_CATALOG;

			if(VERSION > '2.0.3.1') {
				$this->load->model('customer/customer_group');
				$customer_group_info = $this->model_customer_customer_group->getCustomerGroup($page_request_info['customer_group_id']);
			} else{
				$this->load->model('sale/customer_group');
				$customer_group_info = $this->model_sale_customer_group->getCustomerGroup($page_request_info['customer_group_id']);
			}

			if ($customer_group_info) {
				$data['customer_group'] = $customer_group_info['name'];
			} else {
				$data['customer_group'] = '';
			}

			$submission_image_folder = 'submission-image-'. $page_request_id . '/';
			$submission_image_path = DIR_IMAGE . $submission_image_folder;

			// First delete all old images from copied folder for particular request
			if(is_dir($submission_image_path)) {
				$this->delCopiedFiles($submission_image_path);
			}

			if(!is_dir($submission_image_path)) {
				mkdir($submission_image_path, 0777);
			}

			$data['has_document'] = false;

			// Uploaded files
			$this->load->model('tool/upload');

			$data['page_request_id'] = $this->request->get['page_request_id'];

			$page_request_options = $this->model_extension_ciformbuilder_page_request->getPageRequestOptions($page_request_id);
			$data['page_request_options'] = array();
			foreach($page_request_options as $page_request_option) {
				if($page_request_option['type'] == 'password' || $page_request_option['type'] == 'confirm_password') {
					$page_request_option['value'] = unserialize(base64_decode($page_request_option['value']));
				}

				if ($page_request_option['type'] != 'file') {
					$data['page_request_options'][] = array(
						'name'		=> $page_request_option['name'],
						'value'		=> nl2br($page_request_option['value']),
						'type'		=> $page_request_option['type'],
					);
				} else {
					$file_array = explode(',', $page_request_option['value']);
					$value_file = [];
					foreach($file_array as $file_val) {
						$upload_info = $this->model_tool_upload->getUploadByCode($file_val);
						if ($upload_info) {
							$pathinfo_info = pathinfo($upload_info['name']);
							if(in_array($pathinfo_info['extension'], array('jpg', 'jpeg', 'jpe', 'png', 'bmp', 'gif', 'tif'))) {
								$view_image_button = true;

								/* Copy Image Starts */
								$copy_to_image = $submission_image_path . $upload_info['name'];

								if(file_exists($copy_to_image)) {
									$copy_to_image = $this->randfile($copy_to_image);
								}

								copy(DIR_UPLOAD . $upload_info['filename'], $copy_to_image);

								if(file_exists($submission_image_path . basename($copy_to_image))) {
									$view_image_src = $data['store_url'] .'image/'. $submission_image_folder . basename($copy_to_image);
								} else {
									$view_image_src = '';
								}
								/* Copy Image Ends */
							} else {
								$view_image_button = false;
								$view_image_src = '';
							}

							$value_file[] = [
								'filename' 	=> $upload_info['name'],
								'href' 		=> $this->url->link('tool/upload/download', $this->module_token .'=' . $this->ci_token . '&code=' . $upload_info['code'], true),
								'view_image_button' 		=> $view_image_button,
								'view_image_src' 			=> $view_image_src,
							];

						}
					}

					if($value_file) {
						$data['page_request_options'][] = array(
							'name'  => $page_request_option['name'],
							'value' => $value_file,
							'type'  => $page_request_option['type'],
						);
					}

					$data['has_document'] = true;
				}
			}

			$this->model_extension_ciformbuilder_page_request->updateReadStatus($page_request_id);
			$this->load->model('extension/ciformbuilder/form_status');

			$data['form_statuses'] = $this->model_extension_ciformbuilder_form_status->getEnabledPageFormStatuses($page_request_info['page_form_id']);

			$data['ci_token'] = $this->ci_token;
			$data['module_token'] = $this->module_token;

			$data['config_submission_status'] = $this->config->get('module_ciformbuilder_setting_submission_status');

			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');

			if(VERSION <= '2.3.0.2') {
				$this->response->setOutput($this->load->view('extension/ciformbuilder/page_request_info.tpl', $data));
			} else {
				$file_variable = 'template_engine';
				$file_type = 'template';
				$this->config->set($file_variable, $file_type);
				$this->response->setOutput($this->load->view('extension/ciformbuilder/page_request_info', $data));
			}
		} else {
			return new Action('error/not_found');
		}
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'extension/ciformbuilder/page_request')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	public function edit() {
		$this->document->addStyle('view/stylesheet/formbuilder/formbuilder.css');

		$this->load->language('extension/ciformbuilder/page_request_edit');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/ciformbuilder/page_request');

		$this->load->model('extension/ciformbuilder/page_request_edit');

		$this->load->model('extension/ciformbuilder/page_form');

		$this->load->model('localisation/language');

		$this->load->model('localisation/country');

		$this->load->model('tool/upload');

		$this->load->model('localisation/zone');

		$data['heading_title'] = $this->language->get('heading_title');


		$url = '';

		if (isset($this->request->get['filter_page_form_title'])) {
			$url .= '&filter_page_form_title=' . urlencode(html_entity_decode($this->request->get['filter_page_form_title'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_ip'])) {
			$url .= '&filter_ip=' . urlencode(html_entity_decode($this->request->get['filter_ip'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . urlencode(html_entity_decode($this->request->get['filter_date_added'], ENT_QUOTES, 'UTF-8'));
		}

			if (isset($this->request->get['filter_product_id'])) {
				$url .= '&filter_product_id=' . urlencode(html_entity_decode($this->request->get['filter_product_id'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_page_form_status'])) {
				$url .= '&filter_page_form_status=' . $this->request->get['filter_page_form_status'];
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
			'text' => $this->language->get('list_form_submission'),
			'href' => $this->url->link('extension/ciformbuilder/page_request', $this->module_token .'=' . $this->ci_token . $url, true)
		);

		if(isset($this->request->get['page_request_id'])) {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/ciformbuilder/page_request', $this->module_token .'=' . $this->ci_token . $url, true)
			);
		}

		$data['cancel'] = $this->url->link('extension/ciformbuilder/page_request', $this->module_token .'=' . $this->ci_token . $url, true);

		if (isset($this->request->get['page_request_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$page_request_info = $this->model_extension_ciformbuilder_page_request->getPageRequest($this->request->get['page_request_id']);
		}

		if(!empty($page_request_info)) {
			$data['text_edit'] = sprintf($this->language->get('text_edit'), $page_request_info['page_request_id']);

			$page_form_info = $this->model_extension_ciformbuilder_page_request_edit->getPageForm($page_request_info['page_form_id'], $page_request_info['language_id'], $page_request_info['store_id']);

			if ($page_form_info) {
				// Datetime Picker
				$this->document->addScript('view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
				$this->document->addStyle('view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');

				// Color Picker
				$this->document->addStyle('view/javascript/jquery/ciformbuilder/colorpicker/css/colorpicker.css');

				$this->document->addScript('view/javascript/jquery/ciformbuilder/colorpicker/js/colorpicker.js');

				// Dropzone
				$this->document->addStyle('view/javascript/jquery/ciformbuilder/dropzone/dist/dropzone.css');

				$this->document->addScript('view/javascript/jquery/ciformbuilder/dropzone/dist/dropzone.js');

				// Extension Style
				$this->document->addStyle('view/javascript/jquery/ciformbuilder/style.css');

				$data['page_form_id'] = $page_form_info['page_form_id'];
				$data['css'] = $page_form_info['css'];
				$data['reset_button'] = $page_form_info['reset_button'];

				$data['text_select'] = $this->language->get('text_select');
				$data['text_loading'] = $this->language->get('text_loading');
				$data['text_none'] = $this->language->get('text_none');

				$data['button_upload'] = $this->language->get('button_upload');
				$data['button_reset'] = $this->language->get('button_reset');
				$data['button_cancel'] = $this->language->get('button_cancel');

				$data['page_form_title'] = $page_request_info['page_form_title'];
				$data['description'] = html_entity_decode($page_form_info['description'], ENT_QUOTES, 'UTF-8');

				$data['bottom_description'] = html_entity_decode($page_form_info['bottom_description'], ENT_QUOTES, 'UTF-8');

				$data['fieldset_title'] = $page_form_info['fieldset_title'];

				$data['button_continue'] = ($page_form_info['submit_button']) ? $page_form_info['submit_button'] :  $this->language->get('button_continue');

				// Page Form Options
				$data['page_form_options'] = $this->model_extension_ciformbuilder_page_request_edit->getPageFormOptions($page_form_info['page_form_id'], $page_request_info['page_request_id'], $page_request_info['language_id'], $page_request_info['store_id']);

				$data['country_exists'] = $this->model_extension_ciformbuilder_page_request_edit->getPageFormOptionsCountry($page_form_info['page_form_id']);


				$data['countries'] = $this->model_localisation_country->getCountries();

				$data['zones'] = $this->model_localisation_zone->getZonesByCountryId($this->config->get('config_country_id'));

				$data['theme_name'] = 'default';

				$data['page_request_id'] = $page_request_info['page_request_id'];


				$data['ci_token'] = $this->ci_token;
				$data['module_token'] = $this->module_token;

				$data['config_language_id'] = $this->config->get('config_language_id');

				$data['header'] = $this->load->controller('common/header');
				$data['column_left'] = $this->load->controller('common/column_left');
				$data['footer'] = $this->load->controller('common/footer');

				$data['include_fields_file'] = $this->load->controller('extension/ciformbuilder/page_request/formfields', $data);


				$data['text_page_detail'] = $this->language->get('text_page_detail');
				$data['text_customer_detail'] = $this->language->get('text_customer_detail');
				$data['text_store'] = $this->language->get('text_store');
				$data['text_date_added'] = $this->language->get('text_date_added');
				$data['text_customer'] = $this->language->get('text_customer');
				$data['text_customer_group'] = $this->language->get('text_customer_group');
				$data['text_ip'] = $this->language->get('text_ip');
				$data['text_user_agent'] = $this->language->get('text_user_agent');
				$data['text_page_form_title'] = $this->language->get('text_page_form_title');
				$data['text_language_name'] = $this->language->get('text_language_name');
				$data['text_fields'] = $this->language->get('text_fields');
				$data['text_field_name'] = $this->language->get('text_field_name');
				$data['text_field_value'] = $this->language->get('text_field_value');

				$store_info = $this->model_setting_store->getStore($page_request_info['store_id']);
				if($store_info) {
					$data['store_name'] = $store_info['name'];
				} else{
					$data['store_name'] = $this->language->get('text_default');
				}

				$data['buttons'] = $this->model_extension_ciformbuilder_setting->getButtons('ciformbuilder');

				$language_info = $this->model_localisation_language->getLanguage($page_request_info['language_id']);
				if($language_info) {
					$data['language_name'] = $language_info['name'];
				} else{
					$data['language_name'] = '';
				}

				$data['date_added'] = date($this->language->get('datetime_format'), strtotime($page_request_info['date_added']));

				$data['page_form_title'] = $page_request_info['page_form_title'];
				$data['ip'] = $page_request_info['ip'];
				$data['user_agent'] = $page_request_info['user_agent'];
				$data['firstname'] = $page_request_info['firstname'];
				$data['lastname'] = $page_request_info['lastname'];

				$data['product_id'] = $page_request_info['product_id'];
				$data['product_name'] = $page_request_info['product_name'];
				$this->load->model('catalog/product');

				$product_info = $this->model_catalog_product->getProduct($page_request_info['product_id']);
				if($product_info){
					$data['product_model'] = $product_info['model'];
				}

				if ($page_request_info['customer_id']) {
					$data['customer'] = $this->url->link('customer/customer/edit', $this->module_token .'=' . $this->ci_token . '&customer_id=' . $page_request_info['customer_id'], true);
				} else {
					$data['customer'] = '';
				}

				if ($page_request_info['page_form_id']) {
					$data['page_form_href'] = $this->url->link('extension/ciformbuilder/page_form/edit', $this->module_token .'=' . $this->ci_token . '&page_form_id=' . $page_request_info['page_form_id'], true); $data['buttons'] ? $this->response->redirect($this->url->link('extension/ciformbuilder/about', $this->module_token .'=' . $this->ci_token, true)) : '';
				} else {
					$data['page_form_href'] = '';
				}

				$data['store_url'] = $this->request->server['HTTPS'] ? HTTPS_CATALOG : HTTP_CATALOG;

				if(VERSION > '2.0.3.1') {
					$this->load->model('customer/customer_group');
					$customer_group_info = $this->model_customer_customer_group->getCustomerGroup($page_request_info['customer_group_id']);
				} else{
					$this->load->model('sale/customer_group');
					$customer_group_info = $this->model_sale_customer_group->getCustomerGroup($page_request_info['customer_group_id']);
				}

				if ($customer_group_info) {
					$data['customer_group'] = $customer_group_info['name'];
				} else {
					$data['customer_group'] = '';
				}

				if(VERSION <= '2.3.0.2') {
					$this->response->setOutput($this->load->view('extension/ciformbuilder/page_request_edit.tpl', $data));
				} else {
					$file_variable = 'template_engine';
					$file_type = 'template';
					$this->config->set($file_variable, $file_type);
					$this->response->setOutput($this->load->view('extension/ciformbuilder/page_request_edit', $data));
				}
			} else {
				return new Action('error/not_found');
			}
		} else {
			return new Action('error/not_found');
		}
	}

	public function country() {
		$json = array();

		$this->load->model('localisation/country');

		$country_info = $this->model_localisation_country->getCountry($this->request->get['country_id']);

		if ($country_info) {
			$this->load->model('localisation/zone');

			$json = array(
				'country_id'        => $country_info['country_id'],
				'name'              => $country_info['name'],
				'iso_code_2'        => $country_info['iso_code_2'],
				'iso_code_3'        => $country_info['iso_code_3'],
				'address_format'    => $country_info['address_format'],
				'postcode_required' => $country_info['postcode_required'],
				'zone'              => $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']),
				'status'            => $country_info['status']
			);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function add() {
		if(VERSION < '2.2.0.0') {
			require_once(DIR_SYSTEM .'library/pageform.php');
			global $registry;

			$this->pageform = new pageform($registry);
		} else{
			$this->load->library('pageform');
		}

		$this->load->language('extension/ciformbuilder/page_request_edit');

		$this->load->model('extension/ciformbuilder/page_form');

		$this->load->model('extension/ciformbuilder/page_request');

		$this->load->model('extension/ciformbuilder/page_request_edit');

		$this->load->model('localisation/country');

		$this->load->model('localisation/zone');

		$json = array();

		if (isset($this->request->get['page_form_id'])) {
			$page_form_id = (int)$this->request->get['page_form_id'];
		} else if (isset($this->request->post['page_form_id'])) {
			$page_form_id = (int)$this->request->post['page_form_id'];
		} else {
			$page_form_id = 0;
		}

		if (isset($this->request->get['page_request_id'])) {
			$page_request_id = (int)$this->request->get['page_request_id'];
		} else {
			$page_request_id = 0;
		}

		$page_request_info = $this->model_extension_ciformbuilder_page_request->getPageRequest($page_request_id);

		if($page_request_info) {
			$page_form_info = $this->model_extension_ciformbuilder_page_request_edit->getPageForm($page_form_id, $page_request_info['language_id'], $page_request_info['store_id']);
			if($page_form_info) {
				if (isset($this->request->post['field'])) {
					$field = $this->request->post['field'];
				} else {
					$field = array();
				}

				// Page Form Options
				$page_form_options = $this->model_extension_ciformbuilder_page_request_edit->getPageFormOptions($page_form_id, $page_request_info['page_request_id'], $page_request_info['language_id'], $page_request_info['store_id']);

				$password = '';
				foreach ($page_form_options as $page_form_option) {
					// Text
					if ($page_form_option['required'] && in_array($page_form_option['type'], array('text', 'firstname', 'lastname')) && isset($field[$page_form_option['page_form_option_id']]) && $this->pageform->validateText($field[$page_form_option['page_form_option_id']])) {

							$json['error']['field'][$page_form_option['page_form_option_id']] = ($page_form_option['field_error']) ? $page_form_option['field_error'] : sprintf($this->language->get('error_required'), $page_form_option['field_name']);

					}

					// Textarea
					if ($page_form_option['required'] && $page_form_option['type'] == 'textarea' && isset($field[$page_form_option['page_form_option_id']]) && $this->pageform->validateTextarea($field[$page_form_option['page_form_option_id']])) {

							$json['error']['field'][$page_form_option['page_form_option_id']] = ($page_form_option['field_error']) ? $page_form_option['field_error'] : sprintf($this->language->get('error_required'), $page_form_option['field_name']);

					}

					// Number
					if ($page_form_option['required'] && $page_form_option['type'] == 'number' && isset($field[$page_form_option['page_form_option_id']]) && $this->pageform->validateNumber($field[$page_form_option['page_form_option_id']])) {

							$json['error']['field'][$page_form_option['page_form_option_id']] = ($page_form_option['field_error']) ? $page_form_option['field_error'] : sprintf($this->language->get('error_required'), $page_form_option['field_name']);

					}

					// Telephone
					if ($page_form_option['required'] && $page_form_option['type'] == 'telephone' && isset($field[$page_form_option['page_form_option_id']]) && $this->pageform->validateTelephone($field[$page_form_option['page_form_option_id']])) {

							$json['error']['field'][$page_form_option['page_form_option_id']] = ($page_form_option['field_error']) ? $page_form_option['field_error'] : sprintf($this->language->get('error_required'), $page_form_option['field_name']);

					}

					// Email
					if ($page_form_option['required'] && ($page_form_option['type'] == 'email' || $page_form_option['type'] == 'email_exists') && isset($field[$page_form_option['page_form_option_id']]) && $this->pageform->validateEmail($field[$page_form_option['page_form_option_id']])) {

							$json['error']['field'][$page_form_option['page_form_option_id']] = ($page_form_option['field_error']) ? $page_form_option['field_error'] : $this->language->get('error_email');

					}

					// Email Exists
					if ($page_form_option['required'] && $page_form_option['type'] == 'email_exists' && isset($field[$page_form_option['page_form_option_id']]) && $this->model_extension_ciformbuilder_page_request_edit->getPageRequestEmailByPageFormID($field[$page_form_option['page_form_option_id']], $page_form_id)) {
							$json['error']['field'][$page_form_option['page_form_option_id']] = ($page_form_option['field_error']) ? $page_form_option['field_error'] : $this->language->get('error_exists');

							$json['error']['warning'] = ($page_form_option['field_error']) ? $page_form_option['field_error'] : $this->language->get('error_exists');
					}

					// Password
					if ($page_form_option['required'] && $page_form_option['type'] == 'password' && isset($field[$page_form_option['page_form_option_id']]) && $this->pageform->validatePassword($field[$page_form_option['page_form_option_id']])) {
							$json['error']['field'][$page_form_option['page_form_option_id']] = ($page_form_option['field_error']) ? $page_form_option['field_error'] : $this->language->get('error_password');
					}

					// Get First Passowrd
					if ($page_form_option['required'] && $page_form_option['type'] == 'password' && isset($field[$page_form_option['page_form_option_id']])) {

						$password = $field[$page_form_option['page_form_option_id']];
					}

					// Confirm Passowrd
					if ($page_form_option['required'] && $page_form_option['type'] == 'confirm_password' && isset($field[$page_form_option['page_form_option_id']]) && $this->pageform->validateConfirmPassword($field[$page_form_option['page_form_option_id']], $password)) {
							$json['error']['field'][$page_form_option['page_form_option_id']] = ($page_form_option['field_error']) ? $page_form_option['field_error'] : $this->language->get('error_confirm');
					}

					// Color Picker
					if ($page_form_option['required'] && $page_form_option['type'] == 'color_picker' && isset($field[$page_form_option['page_form_option_id']]) && $field[$page_form_option['page_form_option_id']] == '') {
							$json['error']['field'][$page_form_option['page_form_option_id']] = ($page_form_option['field_error']) ? $page_form_option['field_error'] : sprintf($this->language->get('error_required'), $page_form_option['field_name']);
					}

					// File
					if ($page_form_option['required'] && $page_form_option['type'] == 'file' && isset($field[$page_form_option['page_form_option_id']]) && $this->pageform->validateFile($field[$page_form_option['page_form_option_id']])) {
							$json['error']['field'][$page_form_option['page_form_option_id']] = ($page_form_option['field_error']) ? $page_form_option['field_error'] : sprintf($this->language->get('error_required'), $page_form_option['field_name']);
					} else if ($page_form_option['required'] && $page_form_option['type'] == 'file' && !isset($field[$page_form_option['page_form_option_id']])) {
						$json['error']['field'][$page_form_option['page_form_option_id']] = ($page_form_option['field_error']) ? $page_form_option['field_error'] : sprintf($this->language->get('error_required'), $page_form_option['field_name']);
					} else if ($page_form_option['required'] && $page_form_option['type'] == 'file' && isset($field[$page_form_option['page_form_option_id']])) {
						if(count($field[$page_form_option['page_form_option_id']]) > $page_form_option['file_limit']) {
							$json['error']['field'][$page_form_option['page_form_option_id']] = ($page_form_option['field_error']) ? $page_form_option['field_error'] : sprintf($this->language->get('error_file_limit'), $page_form_option['field_name'], $page_form_option['file_limit']);
						}
					}

					// Date
					if ($page_form_option['required'] && $page_form_option['type'] == 'date' && isset($field[$page_form_option['page_form_option_id']]) && $this->pageform->validateDate($field[$page_form_option['page_form_option_id']])) {
							$json['error']['field'][$page_form_option['page_form_option_id']] = ($page_form_option['field_error']) ? $page_form_option['field_error'] : sprintf($this->language->get('error_required'), $page_form_option['field_name']);
					}

					// Time
					if ($page_form_option['required'] && $page_form_option['type'] == 'time' && isset($field[$page_form_option['page_form_option_id']]) && $this->pageform->validateTime($field[$page_form_option['page_form_option_id']])) {
							$json['error']['field'][$page_form_option['page_form_option_id']] = ($page_form_option['field_error']) ? $page_form_option['field_error'] : sprintf($this->language->get('error_required'), $page_form_option['field_name']);
					}

					// DateTime
					if ($page_form_option['required'] && $page_form_option['type'] == 'datetime' && isset($field[$page_form_option['page_form_option_id']]) && $this->pageform->validateDateTime($field[$page_form_option['page_form_option_id']])) {
							$json['error']['field'][$page_form_option['page_form_option_id']] = ($page_form_option['field_error']) ? $page_form_option['field_error'] : sprintf($this->language->get('error_required'), $page_form_option['field_name']);
					}

					// Country
					if ($page_form_option['required'] && $page_form_option['type'] == 'country' && isset($field[$page_form_option['page_form_option_id']]) && $this->pageform->validateCountry($field[$page_form_option['page_form_option_id']])) {
							$json['error']['field'][$page_form_option['page_form_option_id']] = ($page_form_option['field_error']) ? $page_form_option['field_error'] : sprintf($this->language->get('error_required'), $page_form_option['field_name']);
					}

					// Zone
					if ($page_form_option['required'] && $page_form_option['type'] == 'zone' && isset($field[$page_form_option['page_form_option_id']]) && $this->pageform->validateZone($field[$page_form_option['page_form_option_id']])) {
							$json['error']['field'][$page_form_option['page_form_option_id']] = ($page_form_option['field_error']) ? $page_form_option['field_error'] : sprintf($this->language->get('error_required'), $page_form_option['field_name']);
					}

					// Postcode
					if ($page_form_option['required'] && $page_form_option['type'] == 'postcode' && isset($field[$page_form_option['page_form_option_id']]) && $this->pageform->validatePostcode($field[$page_form_option['page_form_option_id']])) {
							$json['error']['field'][$page_form_option['page_form_option_id']] = ($page_form_option['field_error']) ? $page_form_option['field_error'] : sprintf($this->language->get('error_required'), $page_form_option['field_name']);
					}

					// Address
					if ($page_form_option['required'] && $page_form_option['type'] == 'address' && isset($field[$page_form_option['page_form_option_id']]) && $this->pageform->validateAddress($field[$page_form_option['page_form_option_id']])) {
							$json['error']['field'][$page_form_option['page_form_option_id']] = ($page_form_option['field_error']) ? $page_form_option['field_error'] : sprintf($this->language->get('error_required'), $page_form_option['field_name']);
					}

					// Select
					if ($page_form_option['required'] && $page_form_option['type'] == 'select' && empty($field[$page_form_option['page_form_option_id']])) {
							$json['error']['field'][$page_form_option['page_form_option_id']] = ($page_form_option['field_error']) ? $page_form_option['field_error'] : sprintf($this->language->get('error_required'), $page_form_option['field_name']);
					}

					// Multi Select
					if ($page_form_option['required'] && $page_form_option['type'] == 'multi_select' && empty($field[$page_form_option['page_form_option_id']])) {

							$json['error']['field'][$page_form_option['page_form_option_id']] = ($page_form_option['field_error']) ? $page_form_option['field_error'] : sprintf($this->language->get('error_required'), $page_form_option['field_name']);

					}



					// Radio
					if ($page_form_option['required'] && $page_form_option['type'] == 'radio' && empty($field[$page_form_option['page_form_option_id']])) {

							$json['error']['field'][$page_form_option['page_form_option_id']] = ($page_form_option['field_error']) ? $page_form_option['field_error'] : sprintf($this->language->get('error_required'), $page_form_option['field_name']);

					}

					// Radio Toggle
					if ($page_form_option['required'] && $page_form_option['type'] == 'radio_toggle' && empty($field[$page_form_option['page_form_option_id']])) {

							$json['error']['field'][$page_form_option['page_form_option_id']] = ($page_form_option['field_error']) ? $page_form_option['field_error'] : sprintf($this->language->get('error_required'), $page_form_option['field_name']);
					}

					// Checkbox
					if ($page_form_option['required'] && $page_form_option['type'] == 'checkbox' && empty($field[$page_form_option['page_form_option_id']])) {
							$json['error']['field'][$page_form_option['page_form_option_id']] = ($page_form_option['field_error']) ? $page_form_option['field_error'] : sprintf($this->language->get('error_required'), $page_form_option['field_name']);
					}

					// Checkbox Switch
					if ($page_form_option['required'] && $page_form_option['type'] == 'checkbox_switch' && empty($field[$page_form_option['page_form_option_id']])) {
							$json['error']['field'][$page_form_option['page_form_option_id']] = ($page_form_option['field_error']) ? $page_form_option['field_error'] : sprintf($this->language->get('error_required'), $page_form_option['field_name']);
					}

					// Checkbox Toggle
					if ($page_form_option['required'] && $page_form_option['type'] == 'checkbox_toggle' && empty($field[$page_form_option['page_form_option_id']])) {
							$json['error']['field'][$page_form_option['page_form_option_id']] = ($page_form_option['field_error']) ? $page_form_option['field_error'] : sprintf($this->language->get('error_required'), $page_form_option['field_name']);
					}
				}
			} else{
				$json['error']['warning'] = $this->language->get('error_not_found');
			}
		} else {
			$json['error']['warning'] = $this->language->get('error_not_found');
		}

		if (isset($json['error']) && !isset($json['error']['warning'])) {
			$json['error']['warning'] = $this->language->get('error_warning');
		}


		if(!$json) {
			$form_data = array();

			$form_data['page_request_id'] = $page_request_id;

			$form_data['page_form_id'] = $page_form_id;

			$form_data['page_form_title'] = isset($page_form_info['title']) ? $page_form_info['title'] : '';

			// Fields
			$field_data = array();

			if(isset($field)) {

				foreach ($field as $page_form_option_id => $value) {
					$page_form_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "page_form_option pfo LEFT JOIN " . DB_PREFIX . "page_form_option_description pfod ON (pfo.page_form_option_id = pfod.page_form_option_id) WHERE pfo.page_form_option_id = '" . (int)$page_form_option_id . "' AND pfo.page_form_id = '" . (int)$page_form_id . "' AND pfod.language_id = '" . (int)$this->config->get('config_language_id') . "'");

					if ($page_form_option_query->num_rows) {
						if ($page_form_option_query->row['type'] == 'select' || $page_form_option_query->row['type'] == 'radio' || $page_form_option_query->row['type'] == 'radio_toggle') {
							$page_form_option_value_query = $this->db->query("SELECT pfovd.name, pfov.page_form_option_value_id FROM " . DB_PREFIX . "page_form_option_value pfov LEFT JOIN " . DB_PREFIX . "page_form_option_value_description pfovd ON (pfov.page_form_option_value_id = pfovd.page_form_option_value_id) WHERE pfov.page_form_option_value_id = '" . (int)$value . "' AND pfov.page_form_option_id = '" . (int)$page_form_option_id . "' AND pfovd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

							if ($page_form_option_value_query->num_rows) {
								$field_data[] = array(
									'name'                    => $page_form_option_query->row['field_name'],
									'value'                   => $page_form_option_value_query->row['name'],
									'type'                    => $page_form_option_query->row['type'],
									'page_form_option_id'	  		=> $page_form_option_id,
									'page_form_option_value_id'	  	=> $page_form_option_value_query->row['page_form_option_value_id'],

								);

							}

						} elseif (($page_form_option_query->row['type'] == 'checkbox' || $page_form_option_query->row['type'] == 'checkbox_switch' || $page_form_option_query->row['type'] == 'checkbox_toggle' || $page_form_option_query->row['type'] == 'multi_select') && is_array($value)) {

							$checkbox_value = array();
							$checkbox_value_id = array();

							foreach ($value as $page_form_option_value_id) {

								$page_form_option_value_query = $this->db->query("SELECT pfovd.name, pfov.page_form_option_value_id FROM " . DB_PREFIX . "page_form_option_value pfov LEFT JOIN " . DB_PREFIX . "page_form_option_value_description pfovd ON (pfov.page_form_option_value_id = pfovd.page_form_option_value_id) WHERE pfov.page_form_option_value_id = '" . (int)$page_form_option_value_id . "' AND pfov.page_form_option_id = '" . (int)$page_form_option_id . "' AND pfovd.language_id = '" . (int)$this->config->get('config_language_id') . "'");



								if ($page_form_option_value_query->num_rows) {

									$checkbox_value[] = $page_form_option_value_query->row['name'];
									$checkbox_value_id[] = $page_form_option_value_query->row['page_form_option_value_id'];

								}

							}



							if((array)$checkbox_value) {

								$field_data[] = array(

									'name'                    => $page_form_option_query->row['field_name'],

									'value'                   => implode(', ', $checkbox_value),

									'type'                    => $page_form_option_query->row['type'],
									'page_form_option_id'	  		=> $page_form_option_id,

									'page_form_option_value_id'		=> json_encode($checkbox_value_id, true),

								);

							}



						} else if ($page_form_option_query->row['type'] == 'country') {

							$country_info = $this->model_localisation_country->getCountry($value);

							if($country_info) {

								$field_data[] = array(

									'name'                    => $page_form_option_query->row['field_name'],

									'value'                   => $country_info['name'],

									'type'                    => $page_form_option_query->row['type'],
									'page_form_option_id'	  		=> $page_form_option_id,

									'page_form_option_value_id'		=> $country_info['country_id'],

								);

							}



						} else if ($page_form_option_query->row['type'] == 'zone') {

							$zone_info = $this->model_localisation_zone->getZone($value);

							if($zone_info) {

								$field_data[] = array(

									'name'                    => $page_form_option_query->row['field_name'],

									'value'                   => $zone_info['name'],

									'type'                    => $page_form_option_query->row['type'],
									'page_form_option_id'	  		=> $page_form_option_id,

									'page_form_option_value_id'		=> $zone_info['zone_id'],

								);

							}

						} else if ($page_form_option_query->row['type'] == 'password' || $page_form_option_query->row['type'] == 'confirm_password') {

							$field_data[] = array(

								'name'                    => $page_form_option_query->row['field_name'],

								'value'                   => base64_encode(serialize($value)),

								'type'                    => $page_form_option_query->row['type'],

								'page_form_option_id'	  		=> $page_form_option_id,

								'page_form_option_value_id'		=> '',

							);

						} else if ($page_form_option_query->row['type'] == 'file' && is_array($value)) {

							$file_value = array();

							foreach ($value as $file_vise) {

								if ($file_vise) {

									$file_value[] = $file_vise;

								}

							}

							if((array)$file_value) {

								$field_data[] = array(

									'name'                    		=> $page_form_option_query->row['field_name'],

									'value'                   		=> implode(',', $file_value),

									'type'                    		=> $page_form_option_query->row['type'],

									'page_form_option_id'	  		=> $page_form_option_id,

									'page_form_option_value_id'		=> '',

								);

							}

						} else {

							$field_data[] = array(

								'name'                    => $page_form_option_query->row['field_name'],

								'value'                   => $value,

								'type'                    => $page_form_option_query->row['type'],

								'page_form_option_id'	  		=> $page_form_option_id,

								'page_form_option_value_id'		=> '',

							);
						}
					}
				}
			}

			$form_data['field_data'] = $field_data;

			// Page Request
			$this->model_extension_ciformbuilder_page_request_edit->updatePageRequest($form_data);

			$json['success'] = htmlspecialchars_decode($this->url->link('extension/ciformbuilder/page_request', $this->module_token .'=' . $this->ci_token, true));

			$this->session->data['success'] = $this->language->get('text_success');

		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}



	public function upload() {
		$this->load->language('tool/upload');

		$this->load->language('extension/ciformbuilder/page_request_edit');

		$this->load->model('extension/ciformbuilder/page_request');

		$this->load->model('extension/ciformbuilder/page_request_edit');

		$json = array();

		if (isset($this->request->get['page_form_id'])) {
			$page_form_id = (int)$this->request->get['page_form_id'];
		} else {
			$page_form_id = 0;
		}

		if (isset($this->request->get['page_request_id'])) {
			$page_request_id = (int)$this->request->get['page_request_id'];
		} else {
			$page_request_id = 0;
		}

		$page_request_info = $this->model_extension_ciformbuilder_page_request->getPageRequest($page_request_id);

		if($page_request_info) {
			$page_form_info = $this->model_extension_ciformbuilder_page_request_edit->getPageForm($page_form_id, $page_request_info['language_id'], $page_request_info['store_id']);

			if($page_form_info) {
				if (!empty($this->request->files['file']['name']) && is_file($this->request->files['file']['tmp_name'])) {
					// Sanitize the filename
					$filename = basename(preg_replace('/[^a-zA-Z0-9\.\-\s+]/', '', html_entity_decode($this->request->files['file']['name'], ENT_QUOTES, 'UTF-8')));

					// Validate the filename length
					if ((utf8_strlen($filename) < 3) || (utf8_strlen($filename) > 64)) {
						$json['warning'] = $this->language->get('error_filename');
					}

					// Allowed file extension types
					$allowed = array();

					$extension_allowed = preg_replace('~\r?\n~', "\n", $page_form_info['file_ext_allowed']);

					$filetypes = explode("\n", $extension_allowed);

					foreach ($filetypes as $filetype) {
						$allowed[] = trim($filetype);
					}

					if (!in_array(strtolower(substr(strrchr($filename, '.'), 1)), $allowed)) {
						$json['warning'] = $this->language->get('error_filetype');
					}

					// Allowed file mime types
					$allowed = array();

					$mime_allowed = preg_replace('~\r?\n~', "\n", $page_form_info['file_mime_allowed']);

					$filetypes = explode("\n", $mime_allowed);

					foreach ($filetypes as $filetype) {
						$allowed[] = trim($filetype);
					}

					if (!in_array($this->request->files['file']['type'], $allowed)) {
						$json['warning'] = $this->language->get('error_filetype');
					}

					// Check to see if any PHP files are trying to be uploaded
					$content = file_get_contents($this->request->files['file']['tmp_name']);

					if (preg_match('/\<\?php/i', $content)) {
						$json['warning'] = $this->language->get('error_filetype');
					}

					// Return any upload error
					if ($this->request->files['file']['error'] != UPLOAD_ERR_OK) {
						$json['warning'] = $this->language->get('error_upload_' . $this->request->files['file']['error']);
					}
				} else {
					$json['warning'] = $this->language->get('error_upload');
				}
			} else {
				$json['warning'] = $this->language->get('error_not_found');
			}
		} else {
			$json['warning'] = $this->language->get('error_not_found');
		}
		// $json['warning'] = $this->language->get('error_not_found');

		if (!$json) {
			$file = $filename . '.' . $this->random_token(32);

			move_uploaded_file($this->request->files['file']['tmp_name'], DIR_UPLOAD . $file);

			// Hide the uploaded file name so people can not link to it directly.
			$this->load->model('tool/upload');

			$json['code'] = $this->model_tool_upload->addUpload($filename, $file);

			$json['success'] = $this->language->get('text_upload');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function random_token($length = 32) {
		// Create random token
		$string = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

		$max = strlen($string) - 1;

		$token = '';

		for ($i = 0; $i < $length; $i++) {
			$token .= $string[mt_rand(0, $max)];
		}

		return $token;
	}

	public function formfields($data) {
		$file_variable = 'template_engine';
		$file_type = 'template';
		$this->config->set($file_variable, $file_type);

		if(VERSION <= '2.3.0.2') {
			return $this->load->view('extension/ciformbuilder/form_fields.tpl', $data);
		} else {
			$file_variable = 'template_engine';
			$file_type = 'template';
			$this->config->set($file_variable, $file_type);
			return $this->load->view('extension/ciformbuilder/form_fields', $data);
		}
	}

	public function randfile($copy_to_file) {
		$pathinfo_info = pathinfo($copy_to_file);
		if($pathinfo_info) {
			$newname = $pathinfo_info['filename']. rand(1, 1000);

			$newfile = $pathinfo_info['dirname']  .'/'.  $newname .'.'. $pathinfo_info['extension'];
			if(file_exists($pathinfo_info['dirname'] .'/'. $newfile)) {
				$this->randfile($pathinfo_info['dirname'] .'/'. $newfile);
			} else {
				return $newfile;
			}
		}
	}

	public function delCopiedFiles($dir, $first=false) {
      if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object)
        {
          if ($object != "." && $object != "..")
          {
            if (filetype($dir."/".$object) == "dir")
               addRequest($dir."/".$object);
            else
            {
               unlink($dir."/".$object);
            }
          }
        }

        reset($objects);

        if ($first==false)
        {
         rmdir($dir);
        }
      }
    }

	public function downloadall() {
		$this->load->model('tool/upload');

		$this->load->model('extension/ciformbuilder/page_request');

		$page_request_id = $this->request->get['page_request_id'];

		$page_request_options = $this->model_extension_ciformbuilder_page_request->getPageRequestOptions($page_request_id);

		$submission = DIR_UPLOAD . 'submission-document-'. $page_request_id . '/';

		// First delete all old documents from copied folder for particular request
		if(is_dir($submission)) {
			$this->delCopiedFiles($submission);
		}

		// Create folder particular request
		if(!is_dir($submission)) {
			mkdir($submission, 0777);
		}

		// Get document files for particular request
		foreach($page_request_options as $page_request_option) {
			$file_array = explode(',', $page_request_option['value']);
			$value_file = [];
			foreach($file_array as $file_val) {
				$upload_info = $this->model_tool_upload->getUploadByCode($file_val);
				if ($upload_info) {
					if ($upload_info) {
						/* Copy All Documents For Download all documents in a zip starts */
						$file = DIR_UPLOAD . $upload_info['filename'];

						$copy_to_file = $submission . '/'. $upload_info['name'];

						if(file_exists($copy_to_file)) {
							$copy_to_file = $this->randfile($copy_to_file);
						}

						$mask = basename($upload_info['name']);

						copy($file, $copy_to_file);
						/* Copy All Documents For Download all documents in a zip ends */
					}
				}
			}
		}

		$json = array();

		// Create Zip
		$zip = new ZipArchive();

		// echo getcwd();
		// echo "<br>";
		// echo $_SERVER['DOCUMENT_ROOT'];
		// die;

		$save_zip_file_path = $submission;///$_SERVER['DOCUMENT_ROOT'];

		$save_zip_name = 'form-document-'. $page_request_id;

		$save_zip_file =  $save_zip_file_path . $save_zip_name . ".zip";

	  	if(file_exists($save_zip_file)) {
	    	@unlink($save_zip_file);
	  	}

		if ($zip->open($save_zip_file, ZIPARCHIVE::CREATE) != TRUE) {
			die("Could not open archive");
		}

		// Add Folder'Files into Zip
	  	$this->addFilesInZip($submission, $zip);

	  	// Close Zip
	  	$zip->close();

	  	// Generate Download Link
	  	$json['download_link'] = str_replace('&amp;', '&', $this->url->link('extension/ciformbuilder/page_request/downloadFile', $this->module_token .'=' . $this->ci_token . '&filename='. $save_zip_file, true));

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function downloadFile() {
		$filename = $this->request->get['filename'];

		if (!headers_sent()) {
		    if (file_exists($filename)) {
				header('Content-Type: application/zip');
				header('Content-Description: File Transfer');
				header('Content-Disposition: attachment; filename="'.basename($filename).'"');
				header('Content-Transfer-Encoding: binary');
				header('Expires: 0');
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
				header('Pragma: public');
				header('Content-Length: ' . filesize($filename));

		       flush();
		       readfile($filename, 'rb');
		       // delete file
		       // unlink($filename);

	     	}
     	} else {
     		exit('Error: Headers already sent out!');
     	}
 	}

  	public function addFilesInZip($path, $zip) {
	    if (is_dir($path)) {
	      	if ($dh = opendir($path)) {

         		$dirs = scandir($path);
				foreach ($dirs as $file) {
					// If file
					if($file != '' && $file != '.' && $file != '..'){
					 	if (is_file($path.$file)) {
							$zip->addFile($path.$file, basename($file));
					 	} else if (is_dir($path.$file)) {

					      // Add empty directory
					      $zip->addEmptyDir($path.$file);

					      $folder = $path.$file.'/';
					      // Read data of the folder
					      $this->addFilesInZip($folder, $zip);
					   }

					}
				}

	         closedir($dh);
	       }
	    }
  	}

  	public function pdf() {
		$this->load->model('extension/ciformbuilder/page_form');

		$this->load->model('extension/ciformbuilder/page_request');

		$this->load->model('setting/setting');

		$this->load->model('setting/store');

		$this->load->model('tool/upload');

		$this->load->model('tool/image');

		$this->load->model('localisation/language');

  		$this->load->language('extension/ciformbuilder/page_request_pdf');

  		$has_request = false;

  		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_page_detail'] = $this->language->get('text_page_detail');
		$data['text_customer_detail'] = $this->language->get('text_customer_detail');
		$data['text_store'] = $this->language->get('text_store');
		$data['text_date_added'] = $this->language->get('text_date_added');
		$data['text_customer'] = $this->language->get('text_customer');
		$data['text_customer_group'] = $this->language->get('text_customer_group');
		$data['text_ip'] = $this->language->get('text_ip');
		$data['text_user_agent'] = $this->language->get('text_user_agent');
		$data['text_page_form_title'] = $this->language->get('text_page_form_title');
		$data['text_language_name'] = $this->language->get('text_language_name');
		$data['text_fields'] = $this->language->get('text_fields');
		$data['text_field_name'] = $this->language->get('text_field_name');
		$data['text_field_value'] = $this->language->get('text_field_value');

		if ($this->request->server['HTTPS']) {
			$data['base'] = HTTPS_SERVER;
		} else {
			$data['base'] = HTTP_SERVER;
		}

		$data['store_url'] = $this->request->server['HTTPS'] ? HTTPS_CATALOG : HTTP_CATALOG;

		$data['direction'] = $this->language->get('direction');
		$data['lang'] = $this->language->get('code');

		$data['page_requests'] = array();

		$page_requests = array();

		if (isset($this->request->post['selected'])) {
			$page_requests = $this->request->post['selected'];
		} elseif (isset($this->request->get['page_request_id'])) {
			$page_requests[] = $this->request->get['page_request_id'];
		}

		/* TEMP STARTS */
		if(!$page_requests) {
		$this->request->get['page_request_id'] = 37;
		$page_requests[] = $this->request->get['page_request_id'];

		$this->request->get['page_request_id'] = 38;
		$page_requests[] = $this->request->get['page_request_id'];
		}
		/* TEMP ENDS */

		$data['forms_submissions'] = [];
		foreach ($page_requests as $page_request_id) {
			$page_request_info = $this->model_extension_ciformbuilder_page_request->getPageRequest($page_request_id);

			if ($page_request_info) {
				$page_form_info = $this->model_extension_ciformbuilder_page_request->getPageFormDescription($page_request_info['page_form_id']);

				$info = [];
				$store_info = $this->model_setting_store->getStore($page_request_info['store_id']);
				if($store_info) {
					$info['store_name'] = $store_info['name'];
				} else{
					$info['store_name'] = $this->language->get('text_default');
				}

				$language_info = $this->model_localisation_language->getLanguage($page_request_info['language_id']);
				if($language_info) {
					$info['language_name'] = $language_info['name'];
				} else{
					$info['language_name'] = '';
				}

				$info['date_added'] = date($this->language->get('datetime_format'), strtotime($page_request_info['date_added']));

				$info['page_form_title'] = $page_request_info['page_form_title'];
				$info['ip'] = $page_request_info['ip'];
				$info['user_agent'] = $page_request_info['user_agent'];
				$info['firstname'] = $page_request_info['firstname'];
				$info['lastname'] = $page_request_info['lastname'];

				if ($page_request_info['customer_id']) {
					$info['customer'] = $this->url->link('customer/customer/edit', $this->module_token .'=' . $this->ci_token . '&customer_id=' . $page_request_info['customer_id'], true);
				} else {
					$info['customer'] = '';
				}

				if(VERSION > '2.0.3.1') {
					$this->load->model('customer/customer_group');
					$customer_group_info = $this->model_customer_customer_group->getCustomerGroup($page_request_info['customer_group_id']);
				} else{
					$this->load->model('sale/customer_group');
					$customer_group_info = $this->model_sale_customer_group->getCustomerGroup($page_request_info['customer_group_id']);
				}

				if ($customer_group_info) {
					$info['customer_group'] = $customer_group_info['name'];
				} else {
					$info['customer_group'] = '';
				}

				if (!empty($page_form_info) && is_file(DIR_IMAGE . $page_form_info['logo'])) {
					$info['thumb_logo'] = $data['store_url'] . 'image/' . $page_form_info['logo'];
				} else {
					$info['thumb_logo'] = '';
				}

				// Uploaded files
				$page_request_options = $this->model_extension_ciformbuilder_page_request->getPageRequestOptions($page_request_id);

				$info['page_request_options'] = array();
				foreach($page_request_options as $page_request_option) {
					if($page_request_option['type'] == 'password' || $page_request_option['type'] == 'confirm_password') {
						$page_request_option['value'] = unserialize(base64_decode($page_request_option['value']));
					}

					if ($page_request_option['type'] != 'file') {
						$info['page_request_options'][] = array(
							'name'		=> $page_request_option['name'],
							'value'		=> nl2br($page_request_option['value']),
							'type'		=> $page_request_option['type'],
						);
					} else{
						$file_array = explode(',', $page_request_option['value']);
						$value_file = [];
						foreach($file_array as $file_val) {
							$upload_info = $this->model_tool_upload->getUploadByCode($file_val);
							if ($upload_info) {
								$pathinfo_info = pathinfo($upload_info['name']);
								if(in_array($pathinfo_info['extension'], array('jpg', 'jpeg', 'jpe', 'png', 'bmp', 'gif', 'tif'))) {
									$view_image_button = true;
									$view_image_src = $data['store_url'] .'storage/upload/'. $upload_info['filename'];
								} else {
									$view_image_button = false;
									$view_image_src = '';
								}

								$value_file[] = [
									'filename' 	=> $upload_info['name'],
									'href' 		=> $this->url->link('tool/upload/download', $this->module_token .'=' . $this->ci_token . '&code=' . $upload_info['code'], true),
									'view_image_button' 		=> $view_image_button,
									'view_image_src' 			=> $view_image_src,
								];
							}
						}

						if($value_file) {
							$info['page_request_options'][] = array(
								'name'  => $page_request_option['name'],
								'value' => $value_file,
								'type'  => $page_request_option['type'],
							);
						}
					}
				}

				$data['forms_submissions'][] = $info;

				$has_request = true;
			}
		}

		if($has_request) {
			/* TEMP STARTS */
			/*
			$file_variable = 'template_engine';
			$file_type = 'template';
			$this->config->set($file_variable, $file_type);
			$this->response->setOutput($this->load->view('extension/ciformbuilder/page_request_pdf', $data));
			/* TEMP Ends */
			/* */
			// Instantiate and use the dompdf class
			$pdfOptions = new \Dompdf\Options();
	    	$pdfOptions->set('defaultFont', 'Arial');
			$pdfOptions->setIsHtml5ParserEnabled(true);
			$pdfOptions->setIsPhpEnabled(true);
			$pdfOptions->setIsRemoteEnabled(true);

	    	// Instantiate Dompdf with our options
	    	$dompdf = new Dompdf();
	        $dompdf->setOptions($pdfOptions);

			// $dompdf->set_option('fontHeightRatio', 2.0);

			// (Optional) Setup the paper size and orientation
			$dompdf->setPaper('A4', 'landscape');

			// $dompdf->setBasePath(HTTP_SERVER . 'view/stylesheet/stylesheet.css');
			// $dompdf->set_base_path($data['store_url'] . 'admin/view/stylesheet/');

			// $canvas = $dompdf->get_canvas();

			if(VERSION <= '2.3.0.2') {
				$dompdf->loadHtml($this->load->view('extension/ciformbuilder/page_request_pdf.tpl', $data));
			} else {
				$this->config->set('template_engine', 'template');
				$dompdf->loadHtml($this->load->view('extension/ciformbuilder/page_request_pdf', $data));
			}

			// Render the HTML as PDF
			$dompdf->render();

			// Output the generated PDF to Browser
			$dompdf->stream("form_submission.pdf", array("Attachment" => false));
		/* */
		} else {
			return new Action('error/not_found');
		}

  	}

  	public function history() {
		$this->load->language('extension/ciformbuilder/page_request');

		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_status'] = $this->language->get('column_status');

		$data['text_latest'] = $this->language->get('text_latest');
		$data['text_no_results'] = $this->language->get('text_no_results');

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['histories'] = array();

		$this->load->model('extension/ciformbuilder/page_request');

		$results = $this->model_extension_ciformbuilder_page_request->getHistories($this->request->get['page_request_id'], ($page - 1) * 10, 10);
		
		foreach ($results as $result) {
			$data['histories'][] = array(
				'status'     => $result['status'],
				'bgcolor' => $result['bgcolor'],
				'textcolor' => $result['textcolor'],
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
			);
		}

		$history_total = $this->model_extension_ciformbuilder_page_request->getTotalHistories($this->request->get['page_request_id']);

		$pagination = new Pagination();
		$pagination->total = $history_total;
		$pagination->page = $page;
		$pagination->limit = 10;
		$pagination->url = $this->url->link('extension/ciformbuilder/page_request/history', $this->module_token .'=' . $this->ci_token . '&page_request_id=' . $this->request->get['page_request_id'] . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($history_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($history_total - 10)) ? $history_total : ((($page - 1) * 10) + 10), $history_total, ceil($history_total / 10));

		if(VERSION <= '2.3.0.2') {
			$this->response->setOutput($this->load->view('extension/ciformbuilder/form_status_history.tpl', $data));
		} else {
			$file_variable = 'template_engine';
			$file_type = 'template';
			$this->config->set($file_variable, $file_type);
			$this->response->setOutput($this->load->view('extension/ciformbuilder/form_status_history', $data));
		}
	}

	public function addPageRequestHistory() {
		$this->load->language('extension/ciformbuilder/page_request');

		$this->load->model('extension/ciformbuilder/page_request');
		$this->load->model('extension/ciformbuilder/page_form');
		$this->load->model('extension/ciformbuilder/form_status');

		$json = array();

		if (isset($this->request->get['id'])){
			$id = $this->request->get['id'];
		} else {
			$id = 0;
		}

		if (isset($this->request->post['form_status_id'])){
			$form_status_id = $this->request->post['form_status_id'];
		} else {
			$form_status_id = 0;
		}

		if (isset($this->request->post['notify'])){
			$notify = $this->request->post['notify'];
		} else {
			$notify = 0;
		}

		if (!$this->user->hasPermission('modify', 'extension/ciformbuilder/page_request')) {
			$json['error'] = $this->language->get('error_permission');
		}

		$request_info = $this->model_extension_ciformbuilder_page_request->getPageRequest($id);

		if (!$json) {
			if ($form_status_id && $request_info) {
				$this->model_extension_ciformbuilder_page_request->addPageRequestHistory($request_info, $form_status_id, $notify);

				$page_form = $this->model_extension_ciformbuilder_page_form->getPageForm($request_info['page_form_id']);

				if($page_form) {
					$form_status_info = $this->model_extension_ciformbuilder_form_status->getFormStatus($form_status_id);
					$json['form_status'] = $form_status_info['name'];
					$json['bgcolor'] = isset($form_status_info['bgcolor']) ? $form_status_info['bgcolor'] : '';
					$json['textcolor'] = isset($form_status_info['textcolor']) ? $form_status_info['textcolor'] : '';

					$json['sent_date'] = date('d/m/Y',strtotime($request_info['date_modified']));

					$json['success'] = $this->language->get('text_success');
				} else {
					$json['error'] = $this->language->get('error_form_not_found');
				}
			} else {
				$json['error'] = $this->language->get('error_request_not_found');
			}
		}

		$this->response->addHeader('Content-Type: application/json');

		$this->response->setOutput(json_encode($json));

	}
}