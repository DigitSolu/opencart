<?php
/*
	$Project: Task Scheduler $
	$Author: karapuz team <support@ka-station.com> $

	$Version: 3.0.3.8 $ ($Revision: 139 $)
*/

namespace extension\ka_extensions\ka_scheduler;

class ControllerKaTasks extends \KaController { 

	protected function onLoad() {
		$this->load->language('ka_extensions/ka_scheduler');
		$this->load->model('localisation/language');
		$this->kamodel('ka_tasks');
	}

			
  	public function index() {
  	
    	$this->document->setTitle($this->language->get('Task Scheduler'));
    	$this->getList();
  	}


  	public function save() {

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

      		$this->kamodel_ka_tasks->saveTask($this->request->post);
		  	
			$url = '';
			if (isset($this->session->data['ka_tasks_url'])) {
				$url = $this->session->data['ka_tasks_url'];
			}
			
   	  		$this->response->redirect($this->url->link('extension/ka_extensions/ka_scheduler/ka_tasks', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}
	
    	$this->getForm();
  	}
  		

  	public function stop() {

		if (!empty($this->request->get['task_id'])) {
	   		$this->kamodel_ka_tasks->stopTask($this->request->get['task_id']);
		  	
			$url = '';
			if (isset($this->session->data['ka_tasks_url'])) {
				$url = $this->session->data['ka_tasks_url'];
			}
			
   	  		$this->response->redirect($this->url->link('extension/ka_extensions/ka_scheduler/ka_tasks', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}
	
    	$this->getForm();
  	}
  	
  	public function stat() {
  	
    	$this->getTaskStat();
  	}

  	  	  	  	
  	public function delete() {

		$url = '';
		if (isset($this->session->data['ka_tasks_url'])) {
			$url = $this->session->data['ka_tasks_url'];
		}
  			
    	if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $task_id) {
				$this->kamodel_ka_tasks->deleteTask($task_id);
			}
			
			$this->addTopMessage($this->language->get('text_success'));
		}
		
		$this->response->redirect($this->url->link('extension/ka_extensions/ka_scheduler/ka_tasks', 'user_token=' . $this->session->data['user_token'] . $url, true));
  	}

  	
  	public function updateList() {

		$url = '';
		if (isset($this->session->data['ka_tasks_url'])) {
			$url = $this->session->data['ka_tasks_url'];
		}
  			
    	if (isset($this->request->post['tasks']) && $this->validateUpdateList()) {
    	
			foreach ($this->request->post['tasks'] as $task_id => $task) {
				
				$rec = array(
					'task_id'  => $task_id,
					'active'   => (isset($task['active']) ? 'Y':'N'),
					'priority' => $task['priority'],
				);
				$this->kamodel_ka_tasks->saveTask($rec);
			}
			
			$this->session->data['success'] = $this->language->get('text_success');
		}
		
		$this->response->redirect($this->url->link('extension/ka_extensions/ka_scheduler/ka_tasks', 'user_token=' . $this->session->data['user_token'] . $url, true));
  	}
  	    
  	
  	protected function getList() {

  		$params = array(
  			'sort'  => 'priority', 
  			'order' => 'ASC', 
  			'page'  => '1'
  		);
  		
  		$url_array = array();
  		foreach ($params as $k => $v) {
			if (isset($this->request->get[$k])) {
				$params[$k] = $this->request->get[$k];				
	  		}
	  		$url_array[$k] = $k . '=' . $params[$k];
	  	}
		$url = '&' . implode('&', $url_array);
		$this->session->data['ka_tasks_url'] = $url;
		
	  	if ($params['order'] == 'ASC') {
	  		$url_array['order'] = 'order=DESC';
	  	} else {
	  		$url_array['order'] = 'order=ASC';
	  	}
	  	$url_array['sort'] = 'sort=kt.name';
		$this->data['sort_name'] = $this->url->link('extension/ka_extensions/ka_scheduler/ka_tasks', 'user_token=' . $this->session->data['user_token'] . '&' . implode('&', $url_array), true);
		
		$url_array['sort'] = 'sort=kt.priority';
		$this->data['sort_priority'] = $this->url->link('extension/ka_extensions/ka_scheduler/ka_tasks', 'user_token=' . $this->session->data['user_token'] . '&' . implode('&', $url_array), true);
			  				
  		$this->data['breadcrumbs'] = array();
   		$this->data['breadcrumbs'][] = array(
       			'text'      => $this->language->get('text_home'),
				'href'      => $this->url->link('common/home', 'user_token=' . $this->session->data['user_token'], true),
      			'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       			'text'      => $this->language->get('Tasks'),
				'href'      => $this->url->link('extension/ka_extensions/ka_scheduler/ka_tasks', 'user_token=' . $this->session->data['user_token'] . $url, true),
      			'separator' => ' :: '
   		);

   		$is_lite = $this->data['is_lite'] = $this->kamodel_ka_tasks->isLite();
//		$this->data['insert']        = $this->url->link('extension/ka_extensions/ka_scheduler/ka_tasks/save', 'user_token=' . $this->session->data['user_token'] . $url, true);
		$this->data['update_list']   = $this->url->link('extension/ka_extensions/ka_scheduler/ka_tasks/updateList', 'user_token=' . $this->session->data['user_token'] . $url, true);	
		$this->data['delete']        = $this->url->link('extension/ka_extensions/ka_scheduler/ka_tasks/delete', 'user_token=' . $this->session->data['user_token'] . $url, true);	
		$this->data['run_scheduler'] = $this->url->link('extension/ka_extensions/ka_scheduler/run_scheduler', 'user_token=' . $this->session->data['user_token'] . $url, true);
		
		$this->data['tasks'] = array();

		$last_scheduler_run = $this->config->get('ka_scheduler_last_scheduler_run');
		if (!empty($last_scheduler_run)) {
			$this->data['last_scheduler_run'] = date($this->language->get('date_format_short'), strtotime($last_scheduler_run)) 
				. ' ' . date($this->language->get('time_format'), strtotime($last_scheduler_run));
		} else {
			$this->data['last_scheduler_run'] = $this->language->get('Never');
		}
		
		$params['start'] = ($params['page'] - 1) * $this->config->get('config_admin_limit');
		$params['limit'] = $this->config->get('config_admin_limit');
		
		$tasks_total = $this->kamodel_ka_tasks->getTasksTotal($params);
		
		$results = $this->kamodel_ka_tasks->getTasks($params);
 
		foreach ($results as $result) {
			$action = array();
			
			$action[] = array(
				'text' => $this->language->get('Edit'),
				'href' => $this->url->link('extension/ka_extensions/ka_scheduler/ka_tasks/save', 'user_token=' . $this->session->data['user_token'] . '&task_id=' . $result['task_id'] . $url, true)
			);
			
			if (in_array($result['run_status'], array('working', 'not_finished'))) {
				$action[] = array(
					'text' => $this->language->get('Stop'),
					'href' => $this->url->link('extension/ka_extensions/ka_scheduler/ka_tasks/stop', 'user_token=' . $this->session->data['user_token'] . '&task_id=' . $result['task_id'] . $url, true)
				);
			} else {
				$action[] = array(
					'text' => $this->language->get('Run'),
					'href' => $this->url->link('extension/ka_extensions/ka_scheduler/run_scheduler', 'user_token=' . $this->session->data['user_token'] . '&task_id=' . $result['task_id'], true),
				);
			}

			$last_start = strtotime($result['last_run']);
			if ($result['last_run'] != '0000-00-00 00:00:00') {
				$last_start = date($this->language->get('date_format_short'), $last_start) 
					. ' ' . date($this->language->get('time_format'), $last_start);
			} else {
				$last_start = $this->language->get('Never');
			}
									
			$this->data['tasks'][] = array(
				'task_id'   => $result['task_id'],
				'name'           => $result['name'],
				'last_start'     => $last_start,
				'status'         => $result['run_status'],
				'complete_count' => $result['complete_count'],
				'active'         => $result['active'],
				'priority'       => $result['priority'],
				'stat_link'      => $this->url->link('extension/ka_extensions/ka_scheduler/ka_tasks/stat', 'user_token=' . $this->session->data['user_token'] . '&task_id=' . $result['task_id'] . $url, true),
				'selected'       => isset($this->request->post['selected']) && in_array($result['task_id'], $this->request->post['selected']),
				'action'         => $action
			);
		}	

		$statuses = array(
			'not_started'  => 'Idle',
			'working'      => 'Working',
			'not_finished' => 'In Progress',
		);
		$this->data['statuses'] = $statuses;
		
		$this->data['text_no_results'] = $this->language->get('No Results');
		
		$pagination = new \Pagination();
		$pagination->total = $tasks_total;
		$pagination->page  = $params['page'];
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text  = $this->language->get('text_pagination');
		$pagination->url   = $this->url->link('extension/ka_extensions/ka_scheduler/ka_tasks', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);

		$this->data['pagination'] = $pagination->render();
		
		$this->data['params'] = $params;
		
		$this->template = 'extension/ka_extensions/ka_scheduler/ka_tasks_list';
		$this->children = array(
			'common/header',
			'common/column_left',			
			'common/footer'
		);
		
		$this->setOutput();
  	}
  	
  	
  	protected function getForm() {

		$step = 1;
  	    
		$url = '';
		if (isset($this->session->data['ka_tasks_url'])) {
			$url = $this->session->data['ka_tasks_url'];
		}

	
		if (isset($this->request->get['task_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$task = $this->kamodel_ka_tasks->getTask($this->request->get['task_id']);
			
			$task['start_at'] = preg_replace("/(.*)(:\d\d)$/", "$1", $task['start_at']);
			if ($task['end_at'] == '0000-00-00 00:00:00') {
			} else {
				$task['end_at'] = preg_replace("/(.*)(:\d\d)$/", "$1", $task['end_at']);
			}
			
		} elseif ($this->request->server['REQUEST_METHOD'] == 'POST') {
		
			$task = $this->request->post;
			
		} else {
			$task = array(
				'name'       => '',
				'module'     => '',
				'operation'  => '',
				'priority' => '',
				'is_send_email' => 1,
				'period_type' => 'day',
				'every_n_minutes' => 0,
				'period_at_min' => 0,
				'period_at_hour' => 0,
				'period_at_day' => 0,
				'period_at_dow' => 0,
				'period_at_month' => 0,
				'active' => 'Y',
				
				'start_at' => date("Y-m-d 00:00"),
				'end_at' => date("Y-m-d 23:59"),
			);
		}
		
		if (empty($task['active'])) {
			$task['active'] = 'N';
		}
		
		if ($task['end_at'] == '0000-00-00 00:00:00') {
			$data['is_end_at'] = true;
		} else {
			$data['is_end_at'] = false;
		}
		
		$this->data['task'] = $task;
		
		$this->data['modules'] = $this->kamodel_ka_tasks->getSchedulerModules();
		
		$this->data['operations'] = array();
		if (!empty($task['module'])) {
			$this->data['operations'] = $this->kamodel_ka_tasks->getSchedulerOperations($task['module']);
			$step = 2;
		}
		
		$this->data['op_params'] = array();
		if (!empty($task['operation']) || ($step == 2 && empty($this->data['operations']))) {
			$this->data['op_params'] = $this->kamodel_ka_tasks->getOperationParams($task['module'], $task['operation']);
			$step = 3;
		}
		
		$this->data['period_types'] = $this->kamodel_ka_tasks->getPeriodTypes();
		
		$this->data['minutes'] = $this->kamodel_ka_tasks->getPeriodMinutes();
		$this->data['hours']   = $this->kamodel_ka_tasks->getPeriodHours();
		$this->data['days']    = $this->kamodel_ka_tasks->getPeriodDays();
		$this->data['dows']    = $this->kamodel_ka_tasks->getPeriodDows();
		$this->data['months']  = $this->kamodel_ka_tasks->getPeriodMonths();

		$this->data['is_end_at'] = (strtotime($task['end_at']) > 0);
		$this->data['is_send_email'] = (isset($task['is_send_email']) && $task['is_send_email'] > 0);
		
		$this->data['step'] = $step;
		
  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'user_token=' . $this->session->data['user_token'], true),    		
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('Tasks'),
			'href'      => $this->url->link('extension/ka_extensions/ka_scheduler/ka_tasks', 'user_token=' . $this->session->data['user_token'] . $url, true),
      		'separator' => ' :: '
   		);
   		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $task['name'],
   		);
   				
		$this->data['action'] = $this->url->link('extension/ka_extensions/ka_scheduler/ka_tasks/save', 'user_token=' . $this->session->data['user_token'] . $url, true);
		$this->data['cancel'] = $this->url->link('extension/ka_extensions/ka_scheduler/ka_tasks', 'user_token=' . $this->session->data['user_token'] . $url, true);

		$this->data['languages'] = $this->model_localisation_language->getLanguages();

		$this->template = 'extension/ka_extensions/ka_scheduler/ka_task_form';
		$this->children = array(
			'common/header',
			'common/column_left',			
			'common/footer'
		);
		
		$this->setOutput();
  	}


	protected function validateForm() {
	
    	if (!$this->user->hasPermission('modify', 'extension/ka_extensions/ka_scheduler/ka_tasks')) {
      		$this->addTopMessage('Permission error');
      		return false;
    	}
	
   		if (empty($this->request->post['name'])) {
       		$this->addTopMessage('Name is empty', 'E');
       		return false;
    	}
    	
    	if (utf8_strlen($this->request->post['name']) < 3) {    		
    		$this->addTopMessage('Name should be not less than 3 characters', 'E');
    		return false;
    	}

    	if (empty($this->request->post['module'])) {
    		$this->addTopMessage('Module is not selected', 'E');
    		return false;
    	}
    	
    	$operations = $this->kamodel_ka_tasks->getSchedulerOperations($this->request->post['module']);
    	
    	if (empty($this->request->post['operation'])) {
	    	$operation = '';
	    } else {
    		$operation = $this->request->post['operation'];
    	}
    	
    	if (!empty($operations) && empty($operation)) {
    		if ($this->request->post['step'] > 1) {
	    		$this->addTopMessage('Operation is not selected', 'E');
	    	}
    		return false;
    	}
    	
    	$params = $this->kamodel_ka_tasks->getOperationParams($this->request->post['module'], $operation);
    	
    	if (!empty($params)) {
    		foreach ($params as $k => $param) {
    			if (!empty($param['required']) && empty($this->request->post['params'][$k])) 
    			{
    				if ($this->request->post['step'] > 2) {
	    				$this->addTopMessage('Required operation parameters are empty', 'E');
	    			}
    				return false;
    			}
    		}
    	}

    	return true;
	}
  	  
  	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'extension/ka_extensions/ka_scheduler/ka_tasks')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}
		
		if (!$this->error) { 
	  		return true;
		} else {
	  		return false;
		}
  	}

  	  	
  	protected function validateUpdateList() {
		if (!$this->user->hasPermission('modify', 'extension/ka_extensions/ka_scheduler/ka_tasks')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}
		
		if (!$this->error) { 
	  		return true;
		} else {
	  		return false;
		}
  	}
  	
  	
  	protected function getTaskStat() {

		$url = '';
		if (isset($this->session->data['ka_tasks_url'])) {
			$url = $this->session->data['ka_tasks_url'];
		}
	
		if (isset($this->request->get['task_id'])) {
			$task = $this->kamodel_ka_tasks->getTask($this->request->get['task_id']);
			
		} else {
			$this->response->redirect($this->url->link('extension/ka_extensions/ka_scheduler/ka_tasks', 'user_token=' . $this->session->data['user_token'] . $url, true));		
		}
		
		$this->data['task'] = $task;
		
  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'user_token=' . $this->session->data['user_token'], true),    		
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('Tasks'),
			'href'      => $this->url->link('extension/ka_extensions/ka_scheduler/ka_tasks', 'user_token=' . $this->session->data['user_token'] . $url, true),
      		'separator' => ' :: '
   		);

		$this->data['cancel'] = $this->url->link('extension/ka_extensions/ka_scheduler/ka_tasks', 'user_token=' . $this->session->data['user_token'] . $url, true);

		$this->data['languages'] = $this->model_localisation_language->getLanguages();

		$this->template = 'extension/ka_extensions/ka_scheduler/ka_task_stat';
		$this->children = array(
			'common/header',
			'common/column_left',			
			'common/footer'
		);
		
		$this->setOutput();
  	}
  	
}

class_alias(__NAMESPACE__ . '\ControllerKaTasks', 'ControllerExtensionKaExtensionsKaSchedulerKaTasks');