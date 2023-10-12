<?php
/*
	$Project: Advanced Filter $
	$Author: karapuz team <support@ka-station.com> $

	$Version: 1.0.1.2 $ ($Revision: 60 $)
*/

namespace extension\ka_extensions\ka_adv_filter;

class ModelFilter extends \KaModel {

	public function addFilterValue($filter_group_id, $data) {

		if (empty($data['names'])) {
			return 0;
		}

		$image = isset($data['image']) ? $data['image']:'';

		//when the filter is not found, it will be added
		//
		$this->db->query("INSERT INTO " . DB_PREFIX . "filter SET
			image = '$image',
			filter_group_id = '$filter_group_id'
		");
		
		$filter_id = $this->db->getLastId();
		if (empty($filter_id)) {
			$this->log->write("ERROR: Filter was not added" . __METHOD__);
			return 0;
		}

		// insert the filter for the current langauge
		//
		foreach ($data['names'] as $language_id => $name) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "filter_description SET
				filter_group_id = '$filter_group_id',
				filter_id = '$filter_id',
				language_id = '$language_id',
				name = '" . $this->db->escape($name) . "'
			");
		}
		
		return $filter_id;
	}	
}

class_alias(__NAMESPACE__ . '\ModelFilter', 'ModelExtensionKaExtensionsKaAdvFilterFilter');