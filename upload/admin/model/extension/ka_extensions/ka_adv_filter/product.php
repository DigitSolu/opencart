<?php
/*
	$Project: Advanced Filter $
	$Author: karapuz team <support@ka-station.com> $

	$Version: 1.0.1.2 $ ($Revision: 60 $)
*/

namespace extension\ka_extensions\ka_adv_filter;

class ModelProduct extends \KaModel {

	protected function onLoad() {
		$this->kamodel('filter');
	}

	public function onProductUpdate($product_id) {
	
		if ('auto_update_filters') {
			$this->rebuildProductFilters($product_id);
		}
		
		$this->rebuildCategoryFilters($product_id);
		
		$this->kamodel('price_search');
		$this->kamodel_price_search->rebuild($product_id);
	
	}
	
	
	public function rebuildCategoryFilters($product_id) {
	
		$this->kamodel('category');
		
		$qry = $this->db->query("SELECT category_id FROM " . DB_PREFIX . "product_to_category
			WHERE product_id = '" . (int)$product_id . "'
		");
		
		if (empty($qry->row['category_id'])) {
			return false;
		}
		
		foreach ($qry->rows as $row) {
			$this->kamodel_category->rebuildCategoryFilters($row['category_id']);
		}
		
		return true;
	}
	
	
	
	
	/*
		Count a number of products from the specific product to calculate the remainder of the products
		to process
	*/
	public function countProducts($product_id = 0) {
		$total = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product WHERE
			product_id >= '" . (int) $product_id . "'
			ORDER BY product_id 
		")->row['total'];
		
		return $total;
	}
	
	
	protected function getFilterGroups() {
	
		static $filter_groups = null;
		if (!is_null($filter_groups)) {
			return $filter_groups;
		}
	
		$filter_groups = array();
		
		$qry = $this->db->query("SELECT filter_group_id FROM " . DB_PREFIX . "filter_group");
		if (empty($qry->rows)) {
			return $filter_groups;
		}
		
		foreach ($qry->rows as $fgk => $fgv) {
			$filter_groups[] = $fgv['filter_group_id'];
		}

		return $filter_groups;
	}
	
	
	/*
		Returns:
		0 - no options for fitlers are available for the product
		1 - options are available
	*/
	public function rebuildProductFilters($product_id) {
	
		$product_id = (int)$product_id;
	
		// get option groups of the product which have linked filter groups
		//
		$options = $this->db->query("SELECT o.* FROM `" . DB_PREFIX . "option` o INNER JOIN
			" . DB_PREFIX . "product_option po ON o.option_id = po.option_id
			WHERE o.linked_fg_id 
			AND po.product_id = $product_id
			GROUP BY o.option_id
		")->rows;
		
		if (empty($options)) {
			return 0;
		}
		
		$this->load->model('catalog/option');
		
		$language_id = $this->config->get('config_language_id');

		foreach ($options as $option) {
		
			// delete links of old product filters of the option
			//
			$this->db->query("DELETE pf FROM " . DB_PREFIX . "product_filter pf
				INNER JOIN `" . DB_PREFIX. "filter` f ON pf.filter_id = f.filter_id 
				WHERE pf.product_id = $product_id
				AND f.filter_group_id = '" . $option['linked_fg_id'] . "'
			");
			
			// get product option values
			// - filters are not created for simple option values like (dark blue-green)
			//
			$option_values = $this->db->query("SELECT pov.*, oc.* FROM `" . DB_PREFIX . "product_option_value` pov 
				INNER JOIN " . DB_PREFIX . "option_value ov ON pov.option_value_id = ov.option_value_id
				LEFT JOIN " . DB_PREFIX . "ka_ov_components oc ON pov.option_value_id = oc.simple_option_value_id
				WHERE pov.product_id = '$product_id'
				AND pov.option_id = '$option[option_id]'
				GROUP BY pov.product_option_value_id
			")->rows;

			if (empty($option_values)) {
				continue;
			}
			
			foreach ($option_values as $ovv) {
			
				if (empty($ovv['simple_option_value_id'])) {
					$ov = $this->model_catalog_option->getOptionValue($ovv['option_value_id']);
				} else {
					$ov = $this->model_catalog_option->getOptionValue($ovv['compound_option_value_id']);
				}
				
				// try to find filter id by an option name
				//
				$filter = $this->db->query("SELECT * FROM " . DB_PREFIX . "filter_description 
					WHERE name LIKE '" . $this->db->escape($ov['name']) . "' 
						AND filter_group_id = '" . $option['linked_fg_id'] . "'
				")->row;

				// add the filter if it does not exist
				//
				if (empty($filter)) {
					$ov_data = $this->getOptionValue($ov['option_value_id']);
					$filter_id = $this->kamodel_filter->addFiltervalue($option['linked_fg_id'], $ov_data);
				} else {
					$filter_id = $filter['filter_id'];
				}
				
				// insert filter id to the product
				//
				$this->db->query("REPLACE INTO " . DB_PREFIX . "product_filter SET
					product_id = $product_id,
					filter_id = $filter_id
				");
			}
		}
		
		return 1;
	}
	

	public function fillFilters($last_product_id, &$stats) {

		$time_start = time();
	
		if ($last_product_id == 0) {
		
			// delete orhan records in the filter table
			//
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_filter WHERE filter_id NOT IN 
				(SELECT filter_id FROM " . DB_PREFIX . "filter)
			");
			
			$this->db->query("DELETE FROM " . DB_PREFIX . "filter_description WHERE filter_id NOT IN 
				(SELECT filter_id FROM " . DB_PREFIX . "filter)
			");
		}
		
		$products = $this->db->query("SELECT product_id FROM " . DB_PREFIX . "product
			WHERE product_id > '" . (int) $last_product_id . "'
			ORDER BY product_id 
			LIMIT 100
		")->rows;
		
		if (empty($products)) {
			return 0;
		}
		
		foreach ($products as $ck => $cv) {
			$last_product_id = $cv['product_id'];

			$wo_filter = $this->rebuildProductFilters($last_product_id);
			
			if ($wo_filter) {
				$stats['products_w_filter']++;
			} else {
				$stats['products_wo_filter']++;
			}			
			
			if (time() - $time_start > 15) {
				break;
			}
		}
		
		return $last_product_id;
	}
	
	
	public function getOptionValue($option_value_id) {
	
		$ov_data = $this->db->query("SELECT * FROM " . DB_PREFIX . "option_value WHERE 
			option_value_id = '" . (int)$option_value_id . "'
		")->row;
		
		if (empty($ov_data)) {
			return false;
		}
		
		$ov_descr = $this->db->query("SELECT * FROM " . DB_PREFIX . "option_value_description 
			WHERE option_value_id = '" . (int)$ov_data['option_value_id'] . "'
		")->rows;

		$this->load->model('localisation/language');
		$languages = $this->model_localisation_language->getLanguages();

		// init fields
		$ov_data['names'] = array();
		foreach ($languages as $lng) {
			$ov_data['names'][$lng['language_id']] = '';
		}
		
		// fill in fields with the option data
		foreach ($ov_descr as $ovd) {
			$ov_data['names'][$ovd['language_id']] = $ovd['name'];
		}		
	
		return $ov_data;	
	}	
}

class_alias(__NAMESPACE__ . '\ModelProduct', 'ModelExtensionKaExtensionsKaAdvFilterProduct');