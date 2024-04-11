<?php
defined("BASEPATH") or exit("No direct script access allowed");

class CategoriesController extends CI_Controller
{
    public function __construct() {
        parent:: __construct();
        $this->load->model("CategoryModel");
    }
    public function process_add_category()
    {
		$name = $this->input->post("name", TRUE);
		$description = $this->input->post("description", TRUE);
        $uploaded_image = $_FILES["image"];
		/*$category_data = array(
			"category_name" => $name,
			"category_description" => $description,
			"uploaded_image" => $uploaded_image
		); */
        $response = array(
            "newCsrfToken" => $this->security->get_csrf_hash(),
            "message" => "success",
            "category_description" => $description,
            "uploaded_image" => $uploaded_image
        );
        echo json_encode($response);
    }
}
