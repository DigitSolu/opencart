<?php
/*
	$Project: Advanced Filter $
	$Author: karapuz team <support@ka-station.com> $

	$Version: 1.0.1.0 $ ($Revision: 34 $)
*/
namespace extension\ka_extensions\ka_adv_filter;

class ControllerPrice extends \KaController {

	/* ajax call 
	
		It continues execution when 'continue=1' parameter is passed 
		otherwise it starts the rebuild from the beginning
		
	*/	
	public function rebuildPrices() {
	
		$json = array();
		$this->kamodel('price_search');

		if (empty($this->session->data['total_price_products']) || empty($this->request->get['continue'])) {
			$this->session->data['total_price_products'] = $this->kamodel_price_search->countProducts();
			$this->session->data['last_price_product_id'] = 0;
			$this->session->data['rebuild_stats'] = array(
				'records_total'    => 0,
				'products_total'   => 0,
			);
		}
		
		$this->session->data['last_price_product_id'] = $this->kamodel_price_search->fillPriceSearch($this->session->data['last_price_product_id'], $this->session->data['rebuild_stats']);
		
		if (empty($this->session->data['last_price_product_id'])) { // on completion

			$json['complete_at'] = '100%';
			$json['result']      = 'end';
			$this->session->data['total_price_search'] = 0;
			
			$message = array(
				'Price ranges have been generated successfully.'
			);
			
			$message[] = 'Total records: '  . $this->session->data['rebuild_stats']['records_total'];
			$message[] = 'Total products: '  . $this->session->data['rebuild_stats']['products_total'];
			
			$this->session->data['success'] = implode("<br />", $message);			

			$this->kamodel_price_search->updateLastRebuild();
			
		} else {
			$products_left = $this->kamodel_price_search->countProducts($this->session->data['last_price_product_id']);
					
			$complete_at = floor(($this->session->data['total_price_products'] - $products_left) / ($this->session->data['total_price_products'] / 100));
			$json['complete_at'] = $complete_at . '%';
			$json['result']      = 'continue';				
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
		
		return $json;
	}
}

class_alias(__NAMESPACE__ . '\ControllerPrice', 'ControllerExtensionKaExtensionsKaAdvFilterPrice');