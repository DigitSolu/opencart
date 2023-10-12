<?php
class ControllerExtensionModuleNdTidioChat extends Controller {
  private $error = [];

  public function index() {
    $this->load->language('extension/module/nd_tidio_chat');

    if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
      $this->load->model('setting/setting');

      $this->model_setting_setting->editSetting('module_nd_tidio_chat', $this->request->post);

      $this->session->data['success'] = $this->language->get('text_success');

      $this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
    }

    $this->document->setTitle($this->language->get('heading_title'));

    $data['breadcrumbs'] = [];

    $data['breadcrumbs'][] = [
      'text' => $this->language->get('text_home'),
      'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
    ];

    $data['breadcrumbs'][] = [
      'text' => $this->language->get('text_extension'),
      'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module')
    ];

    $data['breadcrumbs'][] = [
      'text' => $this->language->get('heading_title'),
      'href' => $this->url->link('extension/module/nd_tidio_chat', 'user_token=' . $this->session->data['user_token'])
    ];

    $data['save'] = $this->url->link('extension/module/nd_tidio_chat', 'user_token=' . $this->session->data['user_token']);
    $data['back'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module');

    $data['tidio_chat_status'] = $this->config->get('module_nd_tidio_chat_status');
    $data['tidio_chat_script_code'] = $this->config->get('module_nd_tidio_chat_script_code');

    if (!empty($this->config->get('module_nd_tidio_chat_script_code'))) {
      $data['tidio_chat_script_code'] = "&lt;script src=&quot;" . $this->config->get('module_nd_tidio_chat_script_code') . "&quot;&gt;&lt/script&gt;";
    }

    $data['header'] = $this->load->controller('common/header');
    $data['column_left'] = $this->load->controller('common/column_left');
    $data['footer'] = $this->load->controller('common/footer');

    $this->response->setOutput($this->load->view('extension/module/nd_tidio_chat', $data));
  }

  protected function validate() {
    if (!$this->user->hasPermission('modify', 'extension/module/nd_tidio_chat')) {
      $this->error['warning'] = $this->language->get('error_permission');
    }

    if (!$this->error) {
      // Get only src from Script Code
      if (mb_strrpos($this->request->post['module_nd_tidio_chat_script_code'], '&quot;')) {
        $script_code = $this->request->post['module_nd_tidio_chat_script_code'];
        $end_key = mb_strrpos($script_code, '&quot;');
        $start_key = mb_strpos($script_code, '&quot;');
        $final = mb_substr($script_code, $start_key + 6, $end_key - $start_key - 6);
        $this->request->post['module_nd_tidio_chat_script_code'] = $final;
      }
    }

    return !$this->error;
  }

  public function install() {
    $events[] = [
      'code' => 'catalog_footer_tidio_chat',
      'trigger' => 'catalog/controller/common/footer/before',
      'action' => 'extension/module/nd_tidio_chat/addScript',
      'status' => true,
      'sort_order' => 1
    ];

    $this->load->model('setting/event');

    foreach ($events as $event) {
      $this->model_setting_event->addEvent($event['code'], $event['trigger'], $event['action']);
    }

    // Add extension permissions
    $this->load->model('user/user_group');
    $this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/module/nd_tidio_chat');
    $this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/module/nd_tidio_chat');
  }

  public function uninstall() {
    $this->load->model('setting/event');
    $this->model_setting_event->deleteEventByCode('admin_column_left_tidio_chat');
    $this->model_setting_event->deleteEventByCode('catalog_footer_tidio_chat');

    // Remove extension permissions
    $this->load->model('user/user_group');
    $this->model_user_user_group->removePermission($this->user->getGroupId(), 'access', 'extension/module/nd_tidio_chat');
    $this->model_user_user_group->removePermission($this->user->getGroupId(), 'modify', 'extension/module/nd_tidio_chat');
  }
}
