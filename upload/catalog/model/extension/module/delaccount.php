<?php
class ModelExtensionModuleDelaccount extends Model {

    private $error = array();
    private $prefix;

    public function __construct($registry) {
        parent::__construct($registry);
        $this->prefix = (version_compare(VERSION, '3.0', '>=')) ? 'module_' : '';
    }

	public function deleteAccount($customer_id, $order, $review) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int) $customer_id . "'");
		if ($query->num_rows) {
			$customer = $query->row;
			$this->db->query("DELETE FROM `" . DB_PREFIX . "customer` WHERE customer_id = '" . (int)$customer_id . "'");
			$this->db->query("DELETE FROM `" . DB_PREFIX . "address` WHERE customer_id = '" . (int)$customer_id . "'");
			$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_activity` WHERE customer_id = '" . (int)$customer_id . "'");
			$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_affiliate` WHERE customer_id = '" . (int)$customer_id . "'");
			$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_approval` WHERE customer_id = '" . (int)$customer_id . "'");
			$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_history` WHERE customer_id = '" . (int)$customer_id . "'");
			$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_ip` WHERE customer_id = '" . (int)$customer_id . "'");
			$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_online` WHERE customer_id = '" . (int)$customer_id . "'");
			$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_reward` WHERE customer_id = '" . (int)$customer_id . "'");
			$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_search` WHERE customer_id = '" . (int)$customer_id . "'");
			$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_transaction` WHERE customer_id = '" . (int)$customer_id . "'");
			$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_wishlist` WHERE customer_id = '" . (int)$customer_id . "'");
			$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_login` WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($customer['email'])) . "'");
			$this->db->query("DELETE FROM `" . DB_PREFIX . "return` WHERE customer_id = '" . (int)$customer_id . "'");
			$this->db->query("DELETE FROM `" . DB_PREFIX . "cart` WHERE customer_id = '" . (int)$customer_id . "'");
			if ($review == 'anonymize') {
				$this->db->query("UPDATE `" . DB_PREFIX . "review` SET author = 'Anonymous' WHERE customer_id = '" . (int)$customer_id . "'");
			} elseif ($review == 'delete') {
				$this->db->query("DELETE FROM `" . DB_PREFIX . "review` WHERE customer_id = '" . (int)$customer_id . "'");
			}
			if ($order == 'anonymize') {
				$this->db->query("UPDATE `" . DB_PREFIX . "order` SET customer_id = '0', firstname = 'Anonymous', lastname = 'Deleted', email = 'anonymous@mail.com', telephone = '', fax = '', custom_field = '[]', payment_firstname = 'Anonymous', payment_lastname = 'Anonymous', payment_company = '', payment_address_1 = 'Anonymous', payment_address_2 = 'Anonymous', payment_city = 'Anonymous', payment_postcode = '', payment_custom_field = '[]', shipping_firstname = 'Anonymous', shipping_lastname = 'Anonymous', shipping_company = '', shipping_address_1 = 'Anonymous', shipping_address_2 = 'Anonymous', shipping_city = 'Anonymous', shipping_postcode = '', shipping_custom_field = '[]', affiliate_id = 0, ip = '127.0.0.1', user_agent = 'Anonymous', accept_language = '' WHERE customer_id = '" . (int)$customer_id . "'");
			} elseif ($order == 'delete') {
				$this->db->query("DELETE FROM `" . DB_PREFIX . "order` WHERE customer_id = '" . (int)$customer_id . "'");
			}
		}
	}

	public function editCode($email, $code) {
		$this->db->query("UPDATE `" . DB_PREFIX . "customer` SET code = '" . $this->db->escape($code) . "' WHERE LCASE(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");
	}

}