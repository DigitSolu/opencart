<?php
class ModelExtensionCiformbuilderFormStatus extends Model {
	public function addFormStatus($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "page_form_status SET shortcode = '" . $this->db->escape($data['shortcode']) . "',status = '" . (int)$data['status'] . "',sort_order = '" . (int)$data['sort_order'] . "',bgcolor = '" . $this->db->escape($data['bgcolor']) . "',textcolor = '" . $this->db->escape($data['textcolor']) . "'");

		$form_status_id = $this->db->getLastId();

		foreach ($data['form_status'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "page_form_status_description SET form_status_id = '" . (int)$form_status_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
		}

		$this->cache->delete('form_status');

		return $form_status_id;
	}

	public function editFormStatus($form_status_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "page_form_status SET shortcode = '" . $this->db->escape($data['shortcode']) . "',status = '" . (int)$data['status'] . "',sort_order = '" . (int)$data['sort_order'] . "',bgcolor = '" . $this->db->escape($data['bgcolor']) . "',textcolor = '" . $this->db->escape($data['textcolor']) . "' WHERE form_status_id = '" . (int)$form_status_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "page_form_status_description WHERE form_status_id = '" . (int)$form_status_id . "'");
		foreach ($data['form_status'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "page_form_status_description SET form_status_id = '" . (int)$form_status_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
		}

		$this->cache->delete('form_status');
	}

	public function deleteFormStatus($form_status_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "page_form_status WHERE form_status_id = '" . (int)$form_status_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "page_form_status_description WHERE form_status_id = '" . (int)$form_status_id . "'");

		$this->cache->delete('form_status');
	}

	public function getFormStatus($form_status_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "page_form_status p LEFT JOIN " . DB_PREFIX . "page_form_status_description pd ON (p.form_status_id = pd.form_status_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.form_status_id = '" . (int)$form_status_id . "'");

		return $query->row;
	}

	public function getFormStatuses($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "page_form_status p LEFT JOIN " . DB_PREFIX . "page_form_status_description pd ON (p.form_status_id = pd.form_status_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		$sort_data = array(
			'pd.name',
			'p.status',
			'p.shortcode',
			'p.sort_order'
		);
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY p.sort_order, pd.name";
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

	public function getEnabledFormStatuses() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "page_form_status p LEFT JOIN " . DB_PREFIX . "page_form_status_description pd ON  (p.form_status_id = pd.form_status_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = 1 ORDER BY p.sort_order ASC");
		return $query->rows;
	}

	public function getEnabledPageFormStatuses($page_form_id) {
		$form_statuses = [];
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "page_form_status_email pfs LEFT JOIN " . DB_PREFIX . "page_form_status_email_description pfsd ON (pfs.form_status_email_id = pfsd.form_status_email_id) WHERE pfs.page_form_id = '". (int)$page_form_id ."'AND pfsd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND pfs.status = 1 ORDER BY pfs.sort_order ASC")->rows;
		foreach($query as $value) {
			$status_info = $this->db->query("SELECT * FROM " . DB_PREFIX . "page_form_status p LEFT JOIN " . DB_PREFIX . "page_form_status_description pd ON (p.form_status_id = pd.form_status_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = 1 AND p.form_status_id = '". (int)$value['form_status_id'] ."'")->row;
			if($status_info) {
				$form_statuses[] = [
					'shortcode'				=> $status_info['shortcode'],
					'language_id'			=> $status_info['language_id'],
					'name'					=> $status_info['name'],
					'form_status_email_id'	=> $value['form_status_email_id'],
					'page_form_id'			=> $value['page_form_id'],
					'form_status_id'		=> $value['form_status_id'],
					'sort_order'			=> $value['sort_order'],
					'status'				=> $value['status'],
					'attachment'			=> $value['attachment'],
					'language_id'			=> $value['language_id'],
					'subject'				=> $value['subject'],
					'message'				=> $value['message'],
				];
			}
		}

		return $form_statuses;
	}

	public function getFormStatusDescriptions($form_status_id) {
		$form_status_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "page_form_status_description WHERE form_status_id = '" . (int)$form_status_id . "'");

		foreach ($query->rows as $result) {
			$form_status_data[$result['language_id']] = array('name' => $result['name']);
		}

		return $form_status_data;
	}

	public function getTotalFormStatuses() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "page_form_status p LEFT JOIN " . DB_PREFIX . "page_form_status_description pd ON (p.form_status_id = pd.form_status_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row['total'];
	}

	public function getTotalEnabledPageFormStatuses($page_form_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "page_form_status_email pfs LEFT JOIN " . DB_PREFIX . "page_form_status_email_description pfsd ON (pfs.form_status_email_id = pfsd.form_status_email_id) LEFT JOIN " . DB_PREFIX . "page_form_status p ON (pfs.form_status_id = p.form_status_id) WHERE pfs.page_form_id = '". (int)$page_form_id ."'AND pfsd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND pfs.status = 1 AND p.status = 1 ORDER BY pfs.sort_order ASC");

		return $query->row['total'];
	}

	public function getPageFormStatus($page_form_id) {
		$page_form_description_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "page_form_status_email p LEFT JOIN " . DB_PREFIX . "page_form_status_email_description pd ON (p.form_status_email_id = pd.form_status_email_id) WHERE p.page_form_id = '" . (int)$page_form_id . "' ORDER BY p.sort_order ASC");

		foreach ($query->rows as $result) {
			$desc[$result['language_id']] = array(
				'subject'  => $result['subject'],
				'message'  => $result['message'],
			);

			$page_form_description_data[$result['form_status_id']] = array(
				'desc'            		=> $desc,
				'sort_order'            		=> $result['sort_order'],
				'status'            		=> $result['status'],
				'attachment'      		=> $result['attachment'] ? json_decode($result['attachment'],true) : array(),
			);
		}

		return $page_form_description_data;
	}
}