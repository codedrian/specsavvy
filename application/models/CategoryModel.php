<?php
defined("BASEPATH") or exit("No direct script access allowed");

class CategoryModel extends CI_Model
{
    public function add_user($category_data)
    {
        $sql = "INSERT INTO `category` (`name`, `description`, `image_url`) VALUES (?, ?, ?)";
		$this->db->query($sql, array($category_data['name'], $category_data['description'], $category_data['image_url']));
		error_log("Error in add_user: " .json_encode($this->db->error()));

		if ($this->db->affected_rows() > 0) {
			return  TRUE/* $this->db->insert_id() */;
		} else {
			return FALSE;
		}
    }
}
