<?php

class ControllerExtensionCiformbuilderInformationForm extends Controller {

	public function index() {

		$this->load->language('extension/ciformbuilder/form');

		$this->load->model('extension/ciformbuilder/form');

		$data['text_select'] = $this->language->get('text_select');

		if (isset($this->request->get['information_id'])) {
			$information_id = (int)$this->request->get['information_id'];
		} else {
			$information_id = 0;
		}

		$page_form_info = $this->model_extension_ciformbuilder_form->getPageFormByInformation($information_id);

		if($page_form_info) {
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

			$data['page_form_id'] = $page_form_info['page_form_id'];

			$data['css'] = $page_form_info['css'];

			$data['reset_button'] = $page_form_info['reset_button'];



			$data['text_select'] = $this->language->get('text_select');

			$data['button_upload'] = $this->language->get('button_upload');

			$data['text_loading'] = $this->language->get('text_loading');

			$data['text_none'] = $this->language->get('text_none');

			$data['button_reset'] = $this->language->get('button_reset');



			$data['heading_title'] = $page_form_info['title'];



			$data['description'] = html_entity_decode($page_form_info['description'], ENT_QUOTES, 'UTF-8');

			$data['bottom_description'] = html_entity_decode($page_form_info['bottom_description'], ENT_QUOTES, 'UTF-8');



			$data['fieldset_title'] = $page_form_info['fieldset_title'];

			$data['button_continue'] = ($page_form_info['submit_button']) ? $page_form_info['submit_button'] :  $this->language->get('button_continue');


			// Term Condition code start
			$termcondition = $page_form_info['termcondition'] ? json_decode($page_form_info['termcondition'],true) : array();

			$termcondition_status = isset($termcondition['status']) ? $termcondition['status'] : ''; 
			$this->load->model('catalog/information');

			$data['termcondition_text'] = '';
			if (isset($termcondition['information_id']) && $termcondition['information_id'] && $termcondition_status) {
				$information_info = $this->model_catalog_information->getInformation($termcondition['information_id']);
				$termcondition_text = isset($termcondition['desc'][$this->config->get('config_language_id')]) ? $termcondition['desc'][$this->config->get('config_language_id')] : '';
				if ($information_info) {
					$termcondition_text = str_replace('[information]','<a href="%s" class="agree"><b>%s</b></a>',$termcondition_text);
					$data['termcondition_text'] = sprintf($termcondition_text, $this->url->link('information/information/agree', 'information_id=' . $termcondition['information_id'], true), $information_info['title']);
				} 
			}
			// Term Condition code end

			// Google Analytic code start
			$google_analytic = $page_form_info['google_analytic'] ? json_decode($page_form_info['google_analytic'],true) : array();
			$google_analytic_status = isset($google_analytic['status']) ? $google_analytic['status'] : ''; 
			$data['google_analytic'] = '';
			if($google_analytic_status) {
				$data['google_analytic'] = isset($google_analytic['code']) ? $google_analytic['code'] : ''; 
			}
			// Google Analytic code end


			// Page Form Options

			$data['page_form_options'] = $this->model_extension_ciformbuilder_form->getPageFormOptions($page_form_info['page_form_id']);

			$data['country_exists'] = $this->model_extension_ciformbuilder_form->getPageFormOptionsCountry($page_form_info['page_form_id']);



			$this->load->model('localisation/country');

			$data['countries'] = $this->model_localisation_country->getCountries();





			$this->load->model('localisation/zone');

			$data['zones'] = $this->model_localisation_zone->getZonesByCountryId($this->config->get('config_country_id'));



			// Captcha

			if(VERSION >= '3.0.0.0') {

				if ($this->config->get('captcha_' . $this->config->get('config_captcha') . '_status') && $page_form_info['captcha']) {
					$old_route = $this->request->get['route'];
					$this->request->get['route'] = 'extension/ciformbuilder/form/add';

					$data['captcha'] = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha'));

					$this->request->get['route'] = $old_route;
				} else {

					$data['captcha'] = '';

				}

			} else {

				if ($this->config->get($this->config->get('config_captcha') . '_status') && $page_form_info['captcha']) {

					if (VERSION <= '2.2.0.0') {

						$data['captcha'] = $this->load->controller('captcha/' . $this->config->get('config_captcha'));

					} else {

						$data['captcha'] = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha'));

					}



				} else {

					$data['captcha'] = '';

				}

			}



			if($this->config->get('theme_default_directory')) {

				$data['theme_name'] = $this->config->get('theme_default_directory');

			} else if($this->config->get('config_template')) {

				$data['theme_name'] = $this->config->get('config_template');

			} else{

				$data['theme_name'] = 'default';

			}



			if(empty($data['theme_name'])) {

				$data['theme_name'] = 'default';

			}

			$data['include_fields_file'] = $this->load->controller('extension/ciformbuilder/form/formfields', $data);

			$data['column_left'] = $this->load->controller('common/column_left');

			$data['column_right'] = $this->load->controller('common/column_right');



			if(VERSION < '2.2.0.0') {

				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extension/ciformbuilder/page_oc2/information_form.tpl')) {

					return $this->load->view($this->config->get('config_template') . '/template/extension/ciformbuilder/page_oc2/information_form.tpl', $data);

				} else {

					return $this->load->view('default/template/extension/ciformbuilder/page_oc2/information_form.tpl', $data);

				}

			} else if(VERSION <= '2.3.0.2') {

				return $this->load->view('extension/ciformbuilder/page_oc2/information_form', $data);

			} else {

				return $this->load->view('extension/ciformbuilder/page_oc3/information_form', $data);

			}

		}

	}

}