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
		/*NOTE: Form validation*/
		$this->form_validation->set_rules("name", "Category Name", "required|trim|min_length[5]");
		$this->form_validation->set_rules("description", "Category Description", "required|trim");
		if (empty($_FILES['image']['name'])) {
			$this->form_validation->set_rules('image', 'Image', 'required');
		}
		if ($this->form_validation->run() == FALSE) {
			$response = array(
				"newCsrfToken" => $this->security->get_csrf_hash(),
				"error" => array(
					"name_error" => form_error("name"),
					"description_error" => form_error("description"),
					'image_error' => form_error('image')
				)
			);
			echo json_encode($response);
			return;
			/*NOTE: If user submit a complete form, check the submitted image*/
		} else {
			/*NOTE: Form's file input validation*/
			$config['upload_path']          = './assets/images/category_uploads/';
			$config['allowed_types']        = 'gif|jpg|png';
			$config['max_size']             = 100;
			$config['max_width']            = 1024;
			$config['max_height']           = 768;
			$this->upload->initialize($config);
			$name = $this->input->post("name", TRUE);
			$description = $this->input->post("description", TRUE);

			if (!$this->upload->do_upload("image")) {
				$response = array(
					'newCsrfToken' => $this->security->get_csrf_hash(),
					'image_error' => $this->upload->display_errors()
				);
				echo json_encode($response);
			} else {
				/*TODO: If image is valid, add it to database*/
				$response = array(
					"newCsrfToken" => $this->security->get_csrf_hash(),
					"status" => "success",
					'upload' => $this->upload->data()
				);
				echo json_encode($response);

			}
		}
    }
}
