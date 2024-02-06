<?php
class ControllerExtensionModuleCiformbuilderSetting extends Controller {
	private $error = [];

	private $file_routes = [
		'extension/ciformbuilder/page_form',
		'extension/ciformbuilder/page_request',
		'extension/ciformbuilder/form_status',
		'extension/ciformbuilder/page_request_export_excel',
		'extension/ciformbuilder/page_request_export_spread',
		'extension/ciformbuilder/about',
	];

	private $code = 'ci_formbuilder';
	private $description = 'Form Builder - Codinginspect';
	private $status = 1;
	private $sort_order = 0;
	private $events = [
		'admin'	=> [
			[
				'trigger'	=> 'admin/view/common/column_left/before',
				'action'	=> 'extension/module/ciformbuilder_setting/createMainMenu',
			],
			[
				'trigger'	=> 'admin/view/common/header/after',
				'action'	=> 'extension/module/ciformbuilder_setting/addHeaderScript',
			],
		],
		'catalog'	=> [
			[
				'trigger'	=> 'catalog/controller/common/header/before',
				'action'	=> 'extension/module/ciformbuilder_setting/createHeaderScript',
			],
			[
				'trigger'	=> 'catalog/view/common/menu/before',
				'action'	=> 'extension/module/ciformbuilder_setting/createHeaderMenu',
			],
			[
				'trigger'	=> 'catalog/view/common/footer/before',
				'action'	=> 'extension/module/ciformbuilder_setting/createFooterMenu',
			],
			[
				'trigger'	=> 'catalog/view/information/information/before',
				'action'	=> 'extension/module/ciformbuilder_setting/createInformationForm',
			],
			[
				'trigger'	=> 'catalog/view/product/product/before',
				'action'	=> 'extension/module/ciformbuilder_setting/createProductForm',
			],
			[
				'trigger'	=> 'catalog/view/product/product/after',
				'action'	=> 'extension/module/ciformbuilder_setting/addProductForm',
			],
			[
				'trigger'	=> 'catalog/view/common/footer/after',
				'action'	=> 'extension/module/ciformbuilder_setting/addFooterLink',
			],
			[
				'trigger'	=> 'catalog/view/account/account/after',
				'action'	=> 'extension/module/ciformbuilder_setting/addAccountLink',
			],
		],
	];

	public function __construct($registery) {
		parent::__construct($registery);

		$this->load->model('extension/ciformbuilder/setting');

		if(VERSION <= '2.3.0.2') {
			$this->module_token = 'token';
			$this->ci_token = isset($this->session->data['token']) ? $this->session->data['token'] : '';

			$this->extension_path = 'extension/extension';
		} else {
			$this->module_token = 'user_token';
			$this->ci_token = isset($this->session->data['user_token']) ? $this->session->data['user_token'] : '';

			$this->extension_path = 'marketplace/extension';
		}

		/* Compatibility for oc 2.3x starts */
		if(VERSION <= '2.3.0.2') {
			foreach($this->events['catalog'] as $key => $value) {
				if(strpos($value['trigger'], 'common/menu') !== false) {
					$this->events['catalog'][$key]['trigger'] = str_replace('common/menu', 'common/header', $this->events['catalog'][$key]['trigger']);
				}

				$explode = explode('/', $value['trigger']);
				if(strpos($value['trigger'], 'catalog/view') !== false && end($explode) == 'after') {
					$this->events['catalog'][$key]['trigger'] = 'catalog/view/*/template/'. substr($value['trigger'], strlen('catalog/view/'));
				}
			}
		}
		/* Compatibility for oc 2.3x ends */
	}

	public function install() {
		$filter_data = [
			'events'		=> $this->events,
			'code'			=> $this->code,
			'description'	=> $this->description,
			'status'		=> $this->status,
			'sort_order'	=> $this->sort_order,
		];

		// Remove Events
		$this->model_extension_ciformbuilder_setting->removeEvents($filter_data);

		// Create Events
		$this->model_extension_ciformbuilder_setting->createEvents($filter_data);

		// Create Permission
		$this->model_extension_ciformbuilder_setting->cratePermissions($this->file_routes);

		// Create Tables
		$this->model_extension_ciformbuilder_setting->createTables();

		// Add Sample Data
		$this->model_extension_ciformbuilder_setting->addSampleData();
	}

	public function uninstall() {
		$filter_data = [
			'events'		=> $this->events,
			'code'			=> $this->code,
			'description'	=> $this->description,
			'status'		=> $this->status,
			'sort_order'	=> $this->sort_order,
		];

		$this->model_extension_ciformbuilder_setting->removeEvents($filter_data);
	}

	public function enableEvents() {
		$this->load->language('extension/module/ciformbuilder_setting');

		$json = [];

		if(!$this->config->get('module_ciformbuilder_setting_status')) {
			$json['warning'] = $this->language->get('error_permission');
		}

		if (!$this->user->hasPermission('modify', 'extension/module/ciformbuilder_setting')) {
			$json['warning'] = $this->language->get('error_permission');
		}

		if(!$json) {
			foreach ($this->events as $folder => $folder_info) {
				$this->model_extension_ciformbuilder_setting->enableEvents($this->code .'_'. $folder);
			}

			$this->session->data['success'] = $this->language->get('text_enable_event_success');

			$json['success'] = str_replace('&amp;', '&', $this->url->link('extension/module/ciformbuilder_setting', $this->module_token .'=' . $this->ci_token, true));
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function showevents() {
		echo "<pre>";
		if(isset($this->request->get['test']) && $this->request->get['test'] == 'db') {
			// Database Events
			foreach ($this->events as $folder => $folder_info) {
				if($folder_info) {
					$db_events = [];
					$db_events[$folder] = $this->model_extension_ciformbuilder_setting->getEventsByCode(['code'	=> $this->code .'_'. $folder]);
					echo "--- (". count($db_events[$folder]).") Database Event for ". $folder;
					echo "\n";
					print_r($db_events);
					echo "\n";
				}
			}
		} else {
			// Private Events
			foreach ($this->events as $folder => $folder_info) {
				if($folder_info) {
					$pr = [];
					foreach ($folder_info as $event) {
						$pr[$folder][] = [
							'event_id'	=> 0,
							'code'		=> $this->code .'_'. $folder,
							'trigger'	=> $event['trigger'],
							'action'	=> $event['action'],
							'status'	=> 1,
							'sort_order'=> 0,
						];
					}

					echo "--- (". count($pr[$folder]).") Privatee Event for ". $folder;
					echo "\n";
					print_r($pr);

					echo "\n";
				}
			}
		}

		echo "</pre>";
	}

	public function index() {
		$this->load->language('extension/module/ciformbuilder_setting');

		$this->document->setTitle($this->language->get('heading_title_page'));

		/* checking disabled events starts */
		$data['button_enable_event'] = $this->language->get('button_enable_event');
		$data['info_disabled_events'] = $this->language->get('info_disabled_events');

		$disabled_events = 0;
		if($this->config->get('module_ciformbuilder_setting_status') && count($this->events)) {
			foreach ($this->events as $folder => $folder_info) {
				$filter_data = [
					'code'			=> $this->code .'_'. $folder,
					'filter_status' => 0,
				];

				$disabled_events += $this->model_extension_ciformbuilder_setting->getTotalEvents($filter_data);
			}
		}

		if($disabled_events) {
			$data['action_enable_events'] = str_replace('&amp;', '&', $this->url->link('extension/module/ciformbuilder_setting/enableEvents', $this->module_token .'=' . $this->ci_token, true));
		} else {
			$data['action_enable_events'] = '';
		}
		/* checking disabled events ends */

		/* sync new events starts */
		$add_data = [
			'events'		=> $this->events,
			'code'			=> $this->code,
			'description'	=> $this->description,
			'status'		=> $this->status,
			'sort_order'	=> $this->sort_order,
		];

		$this->model_extension_ciformbuilder_setting->syncEvents($add_data);
		/* sync new events ends */

		$this->load->model('setting/setting');

		$data['buttons'] = $this->model_extension_ciformbuilder_setting->getButtons('ciformbuilder');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$data['buttons'] ? $this->request->post['module_ciformbuilder_setting_status'] = 0 : '';

			$this->model_setting_setting->editSetting('module_ciformbuilder_setting', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/module/ciformbuilder_setting', $this->module_token .'=' . $this->ci_token, true));
		}

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

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', $this->module_token .'=' . $this->ci_token, true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link($this->extension_path, $this->module_token .'=' . $this->ci_token . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title_page'),
			'href' => $this->url->link('extension/module/ciformbuilder_setting', $this->module_token .'=' . $this->ci_token, true)
		);

		$data['action'] = $this->url->link('extension/module/ciformbuilder_setting', $this->module_token .'=' . $this->ci_token, true);

		$data['cancel'] = $this->url->link($this->extension_path, $this->module_token .'=' . $this->ci_token . '&type=module', true);

		$data['page_form_href'] = str_replace('&amp;', '&', $this->url->link('extension/ciformbuilder/page_form', $this->module_token .'=' . $this->ci_token, true));

		$data['page_request_href'] = str_replace('&amp;', '&', $this->url->link('extension/ciformbuilder/page_request', $this->module_token .'=' . $this->ci_token, true));

		if($data['buttons']) {
			$buttons_links = $this->url->link(end($this->file_routes), $this->module_token .'=' . $this->ci_token, true);
		} else {
			$buttons_links = '';
		}

		if (isset($this->request->post['module_ciformbuilder_setting_status'])) {
			$data['module_ciformbuilder_setting_status'] = $this->request->post['module_ciformbuilder_setting_status'];
		} else {
			$data['module_ciformbuilder_setting_status'] = $this->config->get('module_ciformbuilder_setting_status');
		}

		if (isset($this->request->post['module_ciformbuilder_setting_submission_status'])) {
			$data['module_ciformbuilder_setting_submission_status'] = $this->request->post['module_ciformbuilder_setting_submission_status'];
		} else {
			$data['module_ciformbuilder_setting_submission_status'] = $this->config->get('module_ciformbuilder_setting_submission_status');
		}

		if (isset($this->request->post['module_ciformbuilder_setting_customer_record'])) {
			$data['module_ciformbuilder_setting_customer_record'] = $this->request->post['module_ciformbuilder_setting_customer_record'];
		} else {
			$data['module_ciformbuilder_setting_customer_record'] = $this->config->get('module_ciformbuilder_setting_customer_record');
		}

		$data['heading_title'] = $this->language->get('heading_title_page');

		$data['text_edit'] = $this->language->get('text_extension');
		$data['text_general'] = $this->language->get('text_general');
		$data['text_status'] = $this->language->get('text_status');

		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_submission_status'] = $this->language->get('entry_submission_status');
		$data['entry_customer_record'] = $this->language->get('entry_customer_record');

		$data['tab_general'] = $this->language->get('tab_general');
		$data['tab_status'] = $this->language->get('tab_status');
		$data['tab_support'] = $this->language->get('tab_support');

		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['button_page_form'] = $this->language->get('button_page_form');
		$data['button_page_request'] = $this->language->get('button_page_request');
		$data['button_save_stay'] = $this->language->get('button_save_stay');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_save_stay'] = $this->language->get('button_save_stay');

		if($buttons_links) {
			$data['buttons_links'] = $this->model_extension_ciformbuilder_setting->getLinks($buttons_links);
		} else {
			$data['buttons_links'] = [];
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		if(VERSION <= '2.3.0.2') {
			$this->response->setOutput($this->load->view('extension/module/ciformbuilder_setting.tpl', $data));
		} else {
			$file_variable = 'template_engine';
			$file_type = 'template';
			$this->config->set($file_variable, $file_type);
			$this->response->setOutput($this->load->view('extension/module/ciformbuilder_setting', $data));
		}
	}



	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/ciformbuilder_setting')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	// Trigger for admin/view/common/column_left/before
	public function createMainMenu(&$route, &$data, &$code) {
		if(VERSION >= '3.0.0.0') {
			$this->load->model('setting/extension');
			$installed_extensions_codes = $this->model_setting_extension->getInstalled('module');
		} else {
			$this->load->model('extension/extension');
			$installed_extensions_codes = $this->model_extension_extension->getInstalled('module');
		}

		$my_extension_code = 'ciformbuilder';

		if(in_array($my_extension_code, $installed_extensions_codes)) {
			$module_installed = true;
		} else {
			$module_installed = false;
		}

		$p = 3;
		$m = [];

		$formbuilder = [];

		$this->load->language('extension/ciformbuilder/page_form_menu');

		if ($this->user->hasPermission('access', 'extension/module/ciformbuilder_setting')) {
			if($module_installed) {
				$formbuilder[] = array(
					'name'	   => $this->language->get('text_formbuilder_setting'),
					'href'     => $this->url->link('extension/module/ciformbuilder_setting', $this->module_token .'=' . $this->ci_token, true),
					'children' => []
				);
			} else {
				$formbuilder[] = array(
					'name'	   => $this->language->get('text_formbuilder_setting'),
					'href'     => $this->url->link($this->extension_path, $this->module_token .'=' . $this->ci_token .'&type=module', true),
					'children' => []
				);
			}
		}

		if ($this->user->hasPermission('access', 'extension/ciformbuilder/page_form')) {
			$formbuilder[] = array(
				'name'	   => $this->language->get('text_page_form'),
				'href'     => $this->url->link('extension/ciformbuilder/page_form', $this->module_token .'=' . $this->ci_token, true),
				'children' => []
			);
		}

		if ($this->user->hasPermission('access', 'extension/ciformbuilder/form_status') && $this->config->get('module_ciformbuilder_setting_submission_status')) {
			$formbuilder[] = array(
				'name'	   => $this->language->get('text_form_status'),
				'href'     => $this->url->link('extension/ciformbuilder/form_status', $this->module_token .'=' . $this->ci_token, true),
				'children' => []
			);
		}

		if ($this->user->hasPermission('access', 'extension/ciformbuilder/page_request')) {
			$formbuilder[] = array(
				'name'	   => $this->language->get('text_page_request'),
				'href'     => $this->url->link('extension/ciformbuilder/page_request', $this->module_token .'=' . $this->ci_token, true),
				'children' => []
			);
		}

		if ($this->user->hasPermission('access', 'extension/module/ciformbuilder')) {
			$module_data = [];

			if(VERSION >= '3.0.0.0') {
				$this->load->model('setting/module');
				$formbuilder_modules = $this->model_setting_module->getModulesByCode('ciformbuilder');
			} else {
				$this->load->model('extension/module');
				$formbuilder_modules = $this->model_extension_module->getModulesByCode('ciformbuilder');
			}

        	foreach ($formbuilder_modules as $formbuilder_module) {
				$formbuilder[] = array(
					'name'     => $this->language->get('menu_ciformbuilder_module_child') . $formbuilder_module['name'],
					'href'     => $this->url->link('extension/module/ciformbuilder', $this->module_token .'=' . $this->ci_token . '&module_id='. $formbuilder_module['module_id'], true),
					'children' => []
          		);
        	}

    		$formbuilder[] = array(
	          	'name'     => $this->language->get('menu_ciformbuilder_module'),
	          	'href'     => (count($formbuilder_modules) >= 1) ? $this->url->link('extension/module/ciformbuilder', $this->module_token .'=' . $this->ci_token, true) : $this->url->link($this->extension_path, $this->module_token .'=' . $this->ci_token . '&type=module', true),
	          	'children' => []
	        );
      	}

      	if ($this->user->hasPermission('access', 'extension/ciformbuilder/about')) {
			$formbuilder[] = array(
				'name'	   => $this->language->get('text_ciformbuilder_about'),
				'href'     => $this->url->link('extension/ciformbuilder/about', $this->module_token .'=' . $this->ci_token, true),
				'children' => []
			);
		}

		if ($formbuilder) {
			$m = array(
				'id'			=> 'menu-ciformbuilder',
				'icon'			=> 'fa fa-file',
				'name'	   		=> $this->language->get('text_formbuilder'),
				'href'     		=> '',
				'children' 		=> $formbuilder
			);
		}

		if($m) {
			$data['menus'] = array_merge(array_slice($data['menus'], 0, $p), array($m), array_slice($data['menus'], $p));
		}
	}

	// Trigger for admin/view/common/header/after
	public function addHeaderScript(&$route, &$data, &$output) {
		$find = '<script type="text/javascript" src="view/javascript/bootstrap/js/bootstrap.min.js"></script>';

		$add_src = 'view/javascript/jquery/jquery-ui/jquery-ui.js';
		$add_string = '<script type="text/javascript" src="'. $add_src .'"></script>';

		if(utf8_strpos($output, $add_src) === false) {
			$output = str_replace($find, $add_string ."\n". $find, $output);
		}
	}

}