<?php
class ModelExtensionCiformbuilderRequest extends Model {
	public function addPageRequest($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "page_request SET page_form_id = '". (int)$data['page_form_id'] ."', customer_id = '" . (int)$data['customer_id'] . "', customer_group_id = '" . (int)$data['customer_group_id'] . "', store_id = '" . (int)$data['store_id'] . "', language_id = '" . (int)$data['language_id'] . "', page_form_title = '" . $this->db->escape($data['page_form_title']) . "',  firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', ip = '" . $this->db->escape($data['ip']) . "', user_agent = '" . $this->db->escape($this->request->server['HTTP_USER_AGENT']) . "', product_id = '" . (int)$data['product_id'] . "', product_name = '" . $this->db->escape($data['product_name']) . "', date_added = NOW()");

		$page_request_id = $this->db->getLastId();

		// Page Request Options - (Fields) //// $field_data ////
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

		
		// Send Email System
		$this->load->model('extension/ciformbuilder/form');

		$this->load->model('tool/upload');

		$page_form_info = $this->model_extension_ciformbuilder_form->getPageForm($data['page_form_id']);

		if($page_form_info) {
			if ($page_form_info['default_form_status']) {
				$this->db->query("UPDATE `" . DB_PREFIX . "page_request` SET form_status_id = '" . (int)$page_form_info['default_form_status'] . "', date_modified = NOW() WHERE page_request_id = '" . (int)$page_request_id . "'");

				$this->db->query("INSERT INTO " . DB_PREFIX . "page_request_history SET page_request_id = '" . (int)$page_request_id . "',page_form_id = '" . (int)$data['page_form_id'] . "', form_status_id = '" . (int)$page_form_info['default_form_status'] . "', date_added = NOW()");
			}

			if(!empty($page_form_info['customer_email_status'])) {
				$page_request_info = $this->getPageRequestEmail($page_form_info['page_form_id'], $page_request_id);

				if($page_request_info) {
					$customer_email = $page_request_info['value'];
				} else{
					$customer_email = $this->customer->getEmail();
				}
				if($customer_email) {
					if(!empty($page_form_info['customer_subject'])) {
						$subject = $page_form_info['customer_subject'];
					} else{
						$subject = '';
					}

					if(!empty($page_form_info['customer_message'])) {
						$message = $page_form_info['customer_message'];
					} else{
						$message = '';
					}

					if ($this->request->server['HTTPS']) {
						$server = $this->config->get('config_ssl');
					} else {
						$server = $this->config->get('config_url');
					}

					if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
						$logo = $server . 'image/' . $this->config->get('config_logo');
					} else {
						$logo = '';
					}

					$home_href = $this->url->link('common/home', '', true);

					$information_data = array();
					$information_data['infos'] = array();
					$upload_data = array();
					if (isset($data['field_data'])) {
						foreach ($data['field_data'] as $field_data) {
							if($field_data) {
								if($field_data['type'] == 'password' || $field_data['type'] == 'confirm_password') {
									$field_data['value'] = unserialize(base64_decode($field_data['value']));

								}

								if ($field_data['type'] != 'file') {
									$information_data['infos'][] = array(
										'name'			=> $field_data['name'],
										'value'			=> nl2br($field_data['value']),
										'type'  		=> $field_data['type'],
									);
								} else {
									$explode_file_values = explode(',', $field_data['value']);
									$value_file = [];

									foreach($explode_file_values as $explode_file_value) {
										$upload_info = $this->model_tool_upload->getUploadByCode($explode_file_value);
										if ($upload_info) {

											$value_file[] = [
												'filename' 		=> $upload_info['name'],
												'href'  		=> $this->url->link('extension/ciformbuilder/form/download', 'code=' . $upload_info['code'], true),

											];

											$orgname = DIR_UPLOAD . $upload_info['filename'];
											$temp_name = DIR_UPLOAD . $upload_info['name'];
											copy($orgname, $temp_name);
											$upload_data[] = $temp_name;
										}
									}

									if($value_file) {
										$information_data['infos'][] = array(
											'name'			=> $field_data['name'],
											'value'			=> $value_file,
											'type'  		=> $field_data['type'],
										);
									}

								}
							}

						}
					}

					if(VERSION < '2.2.0.0') {
						if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extension/mail_formsubmit_info.tpl')) {
							$information_html = $this->load->view($this->config->get('config_template') . '/template/extension/ciformbuilder/page_oc2/mail_formsubmit_info.tpl', $information_data);
						} else {
							$information_html = $this->load->view('default/template/extension/ciformbuilder/page_oc2/mail_formsubmit_info.tpl', $information_data);
						}
					} else if(VERSION <= '2.3.0.2') {
						$information_html = $this->load->view('extension/ciformbuilder/page_oc2/mail_formsubmit_info', $information_data);
					} else {
						$information_html = $this->load->view('extension/ciformbuilder/page_oc3/mail_formsubmit_info', $information_data);
					}

					$find = array(
						'{STORE_NAME}',
						'{STORE_LINK}',
						'{LOGO}',
						'{PRODUCT_ID}',
						'{PRODUCT_NAME}',
						'{PRODUCT_MODEL}',
						'{PRODUCT_LINK}',
						'{PRODUCT_IMAGE}',
						'{INFORMATION}',
					);

					$replace = array(
						'STORE_NAME'					=> $this->config->get('config_name'),
						'STORE_LINK'					=> $home_href,
						'LOGO'							=> '<img src="'. $logo .'" alt="'. $this->config->get('config_name') .'" title="'. $this->config->get('config_name') .'" />',
						'PRODUCT_ID'					=> $data['product_id'],
						'PRODUCT_NAME'					=> $data['product_name'],
						'PRODUCT_MODEL'					=> $data['product_model'],
						'PRODUCT_LINK'					=> $data['product_link'],
						'PRODUCT_IMAGE'					=> $data['product_image'],
						'INFORMATION'					=> $information_html,
					);

					if(!empty($subject)) {
						$subject = str_replace($find, $replace, $subject);
					}else{
						$subject = '';
					}

					$html_data = array();
					$html_data['subject'] = $subject;

					if(!empty($message)) {
						$html_data['message'] = str_replace($find, $replace, $message);
						$html_data['message'] = html_entity_decode($html_data['message'], ENT_QUOTES, 'UTF-8');
					}else{
						$html_data['message'] = '';
					}

					if(VERSION < '2.2.0.0') {
						if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extension/mail_formsubmit_customer.tpl')) {
							$mail_html = $this->load->view($this->config->get('config_template') . '/template/extension/ciformbuilder/page_oc2/mail_formsubmit_customer.tpl', $html_data);
						} else {
							$mail_html = $this->load->view('default/template/extension/ciformbuilder/page_oc2/mail_formsubmit_customer.tpl', $html_data);
						}
					} else if(VERSION <= '2.3.0.2') {
						$mail_html = $this->load->view('extension/ciformbuilder/page_oc2/mail_formsubmit_customer', $html_data);
					} else {
						$mail_html = $this->load->view('extension/ciformbuilder/page_oc3/mail_formsubmit_customer', $html_data);
					}

					if(VERSION >= '3.0.0.0') {
						$mail = new Mail($this->config->get('config_mail_engine'));
						$mail->parameter = $this->config->get('config_mail_parameter');
						$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
						$mail->smtp_username = $this->config->get('config_mail_smtp_username');
						$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
						$mail->smtp_port = $this->config->get('config_mail_smtp_port');
						$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
					} else if(VERSION <= '2.0.1.1') {
				     	$mail = new Mail($this->config->get('config_mail'));
				    } else {
						$mail = new Mail();
						$mail->protocol = $this->config->get('config_mail_protocol');
						$mail->parameter = $this->config->get('config_mail_parameter');
						$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
						$mail->smtp_username = $this->config->get('config_mail_smtp_username');
						$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
						$mail->smtp_port = $this->config->get('config_mail_smtp_port');
						$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
					}

					$mail->setTo($customer_email);
					$mail->setFrom($this->config->get('config_email'));
					$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));

					if(!empty($upload_data) && !empty($page_form_info['customer_field_attachment'])) {
						foreach ($upload_data as $upload_file_name) {
							if(filesize($upload_file_name) <= 10485760) {
								$mail->addAttachment($upload_file_name);
							}
						}
					}

					$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
					$mail->setHtml($mail_html);

					$mail->send();
				}
			}

			if(!empty($page_form_info['admin_email_status'])) {
				if(!empty($page_form_info['admin_email'])) {
					$admin_email = $page_form_info['admin_email'];
				} else{
					$admin_email = $this->config->get('config_email');
				}

				if(!empty($page_form_info['admin_subject'])) {
					$subject = $page_form_info['admin_subject'];
				} else{
					$subject = '';
				}

				if(!empty($page_form_info['admin_message'])) {
					$message = $page_form_info['admin_message'];
				} else{
					$message = '';
				}

				if ($this->request->server['HTTPS']) {
					$server = $this->config->get('config_ssl');
				} else {
					$server = $this->config->get('config_url');
				}

				if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
					$logo = $server . 'image/' . $this->config->get('config_logo');
				} else {
					$logo = '';
				}

				$home_href = $this->url->link('common/home', '', true);

				$information_data = array();
				$information_data['infos'] = array();
				$upload_data = array();
				if (isset($data['field_data'])) {
					foreach ($data['field_data'] as $field_data) {
						if($field_data) {
							if($field_data['type'] == 'password' || $field_data['type'] == 'confirm_password') {
								$field_data['value'] = unserialize(base64_decode($field_data['value']));

							}

							if ($field_data['type'] != 'file') {
								$information_data['infos'][] = array(
									'name'			=> $field_data['name'],
									'value'			=> nl2br($field_data['value']),
									'type'  		=> $field_data['type'],
								);
							} else {
								$explode_file_values = explode(',', $field_data['value']);
									$value_file = [];

									foreach($explode_file_values as $explode_file_value) {
										$upload_info = $this->model_tool_upload->getUploadByCode($explode_file_value);
										if ($upload_info) {

											$value_file[] = [
												'filename' 		=> $upload_info['name'],
												'href'  		=> $this->url->link('extension/ciformbuilder/form/download', 'code=' . $upload_info['code'], true),

											];

											$orgname = DIR_UPLOAD . $upload_info['filename'];
											$temp_name = DIR_UPLOAD . $upload_info['name'];
											copy($orgname, $temp_name);
											$upload_data[] = $temp_name;
										}
									}

									if($value_file) {
										$information_data['infos'][] = array(
											'name'			=> $field_data['name'],
											'value'			=> $value_file,
											'type'  		=> $field_data['type'],
										);
									}
							}
						}

					}
				}

				if(VERSION < '2.2.0.0') {
					if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extension/mail_formsubmit_info.tpl')) {
						$information_html = $this->load->view($this->config->get('config_template') . '/template/extension/ciformbuilder/page_oc2/mail_formsubmit_info.tpl', $information_data);
					} else {
						$information_html = $this->load->view('default/template/extension/ciformbuilder/page_oc2/mail_formsubmit_info.tpl', $information_data);
					}
				} else if(VERSION <= '2.3.0.2') {
					$information_html = $this->load->view('extension/ciformbuilder/page_oc2/mail_formsubmit_info', $information_data);
				} else {
					$information_html = $this->load->view('extension/ciformbuilder/page_oc3/mail_formsubmit_info', $information_data);
				}

				$find = array(
					'{STORE_NAME}',
					'{STORE_LINK}',
					'{LOGO}',
					'{PRODUCT_ID}',
					'{PRODUCT_NAME}',
					'{PRODUCT_MODEL}',
					'{PRODUCT_LINK}',
					'{PRODUCT_IMAGE}',
					'{INFORMATION}',
				);

				$replace = array(
					'STORE_NAME'					=> $this->config->get('config_name'),
					'STORE_LINK'					=> $home_href,
					'LOGO'							=> '<img src="'. $logo .'" alt="'. $this->config->get('config_name') .'" title="'. $this->config->get('config_name') .'" />',
					'PRODUCT_ID'					=> $data['product_id'],
					'PRODUCT_NAME'					=> $data['product_name'],
					'PRODUCT_MODEL'					=> $data['product_model'],
					'PRODUCT_LINK'					=> $data['product_link'],
					'PRODUCT_IMAGE'					=> $data['product_image'],
					'INFORMATION'					=> $information_html,
				);

				if(!empty($subject)) {
					$subject = str_replace($find, $replace, $subject);
				}else{
					$subject = '';
				}

				$html_data = array();
				$html_data['subject'] = $subject;

				if(!empty($message)) {
					$html_data['message'] = str_replace($find, $replace, $message);
					$html_data['message'] = html_entity_decode($html_data['message'], ENT_QUOTES, 'UTF-8');
				}else{
					$html_data['message'] = '';
				}

				if(VERSION < '2.2.0.0') {
					if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extension/mail_formsubmit_admin.tpl')) {
						$mail_html = $this->load->view($this->config->get('config_template') . '/template/extension/ciformbuilder/page_oc2/mail_formsubmit_admin.tpl', $html_data);
					} else {
						$mail_html = $this->load->view('default/template/extension/ciformbuilder/page_oc2/mail_formsubmit_admin.tpl', $html_data);
					}
				} else if(VERSION <= '2.3.0.2') {
					$mail_html = $this->load->view('extension/ciformbuilder/page_oc2/mail_formsubmit_admin', $html_data);
				} else {
					$mail_html = $this->load->view('extension/ciformbuilder/page_oc3/mail_formsubmit_admin', $html_data);
				}

				if(VERSION >= '3.0.0.0') {
					$mail = new Mail($this->config->get('config_mail_engine'));
					$mail->parameter = $this->config->get('config_mail_parameter');
					$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
					$mail->smtp_username = $this->config->get('config_mail_smtp_username');
					$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
					$mail->smtp_port = $this->config->get('config_mail_smtp_port');
					$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
				} else if(VERSION <= '2.0.1.1') {
			     	$mail = new Mail($this->config->get('config_mail'));
			    } else {
					$mail = new Mail();
					$mail->protocol = $this->config->get('config_mail_protocol');
					$mail->parameter = $this->config->get('config_mail_parameter');
					$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
					$mail->smtp_username = $this->config->get('config_mail_smtp_username');
					$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
					$mail->smtp_port = $this->config->get('config_mail_smtp_port');
					$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
				}

				$mail->setTo($admin_email);
				$mail->setFrom($this->config->get('config_email'));
				$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));

				$page_request_info = $this->getPageRequestEmail($page_form_info['page_form_id'], $page_request_id);

				if($page_request_info) {
					$customer_email = $page_request_info['value'];
				} else{
					$customer_email = $this->customer->getEmail();
				}

				if($customer_email) {
					$mail->setReplyTo($customer_email);
				}

				if(!empty($upload_data) && !empty($page_form_info['admin_field_attachment'])) {
					foreach ($upload_data as $upload_file_name) {
						if(filesize($upload_file_name) <= 10485760) {
							$mail->addAttachment($upload_file_name);
						}
					}
				}
				$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
				$mail->setHtml($mail_html);

				$mail->send();

				// Send to additional alert emails if new account email is enabled
				if(!empty($page_form_info['mail_alert_email_status'])) {
					$emails = explode(',', $page_form_info['mail_alert_email']);

					foreach ($emails as $email) {
						if (utf8_strlen($email) > 0 && filter_var($email, FILTER_VALIDATE_EMAIL)) {
							$mail->setTo($email);

							$mail->send();
						}
					}
				}
			}
		}

		if(!empty($upload_data)) {
			foreach ($upload_data as $upload_file_name) {
				@unlink( $upload_file_name );
			}
		}
	}

	public function getPageRequestEmail($page_form_id, $page_request_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "page_request_option` WHERE  `page_form_id` = '" . (int)$page_form_id . "' AND (`type` = 'email' OR `type` = 'email_exists') AND page_request_id='". (int)$page_request_id ."'");

		return $query->row;
	}

	public function getPageRequests($data = array()) {
		$sql = "SELECT *, CONCAT(pg.firstname, ' ', pg.lastname) AS customer FROM " . DB_PREFIX . "page_request pg WHERE pg.page_request_id > 0 AND pg.customer_id = '". (int)$this->customer->getId() ."'";

		if (!empty($data['filter_page_form_title'])) {
			$sql .= " AND pg.page_form_title LIKE '%" . $this->db->escape($data['filter_page_form_title']) . "%'";
		}

		if (!empty($data['filter_customer'])) {
			$sql .= " AND CONCAT(pg.firstname, ' ', pg.lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
		}

		if (!empty($data['filter_ip'])) {
			$sql .= " AND pg.ip = '" . $this->db->escape($data['filter_ip']) . "'";
		}

		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(pg.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if (!empty($data['export_date_start'])) {
			$sql .= " AND DATE(pg.date_added) >= '" . $this->db->escape($data['export_date_start']) . "'";
		}

		if (!empty($data['export_date_end'])) {
			$sql .= " AND DATE(pg.date_added) <= '" . $this->db->escape($data['export_date_end']) . "'";
		}

		if (!empty($data['filter_product_id'])) {
			$sql .= " AND pg.product_id = '" . $this->db->escape($data['filter_product_id']) . "'";
		}

		$sort_data = array(
			'customer',
			'pg.date_added',
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY pg.date_added";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if(empty($data['nopagination'])) {
			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}

				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}

				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getTotalPageRequests($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "page_request pg WHERE pg.page_request_id > 0 AND pg.customer_id = '". (int)$this->customer->getId() ."'";

		if (!empty($data['filter_page_form_title'])) {
			$sql .= " AND pg.page_form_title LIKE '%" . $this->db->escape($data['filter_page_form_title']) . "%'";
		}

		if (!empty($data['filter_customer'])) {
			$sql .= " AND CONCAT(pg.firstname, ' ', pg.lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
		}

		if (!empty($data['filter_ip'])) {
			$sql .= " AND pg.ip = '" . $this->db->escape($data['filter_ip']) . "'";
		}

		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(pg.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if (!empty($data['export_date_start'])) {
			$sql .= " AND DATE(pg.date_added) >= '" . $this->db->escape($data['export_date_start']) . "'";
		}

		if (!empty($data['export_date_end'])) {
			$sql .= " AND DATE(pg.date_added) <= '" . $this->db->escape($data['export_date_end']) . "'";
		}

		if (!empty($data['filter_product_id'])) {
			$sql .= " AND pg.product_id = '" . $this->db->escape($data['filter_product_id']) . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getPageRequest($page_request_id) {
		$sql = "SELECT DISTINCT * FROM " . DB_PREFIX . "page_request pg WHERE pg.page_request_id = '" . (int)$page_request_id . "' AND pg.customer_id = '". (int)$this->customer->getId() ."'";

		$query = $this->db->query($sql);

		return $query->row;
	}

	public function getPageRequestOptions($page_request_id) {
		$query = $this->db->query("SELECT `name`, `value`, `type`, page_form_option_id, page_form_option_value_id FROM " . DB_PREFIX . "page_request_option  WHERE page_request_id = '" . (int)$page_request_id . "' ORDER BY page_request_option_id ASC");

		return $query->rows;
	}
}