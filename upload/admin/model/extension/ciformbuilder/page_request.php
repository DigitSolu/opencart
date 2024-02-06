<?php
class ModelExtensionCiformbuilderPageRequest extends Model {
	public function getPageRequest($page_request_id) {
		$sql = "SELECT DISTINCT *,(SELECT fsd.name FROM " . DB_PREFIX . "page_form_status fs LEFT JOIN " . DB_PREFIX . "page_form_status_description fsd ON (fs.form_status_id = fsd.form_status_id) WHERE fs.form_status_id = pg.form_status_id AND fsd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS form_status FROM " . DB_PREFIX . "page_request pg WHERE pg.page_request_id = '" . (int)$page_request_id . "'";

		$query = $this->db->query($sql);

		return $query->row;
	}

	public function deletePageRequest($page_request_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "page_request` WHERE page_request_id = '" . (int)$page_request_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "page_request_option` WHERE page_request_id = '" . (int)$page_request_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "page_request_history` WHERE page_request_id = '" . (int)$page_request_id . "'");
	}

	public function getPageRequests($data = array()) {
		$sql = "SELECT *, CONCAT(pg.firstname, ' ', pg.lastname) AS customer,(SELECT fsd.name FROM " . DB_PREFIX . "page_form_status fs LEFT JOIN " . DB_PREFIX . "page_form_status_description fsd ON (fs.form_status_id = fsd.form_status_id) WHERE fs.form_status_id = pg.form_status_id AND fsd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS form_status FROM " . DB_PREFIX . "page_request pg WHERE pg.page_request_id > 0";

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

		if (!empty($data['filter_page_form_status'])) {
			$sql .= " AND pg.form_status_id = '" . (int)$data['filter_page_form_status'] . "'";
		}

		$sort_data = array(
			'customer',
			'pg.date_added',
			'pg.product_name',
			'form_status',
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
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "page_request pg WHERE pg.page_request_id > 0";

		if (!empty($data['filter_page_form_title'])) {
			$sql .= " AND pg.page_form_title LIKE '%" . $this->db->escape($data['filter_page_form_title']) . "%'";
		}

		if (!empty($data['filter_page_form_status'])) {
			$sql .= " AND pg.form_status_id = '" . (int)$data['filter_page_form_status'] . "'";
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

	public function getPageRequestOptions($page_request_id) {
		$query = $this->db->query("SELECT `name`, `value`, `type`, page_form_option_id, page_form_option_value_id FROM " . DB_PREFIX . "page_request_option  WHERE page_request_id = '" . (int)$page_request_id . "' ORDER BY page_request_option_id ASC");

		return $query->rows;
	}

	public function getPageRequestOptionValue($page_request_id, $page_form_id, $column_name) {
		$query = $this->db->query("SELECT `name`, `value`, `type`, page_form_option_id, page_form_option_value_id FROM " . DB_PREFIX . "page_request_option  WHERE page_request_id = '" . (int)$page_request_id . "' AND page_form_id = '" . (int)$page_form_id . "' AND `name` = '". $this->db->escape($column_name) ."'");

		return $query->row;
	}

	public function getPageRequestsColumns($page_request_ids) {
		if(!$page_request_ids) {
			return [];
		}

		$sql = "SELECT `name`, `value`, `type`, page_form_option_id, page_form_option_value_id FROM " . DB_PREFIX . "page_request_option WHERE page_request_id IN (" . implode(",", $page_request_ids) . ") ";

		$sql .= " GROUP BY `name`";
		$sql .= " ORDER BY page_request_option_id ASC ";

		$query = $this->db->query($sql);

		return $query->rows;
	}
	
	public function getPageFormDescription($page_form_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "page_form` o LEFT JOIN " . DB_PREFIX . "page_form_description pd ON (o.page_form_id = pd.page_form_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND o.page_form_id = '" . (int)$page_form_id . "'");

		return $query->row;
	}

	public function updateReadStatus($page_request_id) {
		$query = $this->db->query("UPDATE " . DB_PREFIX . "page_request SET read_status = '1' WHERE page_request_id = '" . (int)$page_request_id . "'");
	}

	public function getTotalunreadRequests() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "page_request WHERE read_status = 0");

		return $query->row['total'];
	}

	public function getStatusHistory($page_request_id){
		$query = $this->db->query("SELECT *,(SELECT fsd.name FROM " . DB_PREFIX . "page_form_status fs LEFT JOIN " . DB_PREFIX . "page_form_status_description fsd ON (fs.form_status_id = fsd.form_status_id) WHERE fs.form_status_id = pg.form_status_id AND fsd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS form_status FROM `" . DB_PREFIX . "page_request_history` pg WHERE pg.page_request_id = '" . (int)$page_request_id . "' ORDER BY pg.date_added ASC");

		return $query->rows;
	}

	public function getPageRequestEmail($page_form_id, $page_request_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "page_request_option` WHERE  `page_form_id` = '" . (int)$page_form_id . "' AND (`type` = 'email' OR `type` = 'email_exists') AND page_request_id='". (int)$page_request_id ."'");
		return $query->row;
	}

	public function getHistories($page_request_id, $start = 0, $limit = 10) {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 10;
		}

		$query = $this->db->query("SELECT oh.date_added, osd.name AS status,os.bgcolor,os.textcolor FROM " . DB_PREFIX . "page_request_history oh LEFT JOIN " . DB_PREFIX . "page_form_status os ON oh.form_status_id = os.form_status_id LEFT JOIN " . DB_PREFIX . "page_form_status_description osd ON (os.form_status_id = osd.form_status_id) WHERE oh.page_request_id = '" . (int)$page_request_id . "' AND osd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY oh.date_added DESC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	public function getTotalHistories($page_request_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "page_request_history WHERE page_request_id = '" . (int)$page_request_id . "'");

		return $query->row['total'];
	}



	public function addPageRequestHistory($data, $form_status_id, $notify = 0) {
		$this->load->model('extension/ciformbuilder/page_form');

		$page_form_info = $this->model_extension_ciformbuilder_page_form->getPageForm($data['page_form_id']);

		if(!empty($page_form_info['admin_email'])) {
			$admin_email = $page_form_info['admin_email'];
		} else{
			$admin_email = $this->config->get('config_email');
		}

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "page_form_status_email` pg LEFT JOIN " . DB_PREFIX . "page_form_status_email_description pd ON (pg.form_status_email_id = pd.form_status_email_id) WHERE pg.page_form_id = '" . (int)$data['page_form_id'] . "' AND pg.form_status_id = '" . (int)$form_status_id . "'");

		if($query->num_rows > 0){
			$statuses = $this->db->query("SELECT * FROM `" . DB_PREFIX . "page_form_status` fs LEFT JOIN " . DB_PREFIX . "page_form_status_description fsd ON (fs.form_status_id = fsd.form_status_id) WHERE fsd.language_id = '" . (int)$this->config->get('config_language_id') . "'")->rows;

			$status_info = $this->db->query("SELECT * FROM `" . DB_PREFIX . "page_form_status_email` fs WHERE fs.form_status_id = '" . (int)$form_status_id . "' AND fs.page_form_id = '" . (int)$data['page_form_id'] . "'")->row;

			$page_request_info = $this->getPageRequestEmail($data['page_form_id'], $data['page_request_id']);

			$this->db->query("UPDATE `" . DB_PREFIX . "page_request` SET form_status_id = '" . (int)$form_status_id . "', date_modified = NOW() WHERE page_request_id = '" . (int)$data['page_request_id'] . "'");

			$this->db->query("INSERT INTO " . DB_PREFIX . "page_request_history SET page_request_id = '" . (int)$data['page_request_id'] . "', form_status_id = '" . (int)$form_status_id . "',page_form_id = '" . (int)$data['page_form_id'] . "',date_added = NOW()");

			if($page_request_info) {
				$customer_email = $page_request_info['value'];

				// Send email when change the form submission status
				if($customer_email && $notify) {
					if(!empty($query->row['subject'])) {
						$subject = $query->row['subject'];
					} else{
						$subject = '';
					}
					if(!empty($query->row['message'])) {
						$message = $query->row['message'];
					} else{
						$message = '';
					}

					if($status_info && !empty($status_info['attachment'])) {
						$attachments = json_decode($status_info['attachment'],true);
					} else {
						$attachments = array();
					}

					$this->load->model('tool/upload');
					$upload_data = array();
					foreach($attachments as $code) {
						$upload_info = $this->model_tool_upload->getUploadByCode($code);
						if ($upload_info) {
							$information_data['infos'][] = array(
								'name'			=> $upload_info['name'],
								'href'  		=> HTTPS_CATALOG .'index.php?route=page/form/download', 'code=' . $upload_info['code'],
							);

							$orgname = DIR_UPLOAD . $upload_info['filename'];
							$temp_name = DIR_UPLOAD . $upload_info['name'];
							copy($orgname, $temp_name);
							$upload_data[] = $temp_name;
						}
					}

					if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
						$logo = HTTPS_CATALOG . 'image/' . $this->config->get('config_logo');
					} else {
						$logo = '';
					}

					$home_href = HTTPS_CATALOG;
					$information_data = array();
					$information_data['infos'] = array();
					$options = $this->db->query("SELECT * FROM `" . DB_PREFIX . "page_request_option` pg WHERE pg.page_form_id = '" . (int)$data['page_form_id'] . "' AND pg.page_request_id = '" . (int)$data['page_request_id'] . "'")->rows;
					if (!empty($options)) {
						foreach ($options as $field_data) {
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
									$upload_info = $this->model_tool_upload->getUploadByCode($field_data['value']);
									if ($upload_info) {
										$information_data['infos'][] = array(
											'name'			=> $field_data['name'],
											'value'			=> $upload_info['name'],
											'type'  		=> $field_data['type'],
											'href'  		=> HTTPS_CATALOG .'index.php?route=page/form/download&code='. $upload_info['code'],
										);
										/* $orgname = DIR_UPLOAD . $upload_info['filename'];
										$temp_name = DIR_UPLOAD . $upload_info['name'];
										copy($orgname, $temp_name);
										$upload_data[] = $temp_name; */
									}
								}
							}
						}
					}

					if(VERSION <= '2.3.0.2') {
						$information_html = $this->load->view('extension/ciformbuilder/mail/mail_form_status_info.tpl', $information_data);
					} else {
						$file_variable = 'template_engine';
						$file_type = 'template';
						$this->config->set($file_variable, $file_type);

						$information_html = $this->load->view('extension/ciformbuilder/mail/mail_form_status_info', $information_data);
					}

					$find = array(
						'{LOGO}',
						'{STORE_NAME}',
						'{STORE_LINK}',
						'{INFORMATION}',
					);
					$status_shortcode = array();
					if($statuses) {
						foreach($statuses as $status){
							$status_shortcode[] = $status['shortcode'];
						}
					}

					$find = array_merge($find,$status_shortcode);

					$replace = array(
						'LOGO'							=> '<img src="'. $logo .'" alt="'. $this->config->get('config_name') .'" title="'. $this->config->get('config_name') .'" />',
						'STORE_NAME'					=> $this->config->get('config_name'),
						'STORE_LINK'					=> $home_href,
						'INFORMATION'					=> $information_html,
					);

					$status_shortcode_value = array();
					if($statuses) {
						foreach($statuses as $status){
							$status_shortcode_value[] = $status['name'];
						}
					}

					$replace = array_merge($replace, $status_shortcode_value);

					if(!empty($subject)) {
						$html_data['subject'] = str_replace($find, $replace, $subject);
					}else{
						$html_data['subject'] = '';
					}

					if(!empty($message)) {
						$html_data['message'] = str_replace($find, $replace, $message);
						$html_data['message'] = html_entity_decode($html_data['message'], ENT_QUOTES, 'UTF-8');
					}else{
						$html_data['message'] = '';
					}

					if(VERSION <= '2.3.0.2') {
						$mail_html = $this->load->view('extension/ciformbuilder/mail/mail_form_status.tpl', $html_data);
					} else {
						$file_variable = 'template_engine';
						$file_type = 'template';
						$this->config->set($file_variable, $file_type);

						$mail_html = $this->load->view('extension/ciformbuilder/mail/mail_form_status', $html_data);
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
					$mail->setReplyTo($admin_email);
					$mail->setFrom($this->config->get('config_email'));
					$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
					if(!empty($upload_data)) {
						foreach ($upload_data as $upload_file_name) {
							if(filesize($upload_file_name) <= 15728640) {
								$mail->addAttachment($upload_file_name);
							}
						}
					}

					$mail->setSubject(html_entity_decode($html_data['subject'], ENT_QUOTES, 'UTF-8'));
					$mail->setHtml($mail_html);

					$mail->send();
				}
			}
		}
	}
}