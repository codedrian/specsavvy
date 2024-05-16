<?php defined('BASEPATH') or exit('No direct script access allowed.');

class ProductModel extends CI_Model {

	public function add_product($product_data)
	{
		$sql = 'INSERT INTO `product`(`name`, `description`, `price`, `category_id`, `stock_level`) VALUES(?,?,?,?,?)';
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

	public function add_image($product_id, $url)
	{
		$sql = 'INSERT INTO `product_image`(`product_id`, `image_url`) VALUES(?, ?)';
		$this->db->query($sql, array($product_id, $url));

		if ($this->db->affected_rows() > 0) {
			return array(
				'is_upload-successful' => TRUE
			);
		} else {
			return array(
				'is_upload-successful' => FALSE
			);
		}
	}

	public function fetch_all_product() {
		$query = $this->db->query('SELECT
											p.product_id,
											p.name,
											p.price,
											p.description,
											p.stock_level,
											c.name AS category_name,
											(SELECT
												i.image_url
											FROM
												product_image i
											WHERE
												i.product_id = p.product_id
											LIMIT 1) AS image_url
										FROM
											product p
										INNER JOIN category c ON
											p.category_id = c.category_id;');
		/*TODO:If theres result, return the query result*/
		if($query) {
			return $query->result_array();
		} else {
			return null;
		}
	}
	public function fetch_single_product($productId) {
			$sql = 'SELECT
				p.product_id,
				p.name,
				p.price,
				p.description,
				p.stock_level,
				c.name AS category_name,
				(
				SELECT
					i.image_url
				FROM
					product_image i
				WHERE
					i.product_id = p.product_id
				LIMIT 1
			) AS image_url
			FROM
				product p
			INNER JOIN category c ON
				p.category_id = c.category_id
			WHERE p.product_id = ?';
		$query = $this->db->query($sql, array($productId));

		if($query) {
			return $query->result_array();
		} else {
			show_404();
		}
	}
	public function fetch_image($productId) {
		$sql = 'SELECT `image_url` FROM `product_image` WHERE `product_id` = ?';
		$query = $this->db->query($sql, array($productId));

		if ($query) {
			return $query->result_array();
		} else {
			show_404();
		}
	}
	public function process_product_add_to_cart($cartData) {
		$sql = 'UPDATE `cart` SET  `quantity` = `quantity` + ? WHERE `customer_id` = ? AND `product_id` = ?';
		$query = $this->db->query($sql, array($cartData['quantity'], $cartData['customer_id'], $cartData['product_id']));

		if ($this->db->affected_rows() == 0) {
			$sql = 'INSERT INTO `cart`(`customer_id`, `product_id`, `quantity`) VALUES(?,?,?)';
			$query = $this->db->query($sql, array($cartData['customer_id'], $cartData['product_id'], $cartData['quantity']));
			if ($this->db->affected_rows() == 0) {
				return array(
					'is_submitted_successfully' => TRUE
				);
			}
		} else {
			return array(
				'is_submitted_successfully' => TRUE
			);
		}
	}
	public function getCartProductCount($customer_id) {
		$sql = "SELECT COUNT(product_id) AS `total_product` FROM `cart` WHERE `customer_id` = ?";
		$query = $this->db->query($sql, array($customer_id));

		if ($query) {
			return $query->result_array();
		} else {
			return null;
		}
	}
	public function getCartProducts($customer_id) {
		$sql = "SELECT
					cart_id,
					cus.first_name,
					cus.last_name,
					p.product_id,
					p.name,
					p.price,
					(SELECT 
					     i.image_url 
					 FROM 
					     product_image i
					 WHERE i.product_id = p.product_id
					 LIMIT 1) AS `image_url`,
					SUM(IFNULL(c.quantity, 0)) AS `quantity`,
					(p.price * quantity) AS total_amount
				FROM
					customer cus
				INNER JOIN cart c ON
					cus.customer_id = c.customer_id
				INNER JOIN product p ON
					p.product_id = c.product_id
				WHERE cus.customer_id = ?
				GROUP BY p.product_id ";

		$query = $this->db->query($sql, array($customer_id));
		if ($query) {
			return $query->result_array();
		} else {
			return null;
		}
	}
	/*NOTE: UPDATE and SET the quantity in cart table*/
	public function modifyQuantity($quantityInput) {
		$sql = "UPDATE `cart` SET quantity = ? WHERE `cart_id` = ?";
		$query = $this->db->query($sql, array($quantityInput['quantity'], $quantityInput['cart_id']));

		if ($this->db->affected_rows() > 0) {
			return array(
				'is_updated_successfully' => TRUE,
				'newCsrfToken' => $this->security->get_csrf_hash()
			);
		}
		else {
			return array(
				'is_updated_successfully' => FALSE
			);
		}
	}
}
