<?php
/*
	$Project$
	$Author$

	$Version$ ($Revision$)

	This is a controller class for a basic module settings page.
*/
namespace extension\ka_extensions;

abstract class ControllerSettings extends ControllerInstaller {

	use TraitControllerForm;

	protected function onLoad() {	

		$this->load->language('extension/ka_extensions/' . $this->ext_code);

		return parent::onLoad();
	}

	
	public function index() {

		$heading_title = $this->getTitle();
		$this->document->setTitle($heading_title);

		$fields = $this->getFields();
		
		// get original field values
		$old_data = array();
		foreach ($fields as $k => $v) {
			$old_data[$v['code']] = $this->config->get($v['code']);
		}		
		
		// handle autoinstall actions
		//
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$fields = $this->getFieldsWithData($fields, $old_data, $this->request->post);
			$values = $this->getFieldValues($fields);
			$this->model_setting_setting->editSetting($this->ext_code, $values);
			$this->response->redirect($this->url->link('marketplace/extension', 'type=ka_extensions&user_token=' . $this->session->data['user_token'], true));
			
		} elseif ($this->request->server['REQUEST_METHOD'] == 'POST') {
			$fields = $this->getFieldsWithData($fields, $old_data, $this->request->post, $this->errors);
		} else {
			$fields = $this->getFieldsWithData($fields, $old_data);
		}
		
		$this->data['fields'] = $fields;
		
		$this->data['heading_title']   = $heading_title;

		$this->data['extension_version'] = $this->extension_version;
		
		$this->data['breadcrumbs'] = array();
   		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true),
			'separator' => false
		);

  		$this->data['breadcrumbs'][] = array(
	 		'text'      => $this->language->get('Ka Extensions'),
			'href'      => $this->url->link('marketplace/extension', 'type=ka_extensions&user_token=' . $this->session->data['user_token'], true),
 		);
		
 		$this->data['breadcrumbs'][] = array(
	 		'text'      => $heading_title,
			'href'      => $this->url->link('extension/ka_extensions/' . $this->ext_code, 'type=ka_extensions&user_token=' . $this->session->data['user_token'], true),
 		);
		
		$this->data['action_save'] = $this->url->link('extension/ka_extensions/' . $this->ext_code, 'user_token=' . $this->session->data['user_token'], true);
		$this->data['action_cancel'] = $this->url->link('marketplace/extension', 'type=ka_extensions&user_token=' . $this->session->data['user_token'], true);

		$this->showPage('extension/ka_extensions/common/pages/settings');
	}

		
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/ka_extensions/' . $this->ext_code)) {
			$this->addTopMessage($this->language->get('error_permission'), 'E');
			return false;
		}

		$fields = $this->getFields();
		if (!$this->validateFields($fields, $this->request->post)) {
			return false;
		}

		return true;
	}
	

	public function install() {

		if (!parent::install()) {
			return false;
		} 

		$fields = $this->getFields();
		
		$default = array();
		foreach ($fields as $k => $v) {
			if (isset($v['default_value'])) {
				$default[$v['code']] = $v['default_value'];
			}
		}
		
		$settings = $this->model_setting_setting->getSetting($this->ext_code);
		
		$settings = array_merge($default, $settings);
		
		$this->model_setting_setting->editSetting($this->ext_code, $settings);
		
		return true;
 	}

		
	public function uninstall() {
		$this->model_setting_setting->deleteSetting($this->ext_code);
		return parent::uninstall();
	}
}