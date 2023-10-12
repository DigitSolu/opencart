<?php
/*
	$Project: Advanced Filter $
	$Author: karapuz team <support@ka-station.com> $

	$Version: 1.0.1.2 $ ($Revision: 64 $)
*/

namespace extension\ka_extensions;

class ControllerKaAdvFilter extends \KaInstaller {

	protected $extension_version = '1.0.1.2';
	protected $min_store_version = '3.0.0.0';
	protected $max_store_version = '3.0.3.9';
	protected $min_ka_extensions_version = '4.1.0.13';
	protected $max_ka_extensions_version = '4.1.1.99';

	protected $ext_link  = 'https://www.ka-station.com/advanced-product-filter-for-opencart-3';
	
	protected $tables;

	protected function onLoad() {

		$this->load->language('extension/ka_extensions/ka_adv_filter/settings');
			
 		$this->tables = array(
 			'ka_price_cache' => array(
 				'is_new' => true,
 				'fields' => array(
 					'customer_group_id' => array(
 						'type' => 'int(11)',
 					), 				
 					'product_id' => array(
 						'type' => 'int(11)',
 					),
 					'price' => array(
 						'type' => 'decimal(15,4)',
 					),
 					'updated' => array(
 						'type' => 'tinyint(1)',
 					),
 					'geo_zone_id' => array(
 						'type' => 'int(11)',
 					),
 				),
 				'indexes' => array(
 					'product_id' => array(
 						'query' => 'ALTER TABLE `' . DB_PREFIX . 'ka_price_cache` ADD INDEX(`product_id`)'
 					)
 				)
 			),
 			'ka_category_price_range' => array(
 				'is_new' => true,
 				'fields' => array(
 					'category_id' => array(
 						'type' => 'int(11)',
 					),
 					'geo_zone_id' => array(
 						'type' => 'int(11)',
 					),
 					'customer_group_id' => array(
 						'type' => 'int(11)',
 					),
 					'min_price' => array(
 						'type' => 'decimal(15,4)',
 					),
 					'max_price' => array(
 						'type' => 'decimal(15,4)',
 					),
 					'updated' => array(
 						'type' => 'tinyint(1)',
 					),
 				)
			),			
 			'ka_ov_components' => array(
 				'is_new' => true,
 				'fields' => array(
 					'compound_option_value_id' => array(
 						'type' => 'int(11)',
 					),
 					'simple_option_value_id' => array(
 						'type' => 'int(11)',
 					),
 				)
 			),
 			'option' => array(
 				'fields' => array(
 					'linked_fg_id' => array(
 						'type' => 'int(11)',
 						'query' => "ALTER TABLE `" . DB_PREFIX . "option` ADD `linked_fg_id` INT(11) NOT NULL",
 					),
 				)
 			),
 			'filter' => array(
 				'fields' => array(
 					'image' => array(
 						'type' => 'varchar(255)',
 						'query' => "ALTER TABLE `" . DB_PREFIX . "filter` ADD `image` VARCHAR(255) NOT NULL",
 					),
 				)
 			),
 			'filter_group' => array(
 				'fields' => array(
 					'show_as_image' => array(
 						'type' => 'tinyint(1)',
 						'query' => "ALTER TABLE `" . DB_PREFIX . "filter_group` ADD `show_as_image` TINYINT(1) NOT NULL",
 					),
 				)
 			),
 			'product_option' => array(
 				'fields' => array(),
 				'indexes' => array(
 					'product_id' => array(
 						'query' => "ALTER TABLE `" . DB_PREFIX . "product_option` ADD INDEX(`product_id`)",
 					),
 				),
 			),
		);
		
		$this->tables['ka_price_cache']['query'] = "CREATE TABLE `" .DB_PREFIX . "ka_price_cache` ( 
			`customer_group_id` int(11) NOT NULL,  
			`product_id` int(11) NOT NULL,  
			`price` decimal(15,4) NOT NULL,  
			`updated` tinyint(1) NOT NULL,  
			`geo_zone_id` int(11) NOT NULL,  
			KEY `customer_group_id` (`customer_group_id`,`product_id`),
			KEY `product_id` (`product_id`)
		)";

		$this->tables['ka_category_price_range']['query'] = "CREATE TABLE `" . DB_PREFIX . "ka_category_price_range` (
			 `category_id` int(11) NOT NULL,  
			 `geo_zone_id` int(11) NOT NULL,  
			 `customer_group_id` int(11) NOT NULL,  
			 `min_price` decimal(15,4) NOT NULL,  
			 `max_price` decimal(15,4) NOT NULL,  
			 `updated` tinyint(1) NOT NULL,  
			 KEY `category_id` (`category_id`,`geo_zone_id`,`customer_group_id`)
		 )";

		$this->tables['ka_ov_components']['query'] = "CREATE TABLE `" .DB_PREFIX . "ka_ov_components` ( 
		  `compound_option_value_id` int(11) NOT NULL,
		  `simple_option_value_id` int(11) NOT NULL,
		  UNIQUE KEY `compound_option_value_id` (`compound_option_value_id`,`simple_option_value_id`)
		)";
		 
		return true;
	}


	public function getTitle() {
		$str = str_replace('{{version}}', $this->extension_version, $this->language->get('heading_title_ver'));
		return $str;
	}
	
	
	public function index() {
	
		$this->load->model('setting/setting');
		$model_price_search = $this->kamodel('ka_adv_filter/price_search');

		$heading_title = $this->getTitle();
		$this->document->setTitle($heading_title);

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

			if (!isset($this->request->post['ka_adv_filter_is_price_search_shown'])) {
				$this->request->post['ka_adv_filter_is_price_search_shown'] = '';
			}

			$this->model_setting_setting->editSetting('ka_adv_filter', $this->request->post);
			$this->addTopMessage($this->language->get('Settings have been stored sucessfully.'));
			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=ka_extensions', true));
		}			

		// settings
		//		
		$this->data['heading_title']         = $heading_title;
		$this->data['extension_version']     = $this->extension_version;
	
		$this->data['button_save']           = $this->language->get('button_save');
		$this->data['button_cancel']         = $this->language->get('button_cancel');

		$this->data['action'] = $this->url->link('extension/ka_extensions/ka_adv_filter', 'user_token=' . $this->session->data['user_token'], true);
		$this->data['cancel'] = $this->url->link('marketplace/extension', 'type=ka_extensions&user_token=' . $this->session->data['user_token'], true);

		$this->data['ka_adv_filter_is_price_search_shown'] = $this->config->get('ka_adv_filter_is_price_search_shown');
		$this->data['ka_adv_filter_price_range_look'] = $this->config->get('ka_adv_filter_price_range_look');
		$this->data['ka_adv_filter_price_intervals']  = $this->config->get('ka_adv_filter_price_intervals');
		$this->data['ka_adv_filter_price_intervals_layout'] = $this->config->get('ka_adv_filter_price_intervals_layout');
		$this->data['ka_adv_filter_price_step']       = $this->config->get('ka_adv_filter_price_step');
		
		$tax_errors = array();
		$intersection_details = array();
		
		if ($model_price_search->canTaxesApply($tax_errors)) {
			$this->data['taxes_can_apply'] = true;
			
			if ($this->config->get('config_tax')) {
				$this->data['zones_intersect'] = $model_price_search->taxZonesIntersect($intersection_details);
				$this->data['intersection_details'] = $intersection_details;
			}
		} else {
			$this->data['tax_errors'] = $tax_errors;
			$this->data['taxes_can_apply'] = false;
		}

		$last_rebuild = $this->config->get('ka_adv_filter_last_rebuild');
		if ($last_rebuild) {
			$this->data['last_rebuild'] = date($this->language->get('datetime_format'), $last_rebuild);
		} else {
			$this->data['last_rebuild'] = 'Never';
		}
		
		$this->data['ka_adv_filter_use_taxes'] = $this->config->get('ka_adv_filter_use_taxes');		
		
 		$this->data['breadcrumbs'] = array();
 		$this->data['breadcrumbs'][] = array(
	   		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true),
 		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=ka_extensions', true)
		);

	  	$this->data['breadcrumbs'][] = array(
   			'text'      => $heading_title,
			'href'      => '',
  		);
		
		$this->template = 'extension/ka_extensions/ka_adv_filter/settings';
		
		$this->children = array(
			'common/header',
			'common/column_left',			
			'common/footer'
		);

		$this->setOutput();
	}
	
	
	protected function validate() {
	
		if (!$this->user->hasPermission('modify', 'extension/ka_extensions/ka_adv_filter')) {
			$this->addTopMessage($this->language->get('error_permission'), 'E');
			return false;
		}

		return true;
	}


	public function install() {

		//install task scheduler
		if (!\KaGlobal::isKaInstalled('ka_scheduler', true)) {
			$this->addTopMessage('Task Scheduler extension has to be installed first', 'E');
			return false;
		}
	
		if (!parent::install()) {
			return false;
		}
		
		$this->kamodel('ka_scheduler/ka_tasks');
		$task = array(
			'name'        => 'Filter and Price Cache Rebuild',
			'module'      => 'filters',
			'period_type' => 'day',
			'active' => 'Y',
		);
		
		$this->kamodel_ka_scheduler_ka_tasks->installTask($task, true);
		
		// grant permissions to the import page automatically
		$this->load->model('user/user_group');
		
		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/ka_extensions/ka_adv_filter/category');
		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/ka_extensions/ka_adv_filter/category');

		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/ka_extensions/ka_adv_filter/filter');
		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/ka_extensions/ka_adv_filter/filter');

		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/ka_extensions/ka_adv_filter/product');
		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/ka_extensions/ka_adv_filter/product');

		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/ka_extensions/ka_adv_filter/price');
		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/ka_extensions/ka_adv_filter/price');
		
		$this->load->model('setting/setting');
		$settings = array(
			'ka_adv_filter_price_range_look'       => 'ruler',
			'ka_adv_filter_price_intervals'        => '',
			'ka_adv_filter_price_intervals_layout' => 'radio',
			'ka_adv_filter_price_step'             => '1',
			'ka_adv_filter_is_price_search_shown'  => '1',
			'ka_adv_filter_use_taxes'              => '',
		);
		$this->model_setting_setting->editSetting('ka_adv_filter', $settings);

		return true;
	}

	
	public function uninstall() {
		parent::uninstall();
		
		return true;
	}		
}

class_alias(__NAMESPACE__ . '\ControllerKaAdvFilter', 'ControllerExtensionKaExtensionsKaAdvFilter');