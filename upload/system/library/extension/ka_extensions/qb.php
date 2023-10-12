<?php
/*
	$Project$
	$Author$

	$Version$ ($Revision$)
*/
	
namespace extension\ka_extensions;

/**

This class is used for making complex requests to the database. The class helps to split a request to several functions
so the request can be modified in a child class.

*/
class QB {

	var $select    = array();
	var $delete    = array();
	var $from      = array();
	var $innerJoin = array();
	var $leftJoin  = array();
	var $where     = array();
	var $limit     = array();
	var $orderBy   = array();
	var $groupBy   = array();
	
	protected $db = null;

	public function __construct() {
		$this->db = \KaGlobal::getRegistry()->get('db');
	}

	/**
		PARAMS
		$from     - table name
		$from_key - table alias		
	*/
	public function from($from, $from_key = '') {
		if (!empty($from_key)) {
			$this->from[$from_key] = $from;
		} else {
			$this->from[$from] = $from;
		}
	}

	
	public function select($what, $from = '', $from_key = '') {
		
		$this->select[] = $what;
		
		if (!empty($from)) {
			$this->from($from, $from_key);
		}
	}
	

	public function delete($what, $from = '', $from_key = '') {
		
		$this->delete[] = $what;
		
		if (!empty($from)) {
			$this->from($from, $from_key);
		}
	}
	
	
	public function innerJoin($from, $from_key = '', $condition = '') {
		
		$arr = array(
			'table' => $from,
			'on' => $condition
		);
	
		if (!empty($from_key)) {
			$this->innerJoin[$from_key] = $arr;
		} else {
			$this->innerJoin[$from] = $arr;
		}
	}

	public function leftJoin($from, $from_key = '', $condition = '') {
		
		$arr = array(
			'table' => $from,
			'on' => $condition
		);
	
		if (!empty($from_key)) {
			$this->leftJoin[$from_key] = $arr;
		} else {
			$this->leftJoin[$from] = $arr;
		}
	}
	
	/*
		$where - string or array. The array means all condtions inside the array have OR.
	*/
	public function where($where, $value = null) {
	
		if (!is_null($value)) {
			$where = "$where = '" . $this->db->escape($value) . "'";
		}
		$this->where[] = $where;
	}	
	
	public function limit($start, $limit) {
		$this->limit = array(
			'start' => $start,
			'limit' => $limit
		);
	}
	
	public function orderBy($order, $after = '') {
	
		if (empty($after)) {
			$this->orderBy[$order] = $order;
		} else {
			$this->orderBy = Arrays::insertAfterKey($this->orderBy, $order, $after);
		}
	}

	public function groupBy($groupBy, $after = '') {
	
		if (empty($after)) {
			$this->groupBy[$groupBy] = $groupBy;
		} else {
			$this->groupBy = Arrays::insertAfterKey($this->groupBy, $groupBy, $after);
		}
	}
	
	public function getSql() {

		$sql = '';
		
		// select parameters
		//
		if (!empty($this->select)) {

			$sql .= "SELECT " . implode(",", $this->select) . " ";

		} elseif (!empty($this->delete)) {
		
			$sql .= "DELETE " . implode(",", $this->delete) . " ";
			
		}
		
		// from parameters
		//
		if (!empty($this->from)) {
			$sql .= " FROM ";
			foreach ($this->from as $k => $v) {			
				$sql .= DB_PREFIX . $v;
				if ($v != $k) {
					$sql .= " " . $k;
				}
			}
		}
		
		// inner join parameters
		//
		if (!empty($this->innerJoin)) {
			foreach ($this->innerJoin as $k => $v) {
				$sql .= " INNER JOIN " . DB_PREFIX . $v['table'] . ' ' . $k;
				
				if (!empty($v['on'])) {
					$sql .= " ON " . $v['on'] . " ";
				}
			}
		}

		// left join parameters
		//
		if (!empty($this->leftJoin)) {
			foreach ($this->leftJoin as $k => $v) {
				$sql .= " LEFT JOIN " . DB_PREFIX . $v['table'] . ' ' . $k;
				
				if (!empty($v['on'])) {
					$sql .= " ON " . $v['on'] . " ";
				}
			}
		}
		
		// where parameters
		//
		if (!empty($this->where)) {
			$where = "";
			foreach ($this->where as $k => $v) {
				if (!empty($where)) {
					$where .= " AND ";
				}
				if (is_array($v)) {
					$where .= ' (' . implode(" OR ", $v) . ') ';
				} else {
					$where .= ' (' . $v . ') ';
				}
			}
			$sql .= " WHERE $where";
		}

		// group by
		//
		if (!empty($this->groupBy)) {
			$sql .= " GROUP BY " . implode($this->groupBy);
		}
		
		// order by
		//
		if (!empty($this->orderBy)) {
			$sql .= " ORDER BY " . implode($this->orderBy);
		}
		
		// limit
		//
		if (!empty($this->limit)) {
			if (isset($this->limit['start'])) {
				$sql .= " LIMIT " . $this->limit['start'];
				
				if (isset($this->limit['limit'])) {
					$sql .= ", " . $this->limit['limit'];
				}
			}
		}
		
		return $sql;
	}
	
	
	/**
		Builds and runs the query from the QB data
	*/
	public function query() {
		return $this->db->query($this->getSql());
	}	
}