<?php 
/*
	Project: Task Scheduler
	Author : karapuz <support@ka-station.com>

	Version: 3 ($Revision: 139 $)

*/
namespace extension\ka_extensions\ka_scheduler;

class ModelKaTasks extends \KaModel {

	protected $config_code     = 'ka_scheduler_hidden';
	protected $config_store_id = 0;
	protected $log_file        = 'ka_scheduler.log';
	
	protected $kadb;
	protected $custom_log;
	
	// temporary variables
	protected $wget_path;
	protected $crontab_path;

	function onLoad() {
	
		if (!parent::onLoad()) {
			return false;
		}
	
		$this->custom_log = new \Log($this->log_file);
		$this->kadb = new \KaDb($this->db);

		return true;		
	}

	
	public function logMessage($msg) {
		$this->custom_log->write($msg);
	}

	
	public function getSqlNow() {
		$qry = $this->db->query("SELECT NOW() as now_val");
		if (empty($qry->row)) {
			trigger_error("getNow fails.");
		}
		
		return $qry->row['now_val'];
	}


	public function isLite() {
		return true;	
	}

	/*
		http://stackoverflow.com/questions/3938120/check-if-exec-is-disabled
	*/
	public function isExecAvailable() {
		static $available;

		if (!isset($available)) {
			$available = true;
			if (ini_get('safe_mode')) {
				$available = false;
			} else {
				$d = ini_get('disable_functions');
				$s = ini_get('suhosin.executor.func.blacklist');
				if ("$d$s") {
					$array = preg_split('/,\s*/', "$d,$s");
					if (in_array('exec', $array)) {
						$available = false;
					}
				}
			}
		}

		return $available;
	}
	
	
	public function customExec($command, &$output, &$return_var) {
	
		if (!$this->isExecAvailable()) {
			$output     = '';
			$return_var = '';

			return false;
		}
		
		$res = exec($command, $output, $return_var);
	
		return $res;
	}

	
	public function calcNextRun($last_run, $t) {
	
		$d = getdate($last_run);
		
		if ($d['wday'] == 0) {
			$d['wday'] = 7;
		}
		
		$rec = array();
		
		if ($t['period_type'] == 'year') {
			$rec = array(
				'minutes' => $t['period_at_min'],
				'hours'   => $t['period_at_hour'],
				'mday'    => $t['period_at_day'],
				'mon'     => $t['period_at_month'],
				'mon'     => $t['period_at_month'],
				'year'    => $d['year'] + 1,
			);
			
		} elseif ($t['period_type'] == 'month') {
			$rec = array(
				'minutes' => $t['period_at_min'],
				'hours'   => $t['period_at_hour'],
				'mday'    => $t['period_at_day'],
				'mon'     => intval($d['mon']) + 1,
			);
			
		} elseif ($t['period_type'] == 'day') {
			$rec = array(
				'minutes' => $t['period_at_min'],
				'hours'   => $t['period_at_hour'],
				'mday'    => $d['mday'] + 1,
			);

		} elseif ($t['period_type'] == 'hour') {
			$rec = array(
				'minutes' => $t['period_at_min'],
				'hours'   => $d['hours'] + 1,
			);

		} elseif ($t['period_type'] == 'every_n_minutes') {

			$rec = array(
				'minutes' => $d['minutes'] + $t['every_n_minutes'],
			);
		
		} elseif ($t['period_type'] == 'week') {
			$mday = (7 + $t['period_at_dow'] - $d['wday']);
			if ($mday > 7)
				 $mday = $mday % 7;

			$rec = array(
				'minutes' => $t['period_at_min'],
				'hours'   => $t['period_at_hour'],
				'mday' => ($d['mday'] + $mday)
			);
		}

		$d = array_merge($d, $rec);
		
		$ret = mktime ($d['hours'], $d['minutes'], $d['seconds'], $d['mon'], $d['mday'], $d['year']);
		
		return $ret;
	}
	
	
	public function changeTaskStatus($task_id, $status) {
	
		$rec = array(
			'run_status' => $status
		);
		
		if ($status == 'not_started') {
			$rec['fail_count'] = 0;
		}
		
		$this->kadb->queryUpdate('ka_tasks', $rec, "task_id = '$task_id'");	
	}
	
	
	public function stopTask($task_id) {
	
		$task = $this->getTask($task_id);
		if (empty($task) || !in_array($task['run_status'], array('working', 'not_finished'))) {
			return false;
		}

		$this->load->kamodel('extension/ka_extensions/tasks/' . $task['module']);
		$name = 'model_' . str_replace('/', '_', $task['module']);

		$this->logMessage("stopTask function started for task_id = $task_id");
		
		if (!empty($this->$name) && method_exists($this->$name, 'stopSchedulerOperation')) {
			$this->$name->stopSchedulerOperation($current_task['operation']);
		}
		
		$this->changeTaskStatus($task_id, 'not_started');

		$this->logMessage("stopTask function finished for task_id = $task_id");
		return true;
	}
	

	public function saveTask($data) {

		if (empty($data)) {
			return false;
		}	
	
		$valid_columns = array(
			'task_id', 'name', 'active', 'priority', 'module', 'operation', 'params',
			'period_type', 'every_n_minutes', 'period_at_min', 'period_at_hour', 'period_at_day', 'period_at_dow',
			'period_at_month', 'start_at', 'end_at', 'is_send_email'
		);
	
		$rec = array();
		foreach ($data as $dk => $dv) {
			if (in_array($dk, $valid_columns)) {
				$rec[$dk] = $dv;
			}
		}
		
		$rec['is_send_email'] = (isset($rec['is_send_email']) ? 1:0);
		
		if (isset($rec['params'])) {
			$rec['params'] = serialize($rec['params']);
		}
		
		if (empty($rec['active']) || $rec['active'] != 'Y') {
			$rec['active'] = 'N';
		}
		
		if (empty($data['is_end_at'])) {
			$rec['end_at'] = '0000-00-00 00:00:00';
		}
		
		if (empty($data['task_id'])) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "ka_tasks SET 
				module = '" . $this->db->escape($data['module']) . "'"
			);
			$task_id = $this->db->getLastId();
		} else {
			$task_id = $data['task_id'];
		}
		unset($rec['task_id']);
			
		$this->kadb->queryUpdate('ka_tasks', $rec, "task_id = '$task_id'");
		
		return $task_id;
	}
	
	
	public function deleteTask($task_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "ka_tasks WHERE task_id = '" . (int)$task_id . "'");
	}

		
	public function getTask($task_id, $decode = false) {
	
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ka_tasks 
			WHERE task_id = '" . (int)$task_id . "'"
		);
		
		if (!empty($query->row)) {
			$query->row['params'] = unserialize($query->row['params']);
			$query->row['stat'] = unserialize($query->row['stat']);
		}
		
		if ($decode && !empty($query->row['params'])) {
			foreach ($query->row['params'] as &$p) {
				$p = htmlspecialchars_decode($p);
			}
		}
		
		return $query->row;
	}

	
	protected function getRecords($data) {

		if (empty($data['fields'])) {
			trigger_error("ka_scheduler: No fields data in getRecords() function");
			return false;
		}
	
		$sql = "SELECT " . $data['fields'] . " FROM " . DB_PREFIX . "ka_tasks kt ";
		
		if (!empty($data['where'])) {
			$sql .= " WHERE " . $data['where'];
		}
		
		if (!empty($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];
			
			if (!empty($data['order'])) {
				$sql .= ' ' . $data['order'];
			}
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
		
		return $query;
	}
	
	
	public function getTasks($data = array()) {
	
		$data['fields'] = '*';

		$qry = $this->getRecords($data);
		
		return $qry->rows;
	}
	
	
	public function getTasksTotal($params) {
	
      	$data['fields'] = 'COUNT(*) AS total';
      	
		$qry = $this->getRecords($data);
		
		return $qry->row['total'];
	}
	

	// full model path
	// $model - SampleTask
	//
	public function isSchedulerModel($model) {

		$file = modification(DIR_APPLICATION . 'model/extension/ka_extensions/tasks/' . $model . '.php');
		if (!file_exists($file)) {
			return false;
		}
					
		$class = preg_replace('/[^a-zA-Z0-9]/', '', $model);
		
		$content = file_get_contents($file);
		if (!preg_match("/class[\s]*Model[^\s]*($class)\s/i", $content, $matches)) {
			return false;
		}
		
		@include_once($file);

		$real_class = 'ModelExtensionKaExtensionsTasks' . $matches[1];

		if (!class_exists($real_class)) {
			return false;
		}
		
		$methods = get_class_methods($real_class);
		if (empty($methods)) {
			return false;
		}
		
		if (in_array('runSchedulerOperation', $methods)) {
			return true;
		}
			
		return false;
	}


	/*
	
		RETURNS:
			on error   - false
			on success - array with module (model) names:
						$models = array(
							'1' => 'catalog/product',
							'2' => 'catalog/category',
							'3' => 'user/user'
						);
	*/
	public function getSchedulerModules() {

		// get scheduler models
		//	
		$models = array();
		
		$files = glob(DIR_APPLICATION . 'model/extension/ka_extensions/tasks/*.php');
		
		foreach ($files as $file) {
			$model = basename($file, '.php');
			
			$models[] = $model;
		}
		
		if (empty($models)) {
			return false;
		}

		$scheduler_models = array();
		foreach ($models as $model) {
		
			if (!$this->isSchedulerModel($model)) {
				continue;
			}
			
			$module_name = '';
			
			// try to read human readable module name from the module itself
			//
			$this->load->kamodel('extension/ka_extensions/tasks/' . $model);
			$name = 'model_extension_ka_extensions_tasks_' . $model;
			
			if (method_exists($this->$name, 'getModuleName')) {
				$module_name = $model . " (" . $this->$name->getModuleName() . ")";
			} else {
				$module_name = $model;
			}
			
			$scheduler_models[$model] = $module_name;
		}
		
		return $scheduler_models;
	}
	
	
	public function getSchedulerOperations($module) {
		
		if (!$this->isSchedulerModel($module)) {
			return false;
		}
		
		$this->load->kamodel('extension/ka_extensions/tasks/' . $module);
		$name = 'model_extension_ka_extensions_tasks_' . $module;
		
		if (method_exists($this->$name, 'requestSchedulerOperations')) {
			$ops = $this->$name->requestSchedulerOperations();
		} else {
			$ops = array();
		}

		return $ops;
	}

	/*
		EXAMPLE:
		$params = array(
			'param1' => array(
				'title' => 'Parameter 1',
				'type' => 'select',
				'options' => array(
					'key1' => 'value1',
					'key2' => 'value2'
				),
				'required' => true,
			),
			'param2' => array(
				'title' => 'Parameter 2',
				'type' => 'select',
				'options' => array(
					'key3' => 'value3',
					'key4' => 'value4'
				),
				'required' => true,
			),
		);
	*/
	public function getOperationParams($module, $operation) {
	
		if (!$this->isSchedulerModel($module)) {
			return false;
		}
		
		$this->load->kamodel('extension/ka_extensions/tasks/' . $module);
		$name = 'model_extension_ka_extensions_tasks_' . $module;

		if (method_exists($this->$name, 'requestSchedulerOperationParams')) {				
			$params = $this->$name->requestSchedulerOperationParams($operation);
		} else {
			$params = array();
		}
		
		return $params;
	}
	
	
	public function getPeriodTypes() {
	
		$types = array(
			'every_n_minutes' => $this->language->get('Every N minutes'),
			'hour'   => $this->language->get('Hour'),
			'day'    => $this->language->get('Day'),
			'week'   => $this->language->get('Week'),
			'month'  => $this->language->get('Month'),
			'year'   => $this->language->get('Year'),
		);
		
		return $types;
	}

	public function getPeriodMinutes() {
		$res = range(0, 59);
		return $res;
	}

	public function getPeriodHours() {
		$res = range(0, 23);
		return $res;
	}

	public function getPeriodDays() {
		$res = range(1, 31);
		$res = array_combine($res, $res);
		return $res;
	}
			
	public function getPeriodDows() {
		$res = array(
			'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'
		);
		$res = array_combine(range(1, 7), $res);
		
		return $res;
	}

	public function getPeriodMonths() {
		$res = range(1, 12);
		$res = array_combine($res, $res);
		
		return $res;
	}


	public function getConfigHidden($key) {
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE 
			store_id = '" . (int)$this->config_store_id . "' 
			AND `code` = '" . $this->db->escape($this->config_code) . "'
			AND `key` = '" . $this->db->escape($key) . "'"
		);

		if (empty($query->row)) {
			return null;
		}
		
		if ($query->row['serialized']) {
			$value = unserialize($query->row['value']);
		} else {
			$value = $query->row['value'];
		}
		
		return $value;
	}
			
	
	public function setConfigHidden($key, $value) {
	
		$this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE 
			store_id = '" . (int)$this->config_store_id . "' 
			AND `code` = '" . $this->db->escape($this->config_code) . "'
			AND `key` = '" . $this->db->escape($key) . "'"
		);
		
		$serialized = 0;
		if (is_array($value)) {
			$value = serialize($value);
			$serialized = 1;
		}
				
		$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET 
			store_id = '" . (int)$this->config_store_id . "', 
			`code` = '" . $this->db->escape($this->config_code) . "', 
			`key` = '" . $this->db->escape($key) . "', 
			`value` = '" . $this->db->escape($value) . "',
			serialized = '$serialized'"
		);
	}

	
	public function installTask($task, $replace = false) {

		if (empty($task['module'])) {
			$this->lastError = 'Invalid parameters';
			return false;
		}
		
		if ($replace) {
			$this->db->query("DELETE FROM " . DB_PREFIX . "ka_tasks WHERE
				module = '" . $this->db->escape($task['module']) . "'");			
		}
		
		$this->saveTask($task);
	}


	public function uninstallTask($task_id) {
		$this->deleteTask($task_id);
	}
	

	public function enumTasks($module) {
	
		$data['fields'] = '*';
		$data['where']  = "module = '" . $this->db->escape($module) . "'";

		$res = $this->getRecords($data);
		
		return $res->rows;
	}
	
	
	/*
		returns a text code:
			'crontab_not_found' - warning
			'wget_not_found'    - warning
						
			'cronjob_not_found' - warning
			'wrong_cronjob'     - error
			
			'cronjob_installed' - ok
	*/
	public function getCronjobInstallStatus() {
		
		// check availability of the 'crontab' command
		//
		$res = $out = false;
		$this->customExec("which crontab", $out, $res);

		if (!empty($res) || empty($out)) {
			return 'crontab_not_found';
		}
		$this->crontab_path = $out[0];

		// check availability of the 'wget' command
		//
		$res = $out = false;
		$this->customExec("which wget", $out, $res);
		if (!empty($res)) {
			return 'wget_not_found';
		}
		$this->wget_path = $out[0];

		$status = $this->examineCrontabFile();
		
		return $status;
	}
	
	
	function extractURL($line) {

		if (!preg_match("/([a-z]*):\/\/([^\/]*)\//u", $line, $matches)) {
			return false;
		}
		
		return $matches[0];
	}
	
	/*
		Returns:
			'cronjob_not_found' - 
			'wrong_cronjob'     - 			
			'cronjob_installed' - 			
	*/	
	protected function examineCrontabFile() {
		$res = 'cronjob_not_found';
	
		if (empty($this->crontab_path) || empty($this->wget_path)) {
			return $res;
		}
	
		$st = $out = false;
		$this->customExec($this->crontab_path . " -l", $out, $st);

		if (!empty($st)) {
			return $res;
		}

		if (empty($out)) {
			return 'cronjob_not_found';
		}
		
		$script_key   = $this->config->get('ka_scheduler_run_scheduler_key');
		
		$script_found = false;
		$wget_found   = false;
		
		$key_valid    = false;
		$wget_valid   = false;
		
		$lines = $out;

		foreach ($lines as $lk => $line) {
			
			$key       = '';
			$wget_path = '';
	
			if (preg_match("/ka_scheduler\/run_scheduler/", $line, $matches)) {
			
				$parsed_line = parse_url($this->extractURL($line));
				if ($parsed_line['scheme'] == 'https') {
					$parsed_store = parse_url(HTTPS_SERVER);
				} else {
					$parsed_store = parse_url(HTTP_SERVER);
				}
				
				if ($parsed_store['host'] != $parsed_line['host']) {
					continue;
				}
				
				$script_found = true;
				
				
				if (preg_match("/key=([\S]*)[\"]/", $line, $matches)) {
					if ($matches[1] == $script_key) {
						$key_valid = true;
					}
										
					if (preg_match("/(\S*)\/wget/", $line, $matches)) {
						$wget_found = true;
						if ($matches[0] == $this->wget_path) {
							$wget_valid = true;
							break;
						} else {
							$wget_valid = false;
						}
					}
				}
			}
		}
	
		if (!$script_found) {
			return 'cronjob_not_found';
		}
		
		if ($key_valid && $wget_valid) {
			return 'cronjob_installed';
		}
		
		return 'wrong_cronjob';
	}
	

	/*
		Returns 
			true  - on success
			false - on failre
			
			Additional information can be found in the lastError variable;
	*/
	public function installCronjob() {
		$status = $this->getCronjobInstallStatus();

		$this->lastError = '';		
		if (in_array($status, array('crontab_not_found', 'wget_not_found'))) {
			$this->lastError = 'Cronjob cannot be installed on the server';
			return false;
		}

		$st = $crontab_content = false;
		$this->customExec($this->crontab_path . " -l", $crontab_content, $st);
		if (!empty($st)) {
			$this->lastError = 'Cronjob cannot be installed on the server';
			return false;
		}

		// another example of cronjob line:
		//
		// /usr/bin/wget -O /dev/null "https://www.test.com/admin/index.php?route=extension/ka_extensions/ka_scheduler/run_scheduler&key=123" -q --no-check-certificate
		//
		$cronjob_line = "*/2 * * * * " . $this->wget_path . " -O - \"" .
			HTTP_SERVER . 
			"index.php?route=extension/ka_extensions/ka_scheduler/run_scheduler&key=" .
			$this->config->get('ka_scheduler_run_scheduler_key') .
			"\" --no-check-certificate >/dev/null 2>&1"
		;
		
		$lines = $crontab_content;
		$lines[] = $cronjob_line;
		$crontab_content = implode(PHP_EOL, $lines) . PHP_EOL;
	
		$file = tempnam(DIR_CACHE, 'cron_');
		if (file_put_contents($file, $crontab_content) === FALSE) {
			$this->lastError = "Cannot create a temporary crontab file";
			return false;
		}
		$out = $st = false;
		$this->customExec($this->crontab_path . " " . $file, $out, $st);		
		@unlink($file);
		if (!empty($st)) {
			$this->lastError = 'Cronjob cannot be installed on the server';
			return false;
		}

		return true;			
	}
	
	
	public function deleteCronjob() {
		$status = $this->getCronjobInstallStatus();

		$this->lastError = '';		
		if (in_array($status, array('crontab_not_found'))) {
			$this->lastError = 'Crontab does not exist on the server';
			return false;
		}

		$crontab_content = $st = false;
		$this->customExec($this->crontab_path . " -l", $crontab_content, $st);
		if (!empty($st)) {
			$this->lastError = 'Crontab file is not available on the server';
			return false;
		}

		$lines = $crontab_content;
		$new_lines = array();
		
		foreach ($lines as $line) {
			
			if (preg_match("/ka_scheduler\/run_scheduler/", $line, $matches)) {
				$parsed_line = parse_url($this->extractURL($line));
				if ($parsed_line['scheme'] == 'https') {
					$parsed_store = parse_url(HTTPS_SERVER);
				} else {
					$parsed_store = parse_url(HTTP_SERVER);
				}
				
				if ($parsed_store['host'] == $parsed_line['host']) {
					continue;
				}
			}			
			
			$new_lines[] = $line;
		}
		$crontab_content = implode(PHP_EOL, $new_lines) . PHP_EOL;
	
		$file = tempnam(DIR_CACHE, 'cron_');
		if (file_put_contents($file, $crontab_content) === FALSE) {
			$this->lastError = "Cannot create a temporary crontab file";
			return false;
		}
		$out = $st = '';
		$this->customExec($this->crontab_path . " " . $file, $out, $st);	
		@unlink($file);
		if (!empty($st)) {
			$this->lastError = 'New crontab file cannot be installed on the server';
			return false;
		}

		return true;			
	}
	
	
	public function getActiveTask() {
	
		$qry = $this->db->query("SELECT task_id FROM " . DB_PREFIX . "ka_tasks WHERE
			run_status IN ('working', 'not_finished')
		");
		
		if (empty($qry->rows)) {
			return false;
		}
		
		if (count($qry->rows) > 1) {
			$this->logMessage("Number of active tasks is more than 1. Stopping all active tasks");
			foreach ($qry->rows as $task) {
				$this->stopTask($task['task_id']);
			}
			return false;
		}
		
		$task = $this->getTask($qry->row['task_id'], true);
		
		return $task;
	}
	
	
	public function canTaskWorkFurther($task_id) {
		
		$task = $this->getTask($task_id, true);
		if (empty($task)) {
			return false;
		}
		
		if (!in_array($task['run_status'], array('working', 'not_finished'))) {
			return false;
		}
		
		// time in 'working' or 'not_finished' states.
		//
		$max_alive_time = (int) $this->config->get('ka_scheduler_stop_task_after_n_minutes');
		$max_fails      = (int) $this->config->get('ka_scheduler_stop_task_after_n_failures');
		
		// time in 'working' state without updating the session variable
		//
		$max_working_time  = (int) $this->config->get('ka_scheduler_task_is_dead_after_n_minutes');
		$now               = strtotime($this->getSqlNow());

		if ($max_alive_time <= 0 || $max_alive_time > 360) {
			$max_alive_time = 40;
		}		
		if ($max_working_time <= 0 || $max_working_time > 60) {
			$max_working_time = 10;
		}		
		if ($max_fails <= 0 || $max_fails > 10) {
			$max_fails = 3;
		}
				
		// convert parameters to seconds
		//
		$max_working_time *= 60;
		$max_alive_time   *= 60;
	
		$last_task_run  = strtotime($task['last_run']);
		$first_task_run = strtotime($task['first_run']);

		// prevent overloading the server if some script starts redirection too often
		//
		if ($task['run_count'] >= 10 && 
			($now - $first_task_run < 60))
		{
			$this->kamodel_ka_tasks->logMessage("The task cannot run further. '$task[name]', task_id = $task[task_id] too many redirections for a short period of time)");
			return false;
		}
		
		if ($now - $first_task_run > $max_alive_time) {
			$this->kamodel_ka_tasks->logMessage("The task cannot run further. the '$task[name]' task_id = $task[task_id] . It exceeded 'max_alive_time'.");
			return false;
		}

		if (in_array($task['run_status'], array('working'))) {
			
			if ($now - $last_task_run > $max_working_time) {
					
				if ($task['fail_count'] > $max_fails) {
					$this->kamodel_ka_tasks->logMessage("The task cannot run further. '$task[name]' task. task_id = $task[task_id] . The task had too many fails");
					return false;
				}

				$this->kamodel_ka_tasks->logMessage("Task (" . $task['task_id'] . ") is dead. Resurrection.");
							
				$this->kamodel_ka_tasks->changeTaskStatus($task_id, 'not_finished');
				$this->db->query("UPDATE " . DB_PREFIX . "ka_tasks 
					SET fail_count = fail_count + 1
					WHERE task_id = '$task_id'"
				);
			}
		}

		return true;
	}
	
	
	public function getNextTask() {
	
		$now = strtotime($this->getSqlNow());
	
		$qry = $this->db->query("SELECT task_id, period_type, period_at_min, every_n_minutes,
			period_at_hour, period_at_day, period_at_dow, period_at_month,
			last_run
			FROM " . DB_PREFIX . "ka_tasks
			WHERE
				active = 'Y'
				AND start_at <= NOW()
				AND IF(end_at > 0, end_at > NOW(), TRUE)
			ORDER BY
				last_run ASC, priority DESC
			"
		);
			
		if (empty($qry->row)) {
			return false;
		}

		$current_task = null;
		
		foreach ($qry->rows as $t) {
			$last_run = strtotime($t['last_run']);
			
			$next_run = $this->calcNextRun($last_run, $t);
			
			if (empty($next_run)) {
				$this->logMessage("Wrong next run parameter for task_id = $t[task_id]");
				continue;
			}
			
			if ($now >= $next_run) {
				$current_task = $this->getTask($t['task_id'], true);
				if (!empty($current_task)) {
					break;
				}
			}
		}

		return $current_task;
	}	
}

class_alias(__NAMESPACE__ . '\ModelKaTasks', 'ModelExtensionKaExtensionsKaSchedulerKaTasks');