<?php
class ModelExtensionCiformbuilderFormsubmission extends Model {
	public function getPageRequests($data = array()) {
		$sql = "SELECT *, CONCAT(pg.firstname, ' ', pg.lastname) AS customer,(SELECT fsd.name FROM " . DB_PREFIX . "page_form_status fs LEFT JOIN " . DB_PREFIX . "page_form_status_description fsd ON (fs.form_status_id = fsd.form_status_id) WHERE fs.form_status_id = pg.form_status_id AND fsd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS form_status FROM " . DB_PREFIX . "page_request pg WHERE pg.page_request_id > 0 AND pg.customer_id = '". (int)$this->customer->getId() ."'";

		if (!empty($data['filter_page_form_title'])) {
			$sql .= " AND pg.page_form_title LIKE '%" . $this->db->escape($data['filter_page_form_title']) . "%'";
		}

		if (!empty($data['filter_page_form_status'])) {
			$sql .= " AND pg.form_status_id = '" . (int)$data['filter_page_form_status'] . "'";
		}

		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(pg.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		$sort_data = array(
			'customer',
			'pg.date_added',
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
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "page_request pg WHERE pg.page_request_id > 0 AND pg.customer_id = '". (int)$this->customer->getId() ."'";

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

	public function getFormStatuses($data = array()) {

		$sql = "SELECT * FROM " . DB_PREFIX . "page_form_status p LEFT JOIN " . DB_PREFIX . "page_form_status_description pd ON (p.form_status_id = pd.form_status_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		$sort_data = array(
			'pd.name',
			'p.status',
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

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getFormStatus($form_status_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "page_form_status p LEFT JOIN " . DB_PREFIX . "page_form_status_description pd ON (p.form_status_id = pd.form_status_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.form_status_id = '" . (int)$form_status_id . "'");

		return $query->row;
	}
}