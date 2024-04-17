<?php defined("BASEPATH") or exit("No direct script access allowed.");

class ProductsController extends CI_Controller
{
	public function __construct() {
		parent:: __construct();
		$this->load->model('ProductModel');
	}
    public function process_add_product()
	{
		$data['response'] = array(
			'newCsrfToken' => $this->security->get_csrf_hash(),
			'status' => 'success',
			'message' => 'Product added successfully!'
		);
		echo json_encode($data);
	}
}
