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
		$this->form_validation->set_rules("name", "Category Name", "required|trim");
		$this->form_validation->set_rules("description", "Category Description", "required|trim");
		if (empty($_FILES['image']['name'])) {
			$this->form_validation->set_rules('image', 'Image', 'required');
		}

		if ($this->form_validation->run() == FALSE) {
			$data['response'] = array(
				"newCsrfToken" => $this->security->get_csrf_hash(),
				"error" => array(
					"name_error" => form_error("name"),
					"description_error" => form_error("description"),
					'image_error' => form_error('image'),
					'status' => 'error'
				)
			);
			echo json_encode($data);
			return;
			/*NOTE: If user submit a complete form, check the submitted image*/
		} else {
			$name = $this->input->post("name", TRUE);
			$description = $this->input->post("description", TRUE);

			/*NOTE: Form's file input validation*/
			$config['upload_path'] = './assets/images/category_uploads/';
			$config['allowed_types'] = 'gif|jpg|png';
			$config['file_name'] = $name;
			$config['remove_spaces'] = TRUE;
			$config['max_size'] = 100;
			$config['max_width'] = 1024;
			$config['max_height'] = 768;
			$this->upload->initialize($config);

			if (!$this->upload->do_upload("image")) {
				$data['response'] = array(
					'newCsrfToken' => $this->security->get_csrf_hash(),
					'error' => array(
						'image_error' => $this->upload->display_errors()
					)
				);
				echo json_encode($data);
			} else {
				/*TODO: If image is valid, add it to database*/
				/*contains mage's data*/
				$upload_data = $this->upload->data();
				$image_url = 'assets/images/category_uploads/' . $upload_data['file_name'];

				$category_data = array(
					'name' => $name,
					'description' => $description,
					'image_url' => $image_url
				);
				/*Pass the category data to the model to add it in the database*/
				$data['result'] = $this->CategoryModel->add_user($category_data);
				if ($data['result'] == TRUE) {
					$data['response'] = array(
						"newCsrfToken" => $this->security->get_csrf_hash(),
						"status" => "success",
						'message' => 'Category added successfully!',
					);
					echo json_encode($data);
				}
			}
		}
    }

	public function fetch_category() {
		$data['category'] = $this->CategoryModel->fetch_category();
		echo json_encode($data);
	}
}
