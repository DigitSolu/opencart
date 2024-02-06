<?php
class ControllerExtensionModuleCustomerExport extends Controller {
    private $error = array();

    public function index() {
        $this->load->language('extension/module/customer_export');

        $this->document->setTitle($this->language->get('heading_title'));

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['export_format'] = $this->language->get('export_format');
        $data['export_btn_text'] = $this->language->get('export_btn_text');
        $data['separator_text'] = $this->language->get('separator_text');


        $this->validate();

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('extension/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/module/customer_export', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['action'] = $this->url->link('extension/module/customer_export', 'user_token=' . $this->session->data['user_token'], true);

        $data['cancel'] = $this->url->link('extension/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $data['iframe_url'] = html_entity_decode($this->url->link('extension/module/customer_export/export', 'user_token=' . $this->session->data['user_token'], true));

        $this->response->setOutput($this->load->view('extension/module/customer_export', $data));
    }

    public function export()
    {
        if(isset($_GET['format'])){
            $this->load->model("extension/module/customer_export");

            $format = ($_GET['format'] == "csv") ? 'csv' : "txt";
            $BOM = ($_GET['format'] == "csv") ? chr(0xEF) . chr(0xBB) . chr(0xBF) : null;
            $separator =";";

            if($_GET['format'] == "txt" && isset($_GET['separator']) && !empty($_GET['separator'])) $separator = $_GET['separator'];

            $content = "";
            $content .= $BOM."Customer id".$separator."Firstname".$separator."Lastname".$separator."Email".$separator."Telephone".$separator."Address_1".$separator."Address_2".$separator."City".$separator."Postcode".$separator."is_registered".PHP_EOL;

            $registered_customers = $this->model_extension_module_customer_export->getAllRegisteredCustomers();

            foreach ($registered_customers as $customer){
                $content .= $customer['customer_id'].$separator.$customer['firstname'].$separator.$customer['lastname'].$separator.$customer['email'].$separator.(string)$customer['telephone'].$separator.$customer['address_1'].$separator.$customer['address_2'].$separator.$customer['city'].$separator.$customer['postcode'].$separator."1".PHP_EOL;
            }

            $unregistered_customers = $this->model_extension_module_customer_export->getUnregisteredCustomers();

            foreach ($unregistered_customers as $customer){
                $content .= $customer['customer_id'].$separator.$customer['firstname'].$separator.$customer['lastname'].$separator.$customer['email'].$separator.(string)$customer['telephone'].$separator.$customer['shipping_address_1'].$separator.$customer['shipping_address_1'].$separator.$customer['shipping_city'].$separator.$customer['shipping_postcode'].$separator."0".PHP_EOL;
            }


            $this->file_download($content, $format);

        }

    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'extension/module/customer_export')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    protected function file_download($content, $format) {
            // заставляем браузер показать окно сохранения файла
            header('Content-Type: text/html; charset=utf-8');
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=Export.'.$format);
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . strlen($content));
            // читаем файл и отправляем его пользователю
            echo $content;
            exit;
        }

}