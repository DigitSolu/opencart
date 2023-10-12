<?php
class ModelExtensionModuleDropzone extends Model {
	public function updateImages($product_id, $folder) {
		// Image
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product WHERE product_id = '" . (int)$product_id . "' AND image LIKE 'catalog/products/" . $this->db->escape($folder) . "/%' LIMIT 1");

		if ($query->num_rows) {
			$this->db->query("UPDATE " . DB_PREFIX . "product SET image = '" . $this->db->escape(str_replace($folder, $product_id, $query->row['image'])) . "' WHERE product_id = '" . (int)$product_id . "'");
		}

		// Additional images
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "' AND image LIKE 'catalog/products/" . $this->db->escape($folder) . "/%'");

		foreach ($query->rows as $row) {
			$this->db->query("UPDATE " . DB_PREFIX . "product_image SET image = '" . $this->db->escape(str_replace($folder, $product_id, $row['image'])) . "' WHERE product_image_id = '" . (int)$row['product_image_id'] . "'");
		}
	}
}