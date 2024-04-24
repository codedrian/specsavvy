<?php defined('BASEPATH') or exit('No direct script access allowed.');

class CustomerModel extends CI_Model
{
	public function create_customer($signupInput) {
		$hashed_password = password_hash($signupInput['password'], PASSWORD_DEFAULT);
		$sql = 'INSERT INTO `customer`(`first_name`, `last_name`, `email`, `password`) VALUES(?,?,?,?)';
		$this->db->query($sql, array(
			$signupInput['first_name'],
			$signupInput['last_name'],
			$signupInput['email'],
			$hashed_password
		));

		if ($this->db->affected_rows() > 0) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	public function process_login_form($loginInput) {
		$email = $loginInput['email_address'];
		$password = $loginInput['password'];

		$sql = "SELECT * FROM `customer` WHERE `email` = ?";
		$result = $this->db->query($sql, array($email));

		if ($result && $result->num_rows() > 0) {
			$row = $result->row_array();
			/*NOTE: Check if the returned password is matched to the user's submitted password;*/
			if (password_verify($password, $row['password'])) {
				return array(
					'result' => TRUE,
					'id' => $row['customer_id'],
					'first_name' => $row['first_name']
				);
			}
		}
		return array(
			'result' => FALSE
		);
	}
}

