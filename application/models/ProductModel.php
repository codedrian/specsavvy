<?php defined("BASEPATH") or exit("No direct script access allowed.");

class ProductModel extends CI_Model {

	/*TODO: Add the datas to the PRODUCT table and add the products product id and image urls to the image table*/
	public function add_product($product_data, $image_data) {
		$sql = 'INSERT INTO `products`(`name`, `description`, `price`, `category_id`, `stock_level`) VALUES(?,?,?,?,?)';
		$this->db->query($sql, array($product_data['product_name'], $product_data['description'], $product_data['price'], $product_data['category'], $product_data['inventory']));
		/*Check if it is successfully inserted*/
		if ($this->db->affected_rows() > 0) {
			return array(
				'is_upload_successful' => TRUE,
 				'product_id' => $this->db->insert_id()
			);
		} else {
			return array(
				'is_upload_successful' => FALSE
			);
		}

	}
}
