<?php
/*
	$Project: Advanced Filter $
	$Author: karapuz team <support@ka-station.com> $

	$Version: 1.0.1.2 $ ($Revision: 60 $)
*/

namespace extension\ka_extensions\ka_adv_filter;

class ModelCategory extends \KaModel {

	/*
		Count a number of categories from the specific category to calculate the remainder of the categories
		to process
	*/
	public function countCategories($category_id = 0) {
		$total = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "category WHERE
			category_id >= '" . (int) $category_id . "'
			ORDER BY category_id 
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
	
	
	public function rebuildCategoryFilters($category_id) {
		$filter_groups = $this->getFilterGroups();
		
		$wo_filter = true;
		foreach ($filter_groups as $fgv) {
			if ($this->fillFilterGroup($category_id, $fgv)) {
				$wo_filter = false;
			}
		}
		
		return $wo_filter;
	}

	public function fillFilters($last_category_id, &$stats) {

		$time_start = time();
	
		if ($last_category_id == 0) {
			// delete orhan records in the filter table
			//
			$this->db->query("DELETE FROM " . DB_PREFIX . "category_filter WHERE filter_id NOT IN 
				(SELECT filter_id FROM " . DB_PREFIX . "filter)
			");
			
			$this->db->query("DELETE FROM " . DB_PREFIX . "filter_description WHERE filter_id NOT IN 
				(SELECT filter_id FROM " . DB_PREFIX . "filter)
			");
		}
		
		$categories = $this->db->query("SELECT category_id FROM " . DB_PREFIX . "category
			WHERE category_id > '" . (int) $last_category_id . "'			
			ORDER BY category_id 
			LIMIT 100
		")->rows;
		
		if (empty($categories)) {
			return 0;
		}
		
		foreach ($categories as $ck => $cv) {
			$last_category_id = $cv['category_id'];

			$wo_filter = $this->rebuildCategoryFilters($last_category_id);
			
			if ($wo_filter) {
				$stats['categories_wo_filter']++;
			} else {
				$stats['categories_w_filter']++;
			}			
			
			if (time() - $time_start > 15) {
				break;
			}
		}
		
		return $last_category_id;
	}
	
	/*
		return a number of assigned filter values
	*/
	public function fillFilterGroup($category_id, $filter_group_id) {
	
		$stats = array(
			'empty' => array()
		);
		
		$filter_group_id = (int) $filter_group_id;
		$category_id     = (int) $category_id;
		
		// delete all assinged category filters belonging to $filter_group_id
		//
		$this->db->query("DELETE cf FROM " . DB_PREFIX . "category_filter cf
			INNER JOIN " . DB_PREFIX . "filter f ON cf.filter_id = f.filter_id
			WHERE 
				filter_group_id = '$filter_group_id'
				AND	cf.category_id = '$category_id'
		");
			
		// find all filter_ids from products in the category
		//
		$filter_qry = $this->db->query("SELECT DISTINCT(f.filter_id) FROM " . DB_PREFIX . "filter f
			INNER JOIN " . DB_PREFIX . "product_filter pf ON f.filter_id = pf.filter_id
			INNER JOIN " . DB_PREFIX . "product_to_category ptc ON pf.product_id = ptc.product_id
			INNER JOIN " . DB_PREFIX . "product p ON pf.product_id = p.product_id 
			WHERE
				ptc.category_id = $category_id
				AND f.filter_group_id = $filter_group_id
				AND p.status = 1
		");
		if (empty($filter_qry->rows)) {
			return 0;
		}
			
		// assign found filters to the category
		//
		foreach ($filter_qry->rows as $f) {
			$this->db->query("REPLACE INTO " . DB_PREFIX . "category_filter SET
				category_id = $category_id,
				filter_id = $f[filter_id]
			");			
		}
		
		return count($filter_qry->rows);
	}
}

class_alias(__NAMESPACE__ . '\ModelCategory', 'ModelExtensionKaExtensionsKaAdvFilterCategory');