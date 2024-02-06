<?php
class ModelExtensionOrderExport extends Model {
	public function getProductOrders($data = array()) {
		$sql = "SELECT o.order_id, CONCAT(o.firstname, ' ', o.lastname) AS customer, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int)$this->config->get('config_language_id') . "') AS status, o.shipping_code, o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified, op.order_product_id, op.name As product_name, op.quantity AS product_quantity, op.model AS product_model, op.price AS product_price,op.product_id as product_id, op.tax AS product_tax, op.reward AS product_reward, op.total AS product_total  FROM `" . DB_PREFIX . "order_product` op LEFT JOIN `" . DB_PREFIX . "order` o ON(op.order_id=o.order_id)";

		if (!empty($data['filter_category'])) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p2c.product_id = op.product_id) LEFT JOIN " . DB_PREFIX . "category c ON (p2c.category_id = c.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id)";
		}

		if (isset($data['filter_order_status'])) {
			$implode = array();

			$order_statuses = explode(',', $data['filter_order_status']);

			foreach ($order_statuses as $order_status_id) {
				$implode[] = "o.order_status_id = '" . (int)$order_status_id . "'";
			}

			if ($implode) {
				$sql .= " WHERE (" . implode(" OR ", $implode) . ")";
			} else {

			}
		} else {
			$sql .= " WHERE o.order_status_id > '0'";
		}

		if (!empty($data['filter_order_id'])) {
			$sql .= " AND o.order_id = '" . (int)$data['filter_order_id'] . "'";
		}

		if (!empty($data['order_ids'])) {
		$implode = array();
			foreach ($data['order_ids'] as $order_id) {
					$implode[] = "o.order_id = '" . (int)$order_id . "'";
				}

				if ($implode) {
					$sql .= " and (" . implode(" OR ", $implode) . ")";
				}
		}
		if (isset($data['filter_store_id'])) {
			$sql .= " AND o.store_id = '" . (int)$data['filter_store_id'] . "'";
		}

		if (!empty($data['filter_customer'])) {
			$sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
		}

		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if (!empty($data['filter_date_modified'])) {
			$sql .= " AND DATE(o.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
		}
		if (!empty($data['filter_product'])) {
			$sql .= " AND op.name = '" . $this->db->escape($data['filter_product']) . "'";
		}

		if (!empty($data['filter_category1'])) {
			$sql .= " AND cd.category_id = '" . $this->db->escape($data['filter_category1']) . "'";
		}

		if (!empty($data['filter_category'])) {
			$sql .= " and cd.language_id='".(int)$this->config->get('config_language_id')."'";
		}
		
		if (!empty($data['filter_customer_group_id'])) {
			$sql .= " AND o.customer_group_id = '" . $this->db->escape($data['filter_customer_group_id']) . "'";
		}

		

		if(!empty($data['filter_startdate']))
		{
			$sql .=" AND o.date_added>='".$data['filter_startdate']."'";
		}

		if(!empty($data['filter_enddate']))
		{
			$sql .=" AND o.date_added<='".$data['filter_enddate']."'";
		}

		if (!empty($data['filter_total'])) {
			$sql .= " AND o.total = '" . (float)$data['filter_total'] . "'";
		}

		$sort_data = array(
			'o.order_id',
			'customer',
			'status',
			'o.date_added',
			'o.date_modified',
			'o.total'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY o.order_id";
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

	public function getOrderTotalByCode($order_id, $code) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$order_id . "' AND code ='". $this->db->escape($code) ."'");

		return $query->row;
	}
	
	public function getProductWeight($order_id,$product_id=0) {
		$this->load->model('sale/order');
		$this->load->model('catalog/product');
		$weight = 0;
		$quantity = 0;
		$sql = "SELECT op.product_id,op.quantity,op.order_product_id , p.weight , p.weight_class_id FROM " . DB_PREFIX . "order_product op LEFT JOIN " . DB_PREFIX . "product p ON (op.product_id = p.product_id) WHERE op.order_id = '" . (int)$order_id . "'";
		if(!empty($product_id)){
			$sql .= " and p.product_id = '".$product_id."'";

		}
		$query = $this->db->query($sql);
		foreach ($query->rows as $value) {
			$quantity = $value['quantity'];
			$options = $this->model_sale_order->getOrderOptions($order_id, $value['order_product_id']);

			foreach ($options as $option) {

				$product_option_value_info = $this->getProductOptionValue($value['product_id'], $option['product_option_value_id']);

				if ($product_option_value_info) {
					if ($product_option_value_info['weight_prefix'] == '+') {
						$weight += $product_option_value_info['weight'];
					} elseif ($product_option_value_info['weight_prefix'] == '-') {
						$weight -= $product_option_value_info['weight'];
					}
				}
			}
			$weight += $quantity * $this->weight->convert($value['weight'], $value['weight_class_id'], $this->config->get('config_weight_class_id'));
		}

		$this->load->model('localisation/weight_class');
		$query2 = $this->model_localisation_weight_class->getWeightClass($this->config->get('config_weight_class_id'));

		if(!empty($query2['unit'])){
			$unit = $query2['unit'];
		}else{
			$unit = '';
		}
		return $weight.''.$unit;
	}

	public function getOrders($data = array()) {
		$sql = "SELECT o.order_id, o.customer_group_id, CONCAT(o.firstname, ' ', o.lastname) AS customer, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int)$this->config->get('config_language_id') . "') AS status, o.shipping_code, o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_product op ON (o.order_id = op.order_id) ";

		if (!empty($data['filter_category'])) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p2c.product_id = op.product_id) LEFT JOIN " . DB_PREFIX . "category c ON (p2c.category_id = c.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id)";
		}

		if (isset($data['filter_order_status'])) {
			$implode = array();

			$order_statuses = explode(',', $data['filter_order_status']);

			foreach ($order_statuses as $order_status_id) {
				$implode[] = "o.order_status_id = '" . (int)$order_status_id . "'";
			}

			if ($implode) {
				$sql .= " WHERE (" . implode(" OR ", $implode) . ")";
			}
		} else {
			$sql .= " WHERE o.order_status_id > '0'";
		}

		if (!empty($data['filter_customer_group_id'])) {
			$sql .= " AND o.customer_group_id = '" . (int)$data['filter_customer_group_id'] . "'";
		}
		
		if (!empty($data['filter_order_id'])) {
			$sql .= " AND o.order_id = '" . (int)$data['filter_order_id'] . "'";
		}
		
		if (!empty($data['order_ids'])) {
		$implode = array();
			foreach ($data['order_ids'] as $order_id) {
					$implode[] = "o.order_id = '" . (int)$order_id . "'";
				}

				if ($implode) {
					$sql .= " and (" . implode(" OR ", $implode) . ")";
				}
		}

		if (!empty($data['filter_product'])) {
			$sql .= " AND op.name = '" . $this->db->escape($data['filter_product']) . "'";
		}

		if (!empty($data['filter_category'])) {
			$sql .= " AND cd.name = '" . $this->db->escape($data['filter_category']) . "'";
		}

		if (!empty($data['filter_category'])) {
			$sql .= " and cd.language_id='".(int)$this->config->get('config_language_id')."'";
		}

		if (isset($data['filter_store_id'])) {
			$sql .= " AND o.store_id = '" . (int)$data['filter_store_id'] . "'";
		}
		
		if (!empty($data['filter_customer'])) {
			$sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
		}

		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if (!empty($data['filter_date_modified'])) {
			$sql .= " AND DATE(o.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
		}

		if(!empty($data['filter_startdate']))
		{
			$sql .=" AND o.date_added>'".$data['filter_startdate']."'";
		}
		if(!empty($data['filter_enddate']))
		{
			$sql .=" AND o.date_added<='".$data['filter_enddate']."'";
		}


		if (!empty($data['filter_total'])) {
			$sql .= " AND o.total = '" . (float)$data['filter_total'] . "'";
		}

		$sort_data = array(
			'o.order_id',
			'customer',
			'status',
			'o.date_added',
			'o.date_modified',
			'o.total'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY o.order_id";
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

	public function getTotalOrders($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order`";

		if (isset($data['filter_order_status'])) {
			$implode = array();

			$order_statuses = explode(',', $data['filter_order_status']);

			foreach ($order_statuses as $order_status_id) {
				$implode[] = "order_status_id = '" . (int)$order_status_id . "'";
			}

			if ($implode) {
				$sql .= " WHERE (" . implode(" OR ", $implode) . ")";
			}
		} else {
			$sql .= " WHERE order_status_id > '0'";
		}

		if (!empty($data['filter_order_id'])) {
			$sql .= " AND order_id = '" . (int)$data['filter_order_id'] . "'";
		}

		if (!empty($data['filter_customer'])) {
			$sql .= " AND CONCAT(firstname, ' ', lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
		}
		
		if (isset($data['filter_store_id'])) {
			$sql .= " AND store_id = '" . (int)$data['filter_store_id'] . "'";
		}
		if (!empty($data['filter_customer_group_id'])) {
			$sql .= " AND customer_group_id = '" . (int)$data['filter_customer_group_id'] . "'";
		}
		
		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if (!empty($data['filter_date_modified'])) {
			$sql .= " AND DATE(date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
		}
		if(!empty($data['filter_startdate']))
		{
			$sql .=" AND date_added>'".$data['filter_startdate']."'";
		}
		if(!empty($data['filter_enddate']))
		{
			$sql .=" AND date_added<='".$data['filter_enddate']."'";
		}
		if (!empty($data['filter_total'])) {
			$sql .= " AND total = '" . (float)$data['filter_total'] . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getProductOptionValue($product_id, $product_option_value_id) {
		$query = $this->db->query("SELECT pov.option_value_id, ovd.name, pov.quantity, pov.subtract, pov.price, pov.price_prefix, pov.points, pov.points_prefix, pov.weight, pov.weight_prefix FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_id = '" . (int)$product_id . "' AND pov.product_option_value_id = '" . (int)$product_option_value_id . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getRules($data) {
		$this->installTable();
		$sql = "SELECT * FROM " . DB_PREFIX . "tmd_export_order_rule WHERE rule_id<>0 and rule_name!=''";

		$sort_data = array(
			'rule_id',
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY rule_id";
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

	public function getRule($rule_id) {
		$this->installTable();
		$sql = "SELECT * FROM " . DB_PREFIX . "tmd_export_order_rule WHERE rule_id='".(int)$rule_id."'";
		$query = $this->db->query($sql);
		return $query->row;
	}
	
	public function installTable() {
		$this->db->query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX."tmd_export_order_rule` (
		  `rule_id` int(11) NOT NULL AUTO_INCREMENT,
		  `rule_type` int(11) NOT NULL,
		  `rule_name` text NOT NULL,
		  `rule_value` text NOT NULL,
		  `date_added` date NOT NULL,
		  PRIMARY KEY (`rule_id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
	}

	public function AddTmdExport($data) {
		if(!empty($data['rule_name'])){
		if(!empty($data['rule_id'])) {
			$rule_id = $data['rule_id'];
			$this->db->query("UPDATE " . DB_PREFIX . "tmd_export_order_rule SET rule_name = '" . $this->db->escape($data['rule_name'])."', rule_type = '" . (int)$data['rule_type'] . "', `rule_value` = '" . $this->db->escape(json_encode($data, true)) . "', date_added=NOW() WHERE rule_id = '" . (int)$rule_id . "'");
		} else {
			$this->db->query("INSERT INTO " . DB_PREFIX . "tmd_export_order_rule SET rule_name = '" . $this->db->escape($data['rule_name'])."', rule_type = '" . (int)$data['rule_type'] . "', `rule_value` = '" . $this->db->escape(json_encode($data, true)) . "', date_added=NOW()");
		}
	}
	}

	public function deleteRule($rule_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "tmd_export_order_rule WHERE rule_id = '" . (int)$rule_id . "'");
	}
}
