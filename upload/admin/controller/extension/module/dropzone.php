<?php
class ControllerExtensionModuleDropzone extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/dropzone');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('module_dropzone', $this->request->post);

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
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/dropzone', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/module/dropzone', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

		if (isset($this->request->post['module_dropzone_status'])) {
			$data['module_dropzone_status'] = $this->request->post['module_dropzone_status'];
		} else {
			$data['module_dropzone_status'] = $this->config->get('module_dropzone_status');
		}

		if (isset($this->request->post['module_dropzone_max'])) {
			$data['module_dropzone_max'] = $this->request->post['module_dropzone_max'];
		} elseif ($this->config->get('module_dropzone_max')) {
			$data['module_dropzone_max'] = $this->config->get('module_dropzone_max');
		} else {
			$data['module_dropzone_max'] = 1000000;
		}

		$data['view'] = 'settings';

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/dropzone', $data));
	}

	public function upload() {
		$this->load->language('common/filemanager');

		$json = array();

		// Check user has permission
		if (!$this->user->hasPermission('modify', 'common/filemanager')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!empty($this->request->get['folder_name'])) {
			$folder = $this->request->get['folder_name'];
		} elseif (!empty($this->request->get['product_id'])) {
			$folder = $this->request->get['product_id'];
		} else {
			$folder = $this->session->data['temp_folder'];
		}

		$directory = DIR_IMAGE . 'catalog/products/';

		if (!is_dir($directory)) {
			mkdir($directory, 0777);
			chmod($directory, 0777);
		}

		$folder = preg_replace('/[^A-Za-z0-9_\-]/', '_', $folder);

		if ($folder) {
			$directory = DIR_IMAGE . 'catalog/products/' . $folder . '/';

			if (!is_dir($directory)) {
				mkdir($directory, 0777);
				chmod($directory, 0777);
			}
		}

		// Check its a directory
		if (!is_dir($directory) || substr(str_replace('\\', '/', realpath($directory)), 0, strlen(DIR_IMAGE . 'catalog')) != str_replace('\\', '/', DIR_IMAGE . 'catalog')) {
			$json['error'] = $this->language->get('error_directory');
		}

		if (!$json) {
			if (!empty($this->request->files['file'])) {
				$file = $this->request->files['file'];

				if (!empty($file['name']) && !empty($file['type']) && !empty($file['tmp_name']) && empty($file['error']) && !empty($file['size'])) {
					if (is_file($file['tmp_name'])) {
						// Sanitize the filename
						$filename = basename(html_entity_decode($file['name'], ENT_QUOTES, 'UTF-8'));

						// Validate the filename length
						if ((utf8_strlen($filename) < 3) || (utf8_strlen($filename) > 255)) {
							$json['error'] = $this->language->get('error_filename');
						}

						// Max file size
						$max_size = $this->config->get('module_dropzone_max');

						if ($file['size'] > $max_size) {
							$json['error'] = $this->language->get('error_filesize');
						}

						// Allowed file extension types
						$allowed = array(
							'jpg',
							'jpeg',
							'gif',
							'png'
						);

						if (!in_array(utf8_strtolower(utf8_substr(strrchr($filename, '.'), 1)), $allowed)) {
							$json['error'] = $this->language->get('error_filetype');
						}

						// Allowed file mime types
						$allowed = array(
							'image/jpeg',
							'image/pjpeg',
							'image/png',
							'image/x-png',
							'image/gif'
						);

						if (!in_array($file['type'], $allowed)) {
							$json['error'] = $this->language->get('error_filetype');
						}

						// Return any upload error
						if ($file['error'] != UPLOAD_ERR_OK) {
							$json['error'] = $this->language->get('error_upload_' . $file['error']);
						}
					} else {
						$json['error'] = $this->language->get('error_upload');
					}
				} else {
					$json['error'] = $this->language->get('error_upload');
				}

				if (!$json) {
					if (move_uploaded_file($file['tmp_name'], $directory . $filename)) {
						$json['image'] = utf8_substr($directory . $filename, utf8_strlen(DIR_IMAGE));

						$json['success'] = $this->language->get('text_uploaded');
					} else {
						$json['error'] = $this->language->get('error_upload');
					}
				}
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function productForm($route = '', $data = array(), &$output = '') {
		if ($this->config->get('module_dropzone_status')) {
			$this->load->language('extension/module/dropzone');

			$max_size = $this->config->get('module_dropzone_max');
			$max_size = $max_size / 1024;
			$max_size = $max_size / 1024;
			$max_size = max($max_size, 0);

			$data['max_size'] = round($max_size, 2);

			$data['view'] = 'product_form';

			$output = str_replace('</body>', $this->load->view('extension/module/dropzone', $data) . '</body>', $output);
		}
	}

	public function beforeAddProduct($route = '', $data = array(), $output = '') {
		if ($this->config->get('module_dropzone_status')) {
			if (!isset($this->session->data['temp_folder'])) {
				$this->session->data['temp_folder'] = token(8);
			}
		}
	}

	public function afterAddProduct($route = '', $data = array(), $product_id = 0) {
		if ($this->config->get('module_dropzone_status')) {
			if (isset($this->session->data['temp_folder'])) {
				$folder = $this->session->data['temp_folder'];

				$directory = DIR_IMAGE . 'catalog/products/' . $folder;

				if (is_dir($directory)) {
					$this->load->model('extension/module/dropzone');

					$this->model_extension_module_dropzone->updateImages($product_id, $folder);

					rename($directory, str_replace($folder, $product_id, $directory));
				}

				unset($this->session->data['temp_folder']);
			}
		}
	}

	public function install() {
		$directory = DIR_IMAGE . 'catalog/products/';

		if (!is_dir($directory)) {
			mkdir($directory, 0777);
			chmod($directory, 0777);
		}

		$this->load->model('setting/event');

		$this->model_setting_event->deleteEventByCode('dropzone');

		$this->model_setting_event->addEvent('dropzone', 'admin/controller/catalog/product/add/before', 'extension/module/dropzone/beforeAddProduct');

		$this->model_setting_event->addEvent('dropzone', 'admin/model/catalog/product/addProduct/after', 'extension/module/dropzone/afterAddProduct');

		$this->model_setting_event->addEvent('dropzone', 'admin/view/catalog/product_form/after', 'extension/module/dropzone/productForm');
	}

	public function uninstall() {
		$this->load->model('setting/event');

		$this->model_setting_event->deleteEventByCode('dropzone');
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/dropzone')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}