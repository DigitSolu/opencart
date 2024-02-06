<?php

class ModelExtensionModuleCustomerExport extends Model {

    public function getAllRegisteredCustomers(){
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "customer AS customer, " . DB_PREFIX . "address AS address WHERE customer.customer_id = address.customer_id");

        return $query->rows;
    }

    public function getUnregisteredCustomers() {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order WHERE customer_id=0 AND email NOT LIKE '%localhost.net%' GROUP by email Order by email ASC ");

        return $query->rows;
    }

}