<?php
/*
	$Project: Advanced Filter $
	$Author: karapuz team <support@ka-station.com> $

	$Version: 1.0.1.2 $ ($Revision: 60 $)
*/
namespace extension\ka_extensions\ka_adv_filter;

class ControllerCategory extends \KaController {

	/* ajax call 
	
		It continues execution when 'continue=1' parameter is passed 
		otherwise it starts the rebuild from the beginning
		
	*/	
	public function rebuildFilters() {
	
		$json = array();
		$this->kamodel('category');

		if (empty($this->session->data['total_filter_categories']) || empty($this->request->get['continue'])) {
			$this->session->data['total_filter_categories'] = $this->kamodel_category->countCategories();
			$this->session->data['last_filter_category_id'] = 0;
			$this->session->data['filter_rebuild_stats']    = array(
				'categories_wo_filter' => 0,
				'categories_w_filter'  => 0,
			);
		}
		
		$this->session->data['last_filter_category_id'] = $this->kamodel_category->fillFilters($this->session->data['last_filter_category_id'], $this->session->data['filter_rebuild_stats']);
		
		if (empty($this->session->data['last_filter_category_id'])) {
			$json['complete_at'] = '100%';
			$json['result']      = 'end';
			$this->session->data['total_filter_categories'] = 0;
			
			$message = array(
				'Filters have been generated successfully.'
			);
			
			$message[] = 'Categories without filters: '  . $this->session->data['filter_rebuild_stats']['categories_wo_filter'];
			$message[] = 'Categories with filters: '  . $this->session->data['filter_rebuild_stats']['categories_w_filter'];
			
			$this->session->data['success'] = implode("<br />", $message);			
			
		} else {
			$categories_left = $this->kamodel_category->countCategories($this->session->data['last_filter_category_id']);
					
			$complete_at = floor(($this->session->data['total_filter_categories'] - $categories_left) / ($this->session->data['total_filter_categories'] / 100));
			$json['complete_at'] = $complete_at . '%';
			$json['result']      = 'continue';				
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
		
		return $json;
	}
}

class_alias(__NAMESPACE__ . '\ControllerCategory', 'ControllerExtensionKaExtensionsKaAdvFilterCategory');