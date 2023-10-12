<?php
/*
	$Project: Advanced Filter $
	$Author: karapuz team <support@ka-station.com> $

	$Version: 1.0.1.2 $ ($Revision: 60 $)
*/

namespace extension\ka_extensions\ka_adv_filter;

class ModelOption extends \KaModel {

	public function getComponents($option_value_id) {
	
		$result = $this->db->query("SELECT ovd.* FROM " . DB_PREFIX . "ka_ov_components ovc
			INNER JOIN " . DB_PREFIX . "option_value_description ovd ON ovc.simple_option_value_id = ovd.option_value_id
			WHERE 
				ovc.compound_option_value_id = '"  . (int)$option_value_id . "'
				AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'
		")->rows;
		
		return $result;
	}


	public function findOptionValues($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "option_value_description ovd WHERE 
			ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'
		";

		if (!empty($data['filter_option_id'])) {
			$sql .= " AND ovd.option_id = '" . (int)$data['filter_option_id'] . "'";
		}
		
		if (!empty($data['filter_name'])) {
			$sql .= " AND ovd.name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		$sort_data = array(
			'name',
			'LENGTH(name)'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY name";
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
	
	
	public function copyOptionsToFilter($option_id, $filter_group_id) {

		$language_id = (int)$this->config->get('config_language_id');

		$stats = array(
			'added' => 0
		);
				
		// find all option values
		//
		$ovd_qry = $this->db->query("SELECT ov.*, ovd.* FROM " . DB_PREFIX . "option_value ov 
			INNER JOIN " . DB_PREFIX . "option_value_description ovd ON ov.option_value_id = ovd.option_value_id
			WHERE 
				ov.option_id = '$option_id'
				AND language_id = '$language_id'
		");
		
		foreach ($ovd_qry->rows as $ovd) {
			
			//find filter for the option value
			//
			$fv_qry = $this->db->query("SELECT * FROM " . DB_PREFIX . "filter_description
				WHERE 
					filter_group_id = '$filter_group_id'
					AND name = '" . $this->db->escape($ovd['name']) . "'
			");

			$image = $ovd['image'];
			
			if (!empty($fv_qry->rows)) {
			
				// update the image for existing filter
				//
				$this->db->query("UPDATE " . DB_PREFIX . "filter SET
					image = '" . $this->db->escape($image) . "'
					WHERE
						filter_id = '" . $fv_qry->row['filter_id'] . "'
				");

				continue;
			}
			
			//when the filter is not found, it will be added
			//
			$this->db->query("INSERT INTO " . DB_PREFIX . "filter SET
				image = '$image',				
				filter_group_id = '$filter_group_id'
			");
			
			$filter_id = $this->db->getLastId();
			if (empty($filter_id)) {
				die('Filter was not added');
			}

			// insert the filter for the current langauge
			//
			$this->db->query("INSERT INTO " . DB_PREFIX . "filter_description SET
				filter_id = '$filter_id',
				language_id = '$language_id',
				filter_group_id = '$filter_group_id',
				name = '" . $this->db->escape($ovd['name']) . "'
			");
			$stats['added']++;
		}
		
		return $stats;
	}
	
	
	public function getSourceOption($filter_group_id) {
	
		$option = $this->db->query("SELECT * FROM `" . DB_PREFIX . "option` WHERE
			linked_fg_id = '" . (int)$filter_group_id . "'
		")->row;
		
		if (empty($option)) {
			return false;
		}
	
		$this->load->model('catalog/option');
		
		$option = $this->model_catalog_option->getOption($option['option_id']);
		
		return $option;
	}
	
	
}

class_alias(__NAMESPACE__ . '\ModelOption', 'ModelExtensionKaExtensionsKaAdvFilterOption');