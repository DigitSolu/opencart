<?php
error_reporting(E_ALL);
ini_set('display_errors',1);

set_time_limit(0);

ini_set('memory_limit', '999M');
ini_set('set_time_limit', '0');


// Autoloader
require_once(DIR_SYSTEM . 'library/ciformbuilder/composer/vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\IOFactory;


class ControllerExtensionCiformbuilderPageRequestExportSpread extends Controller {
	private $error = array();
	private $module_token = '';
	private $ci_token = '';

	public function __construct($registry) {
		parent :: __construct($registry);

		if(VERSION <= '2.3.0.2') {
			$this->module_token = 'token';
			$this->ci_token = $this->session->data['token'];
		} else {
			$this->module_token = 'user_token';
			$this->ci_token = $this->session->data['user_token'];
		}
	}

	public function index() {
		$this->load->language('extension/ciformbuilder/page_request');

		$this->load->model('extension/ciformbuilder/page_form');
		$this->load->model('extension/ciformbuilder/page_request');
		$this->load->model('setting/store');
		$this->load->model('localisation/language');
		$this->load->model('customer/customer');
		$this->load->model('tool/upload');

	    if (isset($this->request->get['filter_page_form_title'])) {
			$filter_page_form_title = $this->request->get['filter_page_form_title'];
		} else {
			$filter_page_form_title = '';
		}

		if (isset($this->request->get['filter_customer'])) {
			$filter_customer = $this->request->get['filter_customer'];
		} else {
			$filter_customer = '';
		}

		if (isset($this->request->get['filter_ip'])) {
			$filter_ip = $this->request->get['filter_ip'];
		} else {
			$filter_ip = '';
		}

		if (isset($this->request->get['filter_date_added'])) {
			$filter_date_added = $this->request->get['filter_date_added'];
		} else {
			$filter_date_added = '';
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'pg.date_added';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}

	    if (isset($this->request->get['export_date_start'])) {
			$export_date_start = $this->request->get['export_date_start'];
		} else {
			$export_date_start = '';
		}

	    if (isset($this->request->get['export_date_end'])) {
			$export_date_end = $this->request->get['export_date_end'];
		} else {
			$export_date_end = '';
		}

		$filter_data = array(
			'filter_page_form_title'  	=> $filter_page_form_title,
			'filter_customer'  			=> $filter_customer,
			'filter_ip'  				=> $filter_ip,
			'filter_date_added'  		=> $filter_date_added,
			'sort'  					=> $sort,
			'order' 					=> $order,
			'start'									=> '',
			'limit'									=> '',
			'nopagination'							=> true,
			'export_date_start'						=> $export_date_start,
			'export_date_end'						=> $export_date_end,
		);

		$results = $this->model_extension_ciformbuilder_page_request->getPageRequests($filter_data);

		$this->spreadsheet = new Spreadsheet;

	    $this->spreadsheet->getProperties()
	    ->setCreator("CodingInspect")
	    ->setLastModifiedBy("codinginspect.com")
	    ->setTitle("Form Submissions")
	    ->setSubject("Form Submissions")
	    ->setDescription("Form Submissions")
	    ->setKeywords("Form Submissions")
	    ->setCategory("Form Submissions");

	    $this->spreadsheet->setActiveSheetIndex(0);
	    $sheet = $this->spreadsheet->getActiveSheet();

		$i = 1;
		$ci_column = 'A';

		$page_request_ids = [];
		foreach($results as $result) {
			$page_request_ids[] = $result['page_request_id'];
		}

		$page_request_columns = $this->model_extension_ciformbuilder_page_request->getPageRequestsColumns($page_request_ids);

		$sheet->setCellValue($ci_column .$i, $this->language->get('xls_customer'))->getColumnDimension($ci_column)->setAutoSize(true);
		$sheet->getStyle($ci_column++ .$i)->getAlignment()->setWrapText(true);

		$sheet->setCellValue($ci_column .$i, $this->language->get('xls_page_title'))->getColumnDimension($ci_column)->setAutoSize(true);
		$sheet->getStyle($ci_column++ .$i)->getAlignment()->setWrapText(true);

		$sheet->setCellValue($ci_column .$i, $this->language->get('xls_ip'))->getColumnDimension($ci_column)->setAutoSize(true);
		$sheet->getStyle($ci_column++ .$i)->getAlignment()->setWrapText(true);

		$sheet->setCellValue($ci_column .$i, $this->language->get('xls_date_added'))->getColumnDimension($ci_column)->setAutoSize(true);
		$sheet->getStyle($ci_column++ .$i)->getAlignment()->setWrapText(true);

		$columns_positions = [];
		foreach($page_request_columns as $page_request_column) {
			$columns_positions[$ci_column] = $page_request_column['name'];

			$sheet->setCellValue($ci_column .$i, html_entity_decode($page_request_column['name']))->getColumnDimension($ci_column)->setAutoSize(true);
			$sheet->getStyle($ci_column++ .$i)->getAlignment()->setWrapText(true);
		}

		// Background Color
		$sheet->getStyle('A1:'.$sheet->getHighestColumn().'1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('1A129cf6');

		// Font Color
		$sheet->getStyle('A1:'.$sheet->getHighestColumn().'1')->getFont()->setBold(true)->setSize(12)->getColor()->setARGB('FFFFFF');


		if($results) {
			$sheet->setTitle(sprintf($this->language->get('xls_title'), count($results)));
			foreach($results as $result) {
				$ci_values = 'A';
				$i++;

				/* $page_request_options = $this->model_extension_ciformbuilder_page_request->getPageRequestOptions($result['page_request_id']);
				$field_name = '';
				$c = count($page_request_options);
				foreach ($page_request_options as $key => $page_request_option) {
					if($page_request_option['type'] == 'password' || $page_request_option['type'] == 'confirm_password') {
						$page_request_option['value'] = unserialize(base64_decode($page_request_option['value']));
					}

					$field_name .= $page_request_option['name'] .': '. $page_request_option['value'];

					if(($key +1) < $c) {
						$field_name .= "\n";
					}
				} */

				$page_form_info = $this->model_extension_ciformbuilder_page_request->getPageFormDescription($result['page_form_id']);

				if($page_form_info) {
					$page_title = $page_form_info['title'];
				} else {
					$page_title = '';
				}

				$sheet->setCellValue($ci_values++ .$i, html_entity_decode($result['firstname'].' '.$result['lastname'], ENT_QUOTES, 'UTF-8'));
				$sheet->setCellValue($ci_values++ .$i, $page_title);
				$sheet->setCellValue($ci_values++ .$i, $result['ip']);
				$sheet->setCellValue($ci_values++ .$i, $result['date_added']);

				foreach($columns_positions as $c_name => $column_name) {
					if($c_name == $ci_values) {
						$page_request_option_info = $this->model_extension_ciformbuilder_page_request->getPageRequestOptionValue($result['page_request_id'], $result['page_form_id'], $column_name);

						if($page_request_option_info && $page_request_option_info['type'] != 'file') {
							$field_value = $page_request_option_info['value'];
						} elseif($page_request_option_info && $page_request_option_info['type'] == 'file') {
							$file_array = explode(',', $page_request_option_info['value']);
							$value_file = [];
							foreach($file_array as $file_val) {
								$upload_info = $this->model_tool_upload->getUploadByCode($file_val);
								if($upload_info) {
									$value_file[] = $upload_info['name'];
								}
							}

							if($value_file) {
								$field_value = implode(", ", $value_file);
							}
						} else {
							$field_value = '';
						}

						$sheet->setCellValue($ci_values .$i, $field_value);
						$sheet->getStyle($ci_values++ .$i)->getAlignment()->setWrapText(true);
					}
				}
			}
		}

		$writer = new Xlsx($this->spreadsheet);

		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="FormSubmission.xlsx"');
		header('Cache-Control: max-age=0');

		$writer->save('php://output');
		exit();
	}
}