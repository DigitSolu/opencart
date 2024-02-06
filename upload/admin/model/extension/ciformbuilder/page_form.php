<?php
class ModelExtensionCiformbuilderPageForm extends Model {
	public function addPageForm($data) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "page_form` SET file_ext_allowed = '" . $this->db->escape($data['file_ext_allowed']) . "', file_mime_allowed = '" . $this->db->escape($data['file_mime_allowed']) . "', show_guest = '" . (int)$data['show_guest'] . "', sort_order = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "', customer_email_status = '" . (int)$data['customer_email_status'] . "', admin_email_status = '" . (int)$data['admin_email_status'] . "', admin_email = '" . $this->db->escape($data['admin_email']) . "', mail_alert_email_status = '" . $this->db->escape($data['mail_alert_email_status']) . "', mail_alert_email = '" . $this->db->escape($data['mail_alert_email']) . "', customer_field_attachment = '" . $this->db->escape($data['customer_field_attachment']) . "', admin_field_attachment = '" . $this->db->escape($data['admin_field_attachment']) . "', top = '" . (isset($data['top']) ? (int)$data['top'] : '') . "', bottom = '" . (isset($data['bottom']) ? (int)$data['bottom'] : '') . "', captcha = '" . (int)$data['captcha'] . "', css = '" . $this->db->escape($data['css']) . "', producttype = '" . $this->db->escape($data['producttype']) . "', popup_size  = '" . $this->db->escape($data['popup_size']) . "', reset_button  = '" . $this->db->escape($data['reset_button']) . "', default_form_status = '" . (int)$data['default_form_status'] . "',logo  = '" . $this->db->escape($data['logo']) . "',termcondition = '" . $this->db->escape(isset($data['termcondition']) ? json_encode($data['termcondition']) : json_encode(array())) . "',google_analytic = '" . $this->db->escape(isset($data['google_analytic']) ? json_encode($data['google_analytic']) : json_encode(array())) . "'");

		$page_form_id = $this->db->getLastId();

		foreach ($data['page_form_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "page_form_description SET page_form_id = '" . (int)$page_form_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', description = '" . $this->db->escape($value['description']) . "', bottom_description = '" . $this->db->escape($value['bottom_description']) . "', pbutton_title = '" . $this->db->escape($value['pbutton_title']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "', customer_subject = '" . $this->db->escape($value['customer_subject']) . "', customer_message = '" . $this->db->escape($value['customer_message']) . "', admin_subject = '" . $this->db->escape($value['admin_subject']) . "', admin_message = '" . $this->db->escape($value['admin_message']) . "', success_title = '" . $this->db->escape($value['success_title']) . "', success_description = '" . $this->db->escape($value['success_description']) . "', fieldset_title = '" . $this->db->escape($value['fieldset_title']) . "', submit_button = '" . $this->db->escape($value['submit_button']) . "'");
		}

		if (isset($data['page_form_store'])) {
			foreach ($data['page_form_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "page_form_store SET page_form_id = '" . (int)$page_form_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		if ($data['producttype'] == 'choose') {
			if (isset($data['page_form_product'])) {
				foreach ($data['page_form_product'] as $product_id) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "page_form_product SET page_form_id = '" . (int)$page_form_id . "', product_id = '" . (int)$product_id . "'");
				}
			}
		} else if ($data['producttype'] == 'all') {
			$this->db->query("INSERT INTO " . DB_PREFIX . "page_form_product SET page_form_id = '" . (int)$page_form_id . "', product_id = '" . $this->db->escape($data['producttype']) . "'");
		}

		if (isset($data['page_form_information'])) {
			foreach ($data['page_form_information'] as $information_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "page_form_information SET page_form_id = '" . (int)$page_form_id . "', information_id = '" . (int)$information_id . "'");
			}
		}

		if (isset($data['page_form_customer_group'])) {
			foreach ($data['page_form_customer_group'] as $customer_group_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "page_form_customer_group SET page_form_id = '" . (int)$page_form_id . "', customer_group_id = '" . (int)$customer_group_id . "'");
			}
		}

		if (isset($data['page_form_field'])) {
			foreach ($data['page_form_field'] as $page_form_field) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "page_form_option SET page_form_id = '" . (int)$page_form_id . "', required = '" . (int)$page_form_field['required'] . "', image_align = '" . $this->db->escape($page_form_field['image_align']) . "', thumb_type = '" . $this->db->escape($page_form_field['thumb_type']) . "', icon_class = '" . $this->db->escape($page_form_field['icon_class']) . "', icon_size = '" . $this->db->escape($page_form_field['icon_size']) . "', label_align = '" . $this->db->escape($page_form_field['label_align']) . "', label_display = '" . $this->db->escape($page_form_field['label_display']) . "', auto_fill_value = '" . $this->db->escape($page_form_field['auto_fill_value']) . "', image = '" . $this->db->escape($page_form_field['image']) . "', image_width = '" . $this->db->escape($page_form_field['image_width']) . "', image_height = '" . $this->db->escape($page_form_field['image_height']) . "', class = '" . $this->db->escape($page_form_field['class']) . "', number_input = '" . $this->db->escape($page_form_field['number_input']) . "', file_limit = '" . $this->db->escape($page_form_field['file_limit']) . "', width = '" . $this->db->escape($page_form_field['width']) . "', status = '" . (int)$page_form_field['status'] . "', type = '" . $this->db->escape($page_form_field['type']) . "', sort_order = '" . $this->db->escape($page_form_field['sort_order']) . "'");

				$page_form_option_id = $this->db->getLastId();

				if(isset($page_form_field['description'])) {
					foreach ($page_form_field['description'] as $language_id => $page_form_option_description) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "page_form_option_description SET page_form_option_id = '" . (int)$page_form_option_id . "', page_form_id = '" . (int)$page_form_id . "', language_id = '" . (int)$language_id . "', field_name = '" . $this->db->escape($page_form_option_description['field_name']) . "', field_help = '" . $this->db->escape($page_form_option_description['field_help']) . "', field_error = '" . $this->db->escape($page_form_option_description['field_error']) . "', field_display_message = '" . $this->db->escape($page_form_option_description['field_display_message']) . "', input_group_button_text = '" . $this->db->escape($page_form_option_description['input_group_button_text']) . "', field_placeholder = '" . $this->db->escape($page_form_option_description['field_placeholder']) . "', field_dvalue = '" . $this->db->escape($page_form_option_description['field_dvalue']) . "'");
					}
				}

				if (isset($page_form_field['option_value'])) {
					if ($page_form_field['type'] == 'select' || $page_form_field['type'] == 'radio' || $page_form_field['type'] == 'checkbox' || $page_form_field['type'] == 'radio_toggle' || $page_form_field['type'] == 'checkbox_switch' || $page_form_field['type'] == 'checkbox_toggle' || $page_form_field['type'] == 'multi_select') {
						foreach ($page_form_field['option_value'] as $page_form_option_value) {
							$this->db->query("INSERT INTO " . DB_PREFIX . "page_form_option_value SET page_form_option_id = '" . (int)$page_form_option_id . "', page_form_id = '" . (int)$page_form_id . "', sort_order = '" . (int)$page_form_option_value['sort_order'] . "', image = '" . $this->db->escape($page_form_option_value['image']) . "', color = '" . $this->db->escape($page_form_option_value['color']) . "', default_value = '" . (isset($page_form_option_value['default_value']) ? (int)$page_form_option_value['default_value'] : '') . "'");

							$page_form_option_value_id = $this->db->getLastId();

							if(isset($page_form_option_value['page_form_option_value_description'])) {

								foreach ($page_form_option_value['page_form_option_value_description'] as $language_id => $page_form_option_value_description) {
									$this->db->query("INSERT INTO " . DB_PREFIX . "page_form_option_value_description SET page_form_option_value_id = '" . (int)$page_form_option_value_id . "', page_form_option_id = '" . (int)$page_form_option_id . "', page_form_id = '" . (int)$page_form_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($page_form_option_value_description['name']) . "'");
								}
							}
						}
					}
				}
			}
		}

		if(VERSION <= '2.3.0.2') {
			if (isset($data['keyword'])) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'page_form_id=" . (int)$page_form_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
			}
		} else {
			// SEO URL
			if (isset($data['page_form_seo_url'])) {
				foreach ($data['page_form_seo_url'] as $store_id => $language) {
					foreach ($language as $language_id => $keyword) {
						if (!empty($keyword)) {
							$this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id = '" . (int)$store_id . "', language_id = '" . (int)$language_id . "', query = 'page_form_id=" . (int)$page_form_id . "', keyword = '" . $this->db->escape($keyword) . "'");
						}
					}
				}
			}
		}

		$page_form_id = $this->db->getLastId();

		return $page_form_id;
	}

	public function editPageForm($page_form_id, $data) {
		$this->db->query("UPDATE `" . DB_PREFIX . "page_form` SET file_ext_allowed = '" . $this->db->escape($data['file_ext_allowed']) . "', file_mime_allowed = '" . $this->db->escape($data['file_mime_allowed']) . "', show_guest = '" . (int)$data['show_guest'] . "', status = '" . $this->db->escape($data['status']) . "', sort_order = '" . (int)$data['sort_order'] . "', customer_email_status = '" . (int)$data['customer_email_status'] . "', admin_email_status = '" . (int)$data['admin_email_status'] . "', admin_email = '" . $this->db->escape($data['admin_email']) . "', mail_alert_email_status = '" . $this->db->escape($data['mail_alert_email_status']) . "', mail_alert_email = '" . $this->db->escape($data['mail_alert_email']) . "', customer_field_attachment = '" . $this->db->escape($data['customer_field_attachment']) . "', admin_field_attachment = '" . $this->db->escape($data['admin_field_attachment']) . "', top = '" . (isset($data['top']) ? (int)$data['top'] : '') . "', bottom = '" . (isset($data['bottom']) ? (int)$data['bottom'] : '') . "', captcha = '" . (int)$data['captcha'] . "', css = '" . $this->db->escape($data['css']) . "', producttype = '" . $this->db->escape($data['producttype']) . "', popup_size = '" . $this->db->escape($data['popup_size']) . "', reset_button = '" . $this->db->escape($data['reset_button']) . "', logo  = '" . $this->db->escape($data['logo']) . "',default_form_status = '" . (int)$data['default_form_status'] . "',termcondition = '" . $this->db->escape(isset($data['termcondition']) ? json_encode($data['termcondition']) : json_encode(array())) . "',google_analytic = '" . $this->db->escape(isset($data['google_analytic']) ? json_encode($data['google_analytic']) : json_encode(array())) . "' WHERE page_form_id = '" . (int)$page_form_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "page_form_description WHERE page_form_id = '" . (int)$page_form_id . "'");

		foreach ($data['page_form_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "page_form_description SET page_form_id = '" . (int)$page_form_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', description = '" . $this->db->escape($value['description']) . "', bottom_description = '" . $this->db->escape($value['bottom_description']) . "', pbutton_title = '" . $this->db->escape($value['pbutton_title']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "', customer_subject = '" . $this->db->escape($value['customer_subject']) . "', customer_message = '" . $this->db->escape($value['customer_message']) . "', admin_subject = '" . $this->db->escape($value['admin_subject']) . "', admin_message = '" . $this->db->escape($value['admin_message']) . "', success_title = '" . $this->db->escape($value['success_title']) . "', success_description = '" . $this->db->escape($value['success_description']) . "', fieldset_title = '" . $this->db->escape($value['fieldset_title']) . "', submit_button = '" . $this->db->escape($value['submit_button']) . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "page_form_store WHERE page_form_id = '" . (int)$page_form_id . "'");

		if (isset($data['page_form_store'])) {
			foreach ($data['page_form_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "page_form_store SET page_form_id = '" . (int)$page_form_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "page_form_product WHERE page_form_id = '" . (int)$page_form_id . "'");

		if ($data['producttype'] == 'choose') {
			if (isset($data['page_form_product'])) {
				foreach ($data['page_form_product'] as $product_id) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "page_form_product SET page_form_id = '" . (int)$page_form_id . "', product_id = '" . (int)$product_id . "'");
				}
			}
		} else if ($data['producttype'] == 'all') {
			$this->db->query("INSERT INTO " . DB_PREFIX . "page_form_product SET page_form_id = '" . (int)$page_form_id . "', product_id = '" . $this->db->escape($data['producttype']) . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "page_form_information WHERE page_form_id = '" . (int)$page_form_id . "'");

		if (isset($data['page_form_information'])) {
			foreach ($data['page_form_information'] as $information_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "page_form_information SET page_form_id = '" . (int)$page_form_id . "', information_id = '" . (int)$information_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "page_form_customer_group WHERE page_form_id = '" . (int)$page_form_id . "'");

		if (isset($data['page_form_customer_group'])) {
			foreach ($data['page_form_customer_group'] as $customer_group_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "page_form_customer_group SET page_form_id = '" . (int)$page_form_id . "', customer_group_id = '" . (int)$customer_group_id . "'");
			}
		}


		$this->db->query("DELETE FROM `" . DB_PREFIX . "page_form_option` WHERE page_form_id = '" . (int)$page_form_id . "'");

		$this->db->query("DELETE FROM `" . DB_PREFIX . "page_form_option_description` WHERE page_form_id = '" . (int)$page_form_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "page_form_option_value` WHERE page_form_id = '" . (int)$page_form_id . "'");

		$this->db->query("DELETE FROM `" . DB_PREFIX . "page_form_option_value_description` WHERE page_form_id = '" . (int)$page_form_id . "'");

		if (isset($data['page_form_field'])) {
			foreach ($data['page_form_field'] as $page_form_field) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "page_form_option SET page_form_option_id = '". (int)$page_form_field['page_form_option_id'] ."', page_form_id = '" . (int)$page_form_id . "', required = '" . (int)$page_form_field['required'] . "', thumb_type = '" . $this->db->escape($page_form_field['thumb_type']) . "', image_align = '" . $this->db->escape($page_form_field['image_align']) . "', icon_class = '" . $this->db->escape($page_form_field['icon_class']) . "', icon_size = '" . $this->db->escape($page_form_field['icon_size']) . "', label_align = '" . $this->db->escape($page_form_field['label_align']) . "', label_display = '" . $this->db->escape($page_form_field['label_display']) . "', auto_fill_value = '" . $this->db->escape($page_form_field['auto_fill_value']) . "', image = '" . $this->db->escape($page_form_field['image']) . "', image_width = '" . $this->db->escape($page_form_field['image_width']) . "', image_height = '" . $this->db->escape($page_form_field['image_height']) . "', class = '" . $this->db->escape($page_form_field['class']) . "', number_input = '" . $this->db->escape($page_form_field['number_input']) . "', file_limit = '" . $this->db->escape($page_form_field['file_limit']) . "', width = '" . $this->db->escape($page_form_field['width']) . "', status = '" . (int)$page_form_field['status'] . "', type = '" . $this->db->escape($page_form_field['type']) . "', sort_order = '" . $this->db->escape($page_form_field['sort_order']) . "'");

				$page_form_option_id = $this->db->getLastId();

				if(isset($page_form_field['description'])) {
					foreach ($page_form_field['description'] as $language_id => $page_form_option_description) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "page_form_option_description SET page_form_option_id = '" . (int)$page_form_option_id . "', page_form_id = '" . (int)$page_form_id . "', language_id = '" . (int)$language_id . "', field_name = '" . $this->db->escape($page_form_option_description['field_name']) . "', field_help = '" . $this->db->escape($page_form_option_description['field_help']) . "', field_error = '" . $this->db->escape($page_form_option_description['field_error']) . "', field_display_message = '" . $this->db->escape($page_form_option_description['field_display_message']) . "', input_group_button_text = '" . $this->db->escape($page_form_option_description['input_group_button_text']) . "', field_placeholder = '" . $this->db->escape($page_form_option_description['field_placeholder']) . "', field_dvalue = '" . $this->db->escape($page_form_option_description['field_dvalue']) . "'");
					}
				}

				if (isset($page_form_field['option_value'])) {
					if ($page_form_field['type'] == 'select' || $page_form_field['type'] == 'radio' || $page_form_field['type'] == 'checkbox' || $page_form_field['type'] == 'radio_toggle' || $page_form_field['type'] == 'checkbox_switch' || $page_form_field['type'] == 'checkbox_toggle' || $page_form_field['type'] == 'multi_select') {
						foreach ($page_form_field['option_value'] as $page_form_option_value) {
							$this->db->query("INSERT INTO " . DB_PREFIX . "page_form_option_value SET page_form_option_value_id = '". (int)$page_form_option_value['page_form_option_value_id'] ."', page_form_option_id = '" . (int)$page_form_option_id . "', page_form_id = '" . (int)$page_form_id . "', sort_order = '" . (int)$page_form_option_value['sort_order'] . "', image = '" . $this->db->escape($page_form_option_value['image']) . "', color = '" . $this->db->escape($page_form_option_value['color']) . "', default_value = '" . (isset($page_form_option_value['default_value']) ? (int)$page_form_option_value['default_value'] : '') . "'");

							$page_form_option_value_id = $this->db->getLastId();

							if(isset($page_form_option_value['page_form_option_value_description'])) {

								foreach ($page_form_option_value['page_form_option_value_description'] as $language_id => $page_form_option_value_description) {
									$this->db->query("INSERT INTO " . DB_PREFIX . "page_form_option_value_description SET page_form_option_value_id = '" . (int)$page_form_option_value_id . "', page_form_option_id = '" . (int)$page_form_option_id . "', page_form_id = '" . (int)$page_form_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($page_form_option_value_description['name']) . "'");
								}
							}
						}
					}
				}
			}
		}

		if(VERSION <= '2.3.0.2') {
			$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'page_form_id=" . (int)$page_form_id . "'");

			if ($data['keyword']) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'page_form_id=" . (int)$page_form_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
			}
		} else {
			// SEO URL
			$this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query = 'page_form_id=" . (int)$page_form_id . "'");

			if (isset($data['page_form_seo_url'])) {
				foreach ($data['page_form_seo_url'] as $store_id => $language) {
					foreach ($language as $language_id => $keyword) {
						if (!empty($keyword)) {
							$this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id = '" . (int)$store_id . "', language_id = '" . (int)$language_id . "', query = 'page_form_id=" . (int)$page_form_id . "', keyword = '" . $this->db->escape($keyword) . "'");
						}
					}
				}
			}
		}
	}

	public function deletePageForm($page_form_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "page_form` WHERE page_form_id = '" . (int)$page_form_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "page_form_customer_group` WHERE page_form_id = '" . (int)$page_form_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "page_form_description` WHERE page_form_id = '" . (int)$page_form_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "page_form_option` WHERE page_form_id = '" . (int)$page_form_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "page_form_option_description` WHERE page_form_id = '" . (int)$page_form_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "page_form_option_value` WHERE page_form_id = '" . (int)$page_form_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "page_form_option_value_description` WHERE page_form_id = '" . (int)$page_form_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "page_form_product` WHERE page_form_id = '" . (int)$page_form_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "page_form_store` WHERE page_form_id = '" . (int)$page_form_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "page_form_information` WHERE page_form_id = '" . (int)$page_form_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "page_form_status_email` WHERE page_form_id = '" . (int)$page_form_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "page_form_status_email_description` WHERE page_form_id = '" . (int)$page_form_id . "'");

		if(VERSION <= '2.3.0.2') {
			$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'page_form_id=" . (int)$page_form_id . "'");
		} else {
			$this->db->query("DELETE FROM `" . DB_PREFIX . "seo_url` WHERE query = 'page_form_id=" . (int)$page_form_id . "'");
		}
	}

	public function getPageForm($page_form_id) {
		if(VERSION <= '2.3.0.2') {
			$query = $this->db->query("SELECT DISTINCT *, (SELECT DISTINCT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'page_form_id=" . (int)$page_form_id . "') AS keyword  FROM `" . DB_PREFIX . "page_form` o LEFT JOIN " . DB_PREFIX . "page_form_description pd ON (o.page_form_id = pd.page_form_id) WHERE o.page_form_id = '" . (int)$page_form_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
		} else {
			$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "page_form` o LEFT JOIN " . DB_PREFIX . "page_form_description pd ON (o.page_form_id = pd.page_form_id) WHERE o.page_form_id = '" . (int)$page_form_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
		}

		return $query->row;
	}

	public function getPageForms($data = array()) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "page_form` p LEFT JOIN " . DB_PREFIX . "page_form_description pd ON (p.page_form_id = pd.page_form_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_page_form_id'])) {
			$sql .= " AND p.page_form_id = '" . (int)$data['filter_page_form_id'] . "'";
		}

		if (!empty($data['filter_title'])) {
			$sql .= " AND pd.title LIKE '%" . $this->db->escape($data['filter_title']) . "%'";
		}

		if (isset($data['filter_status']) && $data['filter_status'] !== '') {
			$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
		}

		$sort_data = array(
			'p.page_form_id',
			'pd.title',
			'p.status',
			'p.sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY pd.title";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getPageFormDescriptions($page_form_id) {
		$page_form_description_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "page_form_description WHERE page_form_id = '" . (int)$page_form_id . "'");

		foreach ($query->rows as $result) {
			$page_form_description_data[$result['language_id']] = array(
				'title'            		=> $result['title'],
				'description'      		=> $result['description'],
				'bottom_description'    => $result['bottom_description'],
				'pbutton_title'      	=> $result['pbutton_title'],
				'meta_title'       		=> $result['meta_title'],
				'meta_description' 		=> $result['meta_description'],
				'meta_keyword'     		=> $result['meta_keyword'],
				'admin_subject'     	=> $result['admin_subject'],
				'admin_message'     	=> $result['admin_message'],
				'customer_subject'    	=> $result['customer_subject'],
				'customer_message'     	=> $result['customer_message'],
				'success_title'     	=> $result['success_title'],
				'success_description' 	=> $result['success_description'],
				'fieldset_title' 		=> $result['fieldset_title'],
				'submit_button' 		=> $result['submit_button'],
			);
		}

		return $page_form_description_data;
	}

	public function getTotalPageForms($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "page_form` p LEFT JOIN " . DB_PREFIX . "page_form_description pd ON (p.page_form_id = pd.page_form_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_page_form_id'])) {
			$sql .= " AND p.page_form_id = '" . (int)$data['filter_page_form_id'] . "'";
		}

		if (!empty($data['filter_title'])) {
			$sql .= " AND pd.title LIKE '%" . $this->db->escape($data['filter_title']) . "%'";
		}

		if (isset($data['filter_status']) && $data['filter_status'] !== '') {
			$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getPageFormSeoUrls($page_form_id) {
		$page_form_seo_url_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "seo_url WHERE query = 'page_form_id=" . (int)$page_form_id . "'");

		foreach ($query->rows as $result) {
			$page_form_seo_url_data[$result['store_id']][$result['language_id']] = $result['keyword'];
		}

		return $page_form_seo_url_data;
	}

	public function getPageFormStores($page_form_id) {
		$page_form_store_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "page_form_store WHERE page_form_id = '" . (int)$page_form_id . "'");

		foreach ($query->rows as $result) {
			$page_form_store_data[] = $result['store_id'];
		}

		return $page_form_store_data;
	}

	public function getPageFormInformations($page_form_id) {
		$page_form_information_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "page_form_information WHERE page_form_id = '" . (int)$page_form_id . "'");

		foreach ($query->rows as $result) {
			$page_form_information_data[] = $result['information_id'];
		}

		return $page_form_information_data;
	}

	public function getPageFormProducts($page_form_id) {
		$page_form_product_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "page_form_product WHERE page_form_id = '" . (int)$page_form_id . "'");

		foreach ($query->rows as $result) {
			$page_form_product_data[] = $result['product_id'];
		}

		return $page_form_product_data;
	}

	public function getPageFormCustomerGroups($page_form_id) {
		$page_form_customer_group_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "page_form_customer_group WHERE page_form_id = '" . (int)$page_form_id . "'");

		foreach ($query->rows as $result) {
			$page_form_customer_group_data[] = $result['customer_group_id'];
		}

		return $page_form_customer_group_data;
	}

	public function getPageFormOptions($page_form_id) {
		$this->load->model('tool/image');

		$page_form_option_data = array();

		$page_form_option_query = $this->db->query("SELECT *, pfo.sort_order as sort_order FROM `" . DB_PREFIX . "page_form_option` pfo LEFT JOIN `" . DB_PREFIX . "page_form_option_description` pfod ON (pfo.page_form_option_id = pfod.page_form_option_id) WHERE pfo.page_form_id = '" . (int)$page_form_id . "' AND pfod.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY pfo.sort_order ASC");

		foreach ($page_form_option_query->rows as $page_form_option) {
			$page_form_description_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "page_form_option_description` WHERE page_form_id = '" . (int)$page_form_option['page_form_id'] . "' AND  page_form_option_id = '". (int)$page_form_option['page_form_option_id']  ."'");

			$page_form_description_data = array();
			foreach ($page_form_description_query->rows as $page_form_description_value) {
				$page_form_description_data[$page_form_description_value['language_id']] = array(
					'field_name' 	 	=> $page_form_description_value['field_name'],
					'field_help' 	 	=> $page_form_description_value['field_help'],
					'field_placeholder' => $page_form_description_value['field_placeholder'],
					'field_dvalue' 		=> $page_form_description_value['field_dvalue'],
					'field_error'    	=> $page_form_description_value['field_error'],
					'field_display_message'    	=> $page_form_description_value['field_display_message'],
					'input_group_button_text' => $page_form_description_value['input_group_button_text'],
				);
			}

			$page_form_option_value_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "page_form_option_value` pfov WHERE pfov.page_form_id = '" . (int)$page_form_id . "' AND pfov.page_form_option_id = '". (int)$page_form_option['page_form_option_id'] ."' ORDER BY pfov.sort_order ASC");

			$page_form_option_values = array();
			$page_form_option_values = array();
			foreach ($page_form_option_value_query->rows as $page_form_option_value) {
				$page_form_option_value_description_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "page_form_option_value_description` WHERE page_form_id = '" . (int)$page_form_id . "' AND page_form_option_id = '". (int)$page_form_option_value['page_form_option_id'] ."' AND page_form_option_value_id = '". (int)$page_form_option_value['page_form_option_value_id'] ."'");

				$page_form_option_value_description_data = array();
				foreach ($page_form_option_value_description_query->rows as $page_form_option_value_description_value) {
					$page_form_option_value_description_data[$page_form_option_value_description_value['language_id']] = array(
						'name'    			 => $page_form_option_value_description_value['name'],
					);

				}

				if (!empty($page_form_option_value['image']) && is_file(DIR_IMAGE . $page_form_option_value['image'])) {
					$thumb = $this->model_tool_image->resize($page_form_option_value['image'], 50, 50);
				} else {
					$thumb = $this->model_tool_image->resize('no_image.png', 50, 50);
				}

				$page_form_option_values[] = array(
					'page_form_option_value_id'  => $page_form_option_value['page_form_option_value_id'],
					'page_form_option_id'  		 => $page_form_option_value['page_form_option_id'],
					'sort_order'    			 => $page_form_option_value['sort_order'],
					'image'    			 		 => $page_form_option_value['image'],
					'thumb'    			 		 => $thumb,
					'color'    			 		 => $page_form_option_value['color'],
					'default_value'    			 => $page_form_option_value['default_value'],
					'page_form_option_value_description'		 		 => $page_form_option_value_description_data,
				);
			}

			if (!empty($page_form_option['image']) && is_file(DIR_IMAGE . $page_form_option['image'])) {
				$thumb = $this->model_tool_image->resize($page_form_option['image'], 50, 50);
			} else {
				$thumb = $this->model_tool_image->resize('no_image.png', 50, 50);
			}

			$page_form_option_data[] = array(
				'page_form_option_id'  => $page_form_option['page_form_option_id'],
				'type'                 => $page_form_option['type'],
				'required'             => $page_form_option['required'],
				'thumb_type'           => $page_form_option['thumb_type'] ? $page_form_option['thumb_type'] : 'none',
				'icon_class'           => $page_form_option['icon_class'] ? $page_form_option['icon_class'] : 'fa fa-tag',
				'icon_size'            => $page_form_option['icon_size'] ? $page_form_option['icon_size'] : 70,
				'image_align'          => $page_form_option['image_align'] ? $page_form_option['image_align'] : 'left',
				'label_align'          => $page_form_option['label_align'] ? $page_form_option['label_align'] : 'left',
				'label_display'        => $page_form_option['label_display'],
				'auto_fill_value'      => $page_form_option['auto_fill_value'],
				'image_width'          => $page_form_option['image_width'] ? $page_form_option['image_width'] : 70,
				'image_height'         => $page_form_option['image_height'] ? $page_form_option['image_height'] : 70,
				'image'                => $page_form_option['image'],
				'thumb'                => $thumb,
				'class'                => $page_form_option['class'],
				'number_input'         => $page_form_option['number_input'] ? $page_form_option['number_input'] : 1,
				'file_limit'           => $page_form_option['file_limit'] ? $page_form_option['file_limit'] : 1,
				'width'                => $page_form_option['width'] ? $page_form_option['width'] : 12,
				'status'               => $page_form_option['status'],
				'sort_order'           => $page_form_option['sort_order'],
				'field_name'           => $page_form_option['field_name'],
				'field_help'           => $page_form_option['field_help'],
				'description'		   => $page_form_description_data,
				'option_value'		   => $page_form_option_values,
			);
		}

		return $page_form_option_data;
	}

	public function copyPageForm($page_form_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "page_form WHERE page_form_id = '" . (int)$page_form_id . "'");

		if ($query->num_rows) {
			$data = $query->row;

			$data['status'] = '0';
			$data['keyword'] = '';

			$data['page_form_description'] = $this->getPageFormDescriptions($page_form_id);
			$data['page_form_field'] = $this->getPageFormOptions($page_form_id);
			$data['page_form_customer_group'] = $this->getPageFormCustomerGroups($page_form_id);
			$data['page_form_store'] = $this->getPageFormStores($page_form_id);
			$data['page_form_information'] = $this->getPageFormInformations($page_form_id);
			$data['page_form_product'] = $this->getPageFormProducts($page_form_id);

			$this->addPageForm($data);
		}
	}

	public function addStatus($page_form_id,$data) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "page_form_status_email` WHERE page_form_id = '" . (int)$page_form_id . "'");

		$this->db->query("DELETE FROM `" . DB_PREFIX . "page_form_status_email_description` WHERE page_form_id = '" . (int)$page_form_id . "'");

		if(isset($data['page_form_status_email'])){

			foreach ($data['page_form_status_email'] as $form_status_id => $form_status_data) {

				$this->db->query("INSERT INTO " . DB_PREFIX . "page_form_status_email SET page_form_id = '" . (int)$page_form_id . "',form_status_id = '" . (int)$form_status_id . "',status = '" . (int)$form_status_data['status'] . "',sort_order = '" . (int)$form_status_data['sort_order'] . "',attachment = '" . $this->db->escape(isset($form_status_data['attachment']) ? json_encode($form_status_data['attachment']) : json_encode(array())) . "'");

				

				$form_status_email_id = $this->db->getLastId();

				

				foreach ($form_status_data['desc'] as $language_id => $value) {

					$this->db->query("INSERT INTO " . DB_PREFIX . "page_form_status_email_description SET form_status_email_id = '" . (int)$form_status_email_id . "',page_form_id = '" . (int)$page_form_id . "',form_status_id = '" . (int)$form_status_id . "', language_id = '" . (int)$language_id . "', subject = '" . $this->db->escape($value['subject']) . "', message = '" . $this->db->escape($value['message']) . "'");

				}

			}

		}
	}
}