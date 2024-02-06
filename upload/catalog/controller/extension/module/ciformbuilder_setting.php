<?php
class ControllerExtensionModuleCiformbuilderSetting extends Controller {
	// Trigger for admin/controller/common/header/before
	public function createHeaderScript(&$route, &$data) {
		if(!$this->config->get('module_ciformbuilder_setting_status')) {
			return;
		}

		// Datetime Picker
		if(VERSION >= '3.0.0.0') {
		$this->document->addScript('catalog/view/javascript/jquery/magnific/jquery.magnific-popup.min.js');
		$this->document->addStyle('catalog/view/javascript/jquery/magnific/magnific-popup.css');
		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment/moment.min.js');
		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment/moment-with-locales.min.js');
		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
		$this->document->addStyle('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');
		} else {
		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment.js');
		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
		$this->document->addStyle('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');
		}

		// Dropzone
		$this->document->addStyle('catalog/view/javascript/jquery/ciformbuilder/dropzone/dist/dropzone.css');
		$this->document->addScript('catalog/view/javascript/jquery/ciformbuilder/dropzone/dist/dropzone.js');

		// Extension Script
		$this->document->addScript('catalog/view/javascript/jquery/ciformbuilder/formbuilder.js');

		// Extension Style
		$this->document->addStyle('catalog/view/theme/default/stylesheet/ciformbuilder/style.css');
	}

	// Trigger for catalog/view/common/menu/before
	public function createHeaderMenu(&$route, &$data, &$code) {
		if(!$this->config->get('module_ciformbuilder_setting_status')) {
			return;
		}

		$this->load->model('extension/ciformbuilder/form');

		if($data['categories']) {
			foreach ($this->model_extension_ciformbuilder_form->getPageForms() as $result) {
				if ($result['top']) {
					$data['categories'][] = array(
						'name'     => $result['title'],
						'children' => array(),
						'column'   => 1,
						'href'     => $this->url->link('extension/ciformbuilder/form', 'page_form_id=' . $result['page_form_id'])
					);
				}
			}
		}
	}

	// Trigger for catalog/view/common/footer/before
	public function createFooterMenu(&$route, &$data, &$code) {
		if(!$this->config->get('module_ciformbuilder_setting_status')) {
			return;
		}

		$this->load->model('extension/ciformbuilder/form');

		$data['page_forms'] = array();
		foreach ($this->model_extension_ciformbuilder_form->getPageForms() as $result) {
			if ($result['bottom']) {
				$data['page_forms'][] = array(
					'title' => $result['title'],
					'href'  => $this->url->link('extension/ciformbuilder/form', 'page_form_id=' . $result['page_form_id'])
				);
			}
		}
	}

	// Trgieer for catalog/view/information/information/before
	public function createInformationForm(&$route, &$data, &$code) {
		if(!$this->config->get('module_ciformbuilder_setting_status')) {
			return;
		}

		$data['formbuilder_form'] = $this->load->controller('extension/ciformbuilder/information_form');

		$data['description'] .= $data['formbuilder_form'];
	}

	// Trgieer for catalog/view/product/product/before
	public function createProductForm(&$route, &$data, &$output) {
		if(!$this->config->get('module_ciformbuilder_setting_status') || !isset($this->request->get['product_id'])) {
			return;
		}

		$this->load->model('extension/ciformbuilder/form');
		$data['page_forms'] = array();

		foreach ($this->model_extension_ciformbuilder_form->getAllPageFormsByProduct($this->request->get['product_id']) as $form_val) {
			$data['page_forms'][] = array(
				'page_form_id' 		=> $form_val['page_form_id'],
				'pbutton_title' 	=> $form_val['pbutton_title'] ? html_entity_decode($form_val['pbutton_title'], ENT_QUOTES, 'UTF-8') : html_entity_decode($form_val['title'], ENT_QUOTES, 'UTF-8'),
			);
		}

	}

	// Trigger for catalog/view/product/product/after
	public function addProductForm(&$route, &$data, &$output) {
		if(!$this->config->get('module_ciformbuilder_setting_status')) {
			return;
		}

		$find = $data['button_cart'] .'</button>';

		$add_string = '';
		if (!empty($data['page_forms'])) {
			// $add_string .= '<br/>';
          	$add_string .= '<div class="buttons">';
            foreach ($data['page_forms'] as $page_form) {
            	$add_string .= '<button type="button" data-product-id="'. $data['product_id'] .'" data-form-id="'. $page_form['page_form_id'] .'" id="product-mainformbuilder-'. $page_form['page_form_id'] .'" class="mainformbuilder-button cpointer btn btn-primary btn-lg button">'. $page_form['pbutton_title'] .'</button>';
            	$add_string .="\n";
            }

	        $add_string .= '</div>';
      	}

		$output = str_replace($find, $find ."\n". $add_string, $output);
	}

	// Trigger for catalog/view/common/footer/after
	public function addFooterLink(&$route, &$data, &$output) {
		if(!$this->config->get('module_ciformbuilder_setting_status')) {
			return;
		}

		$find = $data['text_sitemap'] .'</a></li>';

		$add_string = '';
		if (!empty($data['page_forms'])) {
            foreach ($data['page_forms'] as $page_form) {
            	$add_string .= '<li><a href="'. $page_form['href'] .'">'. $page_form['title'] .'</a></li>';
            	$add_string .="\n";
            }
      	}

		$output = str_replace($find, $find ."\n". $add_string, $output);
	}

	// Trigger for catalog/view/account/account/after
	public function addAccountLink(&$route, &$data, &$output) {
		if(!$this->config->get('module_ciformbuilder_setting_status') || !$this->config->get('module_ciformbuilder_setting_customer_record')) {
			return;
		}

		$this->load->language('extension/module/ciformbuilder_setting');

		$data['ciform_submission'] = $this->url->link('extension/ciformbuilder/form_submission', '', true);

		$html = '<h2>'. $this->language->get('text_my_ciform_submission') .'</h2>';
	    $html .= '  <ul class="list-unstyled">';
	    $html .= '    <li><a href="https://shop.newidolgroup.com/index.php?route=extension/ciformbuilder/form&page_form_id=1">填寫髮質皮膚問卷調查</a></li>';
	    $html .= '    <li><a href="'. $data['ciform_submission'] .'">'. $this->language->get('text_ciform_submission') .'</a></li>';
	    $html .= '  </ul>';

		$find = '<h2>'. $data['text_my_newsletter'] .'</h2>';

		$output = str_replace($find, $html ."\n". $find, $output);
	}
}