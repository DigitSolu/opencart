<?php

class ControllerExtensionCiformbuilderFormPopup extends Controller {

	public function index() {

		$json = array();



		$this->load->language('extension/ciformbuilder/form');



		$this->load->model('extension/ciformbuilder/form');



		if (isset($this->request->post['form_id'])) {

			$page_form_id = (int)$this->request->post['form_id'];

		} else {

			$page_form_id = 0;

		}



		if (isset($this->request->post['product_id'])) {

			$data['product_id'] = $product_id = (int)$this->request->post['product_id'];

		} else {

			$data['product_id'] = $product_id = '';

		}



		$page_form_info = $this->model_extension_ciformbuilder_form->getPageForm($page_form_id);



		if ($page_form_info) {

			$data['page_form_id'] = $page_form_info['page_form_id'];

			$data['css'] = $page_form_info['css'];

			$data['reset_button'] = $page_form_info['reset_button'];





			if($page_form_info['popup_size'] == 'small') {

				$data['popup_size'] =  'modal-sm';

			} else if($page_form_info['popup_size'] == 'medium') {

				$data['popup_size'] =  'modal-md';

			} else if($page_form_info['popup_size'] == 'large') {

				$data['popup_size'] =  'modal-lg';

			} else {

				$data['popup_size'] =  'modal-md';

			}


			// Term Condition code start
			$termcondition = $page_form_info['termcondition'] ? json_decode($page_form_info['termcondition'],true) : array();

			$termcondition_status = isset($termcondition['status']) ? $termcondition['status'] : ''; 
			$this->load->model('catalog/information');

			$data['termcondition_text'] = '';
			if (isset($termcondition['information_id']) && $termcondition['information_id'] && $termcondition_status) {
				$information_info = $this->model_catalog_information->getInformation($termcondition['information_id']);
				$termcondition_text = isset($termcondition['desc'][$this->config->get('config_language_id')]) ? $termcondition['desc'][$this->config->get('config_language_id')] : '';
				if ($information_info) {
					$termcondition_text = str_replace('[information]','<a href="%s" target="_blank" class=""><b>%s</b></a>',$termcondition_text);
					$data['termcondition_text'] = sprintf($termcondition_text, $this->url->link('information/information', 'information_id=' . $termcondition['information_id'], true), $information_info['title']);
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


			$data['text_processing'] = $this->language->get('text_processing');

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





			// Page Form Options

			$data['page_form_options'] = $this->model_extension_ciformbuilder_form->getPageFormOptions($page_form_id);

			$data['country_exists'] = $this->model_extension_ciformbuilder_form->getPageFormOptionsCountry($page_form_id);



			$this->load->model('localisation/country');

			$data['countries'] = $this->model_localisation_country->getCountries();





			$this->load->model('localisation/zone');

			$data['zones'] = $this->model_localisation_zone->getZonesByCountryId($this->config->get('config_country_id'));



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


			if(!isset($this->request->get['route'])) {

				$this->request->get['route'] = 'common/home';

			}



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



			if(VERSION < '2.2.0.0') {

				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extension/ciformbuilder/page_oc2/form_popup.tpl')) {

					$json['html'] = $this->load->view($this->config->get('config_template') . '/template/extension/ciformbuilder/page_oc2/form_popup.tpl', $data);

				} else {

					$json['html'] = $this->load->view('default/template/extension/ciformbuilder/page_oc2/form_popup.tpl', $data);

				}

			} else if(VERSION <= '2.3.0.2') {

				$json['html'] = $this->load->view('extension/ciformbuilder/page_oc2/form_popup', $data);

			} else {

				$json['html'] = $this->load->view('extension/ciformbuilder/page_oc3/form_popup', $data);

			}

		} else{

			$json['redirect'] = $this->url->link('error/not_found');

		}



		$this->response->addHeader('Content-Type: application/json');

		$this->response->setOutput(json_encode($json));

	}

}