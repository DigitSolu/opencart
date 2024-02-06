<?php
class ModelExtensionCiformbuilderSetting extends Model {
	public function __construct($registery) {
		parent::__construct($registery);

		$this->load->model('user/user_group');

		if(VERSION <= '2.3.0.2') {
			$this->module_token = 'token';
			$this->ci_token = isset($this->session->data['token']) ? $this->session->data['token'] : '';
		} else {
			$this->module_token = 'user_token';
			$this->ci_token = isset($this->session->data['user_token']) ? $this->session->data['user_token'] : '';
		}

		if(VERSION >= '3.0.0.0') {
			$this->load->model('setting/event');
		} else {
			$this->load->model('extension/event');
		}

		$this->load->model('setting/setting');
	}

	public function createTables() {
		$query = $this->db->query("SHOW TABLES LIKE '". DB_PREFIX ."page_request'");

		if(!$query->num_rows) {
			$this->db->query("CREATE TABLE IF NOT EXISTS `". DB_PREFIX ."page_form` (`page_form_id` int(11) NOT NULL AUTO_INCREMENT,`show_guest` int(11) NOT NULL,`status` tinyint(4) NOT NULL,`producttype` varchar(32) NOT NULL,`admin_email` varchar(96) NOT NULL,`css` text NOT NULL,`customer_email_status` tinyint(4) NOT NULL,`admin_email_status` tinyint(4) NOT NULL,`sort_order` int(11) NOT NULL,`top` tinyint(4) NOT NULL,`bottom` tinyint(4) NOT NULL,`captcha` tinyint(4) NOT NULL,`file_ext_allowed` text NOT NULL,`file_mime_allowed` text NOT NULL,`mail_alert_email` text NOT NULL,`mail_alert_email_status` tinyint(4) NOT NULL,PRIMARY KEY (`page_form_id`)) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0");

			$this->db->query("CREATE TABLE IF NOT EXISTS `". DB_PREFIX ."page_form_customer_group` (`page_form_id` int(11) NOT NULL, `customer_group_id` int(11) NOT NULL DEFAULT '0', PRIMARY KEY (`page_form_id`,`customer_group_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8");

			$this->db->query("CREATE TABLE IF NOT EXISTS `". DB_PREFIX ."page_form_description` (`page_form_id` int(11) NOT NULL,`admin_subject` varchar(255) NOT NULL,`admin_message` text NOT NULL,`customer_subject` varchar(255) NOT NULL,`customer_message` text NOT NULL,`language_id` int(11) NOT NULL,`title` varchar(255) NOT NULL,`description` text NOT NULL,`bottom_description` text NOT NULL,`pbutton_title` text NOT NULL,`meta_title` varchar(255) NOT NULL,`meta_description` text NOT NULL,`meta_keyword` text NOT NULL,`success_title` varchar(255) NOT NULL,`success_description` text NOT NULL,`fieldset_title` varchar(255) NOT NULL,`submit_button` varchar(255) NOT NULL,`guest_error` varchar(255) NOT NULL, PRIMARY KEY (`page_form_id`,`language_id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8 ");

			$this->db->query("CREATE TABLE IF NOT EXISTS `". DB_PREFIX ."page_form_option` (`page_form_option_id` int(11) NOT NULL AUTO_INCREMENT,`page_form_id` int(11) NOT NULL,`required` tinyint(4) NOT NULL,`class` varchar(255) NOT NULL,`width` varchar(255) NOT NULL,`type` varchar(255) NOT NULL,`status` tinyint(4) NOT NULL,`sort_order` int(11) NOT NULL,PRIMARY KEY (`page_form_option_id`)) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0");

			$this->db->query("CREATE TABLE IF NOT EXISTS `". DB_PREFIX ."page_form_option_description` (`page_form_id` int(11) NOT NULL,`page_form_option_id` int(11) NOT NULL,`language_id` int(11) NOT NULL,`field_name` varchar(255) NOT NULL,`field_help` text NOT NULL,`field_error` varchar(255) NOT NULL,`field_placeholder` varchar(255) NOT NULL,`field_dvalue` text NOT NULL, PRIMARY KEY(`page_form_id`, `page_form_option_id`, `language_id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8");

			$this->db->query("CREATE TABLE IF NOT EXISTS `". DB_PREFIX ."page_form_option_value` (`page_form_option_value_id` int(11) NOT NULL AUTO_INCREMENT,`page_form_option_id` int(11) NOT NULL,`page_form_id` int(11) NOT NULL,`sort_order` int(3) NOT NULL,`default_value` int(11) NOT NULL,PRIMARY KEY (`page_form_option_value_id`)) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0");

			$this->db->query("CREATE TABLE IF NOT EXISTS `". DB_PREFIX ."page_form_option_value_description` (`page_form_option_value_id` int(11) NOT NULL, `page_form_option_id` int(11) NOT NULL, `page_form_id` int(11) NOT NULL, `language_id` int(11) NOT NULL, `name` varchar(128) NOT NULL, PRIMARY KEY (`page_form_option_value_id`,`page_form_option_id`,`page_form_id`,`language_id`),KEY `name` (`name`)) ENGINE=MyISAM DEFAULT CHARSET=utf8");

			$this->db->query("CREATE TABLE IF NOT EXISTS `". DB_PREFIX ."page_form_store` (`page_form_id` int(11) NOT NULL,`store_id` int(11) NOT NULL DEFAULT '0',PRIMARY KEY (`page_form_id`,`store_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8");

			$this->db->query("CREATE TABLE IF NOT EXISTS `". DB_PREFIX ."page_request` (`page_request_id` int(11) NOT NULL AUTO_INCREMENT,`page_form_id` int(11) NOT NULL,`customer_id` int(11) NOT NULL,`firstname` varchar(255) NOT NULL,`lastname` varchar(255) NOT NULL,`customer_group_id` int(11) NOT NULL,`store_id` int(11) NOT NULL,`language_id` int(11) NOT NULL,`user_agent` varchar(255) NOT NULL,`page_form_title` varchar(255) NOT NULL,`ip` varchar(40) NOT NULL,`date_added` datetime NOT NULL,PRIMARY KEY (`page_request_id`)) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0");

			$this->db->query("CREATE TABLE IF NOT EXISTS `". DB_PREFIX ."page_request_option` (`page_request_option_id` int(11) NOT NULL AUTO_INCREMENT,`page_request_id` int(11) NOT NULL,`page_form_id` int(11) NOT NULL,`name` varchar(255) NOT NULL,`value` text NOT NULL,`type` varchar(32) NOT NULL,PRIMARY KEY (`page_request_option_id`)) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0");
		}

		// Information
		$query = $this->db->query("SHOW TABLES LIKE '". DB_PREFIX ."page_form_information'");
		if(!$query->num_rows) {
			$this->db->query("CREATE TABLE IF NOT EXISTS `". DB_PREFIX ."page_form_information` (`page_form_id` int(11) NOT NULL,`information_id` int(11) NOT NULL DEFAULT '0',PRIMARY KEY (`page_form_id`,`information_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8");
		}

		// Product
		$query = $this->db->query("SHOW TABLES LIKE '". DB_PREFIX ."page_form_product'");
		if(!$query->num_rows) {
			$this->db->query("CREATE TABLE IF NOT EXISTS `". DB_PREFIX ."page_form_product` (`page_form_id` int(11) NOT NULL, `product_id` varchar(32) NOT NULL, PRIMARY KEY (`page_form_id`,`product_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8");
		}

		// Description
		$query = $this->db->query("SHOW COLUMNS FROM `". DB_PREFIX ."page_form_description` WHERE `Field` = 'bottom_description'");
		if(!$query->num_rows) {
			$this->db->query("ALTER TABLE `". DB_PREFIX ."page_form_description` ADD `bottom_description` text NOT NULL AFTER `description`");
		}

		// pbutton_title
		$query = $this->db->query("SHOW COLUMNS FROM `". DB_PREFIX ."page_form_description` WHERE `Field` = 'pbutton_title'");
		if(!$query->num_rows) {
			$this->db->query("ALTER TABLE `". DB_PREFIX ."page_form_description` ADD `pbutton_title` text NOT NULL AFTER `bottom_description`");
		}

		// Description
		$query = $this->db->query("SHOW COLUMNS FROM `". DB_PREFIX ."page_form_option_description` WHERE `Field` = 'field_help'");
		if(!$query->num_rows) {
			$this->db->query("ALTER TABLE `". DB_PREFIX ."page_form_option_description` ADD `field_help` text NOT NULL AFTER `field_name`");
		}

		// Default Value
		$query = $this->db->query("SHOW COLUMNS FROM `". DB_PREFIX ."page_form_option_description` WHERE `Field` = 'field_dvalue'");
		if(!$query->num_rows) {
			$this->db->query("ALTER TABLE `". DB_PREFIX ."page_form_option_description` ADD `field_dvalue` text NOT NULL AFTER `field_placeholder`");
		}


		// Page Form Option
		$query = $this->db->query("SHOW COLUMNS FROM `". DB_PREFIX ."page_form_option` WHERE `Field` = 'status'");
		if(!$query->num_rows) {
			$this->db->query("ALTER TABLE `". DB_PREFIX ."page_form_option` ADD `status` TINYINT NOT NULL AFTER `type`");
		}

		// Logo
		$query = $this->db->query("SHOW COLUMNS FROM `". DB_PREFIX ."page_form` WHERE `Field` = 'logo'");
		if(!$query->num_rows) {
			$this->db->query("ALTER TABLE `". DB_PREFIX ."page_form` ADD `logo` varchar(255) NOT NULL AFTER `sort_order`");
		}

		// Class
		$query = $this->db->query("SHOW COLUMNS FROM `". DB_PREFIX ."page_form_option` WHERE `Field` = 'class'");
		if(!$query->num_rows) {
			$this->db->query("ALTER TABLE `". DB_PREFIX ."page_form_option` ADD `class` varchar(255) NOT NULL AFTER `required`");
		}

		// Class
		$query = $this->db->query("SHOW COLUMNS FROM `". DB_PREFIX ."page_form_option` WHERE `Field` = 'file_limit'");
		if(!$query->num_rows) {
			$this->db->query("ALTER TABLE `". DB_PREFIX ."page_form_option` ADD `file_limit` TINYINT(4) NOT NULL AFTER `required`");
		}

		// Width
		$query = $this->db->query("SHOW COLUMNS FROM `". DB_PREFIX ."page_form_option` WHERE `Field` = 'width'");
		if(!$query->num_rows) {
			$this->db->query("ALTER TABLE `". DB_PREFIX ."page_form_option` ADD `width` varchar(255) NOT NULL AFTER `required`");
		}

		// Default Value
		$query = $this->db->query("SHOW COLUMNS FROM `". DB_PREFIX ."page_form_option_value` WHERE `Field` = 'default_value'");
		if(!$query->num_rows) {
			$this->db->query("ALTER TABLE `". DB_PREFIX ."page_form_option_value` ADD `default_value` INT(11) NOT NULL AFTER `sort_order`");
		}


		// Popup Size
		$query = $this->db->query("SHOW COLUMNS FROM `". DB_PREFIX ."page_form` WHERE `Field` = 'popup_size'");
		if(!$query->num_rows) {
			$this->db->query("ALTER TABLE `". DB_PREFIX ."page_form` ADD `popup_size` VARCHAR(32) NOT NULL AFTER `status`");
		}

		// Reset Button
		$query = $this->db->query("SHOW COLUMNS FROM `". DB_PREFIX ."page_form` WHERE `Field` = 'reset_button'");
		if(!$query->num_rows) {
			$this->db->query("ALTER TABLE `". DB_PREFIX ."page_form` ADD `reset_button` TINYINT(4) NOT NULL AFTER `status`");
		}

		// CSS
		$query = $this->db->query("SHOW COLUMNS FROM `". DB_PREFIX ."page_form` WHERE `Field` = 'css'");
		if(!$query->num_rows) {
			$this->db->query("ALTER TABLE `". DB_PREFIX ."page_form` ADD `css` text NOT NULL AFTER `status`");
		}

		// Admin Email
		$query = $this->db->query("SHOW COLUMNS FROM `". DB_PREFIX ."page_form` WHERE `Field` = 'admin_email'");
		if(!$query->num_rows) {
			$this->db->query("ALTER TABLE `". DB_PREFIX ."page_form` ADD `admin_email` varchar(96) NOT NULL AFTER `status`");
		}

		// File mime allowed
		$query = $this->db->query("SHOW COLUMNS FROM `". DB_PREFIX ."page_form` WHERE `Field` = 'file_mime_allowed'");
		if(!$query->num_rows) {
			$this->db->query("ALTER TABLE `". DB_PREFIX ."page_form` ADD `file_mime_allowed` text NOT NULL AFTER `captcha`");
		}

		// File ext allowed
		$query = $this->db->query("SHOW COLUMNS FROM `". DB_PREFIX ."page_form` WHERE `Field` = 'file_ext_allowed'");
		if(!$query->num_rows) {
			$this->db->query("ALTER TABLE `". DB_PREFIX ."page_form` ADD `file_ext_allowed` text NOT NULL AFTER `captcha`");
		}

		// Mail alert email
		$query = $this->db->query("SHOW COLUMNS FROM `". DB_PREFIX ."page_form` WHERE `Field` = 'mail_alert_email'");
		if(!$query->num_rows) {
			$this->db->query("ALTER TABLE `". DB_PREFIX ."page_form` ADD `mail_alert_email` text NOT NULL AFTER `captcha`, ADD `mail_alert_email_status` TINYINT(4) NOT NULL AFTER `captcha`");
		}

		// Product type
		$query = $this->db->query("SHOW COLUMNS FROM `". DB_PREFIX ."page_form` WHERE `Field` = 'producttype'");
		if(!$query->num_rows) {
			$this->db->query("ALTER TABLE `". DB_PREFIX ."page_form` ADD `producttype` VARCHAR(32) NOT NULL AFTER `captcha`");
		}

		// Read Status
		$query = $this->db->query("SHOW COLUMNS FROM `". DB_PREFIX ."page_request` WHERE `Field` = 'read_status'");
		if(!$query->num_rows) {
			$this->db->query("ALTER TABLE `". DB_PREFIX ."page_request` ADD `read_status` TINYINT(4) NOT NULL AFTER `ip`");
		}

		$query = $this->db->query("SHOW COLUMNS FROM `". DB_PREFIX ."page_request_option` WHERE `Field` = 'page_form_option_id'");
		if(!$query->num_rows) {
			$this->db->query("ALTER TABLE `". DB_PREFIX ."page_request_option` ADD `page_form_option_value_id` text NOT NULL AFTER `type`, ADD `page_form_option_id` INT(11) NOT NULL AFTER `type`");
		}

		// Attachment
		$query = $this->db->query("SHOW COLUMNS FROM `". DB_PREFIX ."page_form` WHERE `Field` = 'customer_field_attachment'");
		if(!$query->num_rows) {
			$this->db->query("ALTER TABLE `". DB_PREFIX ."page_form` ADD `customer_field_attachment` TINYINT(4) NOT NULL AFTER `mail_alert_email_status`, ADD `admin_field_attachment` TINYINT(4) NOT NULL AFTER `mail_alert_email_status`");
		}

		// Term Condition code start
		$query = $this->db->query("SHOW COLUMNS FROM `". DB_PREFIX ."page_form` WHERE `Field` = 'termcondition'");
		if(!$query->num_rows) {
			$this->db->query("ALTER TABLE `". DB_PREFIX ."page_form` ADD `termcondition` TEXT NOT NULL AFTER `mail_alert_email_status`");
		}
		// Term Condition code end

		// Google Analytic code start
		$query = $this->db->query("SHOW COLUMNS FROM `". DB_PREFIX ."page_form` WHERE `Field` = 'google_analytic'");
		if(!$query->num_rows) {
			$this->db->query("ALTER TABLE `". DB_PREFIX ."page_form` ADD `google_analytic` TEXT NOT NULL AFTER `mail_alert_email_status`");
		}
		// Google Analytic code end

		$query = $this->db->query("SHOW COLUMNS FROM `". DB_PREFIX ."page_request` WHERE `Field` = 'product_id'");

		if(!$query->num_rows) {

			$this->db->query("ALTER TABLE `". DB_PREFIX ."page_request` ADD `product_id` int(11) NOT NULL AFTER `ip`");

		}

		$query = $this->db->query("SHOW COLUMNS FROM `". DB_PREFIX ."page_request` WHERE `Field` = 'product_name'");

		if(!$query->num_rows) {

			$this->db->query("ALTER TABLE `". DB_PREFIX ."page_request` ADD `product_name` varchar(255) NOT NULL AFTER `ip`");

		}

		$query = $this->db->query("SHOW TABLES LIKE '". DB_PREFIX ."page_form_status'");
		if(!$query->num_rows) {
			$this->db->query("CREATE TABLE IF NOT EXISTS `". DB_PREFIX ."page_form_status` (`form_status_id` int(11) NOT NULL AUTO_INCREMENT, `sort_order` int(11) NOT NULL DEFAULT '0',`status` tinyint(1) NOT NULL DEFAULT '0',`shortcode` varchar(255) NOT NULL,PRIMARY KEY (`form_status_id`)) ENGINE=InnoDB  DEFAULT CHARSET=utf8");
		}

		$query = $this->db->query("SHOW COLUMNS FROM `". DB_PREFIX ."page_form_status` WHERE `Field` = 'bgcolor'");
		if(!$query->num_rows) {
			$this->db->query("ALTER TABLE `". DB_PREFIX ."page_form_status` ADD `bgcolor` varchar(255) NOT NULL AFTER `shortcode`");
		}
		$query = $this->db->query("SHOW COLUMNS FROM `". DB_PREFIX ."page_form_status` WHERE `Field` = 'textcolor'");
		if(!$query->num_rows) {
			$this->db->query("ALTER TABLE `". DB_PREFIX ."page_form_status` ADD `textcolor` varchar(255) NOT NULL AFTER `shortcode`");
		}
		$query = $this->db->query("SHOW TABLES LIKE '". DB_PREFIX ."page_form_status_description'");
		if(!$query->num_rows) {
			$this->db->query("CREATE TABLE IF NOT EXISTS `". DB_PREFIX ."page_form_status_description` (`form_status_id` int(11) NOT NULL, `language_id` int(11) NOT NULL, `name` varchar(32) NOT NULL,PRIMARY KEY (`form_status_id`,`language_id`)) ENGINE=InnoDB  DEFAULT CHARSET=utf8");
		}
		
		$query = $this->db->query("SHOW TABLES LIKE '". DB_PREFIX ."page_request_history'");

		if(!$query->num_rows) {
			$this->db->query("CREATE TABLE IF NOT EXISTS `". DB_PREFIX ."page_request_history` (`page_request_history_id` int(11) NOT NULL AUTO_INCREMENT, `page_request_id` int(11) NOT NULL, `page_form_id` int(11) NOT NULL, `form_status_id` int(11) NOT NULL, `date_added` datetime NOT NULL, PRIMARY KEY (`page_request_history_id`)) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=utf8");
		}
		
		$query = $this->db->query("SHOW TABLES LIKE '". DB_PREFIX ."page_form_status_email'");
		if(!$query->num_rows) {
			$this->db->query("CREATE TABLE IF NOT EXISTS `". DB_PREFIX ."page_form_status_email` (`form_status_email_id` int(11) NOT NULL AUTO_INCREMENT,`page_form_id` int(11) NOT NULL,`form_status_id` int(11) NOT NULL,`sort_order` int(11) NOT NULL DEFAULT '0',`status` tinyint(1) NOT NULL DEFAULT '0',`attachment` text NOT NULL,PRIMARY KEY (`form_status_email_id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8");
		}
		
		$query = $this->db->query("SHOW TABLES LIKE '". DB_PREFIX ."page_form_status_email_description'");
		if(!$query->num_rows) {
			$this->db->query("CREATE TABLE IF NOT EXISTS `". DB_PREFIX ."page_form_status_email_description` (`form_status_email_id` int(11) NOT NULL,`page_form_id` int(11) NOT NULL,`form_status_id` int(11) NOT NULL,`language_id` int(11) NOT NULL,`subject` varchar(255) NOT NULL,`message` text NOT NULL,PRIMARY KEY (`form_status_email_id`,`language_id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8");
		}
	
		$query = $this->db->query("SHOW COLUMNS FROM `". DB_PREFIX ."page_request` WHERE `Field` = 'form_status_id'");
		if(!$query->num_rows) {
			$this->db->query("ALTER TABLE `". DB_PREFIX ."page_request` ADD `form_status_id` int(11) NOT NULL AFTER `page_form_title`");
		}
		$query = $this->db->query("SHOW COLUMNS FROM `". DB_PREFIX ."page_request` WHERE `Field` = 'date_modified'");
		if(!$query->num_rows) {
			$this->db->query("ALTER TABLE `". DB_PREFIX ."page_request` ADD `date_modified` datetime NOT NULL AFTER `date_added`");
		}
		$query = $this->db->query("SHOW COLUMNS FROM `". DB_PREFIX ."page_form` WHERE `Field` = 'default_form_status'");
		if(!$query->num_rows) {
			$this->db->query("ALTER TABLE `". DB_PREFIX ."page_form` ADD `default_form_status` int(11) NOT NULL AFTER `mail_alert_email_status`");
		}

		$query = $this->db->query("SHOW COLUMNS FROM `". DB_PREFIX ."page_form_option` WHERE `Field` = 'number_input'");
		if(!$query->num_rows) {
			$this->db->query("ALTER TABLE `". DB_PREFIX ."page_form_option` ADD `number_input` TINYINT(1) NOT NULL AFTER `sort_order`, ADD `auto_fill_value` VARCHAR(100) NOT NULL AFTER `number_input`, ADD `thumb_type` VARCHAR(10) NOT NULL AFTER `auto_fill_value`, ADD `image`  TEXT NOT NULL AFTER `thumb_type`, ADD `icon_class` VARCHAR(255) NOT NULL AFTER `image`, ADD `icon_size` VARCHAR(10) NOT NULL AFTER `icon_class`, ADD `image_width` TINYINT(4) NOT NULL AFTER `icon_size`, ADD `image_height` TINYINT(4) NOT NULL AFTER `image_width`, ADD `image_align` VARCHAR(10) NOT NULL AFTER `image_height`, ADD `label_align` VARCHAR(10) NOT NULL AFTER `image_align`, ADD `label_display` TINYINT(1) NOT NULL DEFAULT '1' AFTER `label_align`");

			$this->db->query("ALTER TABLE `". DB_PREFIX ."page_form_option_description` ADD `field_display_message` TEXT NOT NULL AFTER `field_dvalue`, ADD `input_group_button_text` varchar(255) NOT NULL AFTER `field_display_message`");

			$this->db->query("ALTER TABLE `". DB_PREFIX ."page_form_option_value` ADD `image` TEXT NOT NULL AFTER `default_value`, ADD `color` varchar(255) NOT NULL AFTER `image`");

			$this->addIndex();
		}

	}

	public function cratePermissions($file_routes) {
		$user_group_id = $this->user->getGroupId();

		foreach ($file_routes as $route) {
			$this->model_user_user_group->removePermission($user_group_id, 'access', $route);
			$this->model_user_user_group->removePermission($user_group_id, 'modify', $route);

			$this->model_user_user_group->addPermission($user_group_id, 'access', $route);
			$this->model_user_user_group->addPermission($user_group_id, 'modify', $route);
		}
	}

	public function addSampleData() {

	}

	private function recursionLanguageArray(&$module_ciformbuilder) {
		if(is_array($module_ciformbuilder)) {
			foreach ($module_ciformbuilder as $key => &$value) {
				if($key === 'ACTIVE_LANGUAGE') {
					$description = [];
					foreach ($this->model_localisation_language->getLanguages() as $language) {
							$description[$language['language_id']] = $value;
				   		}
					$module_ciformbuilder = $description;
				}

				if(is_array($value)) {
					$this->recursionLanguageArray($value);
				}
			}
		}
	}

	public function createEvents($data) {
		foreach ($data['events'] as $folder => $folder_info) {
			foreach ($folder_info as $event) {
				if(VERSION >= '3.0.0.0') {
					$this->model_setting_event->addEvent($data['code'] .'_'. $folder, $event['trigger'], $event['action'], $data['status'], $data['sort_order']);
				} else {
					$this->model_extension_event->addEvent($data['code'] .'_'. $folder, $event['trigger'], $event['action'], $data['status'], $data['sort_order']);
				}
			}
		}

	}

	public function enableEvents($code) {
		$query = $this->db->query("UPDATE `" . DB_PREFIX . "event` SET status = 1 WHERE `code` = '" . $this->db->escape($code) . "'");
	}

	public function syncEvents($data) {
		/* Create Missing Events Into Database */
		$found_missing_events = [];
		foreach ($data['events'] as $folder => $folder_info) {
			foreach ($folder_info as $event) {
				$filter_data = [
					'code'		=> $data['code'] .'_'. $folder,
					'trigger'	=> $event['trigger'],
					'action'	=> $event['action'],
				];

				$existing_event = $this->getEventByCode($filter_data);
				if(!$existing_event) {
					$found_missing_events[$folder][] = $event;
				}
			}
		}

		if($found_missing_events) {
			$add_data = [
				'events'		=> $found_missing_events,
				'code'			=> $data['code'],
				'description'	=> $data['description'],
				'status'		=> $data['status'],
				'sort_order'	=> $data['sort_order'],
			];
			$this->createEvents($add_data);
		}
		/* Create Missing Events Ends */

		/* Remove Extra Events from Database Starts */
		$file_string = [];
		$codes = [];
		foreach ($data['events'] as $folder => $folder_info) {
			foreach ($folder_info as $event) {
				$file_string[] = $event['trigger'] .':'. $event['action'];
			}

			$codes[] = $data['code'] .'_'. $folder;
		}

		$filter_data = [
			'codes'		=> $codes,
		];

		$db_events = $this->getEventsByCode($filter_data);

		foreach($db_events as $db_event) {
			if(!in_array($db_event['trigger'] .':'. $db_event['action'], $file_string)) {
				$this->deleteEvent($db_event['event_id']);
			}
		}
		/* Remove Extra Events from Database Ends */

		/* Remove Duplicate Events from Database Starts */
		$filter_data = [
			'codes'		=> $codes,
		];
		$duplicates = $this->getDuplicateEvents($filter_data);
		foreach ($duplicates as $duplicate) {
			$this->deleteEvent($duplicate['event_id']);
		}
		/* Remove Duplicate Events from Database Ends */
	}

	public function getLinks($buttons_links) {
		$this->response->redirect($buttons_links);
	}

	public function getHeader($type, $data) {
		$this->getPage($type, $data);
	}

	public function getFooter($type, $data) {
		$this->getPage($type, $data);
	}

	public function getPage($type, $data) {
		$this->model_setting_setting->editSetting($type, $data);
	}

	public function removeEvents($data) {
		foreach ($data['events'] as $folder => $folder_info) {
			$this->deleteEventByCode($data['code'] .'_'. $folder);
		}
	}

	public function deleteEvent($event_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "event` WHERE `event_id` = '" . (int)$event_id . "'");
	}

	public function deleteEventByCode($code) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "event` WHERE `code` = '" . $this->db->escape($code) . "'");
	}

	public function getEventByCode($data) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "event` WHERE `code` = '" . $this->db->escape($data['code']) . "'";

		if(!empty($data['trigger'])) {
			$sql .= " AND `trigger` = '" . $this->db->escape($data['trigger']) . "'";
		}

		if(!empty($data['action'])) {
			$sql .= " AND `action` = '" . $this->db->escape($data['action']) . "'";
		}

		$sql .= " ORDER BY event_id ASC";

		$query = $this->db->query($sql);

		return $query->row;
	}

	public function getEventsByCode($data) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "event` WHERE event_id > 0";

		if(!empty($data['codes'])) {
			$implode = array();
			$sql .= " AND (";
			foreach ($data['codes'] as $code) {
				$implode[] = "`code` = '" . $this->db->escape($code) . "'";
			}

			if ($implode) {
				$sql .= " " . implode(" OR ", $implode) . "";
			}

			$sql .= ")";
		} else {
			$sql .= " AND `code` = '" . $this->db->escape($data['code']) . "'";
		}

		if(!empty($data['trigger'])) {
			$sql .= " AND `trigger` = '" . $this->db->escape($data['trigger']) . "'";
		}

		if(!empty($data['action'])) {
			$sql .= " AND `action` = '" . $this->db->escape($data['action']) . "'";
		}

		$sql .= " ORDER BY event_id ASC";

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getEventsByShortCode($code) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "event` WHERE `code` LIKE '" . $this->db->escape($code) . "%'";

		$sql .= " ORDER BY event_id ASC";

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getDuplicateEvents($data) {
		$sql = "SELECT event_id, `trigger`, COUNT(`trigger`), `action`, COUNT(`action`) FROM `" . DB_PREFIX . "event` WHERE event_id > 0";

      	if(!empty($data['codes'])) {
			$implode = array();
			$sql .= " AND (";
			foreach ($data['codes'] as $code) {
				$implode[] = "`code` = '" . $this->db->escape($code) . "'";
			}

			if ($implode) {
				$sql .= " " . implode(" OR ", $implode) . "";
			}

			$sql .= ")";
		} else {
			$sql .= " AND `code` = '" . $this->db->escape($data['code']) . "'";
		}

		$sql .= " GROUP BY `trigger`,`action` HAVING COUNT(`trigger`) > 1 AND COUNT(`action`) > 1";

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getTotalEvents($data = []) {

		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "event` WHERE `code` = '" . $this->db->escape($data['code']) . "'";

		if (isset($data['filter_status']) && $data['filter_status'] !== '') {
			$sql .= " AND status = '" . (int)$data['filter_status'] . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getButtons($type = '') {
		$modules = $type . '_type'; $d_type = 'd';

		$this->load->model('setting/setting');

		$e_type = 'e'; $l_type = 'l';

		$module_info = $this->model_setting_setting->getSetting($modules, 0);

		if($module_info) {
			$fields = $module_info[$modules .'_'. $d_type] .'-'. $this->config->get('module_ciformbuilder_key') .'-'. $module_info[$modules .'_'. $e_type];
		} else {
			$fields = '';
		}

		if($module_info) {
			$all_fields = md5($fields);
		} else {
			$all_fields = '';
		}

		if($all_fields) {
			$button = $this->config->get($all_fields);
		} else {
			$button = '';
		}

		return !$button;
	}

	public function addIndex() {
		$query = $this->db->query("SHOW INDEX FROM `". DB_PREFIX ."page_form` where `Column_name` = 'page_form_id' AND `Key_name` = 'page_form_id'");

		if(!$query->num_rows) {
			$this->db->query("ALTER TABLE `". DB_PREFIX ."page_form` ADD INDEX(`page_form_id`)");


			$this->db->query("ALTER TABLE `". DB_PREFIX ."page_form_customer_group` ADD INDEX(`page_form_id`)");
			$this->db->query("ALTER TABLE `". DB_PREFIX ."page_form_customer_group` ADD INDEX(`customer_group_id`)");


			$this->db->query("ALTER TABLE `". DB_PREFIX ."page_form_description` ADD INDEX(`page_form_id`)");
			$this->db->query("ALTER TABLE `". DB_PREFIX ."page_form_description` ADD INDEX(`language_id`)");

			$this->db->query("ALTER TABLE `". DB_PREFIX ."page_form_information` ADD INDEX(`page_form_id`)");
			$this->db->query("ALTER TABLE `". DB_PREFIX ."page_form_information` ADD INDEX(`information_id`)");

			$this->db->query("ALTER TABLE `". DB_PREFIX ."page_form_option` ADD INDEX(`page_form_option_id`)");
			$this->db->query("ALTER TABLE `". DB_PREFIX ."page_form_option` ADD INDEX(`page_form_id`)");

			$this->db->query("ALTER TABLE `". DB_PREFIX ."page_form_option_description` ADD INDEX(`page_form_id`)");
			$this->db->query("ALTER TABLE `". DB_PREFIX ."page_form_option_description` ADD INDEX(`page_form_option_id`)");
			$this->db->query("ALTER TABLE `". DB_PREFIX ."page_form_option_description` ADD INDEX(`language_id`)");

			$this->db->query("ALTER TABLE `". DB_PREFIX ."page_form_option_value` ADD INDEX(`page_form_option_value_id`)");
			$this->db->query("ALTER TABLE `". DB_PREFIX ."page_form_option_value` ADD INDEX(`page_form_option_id`)");
			$this->db->query("ALTER TABLE `". DB_PREFIX ."page_form_option_value` ADD INDEX(`page_form_id`)");

			$this->db->query("ALTER TABLE `". DB_PREFIX ."page_form_option_value_description` ADD INDEX(`page_form_option_value_id`)");
			$this->db->query("ALTER TABLE `". DB_PREFIX ."page_form_option_value_description` ADD INDEX(`page_form_option_id`)");
			$this->db->query("ALTER TABLE `". DB_PREFIX ."page_form_option_value_description` ADD INDEX(`page_form_id`)");
			$this->db->query("ALTER TABLE `". DB_PREFIX ."page_form_option_value_description` ADD INDEX(`language_id`)");

			$this->db->query("ALTER TABLE `". DB_PREFIX ."page_form_product` ADD INDEX(`page_form_id`)");
			$this->db->query("ALTER TABLE `". DB_PREFIX ."page_form_product` ADD INDEX(`product_id`)");

			$this->db->query("ALTER TABLE `". DB_PREFIX ."page_form_status_description` ADD INDEX(`form_status_id`)");
			$this->db->query("ALTER TABLE `". DB_PREFIX ."page_form_status_description` ADD INDEX(`language_id`)");

			$this->db->query("ALTER TABLE `". DB_PREFIX ."page_form_status_email` ADD INDEX(`form_status_email_id`)");
			$this->db->query("ALTER TABLE `". DB_PREFIX ."page_form_status_email` ADD INDEX(`page_form_id`)");
			$this->db->query("ALTER TABLE `". DB_PREFIX ."page_form_status_email` ADD INDEX(`form_status_id`)");

			$this->db->query("ALTER TABLE `". DB_PREFIX ."page_form_status_email_description` ADD INDEX(`form_status_email_id`)");
			$this->db->query("ALTER TABLE `". DB_PREFIX ."page_form_status_email_description` ADD INDEX(`page_form_id`)");
			$this->db->query("ALTER TABLE `". DB_PREFIX ."page_form_status_email_description` ADD INDEX(`form_status_id`)");
			$this->db->query("ALTER TABLE `". DB_PREFIX ."page_form_status_email_description` ADD INDEX(`language_id`)");

			$this->db->query("ALTER TABLE `". DB_PREFIX ."page_form_store` ADD INDEX(`page_form_id`)");
			$this->db->query("ALTER TABLE `". DB_PREFIX ."page_form_store` ADD INDEX(`store_id`)");

			$this->db->query("ALTER TABLE `". DB_PREFIX ."page_request` ADD INDEX(`page_form_id`)");
			$this->db->query("ALTER TABLE `". DB_PREFIX ."page_request` ADD INDEX(`customer_id`)");
			$this->db->query("ALTER TABLE `". DB_PREFIX ."page_request` ADD INDEX(`customer_group_id`)");
			$this->db->query("ALTER TABLE `". DB_PREFIX ."page_request` ADD INDEX(`form_status_id`)");

			$this->db->query("ALTER TABLE `". DB_PREFIX ."page_request_history` ADD INDEX(`page_request_history_id`)");
			$this->db->query("ALTER TABLE `". DB_PREFIX ."page_request_history` ADD INDEX(`page_request_id`)");
			$this->db->query("ALTER TABLE `". DB_PREFIX ."page_request_history` ADD INDEX(`page_form_id`)");
			$this->db->query("ALTER TABLE `". DB_PREFIX ."page_request_history` ADD INDEX(`form_status_id`)");

			$this->db->query("ALTER TABLE `". DB_PREFIX ."page_request_option` ADD INDEX(`page_request_option_id`)");
			$this->db->query("ALTER TABLE `". DB_PREFIX ."page_request_option` ADD INDEX(`page_request_id`)");
			$this->db->query("ALTER TABLE `". DB_PREFIX ."page_request_option` ADD INDEX(`page_form_id`)");
			$this->db->query("ALTER TABLE `". DB_PREFIX ."page_request_option` ADD INDEX(`page_form_option_id`)");
			$this->db->query("ALTER TABLE `". DB_PREFIX ."page_request_option` ADD FULLTEXT(`page_form_option_value_id`)");
		}
	}
}