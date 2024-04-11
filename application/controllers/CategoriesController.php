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
        $response = array(
            "message" => "Added category successfully!",
            "csrfName" => $this->security->get_csrf_token_name(),
            "newCsrfToken" => $this->security->get_csrf_hash()
        );
        echo json_encode($response);
    }
}
