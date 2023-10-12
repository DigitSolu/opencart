<?php
class ControllerExtensionModuleFilter extends Controller {
	public function index() {
		if (isset($this->request->get['path'])) {
			$parts = explode('_', (string)$this->request->get['path']);
		} else {
			$parts = array();
		}

		$category_id = end($parts);
//karapuz (Advanced Filter) 
	
		if (class_exists('\KaGlobal') && \KaGlobal::isKaInstalled('ka_adv_filter')) {
			$this->load->language('extension/ka_extensions/ka_adv_filter/common');
			// When we are on catalog/search page, we will use our custom controller method for preparing the data
			//
			if (empty($category_id)) {
				$model_adv_filter_filter = $this->load->kamodel('extension/ka_extensions/ka_adv_filter/filter');
				$result = $model_adv_filter_filter->searchPage();
				return $result;
			}
		}
///karapuz (Advanced Filter)

		$this->load->model('catalog/category');

		$category_info = $this->model_catalog_category->getCategory($category_id);

		if ($category_info) {
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

			$data['action'] = str_replace('&amp;', '&', $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url));

			if (isset($this->request->get['filter'])) {
				$data['filter_category'] = explode(',', $this->request->get['filter']);
			} else {
				$data['filter_category'] = array();
			}

			$this->load->model('catalog/product');

			$data['filter_groups'] = array();

			$filter_groups = $this->model_catalog_category->getCategoryFilters($category_id);
//karapuz (Advanced Filter) 
			$show_price_search = false;
			if (class_exists('\KaGlobal') && \KaGlobal::isKaInstalled('ka_adv_filter')) {
				$model_adv_filter_filter = $this->load->kamodel('extension/ka_extensions/ka_adv_filter/filter');
				$kamodel_ka_adv_filters_common = $this->load->kamodel('extension/ka_extensions/ka_adv_filter/common');
				
				$is_with_subcategories = $kamodel_ka_adv_filters_common->isWithSubcategories();				
				$show_price_search = $model_adv_filter_filter->preparePriceSearch($category_id, $is_with_subcategories, $data);
			}
///karapuz (Advanced Filter)

			if ($filter_groups || $show_price_search) {
				foreach ($filter_groups as $filter_group) {
					$childen_data = array();

					foreach ($filter_group['filter'] as $filter) {
						$filter_data = array(
							'filter_category_id' => $category_id,
							'filter_filter'      => $filter['filter_id']
						);

//karapuz (Advanced Filter) 
						if (!empty($filter['image'])) {
							$image = $this->model_tool_image->resize($filter['image'], 48, 48);
						} else {
							$image = $this->model_tool_image->resize('no_image.png', 48, 48);
						}
///karapuz (Advanced Filter)
						$childen_data[] = array(
//karapuz (Advanced Filter) 
							'image'     => $image,
///karapuz (Advanced Filter)
							'filter_id' => $filter['filter_id'],
							'name'      => $filter['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data) . ')' : '')
						);
					}

					$data['filter_groups'][] = array(
//karapuz (Advanced Filter) 
						'show_as_image'   => !empty($filter_group['show_as_image']),
///karapuz (Advanced Filter)
						'filter_group_id' => $filter_group['filter_group_id'],
						'name'            => $filter_group['name'],
						'filter'          => $childen_data
					);
				}

				return $this->load->view('extension/module/filter', $data);
			}
		}
	}
}