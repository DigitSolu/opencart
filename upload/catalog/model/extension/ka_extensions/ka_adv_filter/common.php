<?php
/*
	$Project: Advanced Filter $
	$Author: karapuz team <support@ka-station.com> $

	$Version: 1.0.1.2 $ ($Revision: 60 $)
*/

namespace extension\ka_extensions\ka_adv_filter;

class ModelCommon extends \KaModel {

	protected function onLoad() {
		if (!class_exists('\KaGlobal') || !\KaGlobal::isKaInstalled('ka_adv_filter')) {
			return false;
		}
		return true;
	}

	public function getCategories() {
	
		$this->load->model('catalog/category');

		// 3 Level Category Search
		$categories = array();

		$categories_1 = $this->model_catalog_category->getCategories(0);

		foreach ($categories_1 as $category_1) {
			$level_2_data = array();

			$categories_2 = $this->model_catalog_category->getCategories($category_1['category_id']);

			foreach ($categories_2 as $category_2) {
				$level_3_data = array();

				$categories_3 = $this->model_catalog_category->getCategories($category_2['category_id']);

				foreach ($categories_3 as $category_3) {
					$level_3_data[] = array(
						'category_id' => $category_3['category_id'],
						'name'        => $category_3['name'],
					);
				}

				$level_2_data[] = array(
					'category_id' => $category_2['category_id'],
					'name'        => $category_2['name'],
					'children'    => $level_3_data
				);
			}

			$categories[] = array(
				'category_id' => $category_1['category_id'],
				'name'        => $category_1['name'],
				'children'    => $level_2_data
			);
		}
	
		return $categories;
	}

	
	public function isFilterOnSearchPage($store_id = 0) {
	
		$query = $this->db->query("SELECT layout_module_id FROM " . DB_PREFIX . "layout_module lm
			INNER JOIN " . DB_PREFIX . "layout_route lr ON
				lm.layout_id = lr.layout_id 
			WHERE
				lr.store_id = '" . (int) $store_id . "'
				AND lm.code = 'filter'
				AND lr.route = 'product/search'
			LIMIT 1
		");
		
		if (empty($query->rows)) {
			return false;
		}
		
		return true;
	}
	
	
	public function isWithSubcategories() {
		return true;
	}

	
	public function getFiltersByGroups($filters) {
		
		$filter_ids = explode(',', $filters);
		
		$implode = array();
		foreach ($filter_ids as $filter_id) {
			$implode[] = (int)$filter_id;
		}
		
		$filter_groups = $this->db->query("SELECT * FROM `" . DB_PREFIX . "filter` 
			WHERE filter_id IN (" . implode(',', $implode) . ")
		")->rows;
	
		$ret = array();
		if (empty($filter_groups)) {
			return $ret;
		}
		
		foreach ($filter_groups as $fg) {
			if (!isset($ret[$fg['filter_group_id']])) {
				$ret[$fg['filter_group_id']] = array();
			}
			
			$ret[$fg['filter_group_id']][] = $fg['filter_id'];
		}
		
		return $ret;
	}
	
}

class_alias(__NAMESPACE__ . '\ModelCommon', '\ModelExtensionKaExtensionsKaAdvFilterCommon');