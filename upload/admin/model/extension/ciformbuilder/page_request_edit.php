<?php
class ModelExtensionCiformbuilderPageRequestEdit extends Model {
	public function updatePageRequest($data) {
		$this->db->query("UPDATE " . DB_PREFIX . "page_request SET page_form_id = '". (int)$data['page_form_id'] ."', page_form_title = '" . $this->db->escape($data['page_form_title']) . "', read_status = '0' WHERE page_request_id = '". (int)$data['page_request_id'] ."'");

		$page_request_id = $data['page_request_id'];

		// Page Request Options - (Fields) //// $field_data ////
		$this->db->query("DELETE FROM `" . DB_PREFIX . "page_request_option` WHERE page_request_id = '" . (int)$page_request_id . "'");

		if (isset($data['field_data'])) {
			foreach ($data['field_data'] as $field_data) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "page_request_option SET page_request_id = '" . (int)$page_request_id . "', page_form_id = '" . (int)$data['page_form_id'] . "', name = '" . $this->db->escape($field_data['name']) . "', value = '" . $this->db->escape($field_data['value']) . "', type = '" . $this->db->escape($field_data['type']) . "', page_form_option_id = '" . $this->db->escape($field_data['page_form_option_id']) . "', page_form_option_value_id = '" . $this->db->escape($field_data['page_form_option_value_id']) . "'");

				if($field_data['type'] == 'firstname') {
					$this->db->query("UPDATE " . DB_PREFIX . "page_request SET firstname = '" . $this->db->escape($field_data['value']) . "' WHERE page_request_id = '" . (int)$page_request_id . "'");
				}

				if($field_data['type'] == 'lastname') {
					$this->db->query("UPDATE " . DB_PREFIX . "page_request SET lastname = '" . $this->db->escape($field_data['value']) . "' WHERE page_request_id = '" . (int)$page_request_id . "'");
				}
			}
		}
	}
	public function getPageForm($page_form_id, $language_id, $store_id = 0) {
		$forms_data = array();
		$sql = "SELECT DISTINCT * FROM " . DB_PREFIX . "page_form p LEFT JOIN " . DB_PREFIX . "page_form_description pd ON (p.page_form_id = pd.page_form_id) LEFT JOIN " . DB_PREFIX . "page_form_store p2s ON (p.page_form_id = p2s.page_form_id) WHERE p.page_form_id = '" . (int)$page_form_id . "' AND pd.language_id = '" . (int)$language_id . "' AND p2s.store_id = '" . (int)$store_id . "' AND p.status = '1'";

		$query = $this->db->query($sql);

		return $query->row;
	}

	public function getPageFormOptions($page_form_id, $page_request_id, $language_id, $store_id = 0) {
		$this->load->model('tool/upload');
		$this->load->model('localisation/country');
		$this->load->model('localisation/zone');

		$page_form_option_data = array();

		$page_form_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "page_form_option pfo LEFT JOIN " . DB_PREFIX . "page_form_option_description pfod ON (pfo.page_form_option_id = pfod.page_form_option_id) WHERE pfo.page_form_id = '" . (int)$page_form_id . "' AND pfod.language_id = '" . (int)$language_id . "' AND pfo.status ORDER BY pfo.sort_order ASC");

		foreach ($page_form_option_query->rows as $page_form_option) {

			$page_form_option_value_data = array();

			$page_form_option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "page_form_option_value pfov LEFT JOIN " . DB_PREFIX . "page_form_option_value_description pfovd ON (pfov.page_form_option_value_id = pfovd.page_form_option_value_id) WHERE pfov.page_form_option_id = '" . (int)$page_form_option['page_form_option_id'] . "' AND pfovd.language_id = '" . (int)$language_id . "' ORDER BY pfov.sort_order ASC");

			foreach ($page_form_option_value_query->rows as $page_form_option_value) {
				$page_form_option_value_data[] = array(
					'page_form_option_value_id'      => $page_form_option_value['page_form_option_value_id'],
					'name'                    	=> $page_form_option_value['name'],
					'default_value'             => '',
				);
			}

			$field_dvalue = '';
			$extra_field_dvalue = [];
			$zones = '';

			$page_request_option_info = $this->db->query("SELECT * FROM " . DB_PREFIX . "page_request_option WHERE page_request_id = '" . (int)$page_request_id . "' AND page_form_option_id = '". (int)$page_form_option['page_form_option_id'] ."'")->row;

			if($page_request_option_info) {
				if($page_form_option['type'] == 'password' || $page_form_option['type'] == 'confirm_password') {
					$field_dvalue = unserialize(base64_decode($page_request_option_info['value']));
				} else if($page_form_option['type'] == 'zone') {
					$field_dvalue = $page_request_option_info['page_form_option_value_id'];
					$country_info = $this->getCountryByZoneId($page_request_option_info['page_form_option_value_id']);
					if($country_info) {
						$zones = $this->model_localisation_zone->getZonesByCountryId($country_info['country_id']);
					} else {
						$zones = [];
					}
				} else if (in_array($page_form_option['type'], array('country','zone', 'select', 'radio', 'radio_toggle', 'country'))) {
					$field_dvalue = $page_request_option_info['page_form_option_value_id'];
				} else if (in_array($page_form_option['type'], array('checkbox', 'checkbox_switch', 'checkbox_toggle', 'multi_select'))) {
					$field_dvalue = json_decode($page_request_option_info['page_form_option_value_id'], true);
				} else if ($page_form_option['type'] == 'file') {
					$field_dvalue = $page_request_option_info['value'];

					$file_array = explode(',', $page_request_option_info['value']);
					$value_file = [];
					foreach($file_array as $file_val) {
						$upload_info = $this->model_tool_upload->getUploadByCode($file_val);
						if ($upload_info) {
							$extra_field_dvalue[] = [
								'filename'			=> $upload_info['name'],
								'value'				=> $upload_info['code'],
							];
						}
					}
				} else {
					$field_dvalue = $page_request_option_info['value'];
				}
			}

			$page_form_option_data[] = array(
				'page_form_option_id'    => $page_form_option['page_form_option_id'],
				'page_form_option_value' => $page_form_option_value_data,
				'field_name'             => $page_form_option['field_name'],
				'field_help'             => $page_form_option['field_help'],
				'type'                	 => $page_form_option['type'],
				'field_value'            => $page_form_option['field_value'],
				'field_placeholder'      => $page_form_option['field_placeholder'],
				'field_error'      		 => $page_form_option['field_error'],
				'class'      		 	 => $page_form_option['class'],
				'file_limit'      		 => $page_form_option['file_limit'],
				'width'      		 	 => $page_form_option['width'],
				'required'             	 => $page_form_option['required'],
				'field_dvalue'         	 => $field_dvalue,
				'extra_field_dvalue'	 => $extra_field_dvalue,
				'zones'	 				 => $zones,
			);
		}

		return $page_form_option_data;
	}

	public function getPageFormOptionsCountry($page_form_id) {
		$query = $this->db->query("SELECT count(*) as total_country_exists FROM " . DB_PREFIX . "page_form_option pfo WHERE pfo.page_form_id = '" . (int)$page_form_id . "' AND pfo.type = 'country'");

		return $query->row['total_country_exists'];
	}

	public function getPageRequestEmailByPageFormID($email, $page_form_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "page_request_option` WHERE LOWER(`value`) = '" . $this->db->escape(utf8_strtolower($email)) . "' AND `page_form_id` = '" . (int)$page_form_id . "' AND (`type` = 'email' OR `type` = 'email_exists')");

		return $query->row;
	}

	public function getCountryByZoneId($zone_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone WHERE zone_id = '" . (int)$zone_id . "'");

		return $query->row;
	}
}