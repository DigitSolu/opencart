<?php
/*
	$Project: Advanced Filter $
	$Author: karapuz team <support@ka-station.com> $

	$Version: 1.0.1.2 $ ($Revision: 64 $)
*/
namespace extension\ka_extensions\ka_adv_filter;

class ModelPriceSearch extends \KaModel {

	protected function onLoad() {
		if (!class_exists('\KaGlobal') || !\KaGlobal::isKaInstalled('ka_adv_filter')) {
			return false;
		}
		return true;
	}

	
	public function getPriceIntervals($currency = '') {
		$ret = array();
		
		$intervals  = explode(';', $this->config->get('ka_adv_filter_price_intervals'));
		if (empty($intervals)) {
			return $ret;
		}
		
		$price_range_line = $this->language->get('text_price_range_line');
		
		$last = 0;
		if (empty($currency)) {
			$currency = $this->session->data['currency'];
		}
		$default_currency = $this->config->get('config_currency');
		
		foreach ($intervals as $int) {
			$el = array();
			if (empty($int)) {
				continue;
			}
			
			if ($last) {
				$last = $last;
			}			
			
			$from_local = floor($this->currency->convert($last, $default_currency, $currency));
			$to_local   = floor($this->currency->convert($int, $default_currency, $currency));
			
			$from = $this->currency->format($from_local, $currency, 1);
			$to   = $this->currency->format($to_local, $currency, 1);
			$el['text'] = str_replace(array('%from%', '%to%'), array($from, $to), $price_range_line);
			
			$el['from'] = $from_local;
			$el['to']   = $to_local;
			$ret[] = $el;
			
			$last = $int;
		}
		
		if (!empty($ret)) {
			$el = array(
				'from' => $last,
				'to'   => '',
			);
			$text_last_range_line = $this->language->get("text_last_range_line");
			$el['text'] = str_replace(array('%from%'), array($to, ''), $text_last_range_line);
			$ret[] = $el;
		}
		
		if (!empty($ret)) {
			$el = array(
				'text' => $this->language->get('text_any'),
				'from' => '',
				'to'   => '',
			);
				
			array_unshift($ret, $el);
		}

		return $ret;
	}

	public function getGeoZone() {
		$id = $this->tax->getGeoZoneId();
		
		return $id;
	}
		
	public function getPriceRange($category_id = 0, $with_subcategories = false, $currency = '') {
		$ret = array(0, 0);

		$customer_group_id = (int)$this->config->get('config_customer_group_id');
		$geo_zone_id = $this->getGeoZone(); 
		
		if (!empty($category_id)) {
		
			if (!empty($with_subcategories)) {

				$qry = $this->db->query("SELECT MIN(min_price) AS min_price, MAX(max_price) AS max_price FROM " . DB_PREFIX . "ka_category_price_range cpr
					INNER JOIN " . DB_PREFIX . "category_path cp ON cpr.category_id = cp.category_id 
					WHERE cp.path_id = '" . (int)$category_id . "'
					AND geo_zone_id = '$geo_zone_id'
					AND customer_group_id = '$customer_group_id'
				");
				
			} else {
				$qry = $this->db->query("SELECT * FROM " . DB_PREFIX . "ka_category_price_range
					WHERE category_id = '" . (int)$category_id . "'
					AND geo_zone_id = '$geo_zone_id'
					AND customer_group_id = '$customer_group_id'
				");
			}
		} else {
			$qry = $this->db->query("SELECT MIN(price) AS min_price, MAX(price) AS max_price 
				FROM " . DB_PREFIX . "ka_price_cache
				WHERE geo_zone_id = '$geo_zone_id'
				AND customer_group_id = '$customer_group_id'
			");
		}
		
		if (empty($qry->row)) {
			return false;
		}
		
		$ret[0] = $qry->row['min_price'];
		$ret[1] = $qry->row['max_price'];
		
		if (!empty($currency)) {
			$rate = $this->currency->getValue($currency);
			if ($rate != 1) {
				$ret[0] = $ret[0] * $rate;
				$ret[1] = $ret[1] * $rate;
			}
		}

		return $ret;
	}

	
	protected function isPriceCacheValid() {
	
		if (!$this->config->get('ka_adv_filter_last_rebuild')) {
			return false;			
		}

		$last_rebuild = (int) $this->config->get('ka_adv_filter_last_rebuild');

		// if last cache rebuild finished more than 2 days ago we treat it as invalid
		if (time() - $last_rebuild > 172800) {
			$this->log->write('Price cache is outdated in the Advanced Filter module.');
			return false;
		}
		
		return true;
	}
	
	
	public function isPriceSearchShown() {
	
		if (!$this->config->get('ka_adv_filter_is_price_search_shown')) {
			return false;
		}

		if (!$this->isPriceCacheValid()) {
			return false;
		}
		
		return true;
	}
	
	
	/* prices with geo zone id = 0 are prices without any tax */
	public function isTaxNotUsed() {

		if ($this->config->get('config_tax') == 0) {
			return true;
		}
		
		if (!$this->config->get('ka_adv_filter_use_taxes')) {
			return true;
		}

		if (!$this->tax->getGeoZoneId()) {
			return true;
		}
		
		return false;
	}
	
}

class_alias(__NAMESPACE__ . '\ModelPriceSearch', 'ModelExtensionKaExtensionsKaAdvFilterPriceSearch');