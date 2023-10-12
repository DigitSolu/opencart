<?php
/*
	$Project: Advanced Filter $
	$Author: karapuz team <support@ka-station.com> $

	$Version: 1.0.1.2 $ ($Revision: 60 $)
*/

namespace extension\ka_extensions\ka_adv_filter;

class ModelPriceSearch extends \KaModel {

	protected $stats;
	
	static $tax_objects = array();

	/*
		This function is called by cron or by price rebuild routine to process a part of products for 15 seconds
		
		Returns:
			product_id - last processed product id
	*/	
	public function fillPriceSearch($last_product_id, &$stats) {

		$time_start = time();
	
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

			$rebuild_stat = $this->rebuild($last_product_id);

			$stats['records_total'] += $rebuild_stat['records_total'];
			$stats['products_total'] += $rebuild_stat['products_total'];
			
			if (time() - $time_start > 15) {
				break;
			}
		}
		
		return $last_product_id;
	}	
	
	
	/*
		This function rebuilds product price ranges and category price ranges
		
		RETURNS:
			statistics = array(
				'records_total'   => 0, - number of created product price search records
				'products_total'  => 0, - number of updated products
			)
	*/
	public function rebuild($product_id) {

		$this->load->model('setting/setting');
	
		$this->stats = array(
			'records_total'   => 0,
			'products_total'  => 0,
			'geo_zones_total' => 0
		);
		
		$where = array();
		if (!empty($product_id)) {
			$where[] = " product_id = '$product_id'";
		}
		$where = implode(" AND ", $where);
		
		// mark all records as outdated
		//
		$sql = "UPDATE " . DB_PREFIX . "ka_price_cache SET	updated = 0 ";
		if (!empty($where)) {
			$sql .= ' WHERE ' . $where;
		}
		$this->db->query($sql);
		
		// rebuld data
		//
		$this->rebuildInternal($product_id);
		
		// delete outdated records
		//
		$sql = "DELETE FROM " . DB_PREFIX . "ka_price_cache WHERE updated = 0 ";
		if (!empty($where)) {
			$sql .= ' AND ' . $where;
		}
		$this->db->query($sql);
		
		
		// rebuild category data
		//
		$categories = array();
		if (!empty($product_id)) {
			$qry = $this->db->query("SELECT category_id FROM " . DB_PREFIX . "product_to_category
				WHERE product_id = '$product_id'
			");
			if (!empty($qry->rows)) {
				foreach ($qry->rows as $row) {
					$categories[] = $row['category_id'];
				}
			}
		}
				
		$this->rebuildCategoryPriceRanges($categories);
		
		return $this->stats;
	}
	
	
	protected function getTaxGeoZones($tax_class_id, $customer_group_id) {
		$tax_class_id = (int) $tax_class_id;
		$customer_group_id = (int) $customer_group_id;
		
		$sql = "SELECT trate.* FROM " . DB_PREFIX . "tax_rate trate
			INNER JOIN " . DB_PREFIX . "tax_rule trule ON trate.tax_rate_id = trule.tax_rate_id
			INNER JOIN " . DB_PREFIX . "tax_rate_to_customer_group tgroup ON trate.tax_rate_id = tgroup.tax_rate_id
			WHERE 
				trule.tax_class_id = '$tax_class_id'
				AND tgroup.customer_group_id = '$customer_group_id'
		";
		
		$qry = $this->db->query($sql);
		
		$gzones = array();
		if (empty($qry->rows)) {
			return $gzones;
		}
		
		foreach ($qry->rows as $row) {
			$gzones[] = $row['geo_zone_id'];
		}
		
		$gzones = array_unique($gzones);
		
		return $gzones;
	}
	
	
	protected function unsetTaxObjects() {
		self::$tax_objects = array();
	}

	/*
		Returns a tax class object to calculate taxes for specific parameters:
		- geo zone
		- customer group
		
		The tax class objects are cached transparently.
	*/	
	protected function getTaxObject($geo_zone_id, $customer_group_id) {
		
		if (!empty(self::$tax_objects[$geo_zone_id])) {
			return self::$tax_objects[$geo_zone_id];
		}
		
		$this->load->model('localisation/geo_zone');
		$zones = $this->model_localisation_geo_zone->getZoneToGeoZones($geo_zone_id);
		if (empty($zones)) {
			return false;
		}

		// we assume that all zones inside the geo zone are equal, so we use the first found
		// zone for calculating tax rates of the geo zone
		//
		$zone = reset($zones);
		$tax = new \Cart\Tax($this->registry);
		$tax->setShippingAddress($zone['country_id'], $zone['zone_id']);
		$tax->setPaymentAddress($zone['country_id'], $zone['zone_id']);	
		$tax->setStoreAddress($this->config->get('config_country_id'), $this->config->get('config_zone_id'));
		
		self::$tax_objects[$geo_zone_id] = $tax;
		
		return self::$tax_objects[$geo_zone_id];
	}
		
	/*
		prices with geo zone id = 0 are prices without any tax
	*/
	protected function rebuildInternal($product_id = null) {

		$msg = array();
		$is_tax_included = $this->config->get('config_tax') && $this->canTaxesApply($msg);
		
		$qry_groups = $this->db->query("SELECT customer_group_id FROM " . DB_PREFIX . "customer_group");
		if (empty($qry_groups->rows)) {
			return false;
		}
		
		$where = array();
		if (!empty($product_id)) {
			$where[] = " product_id = '$product_id'";
		}
		
		// generate price ranges for active products only
		$where[] =' p.status = 1 ';
		
		
		if (!empty($where)) {
			$where = 'WHERE ' . implode(" AND ", $where);
		} else {
			$where = '';
		}
		
		foreach ($qry_groups->rows as $group) {
		
			// prices are stored in these tables by default
			//   product.price
			//   product_special.price
			//
			$qry_products = $this->db->query("SELECT product_id, price, tax_class_id,
				(SELECT price FROM " . DB_PREFIX . "product_special ps WHERE
					ps.product_id = p.product_id 
					AND ps.customer_group_id = '$group[customer_group_id]' 
					AND (
						(ps.date_start = '0000-00-00' OR ps.date_start < NOW()) 
						AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())
					) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1
				) AS special_price
				FROM " . DB_PREFIX . "product p
				$where
			");
			
			if (empty($qry_products->rows)) {
				continue;
			}
			
			if (empty($this->stats['products_total'])) {
				$this->stats['products_total'] = $qry_products->num_rows;
			}

			// reset tax classes cache because it does not distinguish taxes by customer groups
			//
			$this->unsetTaxObjects();
			
			// get all available geo zones
			//
			// IMPORTANT: prices with geo zone id = 0 are prices without any tax
			//
			$geo_zones = array(0);
			$qry_taxes = $this->db->query("SELECT tax_class_id FROM " . DB_PREFIX . "tax_class");
			if (!empty($qry_taxes->rows)) {
				foreach ($qry_taxes->rows as $tc) {
					$geo_zones = array_merge($geo_zones, $this->getTaxGeoZones($tc['tax_class_id'], $group['customer_group_id']));
				}
			}			
			$geo_zones = array_unique($geo_zones);
			
			if (empty($this->stats['geo_zones_total'])) {
				$this->stats['geo_zones_total'] = count($geo_zones);
			}			

			foreach ($qry_products->rows as $p) {
				$org_price = $p['price'];
				if (!empty($p['special_price'])) {
					$org_price = $p['special_price'];
				}

				if (!empty($geo_zones)) {				
					foreach ($geo_zones as $geo_zone_id) {
						$price = $org_price;
						if ($is_tax_included) {
							$tax = $this->getTaxObject($geo_zone_id, $group['customer_group_id']);
							if (!empty($tax)) {
								$price = $tax->calculate($price, $p['tax_class_id'], $this->config->get('config_tax'));
							}
						}
						$this->db->query("REPLACE INTO " . DB_PREFIX . "ka_price_cache SET
							geo_zone_id = '" . $geo_zone_id . "',
							price = '$price',
							customer_group_id = '$group[customer_group_id]',
							product_id = '$p[product_id]',
							updated = 1
						");
						$this->stats['records_total']++;
					}
				} else {
					$this->db->query("REPLACE INTO " . DB_PREFIX . "ka_price_cache SET
						geo_zone_id = 0,
						price = '$org_price',
						customer_group_id = '$group[customer_group_id]',
						product_id = '$p[product_id]',
						updated = 1
					");
					$this->stats['records_total']++;
				}
			}
		}
	}

		
	public function taxZonesIntersect(&$messages) {
		$messages = array();
		$this->load->model('localisation/geo_zone');
		$this->load->model('localisation/zone');
	
		$qry_taxes = $this->db->query("SELECT * FROM " . DB_PREFIX . "tax_class");
		if (empty($qry_taxes->rows)) {
			return false;
		}
		
		foreach ($qry_taxes->rows as $tax) {
			$tax_zones = array();
			
			$tax_geo_zones = $this->getTaxGeoZones($tax['tax_class_id'], $this->config->get('config_customer_group_id'));
			if (empty($tax_geo_zones)) {
				continue;
			}
			foreach ($tax_geo_zones as $gz) {
				$zones = $this->model_localisation_geo_zone->getZoneToGeoZones($gz);
				if (empty($zones)) {
					return false;
				}
				foreach ($zones as $zone) {
					$all_zone_key = $zone['country_id'] . '_0';
					$zone_key     = $zone['country_id'] . '_' . $zone['zone_id'];
				
					if (in_array($zone_key, $tax_zones)) {
						$zone_info = $this->model_localisation_zone->getZone($zone['zone_id']);
						if (count($messages) < 50) {
							$messages[] = "Zone '$zone_info[name]' (id:$zone[zone_id]) falls into a full country geo zone (id:$zone[geo_zone_id])";
						}
						continue;
					}
					if (in_array($all_zone_key, $tax_zones)) {
						$zone_info = $this->model_localisation_zone->getZone($zone['zone_id']);
						if (count($messages) < 50) {
							$messages[] = "Zone '$zone_info[name]' (id:$zone[zone_id]) falls into a full country geo zone  (id:$zone[geo_zone_id])";
						}
						continue;
					}
										
					$tax_zones[] = $zone_key;
				}
				
			}
		}

		return !empty($messages);
	}
	
	
	/*
		This function uses price_cache table therefore it has to be up-to-date before
		we run rebuilding category price ranges
	*/	
	protected function rebuildCategoryPriceRanges($categories = null) {

		$qry_groups = $this->db->query("SELECT customer_group_id FROM " . DB_PREFIX . "customer_group");
		if (empty($qry_groups->rows)) {
			return false;
		}
		
		$where = '';
		if (!empty($categories)) {
			if (!is_array($categories)) {
				$categories = array($categories);
			}			
			$where = "AND category_id IN ('" . implode("','", $categories) . "')";
		}
		
		$this->db->query("UPDATE " . DB_PREFIX . "ka_category_price_range SET updated = 0 
			WHERE 1 $where
		");
		
		// loop through customer groups
		//	
		foreach ($qry_groups->rows as $group) {

			$geo_zones = $this->getGeoZones($group['customer_group_id']);
			
			foreach ($geo_zones as $gzone) {
			
				// find prices for all categories with a specific customer group and geo zone
				//
				$qry_categories = $this->db->query("SELECT p2c.category_id, MIN(price) AS min_price, MAX(price) AS max_price FROM
					" . DB_PREFIX . "ka_price_cache pc 
					INNER JOIN " . DB_PREFIX . "product_to_category p2c ON pc.product_id = p2c.product_id
					WHERE
						customer_group_id = '$group[customer_group_id]'
						AND geo_zone_id = '$gzone[geo_zone_id]'
					$where
					GROUP BY category_id
				");
				
				if (empty($qry_categories->rows)) {
					continue;
				}
				
				// insert the found category data
				//
				foreach ($qry_categories->rows as $cp) {
					$this->db->query("REPLACE INTO " . DB_PREFIX . "ka_category_price_range SET
						category_id = '$cp[category_id]',
						geo_zone_id = '$gzone[geo_zone_id]',
						customer_group_id = '$group[customer_group_id]',
						min_price = '$cp[min_price]',
						max_price = '$cp[max_price]',
						updated = 1
					");
				}
			}
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX ."ka_category_price_range
			WHERE updated = 0 $where
		");
	}
	
	public function getStats() {
		return $this->stats;
	}
	
	
	public function canTaxesApply(&$errors) {
	
		$errors = array();

		// there are no taxes in the store. Nothing to apply
		//
		$taxes_total = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "tax_class")->row["total"];
		if (empty($taxes_total)) {
			return false;
		}
		
		if ($this->doTaxesContainMixedAddresses()) {
			$errors[] = 'Tax rules are based on different address types. Only shipping or payment address type can be used in taxes for price range calculation with taxes.';
			return false;
		}
		
		
		if (!$this->isConfigAddressInAllTaxes()) {
			$errors[] = 'Address types used in taxes do not match address types selected on the store settings page.
			For example, if you use a payment address for tax calculation, you have to use the payment address
			in the settings. This is a limitation of the price range module.
			';
			return false;
		}
				
		return true;
	}
	
	
	protected function doTaxesContainMixedAddresses() {
		
		$qry = $this->db->query("SELECT COUNT(DISTINCT `based`) AS cnt FROM " . DB_PREFIX . "tax_rule
			WHERE based <> 'store'
			GROUP BY tax_class_id
			HAVING cnt > 1
		");
		
		if ($qry->num_rows) {
			return true;
		}

		return false;	
	}
	
	/*
		Address types in taxes cannot differ from the address type selected
		on the settings page.
	*/	
	protected function isConfigAddressInAllTaxes() {
	
		$tax_default  = $this->config->get('config_tax_default'); // 'shipping' or 'payment'
		$tax_customer = $this->config->get('config_tax_customer'); // 'shipping' or 'payment'
		
		if ($tax_default != $tax_customer) {
			return false;
		}
		
		$qry = $this->db->query("SELECT * FROM " . DB_PREFIX . "tax_rule WHERE
			(based <> '" . $this->db->escape($tax_default) . "' and based <> 'store')
		");
		
		if ($qry->num_rows) {
			return false;
		}
		
		return true;
	}
	
	
	public function updateLastRebuild() {
	
		$this->load->model('setting/setting');
		
		// update the last rebuild statsitics
		//
		$settings = $this->model_setting_setting->getSetting('ka_adv_filter');
		
		$settings['ka_adv_filter_last_rebuild'] =  time();

		// set the flag on price rebuild
		//
		$errors = array();
		$is_tax_included = $this->config->get('config_tax') && $this->canTaxesApply($errors);
		$settings['ka_adv_filter_use_taxes'] = $is_tax_included;
		
		$this->model_setting_setting->editSetting('ka_adv_filter', $settings);
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
	
	
	protected function getGeoZones($customer_group_id) {

		static $cache = array();
		
		if (isset($cache[$customer_group_id])) {
			return $cache[$customer_group_id];
		}
	
		$geo_zones = $this->db->query("SELECT DISTINCT geo_zone_id 
			FROM " . DB_PREFIX. "ka_price_cache
			WHERE customer_group_id = '$customer_group_id'
		")->rows;
		
		$cache[$customer_group_id] = $geo_zones;
		
		return $geo_zones;
	}
}

class_alias(__NAMESPACE__ . '\ModelPriceSearch', 'ModelExtensionKaExtensionsKaAdvFilterPriceSearch');