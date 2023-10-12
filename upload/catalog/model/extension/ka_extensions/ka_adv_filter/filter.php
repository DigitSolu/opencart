<?php
namespace extension\ka_extensions\ka_adv_filter;

class ModelFilter extends \KaModel {

	/*
		This method fills in the data array for a template
	*/
	public function preparePriceSearch($category_id, $with_subcategories, &$data) {

		$model_price_search = $this->load->kamodel('extension/ka_extensions/ka_adv_filter/price_search');
	
		if (!$model_price_search->isPriceSearchShown()) {
			return false;			
		}
		
		$show_price_search = true;

		if ($this->config->get('config_tax') == '1') {
			$data['is_price_range_wo_taxes'] = $model_price_search->isTaxNotUsed();
		}
		
		$this->load->language('extension/ka_extensions/ka_adv_filter/common');
		$data['text_from']  = $this->language->get('text_from');
		$data['text_to']    = $this->language->get('text_to');
		$data['text_price'] = $this->language->get('text_price');
		
		list($min_price, $max_price) = $model_price_search->getPriceRange($category_id, $with_subcategories, $this->session->data['currency']);
		
		$currency = $this->config->get('config_currency');
		if ($currency != $this->session->data['currency']) {
			$min_price = $this->currency->convert($min_price, $currency, $this->session->data['currency']);
			$max_price = $this->currency->convert($max_price, $currency, $this->session->data['currency']);
		}

		$min_price = floor($min_price);
		$max_price = ceil($max_price);
		
		$price_range = array(
			'from' => $min_price,
			'to'   => $max_price
		);
		
		$is_any = false;
		if (!empty($this->request->get['filter_price'])) {
			list($price_range['from'], $price_range['to']) = explode('-', $this->request->get['filter_price']);
			if (is_numeric($price_range['to'])) {
				if ($price_range['from'] >= $price_range['to']) {
					$price_range['to'] += 10;
				}
			}
		} else {
			$is_any = true;
		}

		$data['ka_min_price'] = $min_price;
		$data['ka_max_price'] = max($max_price, $price_range['to']);
		
		$data['ka_price_from'] = $price_range['from'];
		$data['ka_price_to']   = $price_range['to'];
		
		$data['ka_price_intervals'] = $model_price_search->getPriceIntervals();
		
		if (!empty($data['ka_price_intervals'])) {
			if ($is_any) {
				$data['ka_price_intervals'][0]['selected'] = true;
			} else {
			
				foreach ($data['ka_price_intervals'] as &$dpi) {
					if ($dpi['from'] == $price_range['from'] && $dpi['to'] == $price_range['to']) {
						$dpi['selected'] = true;
					} 
				}
			}
		}
		
		$data['ka_price_range_look'] = $this->config->get('ka_adv_filter_price_range_look');
		$data['ka_price_intervals_layout'] = $this->config->get('ka_adv_filter_price_intervals_layout');
		$data['ka_price_step']             = $this->config->get('ka_adv_filter_price_step');
		
		if (in_array($data['ka_price_range_look'], array('intervals', 'ruler_and_intervals'))) {
			if ($data['ka_price_intervals_layout'] == 'radio') {
				$data['show_radio'] = true;
				$data['show_select'] = false;						
			} else {
				$data['show_radio'] = false;
				$data['show_select'] = true;
			}
		} else {
			$data['show_radio'] = false;
			$data['show_select'] = false;
		}			
		
		if (in_array($data['ka_price_range_look'], array('ruler', 'ruler_and_intervals'))) {
			$data['show_ruler'] = true;
		}

		if ($show_price_search) {
			$data['show_price_search'] = true;
			$this->document->addScript('catalog/view/javascript/nouislider/nouislider.js');
			$this->document->addStyle('catalog/view/javascript/nouislider/nouislider.css');
		}
		
		return $show_price_search;
	}
	

	/*
	
		This method is called when the filter section is on the catalog/search page. It works differently
		from category pages that is why we have to move the code here
		
	*/
	public function searchPage() {
		$data = array();
		$kamodel_ka_adv_filters_common = $this->load->kamodel('extension/ka_extensions/ka_adv_filter/common');
		$data['categories'] = $kamodel_ka_adv_filters_common->getCategories();
		
		$this->load->language('extension/module/filter');

		$url = '';
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		if (isset($this->request->get['limit'])) {
			$url .= '&limit=' . $this->request->get['limit'];
		}
		
		$category_id  = 0;
		
		$is_with_subcategories = $kamodel_ka_adv_filters_common->isWithSubcategories();

		if ($is_with_subcategories) {
			$sub_category = 1;
			$data['is_with_subcategories'] = 1;
		} else {
			$sub_category = '';
		}
		
		if (!empty($this->request->get['category_id'])) {
			$category_info = $this->model_catalog_category->getCategory($this->request->get['category_id']);
			if (!empty($category_info['category_id'])) {
				$category_id = $category_info['category_id'];
			}
			
			if (isset($this->request->get['sub_category'])) {
				$sub_category = $this->request->get['sub_category'];
			}
		}
		
		$data['sub_category']  = $sub_category;

		if (!empty($this->request->get['search'])) {
			$url .= '&search=' . urlencode(html_entity_decode($this->request->get['search'], ENT_QUOTES, 'UTF-8'));
		}

		$data['action'] = str_replace('&amp;', '&', $this->url->link('product/search', $url));
		
		$this->load->model('catalog/category');

		// show category filters when the category is specified for the page
		//
		if (!empty($category_id)) {

			if (isset($this->request->get['filter'])) {
				$data['filter_category'] = explode(',', $this->request->get['filter']);
			} else {
				$data['filter_category'] = array();
			}

			// exclude pattern matches
			$data[ 'filter_groups' ]  =  array( ) ;
			$filter_groups  =  $this->model_catalog_category->getCategoryFilters( $category_id );
			
			$show_price_search = $this->preparePriceSearch($category_id, $is_with_subcategories, $data);
			
			if ( $filter_groups || $show_price_search ) {
				foreach ($filter_groups as $filter_group) {
					$childen_data = array();

					foreach ($filter_group['filter'] as $filter) {
						$filter_data = array(
							'filter_category_id' => $category_id,
							'filter_filter'      => $filter['filter_id']
						);

						if (!empty($filter['image'])) {
							$image = $this->model_tool_image->resize($filter['image'], 48, 48);
						} else {
							$image = $this->model_tool_image->resize('no_image.png', 48, 48);
						}
						
						$childen_data[] = array(
							'filter_id' => $filter['filter_id'],
							'image'     => $image,
							'name'      => $filter['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data) . ')' : '')
						);
					}

					$data['filter_groups'][] = array(
						'filter_group_id' => $filter_group['filter_group_id'],
						'show_as_image'   => !empty($filter_group['show_as_image']),
						'name'            => $filter_group['name'],
						'filter'          => $childen_data
					);
				}
			}
		} else {
			$show_price_search = $this->preparePriceSearch(0, $is_with_subcategories, $data);
		}
		$data['category_id'] = $category_id;
		
		$data['category_filter'] = $this->load->view('extension/ka_extensions/ka_adv_filter/category_filter', $data);
		
		return $this->load->view('extension/module/filter', $data);		
	}
}

class_alias(__NAMESPACE__ . '\ModelFilter', 'ModelExtensionKaExtensionsKaAdvFilterFilter');