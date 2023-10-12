<?php
/*
	$Project: Advanced Filter $
	$Author: karapuz team <support@ka-station.com> $

	$Version: 1.0.1.2 $ ($Revision: 60 $)
*/

// this namespace must be used for all tasks
namespace extension\ka_extensions\tasks;

// any task class consists of "Model" and "task class (similar to the file name)"
// other formats are not allowed
//
class ModelFilters extends \KaModel {

	/*
		$operation - operation name (string)
		$params    - array of parameters
		$stat      - array of returned strings for displaying results of the operation
		
		RETURNS:
			$return - text code
				'finished'      - operation is complete
				'not_finished'  - still working (additional calls needed)
	*/
	public function runSchedulerOperation($operation, $params, &$stat) {
	
		$time_start = time();			
		$return_code = 'finished';

		$model_product      = $this->load->kamodel('extension/ka_extensions/ka_adv_filter/product');
		$model_price_search = $this->load->kamodel('extension/ka_extensions/ka_adv_filter/price_search');
		$model_category     = $this->load->kamodel('extension/ka_extensions/ka_adv_filter/category');		
		
		// initialize the task data on the first call
		//
		if (empty($stat)) {
		
			$this->session->data['last_filter_category_id'] = 0;
			
			$this->session->data['last_task_product_id']  = 0;
			$this->session->data['last_task_category_id'] = 0;
		
			$stat = array(
				'price_records_total'  => 0, 
				'price_products_total' => 0,
				
				'products_wo_filter'   => 0,
				'products_w_filter'    => 0,
				
				'categories_wo_filter' => 0,
				'categories_w_filter'  => 0,
			);
			
			$stat['products_total']   = $model_product->countProducts();
			$stat['categories_total'] = $model_category->countCategories();
			
			// define the first stage to process
			$this->session->data['stage'] = 'price_rebuild';
		}
		
		//
		// stage 1: price rebuild
		//		
		if ($this->session->data['stage'] == 'price_rebuild') {
		
			while ($this->session->data['stage'] == 'price_rebuild') {

				$_stat = array(
					'records_total'  => 0,
					'products_total' => 0,
				);
				$this->session->data['last_task_product_id'] = $model_price_search->fillPriceSearch($this->session->data['last_task_product_id'], $_stat);
				
				$stat['price_records_total']  += $_stat['records_total'];
				$stat['price_products_total'] += $_stat['products_total'];
				
				if (empty($this->session->data['last_task_product_id'])) {
					$this->session->data['last_task_product_id'] = 0;
					$this->session->data['stage'] = 'filters_to_products';
					$model_price_search->updateLastRebuild();
				}
				
				if (time() - $time_start > 15) {
					$return_code = 'not_finished';
					return $return_code;
				}
			}			
		}
			
		
		// 
		// stage 2: adding filters to products from option data
		//
		if ($this->session->data['stage'] == 'filters_to_products') {
		
			while ($this->session->data['stage'] == 'filters_to_products') {
			
				$this->session->data['last_task_product_id'] = $model_product->fillFilters($this->session->data['last_task_product_id'], $stat);
				
				if (empty($this->session->data['last_task_product_id'])) {
					$this->session->data['last_task_product_id'] = 0;
					$this->session->data['stage'] = 'filters_to_categories';
				}
				
				if (time() - $time_start > 15) {
					$return_code = 'not_finished';
					return $return_code;
				}
			} 
		}
		
		// 
		// stage 3: adding filters to categoires from product data
		//
		if ($this->session->data['stage'] == 'filters_to_categories') {
		
			while ($this->session->data['stage'] == 'filters_to_categories') {

				$this->session->data['last_task_category_id'] = $model_category->fillFilters($this->session->data['last_task_category_id'], $stat);

				if (empty($this->session->data['last_task_category_id'])) {
					$this->session->data['last_task_category_id'] = 0;
					$this->session->data['stage'] = '';
				}
				
				if (time() - $time_start > 15) {
					$return_code = 'not_finished';
					return $return_code;
				}
			}
		}
		
		return $return_code;
	}
}

// you have to specify a full class name alias for the short class name
//
class_alias(__NAMESPACE__ . '\ModelFilters', 'ModelExtensionKaExtensionsTasksFilters');