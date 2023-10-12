<?php
/*
	$Project: Task Scheduler $
	$Author: karapuz team <support@ka-station.com> $

	$Version: 3.0.3.8 $ ($Revision: 139 $)
*/

namespace extension\ka_extensions\ka_scheduler;

class ControllerRunScheduler extends \KaController {

	protected $last_scheduler_run;
	protected $current_task;
	
	protected $last_error;

	protected function onLoad() {
		$action = new \Action('startup/startup');
		return true;
	}
		
	protected function redirectPage($location) {
		$file = false;
		$line = false;
		if (!headers_sent($file, $line) && !$this->user->isLogged()) {
			$this->response->redirect($location);
		} else {
			if (!empty($file)) {
				$this->last_error = "Output started in file: $file at line: $line. ";
			}		
		
			echo "<html";
		    echo "<head><meta http-equiv=\"Refresh\" content=\"0;URL=" . $location ."\" /></head>";
			echo "<body>";		    
			$this->kamodel_ka_tasks->logMessage($this->last_error .= "Current task ID: " . $this->current_task['task_id'] 
 				. ". Redirect count: " . $this->current_task['run_count'] . "..."
			);
			echo $this->last_error;
			echo "</body></html";
						
		    flush();
		}
		exit;
	}

	
	public function index() {

		$this->kamodel('ka_tasks');
		$this->kamodel_ka_tasks->logMessage("started");

		if (!$this->user->isLogged() && (empty($this->request->get['key']) || 
			$this->request->get['key'] != $this->config->get('ka_scheduler_run_scheduler_key'))
			&& !defined('IS_CONSOLE_MODE')
		) {
			echo "Task Scheduler: wrong key.";
			$this->kamodel_ka_tasks->logMessage("WARNING: key is not valid or not found");
			return;
		}
		
		while (1) {
			if (!$this->run()) {
				break;
			}
		};
		

		if ($this->user->isLogged()) {
		
			$this->addTopMessage("The task finished successfully", "I");
			$this->response->redirect($this->url->link('extension/ka_extensions/ka_scheduler/ka_tasks', 'user_token=' . $this->session->data['user_token'], true));
		
		} else {
			$this->kamodel_ka_tasks->logMessage($this->last_error = "script ends.");
			echo "Task Scheduler: " . $this->last_error;
		}
	}
	
	
	/* 
		RETURNS:
			true  - can be started again
			false - no need to start again, stop the script
	
		possible task statuses
			not_started  - (called 'Idle')
			working      - (called 'Working')
			not_finished - (called 'In progress')
	*/
	protected function run() {

		$sql_now = $this->kamodel_ka_tasks->getSqlNow();
		
		$this->last_scheduler_run = strtotime($this->kamodel_ka_tasks->getConfigHidden('ka_scheduler_last_scheduler_run'));
		$this->kamodel_ka_tasks->setConfigHidden('ka_scheduler_last_scheduler_run', $sql_now);

		$requested_task_id = (isset($this->request->get['task_id'])) ? $this->request->get['task_id']:0;
		if (!empty($requested_task_id)) {
			$this->kamodel_ka_tasks->logMessage("Requested task_id =" . $requested_task_id);
		}

		//
		// STAGE 1: find a task to run
		// 
		// set $current_task to the current task
		//
		$current_task = false;		
		
		// a running task is in priority if it exists
		//
		$active_task = $this->kamodel_ka_tasks->getActiveTask();
		
		if (!empty($active_task)) {
			$this->kamodel_ka_tasks->logMessage("An active task is found. task_id=" . $active_task['task_id']);
		
			if ($this->kamodel_ka_tasks->canTaskWorkFurther($active_task['task_id'])) {
				if ($active_task['run_status'] == 'working') {
					$this->kamodel_ka_tasks->logMessage("An active task is working, no need to start a new process");
					return false;
				}

				if (!empty($requested_task_id) && $active_task['task_id'] != $requested_task_id) {
					$this->kamodel_ka_tasks->logMessage("Another task is running");
					return false;
				}
				
				$current_task = $active_task;
				
			} else {
				// dead tasks can be stopped
				$this->kamodel_ka_tasks->stopTask($active_task['task_id']);
			}
		}

		// when no task found, we can take the next task from a queue
		//
		if (empty($current_task)) {
			if (empty($requested_task_id)) {
				$current_task = $this->kamodel_ka_tasks->getNextTask();
			} else {
				$current_task = $this->kamodel_ka_tasks->getTask($requested_task_id, true);
			}
		}

		// scheduler does not have any active tasks or they do not require start now
		//
		if (empty($current_task)) {
			$this->kamodel_ka_tasks->logMessage("No tasks to run");
		 	return false;
		}
		
		$this->kamodel_ka_tasks->logMessage("Current task: taskid = $current_task[task_id], task module $current_task[module]");

		//
		// STAGE 2: run the found task
		//
		
		// mark the task as started
		//
		if ($current_task['run_status'] == 'not_started') {
			$this->db->query("UPDATE " . DB_PREFIX . "ka_tasks SET 
				first_run = NOW(), run_count = 0 
				WHERE task_id = '$current_task[task_id]'"
			);
		}
		
		// check if the task can realy start
		//
		if (!$this->kamodel_ka_tasks->isSchedulerModel($current_task['module'])) {
		
			$this->kamodel_ka_tasks->changeTaskStatus($current_task['task_id'], 'not_started');		
			$this->kamodel_ka_tasks->logMessage("WARNING: module $current_task[module] cannot be uploaded, it is not compatible with Task Scheduler API");
			
			return false;
		}

		$this->old_session_data = $this->session->data;
		
		if (empty($current_task['run_status']) || $current_task['run_status'] == 'not_started') {
			$stat = array();
			$this->session->data['user_token'] = md5(mt_rand());
			
		} elseif ($current_task['run_status'] == 'not_finished') {
			$stat = $current_task['stat'];
			$this->session->data = unserialize($current_task['session_data']);
			
		} else {
			$this->kamodel_ka_tasks->changeTaskStatus($current_task['task_id'], 'not_started');
			$this->kamodel_ka_tasks->logMessage($e = "ERROR: Unknown run_status ($current_task[run_status]). Reset to not_started.");
			trigger_error($e);
			return false;
		}
		
		$this->load->kamodel('extension/ka_extensions/tasks/' . $current_task['module']);
		$name = 'model_extension_ka_extensions_tasks_' . $current_task['module'];

		if (!is_object($this->$name)) {
			$this->kamodel_ka_tasks->logMessage($this->last_error = "WARNING: model $name cannot be uploaded for unknown reason. Model object does not exist.");
			$this->log->write($this->last_error);
			
			$this->kamodel_ka_tasks->changeTaskStatus($current_task['task_id'], 'not_started');
			
			return false;
		}
		
		// switch the task to 'working' status
		//
		$this->kamodel_ka_tasks->changeTaskStatus($current_task['task_id'], 'working');

		/*
			supported results:
				array(
					'result'       - not_finished/finished;
					'next_task_id' - optional
				)
				
				string             - not_finished/finished
				
			$stat - should be received by reference and statistics results will be returned
					as a hash array.
		*/
		$next_task_id = 0;		
		$res = '';
		
		$run_result = $this->$name->runSchedulerOperation($current_task['operation'], $current_task['params'], $stat);
		if (is_array($run_result)) {
			$res = $run_result['result'];
			if (isset($run_result['next_task_id'])) { 
				$next_task_id = $run_result['next_task_id'];
			}
		} else {
			$res = $run_result;
		}

		//
		// STAGE 3: save results
		//
		                                  	
		$stat = serialize($stat);
			
		$complete_count_sql = '';
		if ($res == 'finished') {
			$session_data = '';
			$run_status = 'not_started';
			$complete_count_sql = "complete_count = complete_count + 1,";
			
		} elseif ($res == 'not_finished') {
			$session_data = serialize($this->session->data);
			$task = $this->kamodel_ka_tasks->getTask($current_task['task_id'], true);
			if ($task['run_status'] == 'not_started') {
				$res = 'finished';
				$run_status = 'not_started';
				$this->kamodel_ka_tasks->logMessage("The task was aborted task_id = $current_task[task_id]");
			} else {
				$run_status = 'not_finished';
			}
			
		} else {
			$this->kamodel_ka_tasks->logMessage($e = "ERROR: Unknown function result ($res). Stopped.");
			trigger_error($e, E_USER_ERROR);
		}
		
		$this->session->data = $this->old_session_data;
		
		$this->db->query("UPDATE " . DB_PREFIX . "ka_tasks
			SET
				stat = '" . $this->db->escape($stat) . "',
				session_data = '" . $this->db->escape($session_data) . "',
				run_status = '$run_status',
				run_count = run_count + 1,
				$complete_count_sql
				last_run = now()
			WHERE
				task_id = '$current_task[task_id]'
		");
		$this->current_task = $this->kamodel_ka_tasks->getTask($current_task['task_id'], true);

		//
		// STAGE 4: redict the page to the next run if required
		//		
		$url = "key=" . $this->config->get('ka_scheduler_run_scheduler_key');
		if (!empty($this->session->data['user_token'])) {
			$url = $url . '&user_token=' . $this->session->data['user_token'];
		}
		
		if ($res == 'not_finished') {
			
			$location = $this->url->link('extension/ka_extensions/ka_scheduler/run_scheduler', $url, true);
			$run_count = $this->current_task['run_count'];
			
			$split_task_by_steps = 0; // for testing_purposes only 
			if ($split_task_by_steps && ($run_count % $split_task_by_steps == 0)) {
				$this->kamodel_ka_tasks->logMessage("Split the task every $split_task_by_steps steps");
			} else {
				$this->kamodel_ka_tasks->logMessage("task is not finished, (run_count: $run_count) redirecting...");
				
				if (defined('IS_CONSOLE_MODE')) {
					return true;
				} else {
					$this->redirectPage($location);
				}
			}
		} elseif ($res == 'finished') {
			
			$task = $this->kamodel_ka_tasks->getTask($current_task['task_id'], true);

			$this->kamodel_ka_tasks->logMessage($this->last_error = "task (id: $current_task[task_id]) is finished.");

			if ($this->config->get('ka_scheduler_send_email_on_completion') == 'Y'
				&& ($task['is_send_email'] > 0)
			) {
				try {
					$ka_mail = new \KaMail($this->registry);
					$ka_mail->data['task'] = $task;
				
					$ka_mail->send($this->config->get('config_email'), $this->config->get('config_email'),
						$this->language->get('Task is complete'), 'extension/ka_extensions/ka_scheduler/mail/ka_task_complete'
					);
				} catch (Exception $e) {
					$this->kamodel_ka_tasks->logMessage('Email was not sent. Exception: ',  $e->getMessage());
				}
			}
			
			if (!empty($next_task_id)) {
				$location = $this->url->link('extension/ka_extensions/ka_scheduler/run_scheduler', $url . '&task_id=' . $next_task_id, true);
				$this->kamodel_ka_tasks->logMessage("next task called (next_task_id: $next_task_id) redirecting...");
				if (defined('IS_CONSOLE_MODE')) {
					return true;
				} else {
					$this->redirectPage($location);
				}
			}
						
		}
		
		return false;
  	}
}

class_alias(__NAMESPACE__ . '\ControllerRunScheduler', 'ControllerExtensionKaExtensionsKaSchedulerRunScheduler');