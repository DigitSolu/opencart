<?php
/*
	$Project: Task Scheduler $
	$Author: karapuz team <support@ka-station.com> $

	$Version: 3.0.3.8 $ ($Revision: 140 $)
*/

namespace extension\ka_extensions;

class ControllerKaScheduler extends \KaInstaller {

	protected $extension_version = '3.0.3.8';
	protected $min_store_version = '3.0.0.0';
	protected $max_store_version = '3.0.9.9';
	protected $min_ka_extensions_version = '4.1.0.0';
	protected $max_ka_extensions_version = '4.1.1.9';
	protected $tables;
	
	//temporary variables
	protected $error;
	
	public function getTitle() {

		$title = $this->language->get('extension_title');
		
		if ($this->kamodel_ka_scheduler_ka_tasks->isLite()) {
			$title = $title . ' Lite';
		}
		
		$title = $title . '(ver.' . $this->extension_version . ')';
		
		return $title;
	}

	
	protected function onLoad() {
		$this->load->language('extension/ka_extensions/ka_scheduler');

		$this->load->language('ka_extensions/ka_scheduler');
		$this->kamodel('ka_scheduler/ka_tasks');
		$this->load->model('setting/setting');
	
 		$this->tables = array(
 		
 			'ka_tasks' => array(
 				'is_new' => true,
 				'fields' => array(
  					'task_id' => array(
  						'type' => 'int(11)',
  					),
  					'name' => array(
  						'type' => 'varchar(128)',
  					),
  					'active' => array(
  						'type' => "enum('N','Y')",
  					),
  					'priority' => array(
  						'type' => 'int(11)',
  					),
  					'is_send_email' => array(
  						'type' => 'tinyint(1)',
  						'query' => 'ALTER TABLE `' . DB_PREFIX . 'ka_tasks` ADD `is_send_email` TINYINT(1) NOT NULL',
  					),  					
  					'module' => array(
  						'type' => 'varchar(255)',
  					),
  					'operation' => array(
  						'type' => 'varchar(128)',
  					),
  					'params' => array(
  						'type' => 'text',
  					),
  					'period_type' => array(
  						'type' => "enum('hour','day','week','month','year', 'every_n_minutes')",
  						'query_change' => "ALTER TABLE `" . DB_PREFIX . "ka_tasks` CHANGE `period_type` `period_type` ENUM( 'hour', 'day', 'week', 'month', 'year', 'every_n_minutes' ) DEFAULT 'day'"
  					),
  					'every_n_minutes' => array(
  						'type' => 'int(11)',
  						'query' => "ALTER TABLE `" . DB_PREFIX . "ka_tasks` ADD `every_n_minutes` INT(11) NOT NULL",
  					),
  					'period_at_min' => array(
  						'type' => 'int(4)',
  					),
  					'period_at_hour' => array(
  						'type' => 'int(4)',
  					),
  					'period_at_day' => array(
  						'type' => 'int(4)',
  					),
  					'period_at_dow' => array(
  						'type' => 'int(4)',
  					),
  					'period_at_month' => array(
  						'type' => 'int(4)',
  					),
  					'start_at' => array(
  						'type' => 'datetime',
  					),
  					'end_at' => array(
  						'type' => 'datetime',
  					),
  					'stat' => array(
  						'type' => 'mediumblob',
  					),
  					'complete_count' => array(
  						'type' => 'int(11)',
  					),
  					'run_status' => array(
  						'type' => "enum('not_started','working','not_finished')",
  					),
  					'first_run' => array(
  						'type' => 'datetime',
  					),
  					'last_run' => array(
  						'type' => 'datetime',
  					),
  					'fail_count' => array(
  						'type' => 'int(11)',
  					),
  					'run_count' => array(
  						'type' => 'int(11)',
  					),
  					'session_data' => array(
  						'type' => 'mediumblob',
  					),
  				),
				'indexes' => array(
					'PRIMARY' => array(
						'query' => "ALTER TABLE `" . DB_PREFIX . "ka_tasks` ADD PRIMARY KEY (`task_id`)",
					),
					'name' => array(
						'query' => "ALTER TABLE `" . DB_PREFIX . "ka_tasks` ADD INDEX (`name`)",
					),
					'last_run' => array(
						'query' => "ALTER TABLE `" . DB_PREFIX . "ka_tasks` ADD INDEX (`last_run`)",
					),
					'priority' => array(
						'query' => "ALTER TABLE `" . DB_PREFIX . "ka_tasks` ADD INDEX (`priority`)",
					),
				),
			),
		); 		
 		
		$this->tables['ka_tasks']['query'] = "
			CREATE TABLE `" . DB_PREFIX . "ka_tasks` (
			  `task_id` int(11) NOT NULL AUTO_INCREMENT,
			  `name` varchar(128) NOT NULL,
			  `active` enum('N','Y') NOT NULL,
			  `priority` int(11) NOT NULL,
			  `is_send_email` TINYINT(1) NOT NULL,
			  `module` varchar(255) DEFAULT NULL,
			  `operation` varchar(128) DEFAULT NULL,
			  `params` text NOT NULL,
			  `period_type` enum('hour','day','week','month','year','every_n_minutes') DEFAULT 'day',
			  `every_n_minutes` int(11) NOT NULL,
			  `period_at_min` int(4) NOT NULL,
			  `period_at_hour` int(4) NOT NULL,
			  `period_at_day` int(4) NOT NULL,
			  `period_at_dow` int(4) NOT NULL,
			  `period_at_month` int(4) NOT NULL,
			  `start_at` datetime NOT NULL,
			  `end_at` datetime NOT NULL,
			  `stat` mediumblob NOT NULL,
			  `complete_count` int(11) NOT NULL,
			  `run_status` enum('not_started','working','not_finished') NOT NULL,
			  `first_run` datetime NOT NULL,
			  `last_run` datetime NOT NULL,
			  `fail_count` int(11) NOT NULL,
			  `run_count` int(11) NOT NULL,
			  `session_data` mediumblob NOT NULL,
			  PRIMARY KEY (`task_id`),
			  KEY `name` (`name`),
			  KEY `last_run` (`last_run`),
			  KEY `priority` (`priority`)
			);
		";

		return true;
	}

	
	public function index() {

		$heading_title = $this->getTitle();
		$this->document->setTitle($heading_title);

		if ($this->request->server['REQUEST_METHOD'] == 'POST' && !empty($this->request->post['mode'])) {
		
			if ($this->request->post['mode'] == 'install') {
				if (!$this->kamodel_ka_scheduler_ka_tasks->installCronjob()) {
					$this->addTopMessage($this->kamodel_ka_scheduler_ka_tasks->getLastError(), 'E');
				} else {
					$this->addTopMessage('Installed successfully');
				}
				
			} elseif ($this->request->post['mode'] == 'uninstall') {
				if (!$this->kamodel_ka_scheduler_ka_tasks->deleteCronjob()) {
					$this->addTopMessage($this->kamodel_ka_scheduler_ka_tasks->getLastError(), 'E');
				} else {
					$this->addTopMessage('Cronjob was removed successfully');
				}				
				
			} elseif ($this->request->post['mode'] == 'reinstall') {
			
				if ($this->kamodel_ka_scheduler_ka_tasks->deleteCronjob()) {
					if (!$this->kamodel_ka_scheduler_ka_tasks->installCronjob()) {
						$this->addTopMessage($this->kamodel_ka_scheduler_ka_tasks->getLastError(), 'E');
					} else {
						$this->addTopMessage('Installed successfully');
					}
				} else {
					$this->addTopMessage($this->kamodel_ka_scheduler_ka_tasks->getLastError(), 'E');
				}
			}

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token']  . '&type=ka_extensions', true));
		}

				
		// handle autoinstall actions
		//
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
		
			if (empty($this->request->post['ka_scheduler_send_email_on_completion'])) {
				$this->request->post['ka_scheduler_send_email_on_completion'] = '';
			}
				
			$this->model_setting_setting->editSetting('ka_scheduler', $this->request->post);
			$this->addTopMessage($this->language->get('Settings have been stored sucessfully.'));
									
			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token']  . '&type=ka_extensions', true));
			
		} elseif ($this->request->server['REQUEST_METHOD'] == 'POST') {
			$this->data = $this->request->post;

		} else {
			$this->data['ka_scheduler_run_scheduler_key']        = $this->config->get('ka_scheduler_run_scheduler_key');
			$this->data['ka_scheduler_send_email_on_completion'] = $this->config->get('ka_scheduler_send_email_on_completion');

			$this->data['ka_scheduler_stop_task_after_n_minutes']    = $this->config->get('ka_scheduler_stop_task_after_n_minutes');
			$this->data['ka_scheduler_stop_task_after_n_failures']   = $this->config->get('ka_scheduler_stop_task_after_n_failures');
			$this->data['ka_scheduler_task_is_dead_after_n_minutes'] = $this->config->get('ka_scheduler_task_is_dead_after_n_minutes');
		}

		$cronjob_install_status = $this->kamodel_ka_scheduler_ka_tasks->getCronjobInstallStatus();
		$this->data['cronjob_install_status'] = $cronjob_install_status;
						
		$this->data['heading_title']   = $heading_title;
	
		$this->data['button_save']     = $this->language->get('button_save');		
		$this->data['button_cancel']   = $this->language->get('button_cancel');

		$this->data['extension_version']        = $this->extension_version;
		$this->data['run_scheduler'] = $this->url->link('extension/ka_extensions/ka_scheduler/run_scheduler', 'key='. $this->data['ka_scheduler_run_scheduler_key'], true);
		$this->data['error'] = $this->error;
		
		
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		$this->data['breadcrumbs'] = array();
   		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'user_token=' . $this->session->data['user_token'], true),
			'separator' => false
		);

  		$this->data['breadcrumbs'][] = array(
	 		'text'      => $this->language->get('Ka Extensions'),
			'href'      => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=ka_extensions', true),
   			'separator' => ' :: '
 		);
		
 		$this->data['breadcrumbs'][] = array(
	 		'text'      => $heading_title,
			'href'      => $this->url->link('extension/ka_extensions/ka_scheduler', 'user_token=' . $this->session->data['user_token'], true),
   			'separator' => ' :: '
 		);
		
		$this->data['action'] = $this->url->link('extension/ka_extensions/ka_scheduler', 'user_token=' . $this->session->data['user_token'], true);
		$this->data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=ka_extensions', true);

		$this->template = 'extension/ka_extensions/ka_scheduler/settings';
		$this->children = array(
			'common/header',
			'common/column_left',			
			'common/footer'
		);
				
		$this->setOutput();
	}

		
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/ka_extensions/ka_scheduler')) {
			$this->addTopMessage($this->language->get('error_permission'), 'E');
			return false;
		}
		
		if (empty($this->request->post['ka_scheduler_run_scheduler_key'])) {
			$this->error['ka_scheduler_run_scheduler_key'] = 'The key is empty. This value is required for security reasons.';
		}
		
		$stop_activity = (int)$this->request->post['ka_scheduler_stop_task_after_n_minutes'];
		if ($stop_activity < 1 || $stop_activity > 360) {
			$this->error['ka_scheduler_stop_task_after_n_minutes'] = 'The value should be between 1 and 360 minutes';
		}
		
		$stop_fails = (int)$this->request->post['ka_scheduler_stop_task_after_n_failures'];
		if ($stop_fails < 1 || $stop_fails > 10) {
			$this->error['ka_scheduler_stop_task_after_n_failures'] = 'The value should be between 1 and 10 times';
		}

		if (!empty($stop_activity)) {
			$dead = (int)$this->request->post['ka_scheduler_task_is_dead_after_n_minutes'];
			if ($dead < 1 || $dead > $stop_activity) {
				$this->error['ka_scheduler_task_is_dead_after_n_minutes'] = "The value should be between 1 and $stop_activity minutes";
			}
		}
				
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
	

	public function install() {

		if (parent::install()) {
			$this->load->model('setting/setting');
			
			$rec = array(
				'ka_scheduler_run_scheduler_key'            => rand(0, 99999),
				'ka_scheduler_stop_task_after_n_minutes'    => 30,
				'ka_scheduler_stop_task_after_n_failures'   => 2,
				'ka_scheduler_task_is_dead_after_n_minutes' => 5,
				'ka_scheduler_send_email_on_completion'     => 'Y',
			);
		
			$this->model_setting_setting->editSetting('ka_scheduler', $rec);
		
			$this->load->model('user/user_group');
			$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/ka_extensions/ka_scheduler/ka_tasks');
			$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/ka_extensions/ka_scheduler/ka_tasks');			
			
			return true;
		} 
		
		return false;
	}

	
	public function uninstall() {
		$this->model_setting_setting->deleteSetting('ka_scheduler');
		
		return true;
	}	

	
	public function isLite() {
		
		$model_ka_tasks = $this->load->kamodel('extension/ka_extensions/ka_scheduler/ka_tasks');
		$is_lite = $model_ka_tasks->isLite();
		
		return $is_lite;
	}
	
	public function isFree() {
		
		$model_ka_tasks = $this->load->kamodel('extension/ka_extensions/ka_scheduler/ka_tasks');
		$is_lite = $model_ka_tasks->isLite();
		
		return $is_lite;
	}
}

class_alias(__NAMESPACE__ . '\ControllerKaScheduler', 'ControllerExtensionKaExtensionsKaScheduler');