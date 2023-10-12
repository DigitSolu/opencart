<?php
class ControllerExtensionModuleNdTidioChat extends Controller {
  public function addScript() {
    if ($this->config->get('module_nd_tidio_chat_status') && !empty($this->config->get('module_nd_tidio_chat_script_code'))) {
      $this->document->addScript(htmlspecialchars_decode($this->config->get('module_nd_tidio_chat_script_code')), 'footer');
    }
  }
}
