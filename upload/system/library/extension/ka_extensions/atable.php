<?php
/*
	$Project$
	$Author$

	$Version$ ($Revision$)
	
	This class is deprecated. It is being replaced by ADBTable + recordset.
	
*/
namespace extension\ka_extensions;

abstract class ATable extends \Model {

	const TABLE_NAME = '';

	protected $errors = array();
	
	// list of table primary keys
	protected $primary_keys = array(); 
	
	// these values are inserted to all records and overwrite passed values with same keys
	protected $values = array();
	
	function setValue($name, $value) {
		$this->values[$name] = $value;
	}

	public function onLoad() {
		$this->primary_keys = $this->getPrimaryKeys();
	}

	function insert($data = null) {
	
		if (!empty($data)) {
			$record = $this->getRecordFromData($data);
			if (empty($record)) {
				return false;
			}
		} else {
			$record = $this->values;
		}

		$id = $this->kadb->insert(static::TABLE_NAME, $record);
		return $id;
	}

	/*
		Update table record(s)
		
		$data - array of fields and values
		$where - condition for where. When it is empty, where is built automatically from 
		         primary fields.
	*/
	function update($data, $where = null) {

		if (is_null($where)) {
			// update by automatically selected primary fields
			//
			$result = $this->save($data, true);
			return $result;
		}
	
		$record = $this->getRecordFromData($data);
		if (empty($record)) {
			return false;
		}

		// update by condition in $where parameter
		$result = $this->kadb->update(static::TABLE_NAME, $record, $where);
		
		return $result;
	}
	
	
	/*
		Save one record from the data
	*/
	function save($data, $allow_insert = true) {
	
		$record = $this->getRecordFromData($data);

		if (empty($record)) {
			return false;
		}

		if ($allow_insert) {
		
			$result = $this->kadb->insertOrUpdate(static::TABLE_NAME, $record);
			
		} else {
		
			$keys = array_keys($record);
			
			if (empty($this->primary_fields)) {
				throw new \Exception(__METHOD__ . " primary keys are not defined for the table:" . static::TABLE_NAME);
			}
			
			$diff = array_diff($this->primary_keys, $keys);
			if (!empty($diff)) {
				throw new \Exception(__METHOD__ . " several primary keys were not provided (" 
					. implode(", ", $diff) . ") in table update (" . static::TABLE_NAME . ")
				");
			} else {
				$where = array();
				foreach ($primary_keys as $pk) {
					$where[] = "$pk = '" . $this->db->escape($record[$pk]) . "'";
					unset($record[$pk]);
				}
				$where = implode(" AND ", $where);
				
				$result = $this->kadb->update(static::TABLE_NAME, $rec, $where);
			}
		}

		return $result;
	}
	
	/*
		Returns an array with a table record which can be updated

		$data - an array of fields with table data
	*/	
	protected function getRecordFromData($data) {

		if (!is_array($data)) {
			return array();
		}
	
		$values = $this->getValuesByFields($data);
		
		return $values;
	}	
	
	
	protected function getValuesByFields($data) {
	
		if (empty($data)) {
			return array();
		}
	
		// get table fields to update
		$fields = $this->getFields();

		// create new records
		//
		$record = array();
		$is_data_found = false;
			
		foreach ($fields as $k => $v) {
			if (isset($data[$k])) {
				$record[$k] = $data[$k];
				$is_data_found = true;
			}
			
			// overwrite any value with values assigned to the table
			// it may be used for values not available for update from the data
			//
			if (isset($this->values[$k])) {
				$record[$k] = $this->values[$k];
			}
		}
		
		// skip records which did not have any values in the data array
		//
		if (!$is_data_found) {
			return array();
		}
			
		return $record;
	}
	
	/*
		Return a list of fields from the table
	*/
	abstract public function getFields();
	
	/*
		return a list of primary keys
	*/
	public function getPrimaryKeys() {
	
		$fields = $this->getFields();
		
		$pkeys = array();
		foreach ($fields as $k => $v) {
			if (!empty($v['primary_key'])) {
				$pk[] = $k;
			}
		}	
		
		return $pkeys;
	}
}