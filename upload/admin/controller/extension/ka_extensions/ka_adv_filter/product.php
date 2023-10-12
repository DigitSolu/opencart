<?php
/*
	$Project: Advanced Filter $
	$Author: karapuz team <support@ka-station.com> $

	$Version: 1.0.1.2 $ ($Revision: 60 $)
*/
namespace extension\ka_extensions\ka_adv_filter;

class ControllerProduct extends \KaController {

	/* ajax call 
	
		It continues execution when 'continue=1' parameter is passed 
		otherwise it starts the rebuild from the beginning
		
	*/	
	public function rebuildFilters() {
	
		$json = array();
		$this->kamodel('product');

		if (empty($this->session->data['total_filter_products']) || empty($this->request->get['continue'])) {
			$this->session->data['total_filter_products'] = $this->kamodel_product->countProducts();
			$this->session->data['last_filter_product_id'] = 0;
			$this->session->data['filter_rebuild_stats']    = array(
				'products_wo_filter' => 0,
				'products_w_filter'  => 0,
			);
		}
		
		$this->session->data['last_filter_product_id'] = $this->kamodel_product->fillFilters($this->session->data['last_filter_product_id'], $this->session->data['filter_rebuild_stats']);
		
		if (empty($this->session->data['last_filter_product_id'])) {
			$json['complete_at'] = '100%';
			$json['result']      = 'end';
			$this->session->data['total_filter_products'] = 0;
			
			$message = array(
				'Filters have been generated successfully.'
			);
			
			$message[] = 'Products without filters: '  . $this->session->data['filter_rebuild_stats']['products_wo_filter'];
			$message[] = 'Products with filters: '  . $this->session->data['filter_rebuild_stats']['products_w_filter'];
			
			$this->session->data['success'] = implode("<br />", $message);			
			
		} else {
			$products_left = $this->kamodel_product->countProducts($this->session->data['last_filter_product_id']);
					
			$complete_at = floor(($this->session->data['total_filter_products'] - $products_left) / ($this->session->data['total_filter_products'] / 100));
			$json['complete_at'] = $complete_at . '%';
			$json['result']      = 'continue';				
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
		
		return $json;
	}
}

class_alias(__NAMESPACE__ . '\ControllerProduct', 'ControllerExtensionKaExtensionsKaAdvFilterProduct');