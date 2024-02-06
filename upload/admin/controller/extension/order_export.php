<?php
// Lib Include 
require_once(DIR_SYSTEM.'/library/tmd/system.php');
require_once(DIR_SYSTEM.'/library/tmd/Psr/autoloader.php');
require_once(DIR_SYSTEM.'/library/tmd/myclabs/Enum.php');
require_once(DIR_SYSTEM.'/library/tmd/ZipStream/autoloader.php');
require_once(DIR_SYSTEM.'/library/tmd/ZipStream/ZipStream.php');
require_once(DIR_SYSTEM.'/library/tmd/PhpSpreadsheet/autoloader.php');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
// Lib Include 

class ControllerExtensionOrderExport extends Controller {
	private $error = array();
	
	public function index() {
		$this->load->language('extension/order_export');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('extension/order_export');
		$this->load->model('sale/order');
		$this->getList();
	}
	
    public function delete() {
        $json = array();
        $this->load->model('extension/order_export');
        $this->load->language('extension/order_export');
        if (!empty($this->request->get['rule_id'])) {
            $this->model_extension_order_export->deleteRule($this->request->get['rule_id']);
            $json['success']  = $this->language->get('text_succdelete');
            $json['redirect'] = $this->url->link('extension/order_export&user_token=' . $this->session->data['user_token'],'',true);
        }                   
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

	protected function getList() {
		$this->registry->set('tmd', new TMD($this->registry));
		$keydata=array(
		'code'=>'tmdkey_orderexport',
		'eid'=>'MjE2NDY=',
		'route'=>'extension/order_export',
		);
		$orderexport=$this->tmd->getkey($keydata['code']);
		$data['getkeyform']=$this->tmd->loadkeyform($keydata);
		
    	$this->load->language('extension/order_export');
		if(isset($this->request->get['rule_id'])) {
			$rule_info = $this->model_extension_order_export->getRule($this->request->get['rule_id']);
		}

		if(isset($rule_info['rule_value'])) {
			$value_info = json_decode($rule_info['rule_value'],true);
		} else {
			$value_info = '';
		}

		if(isset($value_info)){
			$data['orderexport']=$value_info;
		}

		if(isset($value_info['exportfilename'])) {
			$data['filename']=$value_info['exportfilename'];
		} else {
			$data['filename']='';	
		}
		
		if (isset($this->request->get['filter_order_id'])) {
			$filter_order_id = $this->request->get['filter_order_id'];
		} else if (isset($value_info['filter_order_id'])) {
			$filter_order_id = $value_info['filter_order_id'];
		} else {
			$filter_order_id = null;
		}

		if (isset($this->request->get['filter_customer'])) {
			$filter_customer = $this->request->get['filter_customer'];
		}else if (isset($value_info['filter_customer'])) {
			$filter_customer = $value_info['filter_customer'];
		}else {
			$filter_customer = '';
		}

		if (!empty($this->request->get['filter_order_status'])) {
			$this->request->get['filter_order_status'] = explode('%2C', $this->request->get['filter_order_status']);
		}

		if (isset($this->request->get['filter_order_status'])) {
			$filter_order_status = $this->request->get['filter_order_status'];
		}else if (isset($value_info['filter_order_status'])) {
			$filter_order_status = $value_info['filter_order_status'];
		}else {
			$filter_order_status = array();
		}

		if (isset($this->request->get['filter_total'])) {
			$filter_total = $this->request->get['filter_total'];
		}else if (isset($value_info['filter_total'])) {
			$filter_total = $value_info['filter_total'];
		}else {
			$filter_total = null;
		}

		if (isset($this->request->get['filter_date_added'])) {
			$filter_date_added = $this->request->get['filter_date_added'];
		}else if (isset($value_info['filter_date_added'])) {
			$filter_date_added = $value_info['filter_date_added'];
		}else {
			$filter_date_added = null;
		}

		if (isset($this->request->get['filter_date_modified'])) {
			$filter_date_modified = $this->request->get['filter_date_modified'];
		}else if (isset($value_info['filter_date_modified'])) {
			$filter_date_modified = $value_info['filter_date_modified'];
		}else {
			$filter_date_modified = null;
		}

		if (isset($this->request->get['filter_startdate'])) {
			$filter_startdate = $this->request->get['filter_startdate'];
		}else if (isset($value_info['filter_startdate'])) {
			$filter_startdate = $value_info['filter_startdate'];
		}else {
			$filter_startdate = null;
		}

		if (isset($this->request->get['filter_enddate'])) {
			$filter_enddate = $this->request->get['filter_enddate'];
		}else if (isset($value_info['filter_enddate'])) {
			$filter_enddate = $value_info['filter_enddate'];
		} else {
			$filter_enddate = null;
		}
		
		if (isset($this->request->get['filter_customer_group_id'])) {
			$filter_customer_group_id = $this->request->get['filter_customer_group_id'];
		}else if (isset($value_info['filter_customer_group_id'])) {
			$filter_customer_group_id = $value_info['filter_customer_group_id'];
		} else {
			$filter_customer_group_id = null;
		}
		
		if (isset($this->request->get['filter_store_id'])) {
			$filter_store_id = $this->request->get['filter_store_id'];
		}else if (isset($value_info['filter_store_id'])) {
			$filter_store_id = $value_info['filter_store_id'];
		} else {
			$filter_store_id = null;
		}

		if (isset($this->request->get['filter_product'])) {
			$filter_product = $this->request->get['filter_product'];
		}else if (isset($value_info['filter_product'])) {
			$filter_product = $value_info['filter_product'];
		} else {
			$filter_product = null;
		}

		if (isset($this->request->get['filter_category'])) {
			$filter_category = $this->request->get['filter_category'];
		}else if (isset($value_info['filter_category'])) {
			$filter_category = $value_info['filter_category'];
		} else {
			$filter_category = null;
		}

		if (isset($this->request->get['filter_category1'])) {
			$filter_category1 = $this->request->get['filter_category1'];
		}else if (isset($value_info['filter_category1'])) {
			$filter_category1 = $value_info['filter_category1'];
		} else {
			$filter_category1 = null;
		}
		
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'o.order_id';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url='';

		if (isset($this->request->get['rule_id'])) {
			$url .= '&rule_id=' . $this->request->get['rule_id'];
		}

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}
	
		if (isset($this->request->post['filter_total'])) {
			$url .= '&filter_total=' . $this->request->post['filter_total'];
		}

		if (isset($this->request->post['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->post['filter_date_added'];
		}

		if (isset($this->request->post['filter_date_modified'])) {
			$url .= '&filter_date_modified=' . $this->request->post['filter_date_modified'];
		}

		if (isset($this->request->post['filter_startdate'])) {
			$url .= '&filter_startdate=' . $this->request->post['filter_startdate'];
		}

		if (isset($this->request->post['filter_enddate'])) {
			$url .= '&filter_enddate=' . $this->request->post['filter_enddate'];
		}
		
		if (isset($this->request->post['filter_customer_group_id'])) {
			$url .= '&filter_customer_group_id=' . $this->request->post['filter_customer_group_id'];
		}
		
		if (isset($this->request->post['filter_store_id'])) {
			$url .= '&filter_store_id=' . $this->request->post['filter_store_id'];
		}

		if (isset($this->request->post['filter_category'])) {
			$url .= '&filter_category=' . $this->request->post['filter_category'];
		}

		if (isset($this->request->post['filter_category1'])) {
			$url .= '&filter_category1=' . $this->request->post['filter_category1'];
		}

		if (isset($this->request->post['filter_product'])) {
			$url .= '&filter_product=' . $this->request->post['filter_product'];
		}
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/order_export', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);

		$data['formates'] = array();

		$data['formates'][] = array(
			'text' 	=> 'xls',
			'value' => 'xls'
		);
		$data['formates'][] = array(
			'text' 	=> 'xlsx',
			'value' => 'xlsx'
		);
		$data['formates'][] = array(
			'text' 	=> 'csv',
			'value' => 'csv'
		);

		if(isset($this->request->get['rule_id'])) {
			$data['export_action'] = $this->url->link('extension/order_export/export&rule_id='.$this->request->get['rule_id'].'&user_token=' . $this->session->data['user_token'] . $url);
			$data['delete'] = $this->url->link('extension/order_export/delete&rule_id='.$this->request->get['rule_id'].'&user_token=' . $this->session->data['user_token'] . $url);
	  	} else {
			$data['export_action'] = $this->url->link('extension/order_export/export', 'user_token=' . $this->session->data['user_token'] . $url , true);
		}

		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_fileformat'] = $this->language->get('text_fileformat');
		$data['text_export_feilds'] = $this->language->get('text_export_feilds');
		$data['text_order_export'] = $this->language->get('text_order_export');
		$data['text_product_export'] = $this->language->get('text_product_export');
		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_missing'] = $this->language->get('text_missing');
		$data['text_select_all'] = $this->language->get('text_select_all');
		$data['text_product_id'] = $this->language->get('text_product_id');
		$data['text_product_url'] = $this->language->get('text_product_url');
		$data['text_product_imageurl'] = $this->language->get('text_product_imageurl');
		$data['text_product_manufacturer'] = $this->language->get('text_product_manufacturer');

		$data['column_order_id'] = $this->language->get('column_order_id');
		$data['column_customer'] = $this->language->get('column_customer');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_total'] = $this->language->get('column_total');
		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_date_modified'] = $this->language->get('column_date_modified');
		$data['column_action'] = $this->language->get('column_action');

		$data['entry_return_id'] = $this->language->get('entry_return_id');
		$data['entry_order_id'] = $this->language->get('entry_order_id');
		$data['entry_customer'] = $this->language->get('entry_customer');
		$data['entry_order_status'] = $this->language->get('entry_order_status');
		$data['entry_total'] = $this->language->get('entry_total');
		$data['entry_date_added'] = $this->language->get('entry_date_added');
		$data['entry_date_modified'] = $this->language->get('entry_date_modified');
		$data['entry_enddate'] = $this->language->get('entry_enddate');
		$data['entry_startdate'] = $this->language->get('entry_startdate');

		$data['button_invoice_print'] = $this->language->get('button_invoice_print');
		$data['button_shipping_print'] = $this->language->get('button_shipping_print');
		$data['button_insert'] = $this->language->get('button_insert');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_filter'] = $this->language->get('button_filter');
		$data['button_view'] = $this->language->get('button_view');
        $data['button_export'] = $this->language->get('button_export');

		$this->load->model('customer/customer_group');
		$data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();
		$data['customer_group_id'] = $this->language->get('entry_customer_group_id');

		$data['entry_store'] = $this->language->get('entry_store');
		$data['export_subtotal'] = $this->language->get('export_subtotal');
		$data['export_shippingcost'] = $this->language->get('export_shippingcost');

		$data['export_couponcost'] = $this->language->get('export_couponcost');

		$this->load->model('setting/store');
		$data['stores_info'] = array();
		$stores_infos = $this->model_setting_store->getStores();
		foreach ($stores_infos as $result) {
			$data['stores_info'][] = array(
				'store_id' => $result['store_id'],
				'name'     => $result['name'],

			);
		}
		$data['text_default'] = $this->language->get('text_default');

        $data['export_order_id'] = $this->language->get('export_order_id');
        $data['export_customer'] = $this->language->get('export_customer');
        $data['export_customer_email'] = $this->language->get('export_customer_email');
        $data['export_invoice_no'] = $this->language->get('export_invoice_no');
        $data['export_invoice_prefix'] = $this->language->get('export_invoice_prefix');
        $data['export_telephone'] = $this->language->get('export_telephone');
        $data['export_fax'] = $this->language->get('export_fax');
        $data['export_payment_firstname'] = $this->language->get('export_payment_firstname');
        $data['export_payment_lastname'] = $this->language->get('export_payment_lastname');
        $data['export_payment_company'] = $this->language->get('export_payment_company');
        $data['export_payment_address_1'] = $this->language->get('export_payment_address_1');
        $data['export_payment_address_2'] = $this->language->get('export_payment_address_2');
        $data['export_payment_postcode'] = $this->language->get('export_payment_postcode');
        $data['export_payment_city'] = $this->language->get('export_payment_city');
        $data['export_payment_zone'] = $this->language->get('export_payment_zone');
        $data['export_payment_zone_code'] = $this->language->get('export_payment_zone_code');
        $data['export_payment_country'] = $this->language->get('export_payment_country');
        $data['export_payment_iso_code_2'] = $this->language->get('export_payment_iso_code_2');
        $data['export_payment_iso_code_3'] = $this->language->get('export_payment_iso_code_3');
        $data['export_payment_address_format'] = $this->language->get('export_payment_address_format');
        $data['export_payment_custom_field'] = $this->language->get('export_payment_custom_field');
        $data['export_payment_method'] = $this->language->get('export_payment_method');
        $data['export_payment_code'] = $this->language->get('export_payment_code');
        $data['export_shipping_firstname'] = $this->language->get('export_shipping_firstname');
        $data['export_shipping_lastname'] = $this->language->get('export_shipping_lastname');
        $data['export_shipping_company'] = $this->language->get('export_shipping_company');
        $data['export_shipping_address_1'] = $this->language->get('export_shipping_address_1');
        $data['export_shipping_address_2'] = $this->language->get('export_shipping_address_2');
        $data['export_shipping_postcode'] = $this->language->get('export_shipping_postcode');
        $data['export_shipping_city'] = $this->language->get('export_shipping_city');
        $data['export_shipping_zone'] = $this->language->get('export_shipping_zone');
        $data['export_shipping_zone_code'] = $this->language->get('export_shipping_zone_code');
        $data['export_shipping_country'] = $this->language->get('export_shipping_country');
        $data['export_shipping_iso_code_2'] = $this->language->get('export_shipping_iso_code_2');
        $data['export_shipping_iso_code_3'] = $this->language->get('export_shipping_iso_code_3');
        $data['export_shipping_address_format'] = $this->language->get('export_shipping_address_format');
        $data['export_shipping_method'] = $this->language->get('export_shipping_method');
        $data['export_shipping_code'] = $this->language->get('export_shipping_code');
        $data['export_comment'] = $this->language->get('export_comment');
        $data['export_reward'] = $this->language->get('export_reward');
        $data['export_status'] = $this->language->get('export_status');
        $data['export_affiliate_firstname'] = $this->language->get('export_affiliate_firstname');
        $data['export_affiliate_lastname'] = $this->language->get('export_affiliate_lastname');
        $data['export_commission'] = $this->language->get('export_commission');
        $data['export_total'] = $this->language->get('export_total');
        $data['export_date_added'] = $this->language->get('export_date_added');
        $data['export_email'] = $this->language->get('export_email');
        $data['export_coupon_code'] = $this->language->get('export_coupon_code');
        $data['export_voucher_code'] = $this->language->get('export_voucher_code');
        $data['export_store_credit'] = $this->language->get('export_store_credit');
        
        $data['export_weight_total'] = $this->language->get('export_weight_total');
		$data['export_storetype'] = $this->language->get('export_storetype');

        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');
        $data['text_product_export_feilds'] = $this->language->get('text_product_export_feilds');
		$data['text_product_name'] = $this->language->get('text_product_name');
		$data['text_model'] = $this->language->get('text_model');

		$data['text_image'] = $this->language->get('text_image');
		$data['text_sku'] = $this->language->get('text_sku');
		$data['text_upc'] = $this->language->get('text_upc');
		$data['text_ean'] = $this->language->get('text_ean');
		$data['text_jan'] = $this->language->get('text_jan');
		$data['text_isbn'] = $this->language->get('text_isbn');
		$data['text_mpn'] = $this->language->get('text_mpn');

		$data['text_quantity'] = $this->language->get('text_quantity');
		$data['text_price'] = $this->language->get('text_price');
		$data['text_total'] = $this->language->get('text_total');
		$data['text_tax'] = $this->language->get('text_tax');
		$data['text_reward'] = $this->language->get('text_reward');
		$data['text_option'] = $this->language->get('text_option');
		$data['text_product_quantity'] = $this->language->get('text_product_quantity');
		$data['user_token'] = $this->session->data['user_token'];

		if (isset($this->session->data['warning'])) {
			$data['error_warning'] = $this->session->data['warning'];
		
			unset($this->session->data['warning']);
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$data['filter_order_id'] = $filter_order_id;
		$data['filter_customer'] = $filter_customer;
		$data['filter_order_status'] = $filter_order_status;
		$data['filter_total'] = $filter_total;
		$data['filter_date_added'] = $filter_date_added;
		$data['filter_date_modified'] = $filter_date_modified;
		$data['filter_enddate']       = $filter_enddate;
		$data['filter_startdate']     = $filter_startdate;
		$data['filter_customer_group_id']     = $filter_customer_group_id;
		$data['filter_store_id']   = $filter_store_id;
		$data['filter_product']    = $filter_product;
		$data['filter_category']    = $filter_category;
		$data['filter_category1']    = $filter_category1;
		
		$this->load->model('localisation/order_status');
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		$data['sort'] = $sort;
		$data['order'] = $order;

		$this->load->model('customer/customer');
		if(isset($data['filter_customer'])) {
			$customer_info = $this->model_customer_customer->getCustomer($data['filter_customer']);
		}

		$data['reset'] 	= $this->url->link('extension/order_export&user_token=' . $this->session->data['user_token']);

		$this->load->model('extension/order_export');
		$data['rules'] = $this->model_extension_order_export->getRules($data);

		if(isset($this->request->get['rule_id'])) {
			$data['rule_id'] = $this->request->get['rule_id'];
		} else {
			$data['rule_id'] = 0;
		}

		if(isset($this->request->post['rule_type'])) {
			$data['rule_type'] = $this->request->post['rule_type'];
		} else if(!empty($rule_info)) {
			$data['rule_type'] = $rule_info['rule_type'];
		} else {
			$data['rule_type'] = 0;
		}

		if(isset($this->request->post['rule_name'])) {
			$data['rule_name'] = $this->request->post['rule_name'];
		} else if(!empty($rule_info)) {
			$data['rule_name'] = $rule_info['rule_name'];
		} else {
			$data['rule_name'] = '';
		}
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/order_export', $data));
	}

	public function export() {
		if(isset($this->request->post)) {
			$this->load->model('extension/order_export');
			$this->model_extension_order_export->AddTmdExport($this->request->post);
		}
		
		$url = '';

		if (isset($this->request->get['rule_id'])) {
			$url .= '&rule_id=' . $this->request->get['rule_id'];
		}

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}
	
		if (isset($this->request->post['filter_total'])) {
			$url .= '&filter_total=' . $this->request->post['filter_total'];
		}

		if (isset($this->request->post['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->post['filter_date_added'];
		}

		if (isset($this->request->post['filter_date_modified'])) {
			$url .= '&filter_date_modified=' . $this->request->post['filter_date_modified'];
		}

		if (isset($this->request->post['filter_startdate'])) {
			$url .= '&filter_startdate=' . $this->request->post['filter_startdate'];
		}

		if (isset($this->request->post['filter_enddate'])) {
			$url .= '&filter_enddate=' . $this->request->post['filter_enddate'];
		}
		
		if (isset($this->request->post['filter_customer_group_id'])) {
			$url .= '&filter_customer_group_id=' . $this->request->post['filter_customer_group_id'];
		}
		
		if (isset($this->request->post['filter_store_id'])) {
			$url .= '&filter_store_id=' . $this->request->post['filter_store_id'];
		}

		if (isset($this->request->post['filter_category'])) {
			$url .= '&filter_category=' . $this->request->post['filter_category'];
		}

		if (isset($this->request->post['filter_category1'])) {
			$url .= '&filter_category1=' . $this->request->post['filter_category1'];
		}

		if (isset($this->request->post['filter_product'])) {
			$url .= '&filter_product=' . $this->request->post['filter_product'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$orderexport=$this->config->get('tmdkey_orderexport');
		if (empty(trim($orderexport))) {			
		$this->session->data['warning'] ='Module will Work after add License key!';
		$this->response->redirect($this->url->link('extension/order_export', 'user_token=' . $this->session->data['user_token'], true));
		}

		if(empty($this->request->post['order'])) {
			$this->session->data['warning'] = 'Request Error: Order ID must be selected';
			$this->response->redirect($this->url->link('extension/order_export', 'user_token=' . $this->session->data['user_token'], true));
		} else {
			if(isset($this->request->post['order']) && $this->request->post['order']) {
				$this->load->language('extension/order_export');

				if (isset($this->request->post['filter_order_id'])) {
						$filter_order_id = $this->request->post['filter_order_id'];
				} else {
						$filter_order_id = null;
				}

				if (isset($this->request->post['filter_customer'])) {
						$filter_customer = $this->request->post['filter_customer'];
				} else {
						$filter_customer = null;
				}

				if (isset($this->request->post['filter_order_status'])) {
						$filter_order_status = implode(',', $this->request->post['filter_order_status']);
				} else {
						$filter_order_status = null;
				}

				if (isset($this->request->post['filter_total'])) {
						$filter_total = $this->request->post['filter_total'];
				} else {
						$filter_total = null;
				}

				if (isset($this->request->post['filter_date_added'])) {
						$filter_date_added = $this->request->post['filter_date_added'];
				} else {
						$filter_date_added = null;
				}

				if (isset($this->request->post['filter_date_modified'])) {
						$filter_date_modified = $this->request->post['filter_date_modified'];
				} else {
						$filter_date_modified = null;
				}
				if (isset($this->request->post['filter_startdate'])) {
						$filter_startdate = $this->request->post['filter_startdate'];
				} else {
						$filter_startdate = null;
				}
				if (isset($this->request->post['filter_enddate'])) {
						$filter_enddate = $this->request->post['filter_enddate'];
				} else {
						$filter_enddate = null;
				}
				
				if (isset($this->request->post['filter_customer_group_id'])) {
						$filter_customer_group_id = $this->request->post['filter_customer_group_id'];
				} else {
						$filter_customer_group_id = null;
				}
				
				if (isset($this->request->post['filter_store_id'])) {
						$filter_store_id = $this->request->post['filter_store_id'];
				} else {
						$filter_store_id = null;
				}

				if (isset($this->request->post['filter_product'])) {
						$filter_product = $this->request->post['filter_product'];
				} else {
						$filter_product = null;
				}

				if (isset($this->request->post['filter_category'])) {
						$filter_category = $this->request->post['filter_category'];
				} else {
						$filter_category = null;
				}



				if (isset($this->request->post['filter_category1'])) {
						$filter_category1 = $this->request->post['filter_category1'];
				} else {
						$filter_category1 = null;
				}
				if (isset($this->request->post['category_id'])) {
						$filter_category1 = $this->request->post['category_id'];
				}

				if (isset($this->request->get['sort'])) {
						$sort = $this->request->get['sort'];
				} else {
						$sort = 'o.order_id';
				}

				if (isset($this->request->get['order'])) {
						$order = $this->request->get['order'];
				} else {
						$order = 'DESC';
				}

				if (isset($this->request->post['selected'])) {
					$selected = (array)$this->request->post['selected'];
				} else {
					$selected = false;
				}

				$data['orders'] = array();

				$filter_data = array(
					'filter_order_id'      => $filter_order_id,
					'filter_customer'	   => $filter_customer,
					'filter_customer_group_id'	   => $filter_customer_group_id,
					'filter_store_id'	   => $filter_store_id,
					'filter_product'	   => $filter_product,
					'filter_category'	   => $filter_category,
					'filter_category1'	   => $filter_category1,
					'order_ids'	   => $selected,
					'filter_order_status'  => $filter_order_status,
					'filter_total'         => $filter_total,
					'filter_date_added'    => $filter_date_added,
					'filter_date_modified' => $filter_date_modified,
					'filter_startdate'	   => $filter_startdate,
					'filter_enddate' 	   => $filter_enddate,
					'sort'                 => $sort,
					'order'                => $order,
				);

				$this->load->model('sale/order');
				$this->load->model('extension/order_export');
				$this->load->model('catalog/product');
				$this->load->model('customer/custom_field');
				
				$custom_fields = $this->model_customer_custom_field->getCustomFields();
				if(!empty($this->request->post['product'])) {
					$results = $this->model_extension_order_export->getProductOrders($filter_data);

					foreach ($results as $result) {

						$result1=$this->model_catalog_product->getProduct($result['product_id']);
						if(!isset($result1['product_id']))
						{
							 $result1['sku']='';
							 $result1['upc']='';
							 $result1['ean']='';
							 $result1['jan']='';
							 $result1['isbn']='';
							 $result1['mpn']='';

						}

						$order_subtotal = $this->model_extension_order_export->getOrderTotalByCode($result['order_id'],'sub_total');
						if(!empty($order_subtotal['value'])) {
							$order_subtotal = $order_subtotal['value'];
						}else{
							$order_subtotal = '';
						}

						$order_shipping = $this->model_extension_order_export->getOrderTotalByCode($result['order_id'],'shipping');
						if(!empty($order_shipping['value'])) {
							$order_shipping = $order_shipping['value'];
						}else{
							$order_shipping = '';
						}
						// 14-12-2016

						/// 15 April 2019 ///
							$weight = $this->model_extension_order_export->getProductWeight($result['order_id'],$result['product_id']);
						/// 15 April 2019 ///


						// 27-03-2017
						$order_coupon = $this->model_extension_order_export->getOrderTotalByCode($result['order_id'],'coupon');
						if(!empty($order_coupon['title'])) {
							$coupon_code = $order_coupon['title'];
							$couponcost = $order_coupon['value'];
						}else{
							$coupon_code = '';
							$couponcost = '';
						}
						// 27-03-2017

						$order_credit = $this->model_extension_order_export->getOrderTotalByCode($result['order_id'],'credit');
						if(!empty($order_credit)) {
							$store_credit = $this->currency->format($order_credit['value'], $result['currency_code'], $result['currency_value']);
						}else{
							$store_credit = '';
						}

						$order_voucher = $this->model_extension_order_export->getOrderTotalByCode($result['order_id'],'voucher');
						if(!empty($order_voucher['title'])) {
							$voucher_code = $order_voucher['title'];
						}else{
							$voucher_code = '';
						}

						$order_product_options = $this->model_sale_order->getOrderOptions($result['order_id'], $result['order_product_id']);
						$product_options = '';
						foreach($order_product_options as $key => $order_product_option) {
							if($key>0){
								$product_options .= ', ';
							}
							$product_options .=$order_product_option['name'].' - '.$order_product_option['value'];
						}

						$order_info = $this->model_sale_order->getOrder($result['order_id']);

						$data['payment_custom_fields'] = array();
						foreach ($custom_fields as $custom_field) {
							if ($custom_field['location'] == 'address' && isset($order_info['payment_custom_field'][$custom_field['custom_field_id']])) {
								if ($custom_field['type'] == 'select' || $custom_field['type'] == 'radio') {
									$custom_field_value_info = $this->model_customer_custom_field->getCustomFieldValue($order_info['payment_custom_field'][$custom_field['custom_field_id']]);

									if ($custom_field_value_info) {
										$data['payment_custom_fields'][] = array(
											'name'  => $custom_field['name'],
											'value' => $custom_field_value_info['name']
										);
									}
								}

								if ($custom_field['type'] == 'checkbox' && is_array($order_info['payment_custom_field'][$custom_field['custom_field_id']])) {
									foreach ($order_info['payment_custom_field'][$custom_field['custom_field_id']] as $custom_field_value_id) {
										$custom_field_value_info = $this->model_customer_custom_field->getCustomFieldValue($custom_field_value_id);

										if ($custom_field_value_info) {
											$data['payment_custom_fields'][] = array(
												'name'  => $custom_field['name'],
												'value' => $custom_field_value_info['name']
											);
										}
									}
								}

								if ($custom_field['type'] == 'text' || $custom_field['type'] == 'textarea' || $custom_field['type'] == 'file' || $custom_field['type'] == 'date' || $custom_field['type'] == 'datetime' || $custom_field['type'] == 'time') {
									$data['payment_custom_fields'][] = array(
										'name'  => $custom_field['name'],
										'value' => $order_info['payment_custom_field'][$custom_field['custom_field_id']]
									);
								}

								if ($custom_field['type'] == 'file') {
									$upload_info = $this->model_tool_upload->getUploadByCode($order_info['payment_custom_field'][$custom_field['custom_field_id']]);

									if ($upload_info) {
										$data['payment_custom_fields'][] = array(
											'name'  => $custom_field['name'],
											'value' => $upload_info['name']
										);
									}
								}
							}
						}

						$payment_custom_field ='';
						foreach($data['payment_custom_fields'] as $k => $payment_custom_fields) {
							if($k>0) {
								$payment_custom_field = ', ';
							}
							$payment_custom_field .= $payment_custom_fields['name'].' : '.$payment_custom_fields['value'];
						}

						if (!empty($result['product_id'])) {
							$product_url = 'index.php?route=product/product&product_id='.$result['product_id'];	
						} else {
							$product_url = '';
						}
						

						$this->load->model('tool/image');
						$this->load->model('catalog/product');
						$this->load->model('catalog/manufacturer');
						$product_info = $this->model_catalog_product->getProduct($result['product_id']);
						if (is_file(DIR_IMAGE . $product_info['image'])) {
							$product_imageurl = $this->model_tool_image->resize($product_info['image'], 250, 250);
						} else {
							$product_imageurl = $this->model_tool_image->resize('no_image.png', 250, 250);
						}

						$manfac_info = $this->model_catalog_manufacturer->getManufacturer($product_info['manufacturer_id']);

						if(!empty($manfac_info['name'])) {
							$product_manufac_name = $manfac_info['name'];
						}else{
							$product_manufac_name = '';
						}

						$data['orders'][] = array(
								'order_id'       => $result['order_id'],
								'customer'       => $result['customer'],
								// 14-12-2016
								'store_name'       => $order_info['store_name'],
								'subtotal'       => $this->currency->format($order_subtotal, $result['currency_code'], $result['currency_value']),
								'shippingcost'       => $this->currency->format($order_shipping, $result['currency_code'], $result['currency_value']),

								// 14-12-2016
								// 27-03-2016
								'couponcost'       => $this->currency->format($couponcost, $result['currency_code'], $result['currency_value']),
								// 27-03-2016
								/// 15 April 2019 ///
								'weight' 			=>$weight,
								/// 15 April 2019 ///
								'invoice_no'     => $order_info['invoice_no'],
								'invoice_prefix' => $order_info['invoice_prefix'],
								'telephone'      => $order_info['telephone'],
								'fax'            => '',
								'email'          => $order_info['email'],
								'coupon_code'    => $coupon_code,
								'store_credit'   => $store_credit,
								'voucher_code'   => $voucher_code,
								'payment_firstname' 		=> $order_info['payment_firstname'],
								'payment_lastname'  		=> $order_info['payment_lastname'],
								'payment_company'   		=> $order_info['payment_company'],
								'payment_address_1' 		=> $order_info['payment_address_1'],
								'payment_address_2' 		=> $order_info['payment_address_2'],
								'payment_postcode' 			=> $order_info['payment_postcode'],
								'payment_city'     			=> $order_info['payment_city'],
								'payment_zone'     			=> $order_info['payment_zone'],
								'payment_zone_code'     	=> $order_info['payment_zone_code'],
								'payment_country'     		=> $order_info['payment_country'],
								'payment_iso_code_2'     	=> $order_info['payment_iso_code_2'],
								'payment_iso_code_3'     	=> $order_info['payment_iso_code_3'],
								'payment_address_format'    => $order_info['payment_address_format'],
								'payment_custom_field'     	=> $payment_custom_field,
								'payment_method'    		=> $order_info['payment_method'],
								'payment_code'     			=> $order_info['payment_code'],
								'shipping_firstname'     	=> $order_info['shipping_firstname'],
								'shipping_lastname'     	=> $order_info['shipping_lastname'],
								'shipping_company'     		=> $order_info['shipping_company'],
								'shipping_address_1'     	=> $order_info['shipping_address_1'],
								'shipping_address_2'     	=> $order_info['shipping_address_2'],
								'shipping_postcode'     	=> $order_info['shipping_postcode'],
								'shipping_city'     		=> $order_info['shipping_city'],
								'shipping_zone'     		=> $order_info['shipping_zone'],
								'shipping_zone_code'    	=> $order_info['shipping_zone_code'],
								'shipping_country'     		=> $order_info['shipping_country'],
								'shipping_iso_code_2'     	=> $order_info['shipping_iso_code_2'],
								'shipping_iso_code_3'     	=> $order_info['shipping_iso_code_3'],
								'shipping_address_format'   => $order_info['shipping_address_format'],
								'shipping_method'     		=> $order_info['shipping_method'],
								'shipping_code'     		=> $order_info['shipping_code'],
								'comment'     				=> $order_info['comment'],
								'status'           			=> $result['status'],
								'total'            			=> $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
								'reward'           			=> $order_info['reward'],
								'affiliate_firstname'      => $order_info['affiliate_firstname'],
								'affiliate_lastname'       => $order_info['affiliate_lastname'],
								'commission'               => $order_info['commission'],
								'date_added'               => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
								// 11-06-2018
								'date_modified'            => date($this->language->get('date_format_short'), strtotime($result['date_modified'])),
								// 11-06-2018
								'product_id'               => $result['product_id'],
								'product_url'              => str_replace('&amp;', '&', $product_url),
								'product_imageurl'         => $product_imageurl,
								'product_manufac_name'     => $product_manufac_name,
								'product_name'             => $result['product_name'],
								'product_model'            => $result['product_model'],
								'product_sku'			   => $result1['sku'],
								'product_upc'              => $result1['upc'],
								'product_ean' 			   => $result1['ean'],
								'product_jan' 			   => $result1['jan'],
								'product_isbn' 			   => $result1['isbn'],
								'product_mpn' 			   => $result1['mpn'],
								'product_quantity'         => $result['product_quantity'],
								'product_price'            => $this->currency->format($result['product_price'], $result['currency_code'], $result['currency_value']),
								'product_tax'              => $this->currency->format($result['product_tax'], $result['currency_code'], $result['currency_value']),
								'product_total'            => $this->currency->format(($result['product_total'] + ($result['product_tax'])*$result['product_quantity']), $result['currency_code'], $result['currency_value']),
								'product_reward'           => $result['product_reward'],
								'product_options'          => $product_options,
						);
					}
				} else {
					$results = $this->model_extension_order_export->getOrders($filter_data);

					foreach ($results as $result) {

						// 14-12-2016
						$order_subtotal = $this->model_extension_order_export->getOrderTotalByCode($result['order_id'],'sub_total');
						if(!empty($order_subtotal['value'])) {
							$order_subtotal = $order_subtotal['value'];
						}else{
							$order_subtotal = '';
						}

						/// 15 April 2019 ///
							$weight = $this->model_extension_order_export->getProductWeight($result['order_id']);
						/// 15 April 2019 ///

						$order_shipping = $this->model_extension_order_export->getOrderTotalByCode($result['order_id'],'shipping');
						if(!empty($order_shipping['value'])) {
							$order_shipping = $order_shipping['value'];
						}else{
							$order_shipping = '';
						}
						// 14-12-2016
						$order_info = $this->model_sale_order->getOrder($result['order_id']);

						// 27-03-2017
						$order_coupon = $this->model_extension_order_export->getOrderTotalByCode($result['order_id'],'coupon');
						if(!empty($order_coupon['title'])) {
							$coupon_code = $order_coupon['title'];
							$couponcost = $order_coupon['value'];
						}else{
							$coupon_code = '';
							$couponcost = '';
						}
						// 27-03-2017

						$order_credit = $this->model_extension_order_export->getOrderTotalByCode($result['order_id'],'credit');
						if(!empty($order_credit)) {
							$store_credit = $this->currency->format($order_credit['value'], $result['currency_code'], $result['currency_value']);
						}else{
							$store_credit = '';
						}

						$order_voucher = $this->model_extension_order_export->getOrderTotalByCode($result['order_id'],'voucher');
						if(!empty($order_voucher['title'])) {
							$voucher_code = $order_voucher['title'];
						}else{
							$voucher_code = '';
						}

						$data['payment_custom_fields'] = array();
						foreach ($custom_fields as $custom_field) {
							if ($custom_field['location'] == 'address' && isset($order_info['payment_custom_field'][$custom_field['custom_field_id']])) {
								if ($custom_field['type'] == 'select' || $custom_field['type'] == 'radio') {
									$custom_field_value_info = $this->model_customer_custom_field->getCustomFieldValue($order_info['payment_custom_field'][$custom_field['custom_field_id']]);

									if ($custom_field_value_info) {
										$data['payment_custom_fields'][] = array(
											'name'  => $custom_field['name'],
											'value' => $custom_field_value_info['name']
										);
									}
								}

								if ($custom_field['type'] == 'checkbox' && is_array($order_info['payment_custom_field'][$custom_field['custom_field_id']])) {
									foreach ($order_info['payment_custom_field'][$custom_field['custom_field_id']] as $custom_field_value_id) {
										$custom_field_value_info = $this->model_customer_custom_field->getCustomFieldValue($custom_field_value_id);

										if ($custom_field_value_info) {
											$data['payment_custom_fields'][] = array(
												'name'  => $custom_field['name'],
												'value' => $custom_field_value_info['name']
											);
										}
									}
								}

								if ($custom_field['type'] == 'text' || $custom_field['type'] == 'textarea' || $custom_field['type'] == 'file' || $custom_field['type'] == 'date' || $custom_field['type'] == 'datetime' || $custom_field['type'] == 'time') {
									$data['payment_custom_fields'][] = array(
										'name'  => $custom_field['name'],
										'value' => $order_info['payment_custom_field'][$custom_field['custom_field_id']]
									);
								}

								if ($custom_field['type'] == 'file') {
									$upload_info = $this->model_tool_upload->getUploadByCode($order_info['payment_custom_field'][$custom_field['custom_field_id']]);

									if ($upload_info) {
										$data['payment_custom_fields'][] = array(
											'name'  => $custom_field['name'],
											'value' => $upload_info['name']
										);
									}
								}
							}
						}

						$payment_custom_field ='';
						foreach($data['payment_custom_fields'] as $k => $payment_custom_fields) {
							if($k>0) {
								$payment_custom_field = ', ';
							}
							$payment_custom_field .= $payment_custom_fields['name'].' : '.$payment_custom_fields['value'];
						}

						$data['orders'][] = array(
								'order_id'       => $result['order_id'],
								'customer'       => $result['customer'],
								// 14-12-2016
								'store_name'       => $order_info['store_name'],
								'subtotal'       => $this->currency->format($order_subtotal, $result['currency_code'], $result['currency_value']),
								'shippingcost'       => $this->currency->format($order_shipping, $result['currency_code'], $result['currency_value']),

								// 14-12-2016

								// 27-03-2016
								'couponcost'       => $this->currency->format($couponcost, $result['currency_code'], $result['currency_value']),
								// 27-03-2016
								'invoice_no'     => $order_info['invoice_no'],
								'invoice_prefix' => $order_info['invoice_prefix'],
								'telephone'      => $order_info['telephone'],
								'fax'            => '',
								/// 15 April 2019 ///
								'weight'         => $weight,
								/// 15 April 2019 ///
								'email'          => $order_info['email'],
								'coupon_code'    => $coupon_code,
								'store_credit'   => $store_credit,
								'voucher_code'   => $voucher_code,
								'payment_firstname' => $order_info['payment_firstname'],
								'payment_lastname'  => $order_info['payment_lastname'],
								'payment_company'   => $order_info['payment_company'],
								'payment_address_1' => $order_info['payment_address_1'],
								'payment_address_2' => $order_info['payment_address_2'],
								'payment_postcode' => $order_info['payment_postcode'],
								'payment_city'     => $order_info['payment_city'],
								'payment_zone'     => $order_info['payment_zone'],
								'payment_zone_code'     => $order_info['payment_zone_code'],
								'payment_country'     => $order_info['payment_country'],
								'payment_iso_code_2'     => $order_info['payment_iso_code_2'],
								'payment_iso_code_3'     => $order_info['payment_iso_code_3'],
								'payment_address_format'     => $order_info['payment_address_format'],
								'payment_custom_field'       => $payment_custom_field,
								'payment_method'     => $order_info['payment_method'],
								'payment_code'     => $order_info['payment_code'],
								'shipping_firstname'     => $order_info['shipping_firstname'],
								'shipping_lastname'     => $order_info['shipping_lastname'],
								'shipping_company'     => $order_info['shipping_company'],
								'shipping_address_1'     => $order_info['shipping_address_1'],
								'shipping_address_2'     => $order_info['shipping_address_2'],
								'shipping_postcode'     => $order_info['shipping_postcode'],
								'shipping_city'     => $order_info['shipping_city'],
								'shipping_zone'     => $order_info['shipping_zone'],
								'shipping_zone_code'     => $order_info['shipping_zone_code'],
								'shipping_country'     => $order_info['shipping_country'],
								'shipping_iso_code_2'     => $order_info['shipping_iso_code_2'],
								'shipping_iso_code_3'     => $order_info['shipping_iso_code_3'],
								'shipping_address_format'     => $order_info['shipping_address_format'],
								'shipping_method'     => $order_info['shipping_method'],
								'shipping_code'     => $order_info['shipping_code'],
								'comment'     => $order_info['comment'],
								'status'           => $result['status'],
								'total'                => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
								'reward'                   => $order_info['reward'],
								'affiliate_firstname'      => $order_info['affiliate_firstname'],
								'affiliate_lastname'       => $order_info['affiliate_lastname'],
								'commission'               => $order_info['commission'],
								'date_added'               => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
								// 11-06-2018
								'date_modified'               => date($this->language->get('date_format_short'), strtotime($result['date_modified'])),
								// 11-06-2018
						);
					}
				}

				$orders = $this->request->clean($data['orders']);

				//Columns
				$i=1;
				if(!empty($this->request->post['product'])) {
					$export_language =array(
						'order_id' => $this->language->get('export_order_id'),
						//14-12-2016
						'store_name' => $this->language->get('export_storetype'),
						//14-12-2016
						'customer' => $this->language->get('export_customer'),
						'invoice_no' => $this->language->get('export_invoice_no'),
						'invoice_prefix' => $this->language->get('export_invoice_prefix'),
						'telephone' => $this->language->get('export_telephone'),
						'fax'       => $this->language->get('export_fax'),
						'email'     => $this->language->get('export_email'),
						'coupon_code'    => $this->language->get('export_coupon_code'),
						'store_credit'   => $this->language->get('export_store_credit'),
						'voucher_code'   => $this->language->get('export_voucher_code'),
						'payment_firstname' => $this->language->get('export_payment_firstname'),
						'payment_lastname' => $this->language->get('export_payment_lastname'),
						'payment_company' => $this->language->get('export_payment_company'),
						'payment_address_1' => $this->language->get('export_payment_address_1'),
						'payment_address_2' => $this->language->get('export_payment_address_2'),
						'payment_postcode' => $this->language->get('export_payment_postcode'),
						'payment_city' => $this->language->get('export_payment_city'),
						'payment_zone' => $this->language->get('export_payment_zone'),
						'payment_zone_code' => $this->language->get('export_payment_zone_code'),
						'payment_country' => $this->language->get('export_payment_country'),
						'payment_iso_code_2' => $this->language->get('export_payment_iso_code_2'),
						'payment_iso_code_3' => $this->language->get('export_payment_iso_code_3'),
						'payment_address_format' => $this->language->get('export_payment_address_format'),
						'payment_custom_field' => $this->language->get('export_payment_custom_field'),
						'payment_method' => $this->language->get('export_payment_method'),
						'payment_code' => $this->language->get('export_payment_code'),
						'shipping_firstname' => $this->language->get('export_shipping_firstname'),
						'shipping_lastname' => $this->language->get('export_shipping_lastname'),
						'shipping_company' => $this->language->get('export_shipping_company'),
						'shipping_address_1' => $this->language->get('export_shipping_address_1'),
						'shipping_address_2' => $this->language->get('export_shipping_address_2'),
						'shipping_postcode' => $this->language->get('export_shipping_postcode'),
						'shipping_city' => $this->language->get('export_shipping_city'),
						'shipping_zone' => $this->language->get('export_shipping_zone'),
						'shipping_zone_code' => $this->language->get('export_shipping_zone_code'),
						'shipping_country' => $this->language->get('export_shipping_country'),
						'shipping_iso_code_2' => $this->language->get('export_shipping_iso_code_2'),
						'shipping_iso_code_3' => $this->language->get('export_shipping_iso_code_3'),
						'shipping_address_format' => $this->language->get('export_shipping_address_format'),
						'shipping_method' => $this->language->get('export_shipping_method'),
						/// 15 April 2019 ///
						'weight' => $this->language->get('export_weight_total'),
						/// 15 April 2019 ///
						'shipping_code' => $this->language->get('export_shipping_code'),
						'comment' => $this->language->get('export_comment'),
						'reward' => $this->language->get('export_reward'),
						'status' => $this->language->get('export_status'),
						'affiliate_firstname' => $this->language->get('export_affiliate_firstname'),
						'affiliate_lastname' => $this->language->get('export_affiliate_lastname'),
						'commission' => $this->language->get('export_commission'),
						'total' => $this->language->get('export_total'),
						/// 14-12-2016
						'subtotal' => $this->language->get('export_subtotal'),
						'shippingcost' => $this->language->get('export_shippingcost'),
						/// 14-12-2016
						// 27-03-2016
						'couponcost' => $this->language->get('export_couponcost'),
						// 27-03-2016
						'product_id' => $this->language->get('text_product_id'),
						'product_url' => $this->language->get('text_product_url'),
						'product_imageurl' => $this->language->get('text_product_imageurl'),
						'product_manufac_name' => $this->language->get('text_product_manufacturer'),
						'date_added' => $this->language->get('export_date_added'),
						'product_name' => $this->language->get('export_product_name'),
						'product_model' => $this->language->get('export_model'),
						'product_sku' => $this->language->get('text_sku'),
						'product_upc' => $this->language->get('text_upc'),
						'product_ean' => $this->language->get('text_ean'),
						'product_jan' => $this->language->get('text_jan'),
						'product_isbn' => $this->language->get('text_isbn'),
						'product_mpn' => $this->language->get('text_mpn'),
						'product_quantity' => $this->language->get('export_quantity'),
						'product_price'      => $this->language->get('export_price'),
						'product_tax'      => $this->language->get('export_tax'),
						'product_total'      => $this->language->get('export_total'),
						'product_reward'      => $this->language->get('export_reward'),
						'product_options'      => $this->language->get('export_option'),
						// 11-06-2018
						'date_modified' => $this->language->get('entry_date_modified'),
						// 11-06-2018
					);
				}else{
					$export_language =array(
						'order_id' => $this->language->get('export_order_id'),
						//14-12-2016
						'store_name' => $this->language->get('export_storetype'),
						//14-12-2016
						'customer' => $this->language->get('export_customer'),
						'invoice_no' => $this->language->get('export_invoice_no'),
						'invoice_prefix' => $this->language->get('export_invoice_prefix'),
						'telephone' => $this->language->get('export_telephone'),
						'fax' => $this->language->get('export_fax'),
						'email'     => $this->language->get('export_email'),
						'coupon_code'    => $this->language->get('export_coupon_code'),
						'store_credit'   => $this->language->get('export_store_credit'),
						'voucher_code'   => $this->language->get('export_voucher_code'),
						'payment_firstname' => $this->language->get('export_payment_firstname'),
						'payment_lastname' => $this->language->get('export_payment_lastname'),
						'payment_company' => $this->language->get('export_payment_company'),
						'payment_address_1' => $this->language->get('export_payment_address_1'),
						'payment_address_2' => $this->language->get('export_payment_address_2'),
						'payment_postcode' => $this->language->get('export_payment_postcode'),
						'payment_city' => $this->language->get('export_payment_city'),
						'payment_zone' => $this->language->get('export_payment_zone'),
						'payment_zone_code' => $this->language->get('export_payment_zone_code'),
						'payment_country' => $this->language->get('export_payment_country'),
						'payment_iso_code_2' => $this->language->get('export_payment_iso_code_2'),
						'payment_iso_code_3' => $this->language->get('export_payment_iso_code_3'),
						'payment_address_format' => $this->language->get('export_payment_address_format'),
						'payment_custom_field' => $this->language->get('export_payment_custom_field'),
						'payment_method' => $this->language->get('export_payment_method'),
						'payment_code' => $this->language->get('export_payment_code'),
						'shipping_firstname' => $this->language->get('export_shipping_firstname'),
						'shipping_lastname' => $this->language->get('export_shipping_lastname'),
						'shipping_company' => $this->language->get('export_shipping_company'),
						'shipping_address_1' => $this->language->get('export_shipping_address_1'),
						'shipping_address_2' => $this->language->get('export_shipping_address_2'),
						'shipping_postcode' => $this->language->get('export_shipping_postcode'),
						'shipping_city' => $this->language->get('export_shipping_city'),
						'shipping_zone' => $this->language->get('export_shipping_zone'),
						'shipping_zone_code' => $this->language->get('export_shipping_zone_code'),
						'shipping_country' => $this->language->get('export_shipping_country'),
						'shipping_iso_code_2' => $this->language->get('export_shipping_iso_code_2'),
						'shipping_iso_code_3' => $this->language->get('export_shipping_iso_code_3'),
						'shipping_address_format' => $this->language->get('export_shipping_address_format'),
						'shipping_method' => $this->language->get('export_shipping_method'),
						/// 15 April 2019 ///
						'weight' => $this->language->get('export_weight_total'),
						/// 15 April 2019 ///
						'shipping_code' => $this->language->get('export_shipping_code'),
						'comment' => $this->language->get('export_comment'),
						'reward' => $this->language->get('export_reward'),
						'status' => $this->language->get('export_status'),
						'affiliate_firstname' => $this->language->get('export_affiliate_firstname'),
						'affiliate_lastname' => $this->language->get('export_affiliate_lastname'),
						'commission' => $this->language->get('export_commission'),
						'total' => $this->language->get('export_total'),
						/// 14-12-2016
						'subtotal' => $this->language->get('export_subtotal'),
						'shippingcost' => $this->language->get('export_shippingcost'),
						/// 14-12-2016
						//  27-03-2017
						'couponcost' => $this->language->get('export_couponcost'),
						//  27-03-2017
						'date_added' => $this->language->get('export_date_added'),
						// 11-06-2018
						'date_modified' => $this->language->get('entry_date_modified'),
						// 11-06-2018
					);
				}

				$cell_name ='A';

				if(!empty($this->request->post['product'])) {
					$order_data = array_merge($this->request->post['order'],$this->request->post['product']);
				} else {
					$order_data = $this->request->post['order'];
				}
				if(empty($this->request->post['exportfilename'])) {
					$this->session->data['warning'] = 'Request Error:Export File name required';
					$this->response->redirect($this->url->link('extension/order_export', 'user_token=' . $this->session->data['user_token'] , true));
				} else {
				if($this->request->post['format']=='xls' || $this->request->post['format']=='xlsx')
				{

				$spreadsheet = new Spreadsheet();
				
				foreach($order_data as $myorder_cell){
					$spreadsheet->getActiveSheet()->SetCellValue($cell_name.$i, $export_language[$myorder_cell]);
					$cell_name++;
				}

				 //Lists
				$i=2;

				if($orders) {
			 		foreach($orders as $order) {
						$cell_name ='A';
						foreach($order_data as $myorder_cell) {
							$spreadsheet->getActiveSheet()->SetCellValue($cell_name.$i, $order[$myorder_cell]);
							$cell_name++;
						}
						$i++;
					}
				}

				/* color setup */
				for($col = 'A'; $col != $cell_name; $col++) {
			   $spreadsheet->getActiveSheet()->getColumnDimension($col)->setWidth(20);
			 	}

				$spreadsheet->getActiveSheet()->getRowDimension(1)->setRowHeight(25);

				$spreadsheet->getActiveSheet()
				->getStyle('A1:'.$cell_name.'1')
				->getFill()
				->setFillType(PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
				->getStartColor()
				->setARGB('FF4F81BD');

				$styleArray = array(
					'font'  => array(
					'bold'  => true,
					'color' => array('rgb' => 'FFFFFF'),
					'size'  => 9,
					'name'  => 'Verdana'
				));

				$spreadsheet->getActiveSheet()->getStyle('A1:'.$cell_name.'1')->applyFromArray($styleArray);
				$spreadsheet->getActiveSheet()->setTitle('All Orders'); 
				/* color setup */
				if($this->request->post['format']=='xls')
				{			
				$filename = 'export.xls';
				$writer = new Xls($spreadsheet);
				}
				if($this->request->post['format']=='xlsx')
				{	
				$filename = 'export.xlsx';
				$writer = new Xlsx($spreadsheet);
				}
				header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				header('Content-Disposition: attachment; filename="'. urlencode($filename).'"');
				$writer->save('php://output');
				unlink($filename);
				} else {
					
					$filename = $this->request->post['exportfilename'] . '.csv';
					header('Content-Encoding: UTF-8');
					header("Content-type: text/csv; charset=UTF-8");
					header('Content-type: application/csv');
					header('Content-Disposition: attachment; filename='.$filename);
					
					$fp = fopen('php://output', 'w');
					$fileds=array();
					foreach($order_data as $myorder_cell){
									$fileds[]=$export_language[$myorder_cell];
					}
					fputcsv($fp, $fileds);

					if($orders) {
			 			foreach($orders as $order) {
							$fileds=array();
							foreach($order_data as $myorder_cell) {
								$fileds[]=$order[$myorder_cell];
							}
							$fileds = array_map("utf8_decode", $fileds);
							fputcsv($fp,$fileds );
						}
					}
				 fclose($fp);

				}
				}
			} else {
					$this->response->redirect($this->url->link('extension/order_export', 'user_token=' . $this->session->data['user_token'], true));
			}
		}
	}
	public function keysubmit() {
		$json = array(); 
		
      	if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			$keydata=array(
			'code'=>'tmdkey_orderexport',
			'eid'=>'MjE2NDY=',
			'route'=>'sale/order_export',
			'moduledata_key'=>$this->request->post['moduledata_key'],
			);
			$this->registry->set('tmd', new TMD($this->registry));
            $json=$this->tmd->matchkey($keydata);       
		} 
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
