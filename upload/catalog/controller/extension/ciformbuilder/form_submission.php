<?php

class ControllerExtensionCiformbuilderFormsubmission extends Controller {
	private $error = array();

	public function index() {
		if(!$this->config->get('module_ciformbuilder_setting_status') || !$this->config->get('module_ciformbuilder_setting_customer_record')) {
			return new Action('error/not_found');
		}

		if (!$this->customer->isLogged()) {
			$this->response->redirect($this->url->link('account/login', '', true));
		}
		$this->load->language('extension/ciformbuilder/form_submission');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/ciformbuilder/form_submission');

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['filter_page_form_title'])) {
			$filter_page_form_title = $this->request->get['filter_page_form_title'];
		} else {
			$filter_page_form_title = '';
		}
	
		if (isset($this->request->get['filter_date_added'])) {
			$filter_date_added = $this->request->get['filter_date_added'];
		} else {
			$filter_date_added = '';
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

		if (isset($this->request->get['filter_page_form_status'])) {
			$url .= '&filter_page_form_status=' . $this->request->get['filter_page_form_status'];
		}

		if (isset($this->request->get['filter_page_form_title'])) {
			$url .= '&filter_page_form_title=' . urlencode(html_entity_decode($this->request->get['filter_page_form_title'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . urlencode(html_entity_decode($this->request->get['filter_date_added'], ENT_QUOTES, 'UTF-8'));
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home', '', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', '', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/ciformbuilder/form_submission', '', true)
		);

		$data['page_requests'] = array();

		$filter_data = array(
			'filter_page_form_title'  	=> $filter_page_form_title,
			'filter_page_form_status'  	=> $filter_page_form_status,
			'filter_date_added'  		=> $filter_date_added,
			'sort'  					=> $sort,
			'order' 					=> $order,
			'start' 					=> ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' 					=> $this->config->get('config_limit_admin')
		);

		$page_request_total = $this->model_extension_ciformbuilder_form_submission->getTotalPageRequests($filter_data);

		$results = $this->model_extension_ciformbuilder_form_submission->getPageRequests($filter_data);

		$this->load->model('setting/store');
		foreach ($results as $result) {
			$form_status_info = $this->model_extension_ciformbuilder_form_submission->getFormStatus($result['form_status_id']);
			$data['page_requests'][] = array(
				'page_request_id' 	=> $result['page_request_id'],
				'page_form_title' 	=> $result['page_form_title'],
				'form_status'       => $result['form_status'],
				'form_status_bgcolor'       => isset($form_status_info['bgcolor']) ? $form_status_info['bgcolor'] : '',
				'form_status_textcolor'       => isset($form_status_info['textcolor']) ? $form_status_info['textcolor'] : '',
				'date_added'        => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'view'           	=> $this->url->link('extension/ciformbuilder/form_submission/info', '' . '&page_request_id=' . $result['page_request_id'] . $url, true),
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['entry_page_form_title'] = $this->language->get('entry_page_form_title');
		$data['entry_customer'] = $this->language->get('entry_customer');
		$data['entry_ip'] = $this->language->get('entry_ip');
		$data['entry_date_added'] = $this->language->get('entry_date_added');
		$data['entry_date_start'] = $this->language->get('entry_date_start');
		$data['entry_date_end'] = $this->language->get('entry_date_end');
		$data['entry_status'] = $this->language->get('entry_status');

		// Added Product Detail Column start
		$data['column_product_name'] = $this->language->get('column_product_name');
		$data['button_product_info'] = $this->language->get('button_product_info');
		// Added Product Detail Column end

		$data['button_filter'] = $this->language->get('button_filter');

		$data['download_pdf'] = $this->url->link('extension/ciformbuilder/form_submission/pdf', '', true);

		$data['column_title'] = $this->language->get('column_title');
		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_action'] = $this->language->get('column_action');

		$data['button_view'] = $this->language->get('button_view');

		$url = '';

		if (isset($this->request->get['filter_page_form_status'])) {
			$url .= '&filter_page_form_status=' . $this->request->get['filter_page_form_status'];
		}

		if (isset($this->request->get['filter_page_form_title'])) {
			$url .= '&filter_page_form_title=' . urlencode(html_entity_decode($this->request->get['filter_page_form_title'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . urlencode(html_entity_decode($this->request->get['filter_date_added'], ENT_QUOTES, 'UTF-8'));
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_title'] = $this->url->link('extension/ciformbuilder/form_submission', '' . '&sort=pg.page_form_title' . $url, true);
		$data['sort_customer'] = $this->url->link('extension/ciformbuilder/form_submission', '' . '&sort=customer' . $url, true);
		$data['sort_ip'] = $this->url->link('extension/ciformbuilder/form_submission', '' . '&sort=pg.ip' . $url, true);
		$data['sort_date_added'] = $this->url->link('extension/ciformbuilder/form_submission', '' . '&sort=pg.date_added' . $url, true);
		$data['sort_form_status'] = $this->url->link('extension/ciformbuilder/form_submission', '' . '&sort=form_status' . $url, true);

		$url = '';

		if (isset($this->request->get['filter_page_form_status'])) {
			$url .= '&filter_page_form_status=' . $this->request->get['filter_page_form_status'];
		}

		if (isset($this->request->get['filter_page_form_title'])) {
			$url .= '&filter_page_form_title=' . urlencode(html_entity_decode($this->request->get['filter_page_form_title'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . urlencode(html_entity_decode($this->request->get['filter_date_added'], ENT_QUOTES, 'UTF-8'));
		}

	
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$this->load->model('extension/ciformbuilder/form_submission');

		$data['form_statuses'] = $this->model_extension_ciformbuilder_form_submission->getFormStatuses();

		$pagination = new Pagination();
		$pagination->total = $page_request_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('extension/ciformbuilder/form_submission', '' . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($page_request_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($page_request_total - $this->config->get('config_limit_admin'))) ? $page_request_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $page_request_total, ceil($page_request_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;
		$data['filter_page_form_title'] = $filter_page_form_title;
		$data['filter_page_form_status'] = $filter_page_form_status;
		$data['filter_date_added'] = $filter_date_added;

		if(VERSION > '2.0.3.1') {
			$data['customer_action'] = str_replace('&amp;', '&', $this->url->link('customer/customer', '', true));
		} else{
			$data['customer_action'] = str_replace('&amp;', '&', $this->url->link('sale/customer', '', true));
		}

		$data['config_submission_status'] = $this->config->get('module_ciformbuilder_setting_submission_status');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if(VERSION < '2.2.0.0') {
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extension/ciformbuilder/page_oc2/form_submission_list.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/extension/ciformbuilder/page_oc2/form_submission_list.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('default/template/extension/ciformbuilder/page_oc2/form_submission_list.tpl', $data));
			}
		} else if(VERSION <= '2.3.0.2') {
			$this->response->setOutput($this->load->view('extension/ciformbuilder/page_oc2/form_submission_list', $data));
		} else {
			$this->response->setOutput($this->load->view('extension/ciformbuilder/page_oc3/form_submission_list', $data));
		}
	}

	public function info() {
		if(!$this->config->get('module_ciformbuilder_setting_status') || !$this->config->get('module_ciformbuilder_setting_customer_record')) {
			return new Action('error/not_found');
		}

		if (!$this->customer->isLogged()) {
			$this->response->redirect($this->url->link('account/login', '', true));
		}

		$this->load->model('extension/ciformbuilder/form');

		$this->load->model('extension/ciformbuilder/form_submission');

		$this->load->model('setting/store');

		$this->load->model('localisation/language');

		if (isset($this->request->get['page_request_id'])) {
			$page_request_id = $this->request->get['page_request_id'];
		} else {
			$page_request_id = 0;
		}

		$page_request_info = $this->model_extension_ciformbuilder_form_submission->getPageRequest($page_request_id);

		if ($page_request_info) {
			$this->load->language('extension/ciformbuilder/form_submission');

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

			// Added Product Detail column start
			$data['text_product_id'] = $this->language->get('text_product_id');

			$data['text_product_name'] = $this->language->get('text_product_name');

			$data['text_product_model'] = $this->language->get('text_product_model');

			$data['text_product_detail'] = $this->language->get('text_product_detail');
			// Added Product Detail column end

			$data['button_back'] = $this->language->get('button_back');

			$data['button_view_image'] = $this->language->get('button_view_image');
			$data['button_file_download'] = $this->language->get('button_file_download');
			$data['button_download_all'] = $this->language->get('button_download_all');

			$url = '';

			if (isset($this->request->get['filter_page_request_id'])) {
				$url .= '&filter_page_request_id=' . $this->request->get['filter_page_request_id'];
			}

			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/dashboard', '', true)
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_account'),
				'href' => $this->url->link('account/account', '', true)
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/ciformbuilder/form_submission', '', true)
			);

			$data['back'] = $this->url->link('extension/ciformbuilder/form_submission', '', true);

			// $store_info = $this->model_setting_store->getStore($page_request_info['store_id']);
			// if($store_info) {
			// 	$data['store_name'] = $store_info['name'];
			// } else{
			// 	$data['store_name'] = $this->language->get('text_default');
			// }

			$data['store_name'] = $this->language->get('text_default');

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

			// Added Product Detail Column start
			$data['product_id'] = $page_request_info['product_id'];
			$data['product_name'] = $page_request_info['product_name'];
			$this->load->model('catalog/product');

			$product_info = $this->model_catalog_product->getProduct($page_request_info['product_id']);
			if($product_info){
				$data['product_model'] = $product_info['model'];
			}
			// Added Product Detail Column end
			
			$data['product_link'] = $this->url->link('product/product', 'product_id='. $page_request_info['product_id'], true);

			if ($page_request_info['customer_id']) {
				$data['customer'] = $this->url->link('account/account', ''. true);
			} else {
				$data['customer'] = '';
			}

			if ($page_request_info['page_form_id']) {
				$data['page_form_href'] = $this->url->link('extension/ciformbuilder/form', '' . '&page_form_id=' . $page_request_info['page_form_id'], true);
			} else {
				$data['page_form_href'] = '';
			}

			$data['store_url'] = $this->request->server['HTTPS'] ? HTTPS_SERVER : HTTP_SERVER;

			$this->load->model('account/customer_group');
				$customer_group_info = $this->model_account_customer_group->getCustomerGroup($page_request_info['customer_group_id']);

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

			$page_request_options = $this->model_extension_ciformbuilder_form_submission->getPageRequestOptions($page_request_id);
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
								'href' 		=> $this->url->link('extension/ciformbuilder/form/download', '' . '&code=' . $upload_info['code'], true),
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

			$data['config_submission_status'] = $this->config->get('module_ciformbuilder_setting_submission_status');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			if(VERSION < '2.2.0.0') {
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extension/ciformbuilder/page_oc2/form_submission_info.tpl')) {
					$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/extension/ciformbuilder/page_oc2/form_submission_info.tpl', $data));
				} else {
					$this->response->setOutput($this->load->view('default/template/extension/ciformbuilder/page_oc2/form_submission_info.tpl', $data));
				}
			} else if(VERSION <= '2.3.0.2') {
				$this->response->setOutput($this->load->view('extension/ciformbuilder/page_oc2/form_submission_info', $data));
			} else {
				$this->response->setOutput($this->load->view('extension/ciformbuilder/page_oc3/form_submission_info', $data));
			}
		} else {
			return new Action('error/not_found');
		}
	}

	public function downloadall() {
		$this->load->model('tool/upload');

		$this->load->model('extension/ciformbuilder/form_submission');

		$page_request_id = $this->request->get['page_request_id'];

		$page_request_options = $this->model_extension_ciformbuilder_form_submission->getPageRequestOptions($page_request_id);

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
	    	@unlink ($save_zip_file);
	  	}

		if ($zip->open($save_zip_file, ZIPARCHIVE::CREATE) != TRUE) {
			die ("Could not open archive");
		}

		// Add Folder'Files into Zip
	  	$this->addFilesInZip($submission, $zip);

	  	// Close Zip
	  	$zip->close();

	  	// Generate Download Link
	  	$json['download_link'] = str_replace('&amp;', '&', $this->url->link('extension/ciformbuilder/form_submission/downloadFile', 'filename='. $save_zip_file, true));

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

  	public function history() {
		$this->load->language('extension/ciformbuilder/form_submission');

		$data['text_history'] = $this->language->get('text_history');
		$data['text_latest'] = $this->language->get('text_latest');
		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_status'] = $this->language->get('column_status');

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['histories'] = array();

		$this->load->model('extension/ciformbuilder/form_submission');

		$results = $this->model_extension_ciformbuilder_form_submission->getHistories($this->request->get['page_request_id'], ($page - 1) * 10, 10);

		foreach ($results as $result) {
			$data['histories'][] = array(
				'status'     => $result['status'],
				'bgcolor'     => $result['bgcolor'],
				'textcolor'     => $result['textcolor'],
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
			);
		}

		$history_total = $this->model_extension_ciformbuilder_form_submission->getTotalHistories($this->request->get['page_request_id']);

		$pagination = new Pagination();
		$pagination->total = $history_total;
		$pagination->page = $page;
		$pagination->limit = 10;
		$pagination->url = $this->url->link('extension/ciformbuilder/form_submission/history', 'page_request_id=' . $this->request->get['page_request_id'] . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($history_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($history_total - 10)) ? $history_total : ((($page - 1) * 10) + 10), $history_total, ceil($history_total / 10));

		if(VERSION < '2.2.0.0') {
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extension/ciformbuilder/page_oc2/form_status_history.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/extension/ciformbuilder/page_oc2/form_status_history.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('default/template/extension/ciformbuilder/page_oc2/form_status_history.tpl', $data));
			}
		} else if(VERSION <= '2.3.0.2') {
			$this->response->setOutput($this->load->view('extension/ciformbuilder/page_oc2/form_status_history', $data));
		} else {
			$this->response->setOutput($this->load->view('extension/ciformbuilder/page_oc3/form_status_history', $data));
		}
	}
}