<?php
class ModelExtensionCiFormbuilderForm extends Model {
	public function getPageForm($page_form_id) {
		$forms_data = [];
		$sql = "SELECT DISTINCT * FROM " . DB_PREFIX . "page_form p LEFT JOIN " . DB_PREFIX . "page_form_description pd ON (p.page_form_id = pd.page_form_id) LEFT JOIN " . DB_PREFIX . "page_form_store p2s ON (p.page_form_id = p2s.page_form_id) WHERE p.page_form_id = '" . (int)$page_form_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND p.status = '1'";

		if(!$this->customer->isLogged()) {
			$sql .= " AND p.show_guest = '1'";
		}

		$row = $this->db->query($sql)->row;

		if($row) {
			// Customer Group
			$find_mygroup = false;
			if($this->customer->isLogged()) {
				// This is Customer
				$customer_group_id = $this->customer->getGroupId();
				$customer_group_query = $this->db->query("SELECT * FROM ". DB_PREFIX ."page_form_customer_group WHERE page_form_id = '". (int)$row['page_form_id'] ."' AND customer_group_id = '". (int)$customer_group_id ."'");

				if($customer_group_query->num_rows) {
					$find_mygroup = true;
				}
			} else{
				// This is Guest
				$find_mygroup = true;
			}

			if($find_mygroup) {
				$forms_data = $row;
			}
		}

		if(!$this->config->get('module_ciformbuilder_setting_status') || !$this->config->get('ciformbuilder_type_d')) {
			$forms_data = [];
		}

		return $forms_data;
	}

	public function getPageForms() {
		$forms_data = [];
		$sql = "SELECT * FROM " . DB_PREFIX . "page_form p LEFT JOIN " . DB_PREFIX . "page_form_description pd ON (p.page_form_id = pd.page_form_id) LEFT JOIN " . DB_PREFIX . "page_form_store p2s ON (p.page_form_id = p2s.page_form_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND p.status = '1'";

		if(!$this->customer->isLogged()) {
			$sql .= " AND p.show_guest = '1'";
		}

		 $sql .= " ORDER BY p.sort_order, LCASE(pd.title) ASC";

		$query = $this->db->query($sql);

		foreach($query->rows as $row) {
			// Customer Group
			$find_mygroup = false;
			if($this->customer->isLogged()) {
				// This is Customer
				$customer_group_id = $this->customer->getGroupId();
				$customer_group_query = $this->db->query("SELECT * FROM ". DB_PREFIX ."page_form_customer_group WHERE page_form_id = '". (int)$row['page_form_id'] ."' AND customer_group_id = '". (int)$customer_group_id ."'");

				if($customer_group_query->num_rows) {
					$find_mygroup = true;
				}
			} else{
				// This is Guest
				$find_mygroup = true;
			}

			if($find_mygroup) {
				$forms_data[] = $row;
			}

		}

		if(!$this->config->get('module_ciformbuilder_setting_status') || !$this->config->get('ciformbuilder_type_d')) {
			$forms_data = [];
		}

		return $forms_data;
	}

	public function getPageFormOptions($page_form_id) {
		$this->load->model('tool/image');

		$page_form_option_data = [];

		$page_form_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "page_form_option pfo LEFT JOIN " . DB_PREFIX . "page_form_option_description pfod ON (pfo.page_form_option_id = pfod.page_form_option_id) WHERE pfo.page_form_id = '" . (int)$page_form_id . "' AND pfod.language_id = '" . (int)$this->config->get('config_language_id') . "' AND pfo.status ORDER BY pfo.sort_order ASC");

		foreach ($page_form_option_query->rows as $page_form_option) {

			$page_form_option_value_data = array();

			$page_form_option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "page_form_option_value pfov LEFT JOIN " . DB_PREFIX . "page_form_option_value_description pfovd ON (pfov.page_form_option_value_id = pfovd.page_form_option_value_id) WHERE pfov.page_form_option_id = '" . (int)$page_form_option['page_form_option_id'] . "' AND pfovd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY pfov.sort_order ASC");

			foreach ($page_form_option_value_query->rows as $page_form_option_value) {
				if (isset($page_form_option_value['image']) && is_file(DIR_IMAGE . $page_form_option_value['image'])) {
					$thumb = $this->model_tool_image->resize($page_form_option_value['image'], 40, 40);
				} else {
					$thumb = '';
				}

				$page_form_option_value_data[] = array(
					'page_form_option_value_id'      => $page_form_option_value['page_form_option_value_id'],
					'name'                    	=> $page_form_option_value['name'],
					'default_value'             => $page_form_option_value['default_value'],
					'thumb'                    	=> $thumb,
					'color'                    	=> $page_form_option_value['color'],
				);
			}

			$field_dvalue = $page_form_option['field_dvalue'];

			if(!empty($page_form_option['auto_fill_value'])) {
				$field_dvalue = $this->getAutoFillValue($page_form_option['auto_fill_value']);
			}


			if (isset($page_form_option['image']) && is_file(DIR_IMAGE . $page_form_option['image'])) {

				$image_width = $page_form_option['image_width'] ? $page_form_option['image_width'] : 70;
				$image_height = $page_form_option['image_height'] ? $page_form_option['image_height'] : 70;

				$thumb = $this->model_tool_image->resize($page_form_option['image'], $image_width, $image_height);
			} else {
				$thumb = '';
			}

			$page_form_option_data[] = array(
				'page_form_option_id'    => $page_form_option['page_form_option_id'],
				'page_form_option_value' => $page_form_option_value_data,
				'field_name'             => $page_form_option['field_name'],
				'field_help'             => $page_form_option['field_help'],
				'type'                	 => $page_form_option['type'],
				'field_placeholder'      => $page_form_option['field_placeholder'],
				'field_error'      		 => $page_form_option['field_error'],
				'class'      		 	 => $page_form_option['class'],
				'width'      		 	 => $page_form_option['width'],
				'required'             	 => $page_form_option['required'],

				'field_dvalue'      	 => $field_dvalue,

				'thumb_type'      		 => $page_form_option['thumb_type'],
				'thumb'      		 	 => $thumb,
				'image_align'      	 	 => $page_form_option['image_align'],
				'label_align'      	 	 => $page_form_option['label_align'],
				'label_display'			 => $page_form_option['label_display'],
				'icon_class'			 => $page_form_option['icon_class'],
				'icon_size'			 	 => $page_form_option['icon_size'],
				'file_limit'      		 => $page_form_option['file_limit'],
				'number_input'      	 => $page_form_option['number_input'],
				'input_group_button_text'=> $page_form_option['input_group_button_text'],
				'field_display_message'	 => html_entity_decode($page_form_option['field_display_message'], ENT_QUOTES, 'UTF-8'),
			);
		}

		return $page_form_option_data;
	}

	public function getPageFormOptionsCountry($page_form_id) {
		$query = $this->db->query("SELECT count(*) as total_country_exists FROM " . DB_PREFIX . "page_form_option pfo WHERE pfo.page_form_id = '" . (int)$page_form_id . "' AND pfo.type = 'country' AND pfo.status = 1");

		return $query->row['total_country_exists'];
	}

	public function getPageRequestEmailByPageFormID($email, $page_form_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "page_request_option` WHERE LOWER(`value`) = '" . $this->db->escape(utf8_strtolower($email)) . "' AND `page_form_id` = '" . (int)$page_form_id . "' AND (`type` = 'email' OR `type` = 'email_exists')");

		return $query->row;
	}

	public function getPageFormByInformation($information_id) {
		$forms_data = array();
		$sql = "SELECT DISTINCT * FROM " . DB_PREFIX . "page_form p LEFT JOIN " . DB_PREFIX . "page_form_description pd ON (p.page_form_id = pd.page_form_id) LEFT JOIN " . DB_PREFIX . "page_form_store p2s ON (p.page_form_id = p2s.page_form_id) LEFT JOIN " . DB_PREFIX . "page_form_information p2i ON (p.page_form_id = p2i.page_form_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND p2i.information_id = '" . (int)$information_id . "' AND p.status = '1'";

		if(!$this->customer->isLogged()) {
			$sql .= " AND p.show_guest = '1'";
		}

		$row = $this->db->query($sql)->row;

		if($row) {
			// Customer Group
			$find_mygroup = false;
			if($this->customer->isLogged()) {
				// This is Customer
				$customer_group_id = $this->customer->getGroupId();
				$customer_group_query = $this->db->query("SELECT * FROM ". DB_PREFIX ."page_form_customer_group WHERE page_form_id = '". (int)$row['page_form_id'] ."' AND customer_group_id = '". (int)$customer_group_id ."'");

				if($customer_group_query->num_rows) {
					$find_mygroup = true;
				}
			} else{
				// This is Guest
				$find_mygroup = true;
			}

			if($find_mygroup) {
				$forms_data = $row;
			}
		}

		return $forms_data;
	}

	public function getAllPageFormsByProduct($product_id) {
		$forms_data = array();
		$sql = "SELECT DISTINCT * FROM " . DB_PREFIX . "page_form p LEFT JOIN " . DB_PREFIX . "page_form_description pd ON (p.page_form_id = pd.page_form_id) LEFT JOIN " . DB_PREFIX . "page_form_store p2s ON (p.page_form_id = p2s.page_form_id) LEFT JOIN " . DB_PREFIX . "page_form_product p2p ON (p.page_form_id = p2p.page_form_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND p.status = '1' AND ( p2p.product_id = '" . (int)$product_id . "' OR product_id = 'all') ";

		if(!$this->customer->isLogged()) {
			$sql .= " AND p.show_guest = '1'";
		}

		$query = $this->db->query($sql);

		foreach($query->rows as $row) {
			// Customer Group
			$find_mygroup = false;
			if($this->customer->isLogged()) {
				// This is Customer
				$customer_group_id = $this->customer->getGroupId();
				$customer_group_query = $this->db->query("SELECT * FROM ". DB_PREFIX ."page_form_customer_group WHERE page_form_id = '". (int)$row['page_form_id'] ."' AND customer_group_id = '". (int)$customer_group_id ."'");

				if($customer_group_query->num_rows) {
					$find_mygroup = true;
				}
			} else{
				// This is Guest
				$find_mygroup = true;
			}

			if($find_mygroup) {
				$forms_data[] = $row;
			}

		}

		return $forms_data;
	}

	public function getAutoFillValue($auto_fill_type = '') {
		$this->load->model('account/address');

		$address_info = $this->model_account_address->getAddress($this->customer->getAddressId());

		$value = '';

		if($auto_fill_type == 'name') {
			$value = $this->customer->getFirstName() .' '. $this->customer->getLastName();
		} elseif($auto_fill_type == 'firstname') {
			$value = $this->customer->getFirstName();
		} elseif($auto_fill_type == 'lastname') {
			$value = $this->customer->getLastName();
		} elseif($auto_fill_type == 'email') {
			$value = $this->customer->getEmail();
		} elseif($auto_fill_type == 'telephone') {
			$value = $this->customer->getTelephone();
		}

		if($address_info) {
			if($auto_fill_type == 'company') {
				$value = $address_info['company'];
			} elseif($auto_fill_type == 'address_1') {
				$value = $address_info['address_1'];
			} elseif($auto_fill_type == 'address_2') {
				$value = $address_info['address_2'];
			} elseif($auto_fill_type == 'city') {
				$value = $address_info['city'];
			} elseif($auto_fill_type == 'postcode') {
				$value = $address_info['postcode'];
			} elseif($auto_fill_type == 'country_id') {
				$value = $address_info['country_id'];
			} elseif($auto_fill_type == 'country') {
				$value = $address_info['country'];
			} elseif($auto_fill_type == 'zone_id') {
				$value = $address_info['zone_id'];
			} elseif($auto_fill_type == 'zone') {
				$value = $address_info['zone'];
			}
		}

		return $value;
	}
}