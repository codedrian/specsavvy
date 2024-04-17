<?php defined("BASEPATH") or exit("No direct script access allowed.");

class ProductsController extends CI_Controller
{
	public function __construct() {
		parent:: __construct();
		$this->load->model('ProductModel');
	}
    public function process_add_product()
	{
		$product_data = array(
			'product_name' => $this->input->post('product_name', TRUE),
			'description' => $this->input->post('description', TRUE),
			'category' => $this->input->post('category', TRUE),
			'price' => $this->input->post('price', TRUE),
			'inventory' => $this->input->post('inventory', TRUE),
		);

		$this->form_validation->set_rules('product_name', 'Product name','required|trim');
		$this->form_validation->set_rules('description', 'Description', 'required|trim');
		$this->form_validation->set_rules('category', 'Category', 'required|trim');
		$this->form_validation->set_rules('price', 'Price', 'required|trim');
		$this->form_validation->set_rules('inventory', 'Inventory', 'required|trim');
		/*if (empty($_FILES['uploaded_image']['name'])) {
			$this->form_validation->set_rules('image', 'Image', 'required');
		}*/

		if ($this->form_validation->run() == FALSE) {
			$data['response'] = array(
				'newCsrfToken' => $this->security->get_csrf_hash(),
				'status' => 'error',
				'message' => 'Error submitting the product. Please try again.',
				'error' => array(
					'name' => form_error('product_name'),
					'description' => form_error('description'),
					'category' => form_error('category'),
					'price' => form_error('price'),
					'inventory' => form_error('inventory')
				)
			);
			echo json_encode($data);
			return;
		}
		/*Image configuration: Loop each image */
		$image_count = count($_FILES['uploadedImages']['name']);
		/*contains each image data*/
		$image_data = [];
		$image_error = [];
		for ($i=0; $i <$image_count; $i++ ) {
			$_FILES['uploadedImage']['name'] = $_FILES['uploadedImages']['name'][$i];
			$_FILES['uploadedImage']['type'] = $_FILES['uploadedImages']['type'][$i];
			$_FILES['uploadedImage']['size'] = $_FILES['uploadedImages']['size'][$i];
			$_FILES['uploadedImage']['tmp_name'] = $_FILES['uploadedImages']['tmp_name'][$i];
			$_FILES['uploadedImage']['error'] = $_FILES['uploadedImages']['error'][$i];
			/*NOTE: Next, validate it*/
			$upload_path = './assets/images/products/';
			if (!is_dir($upload_path)) {
				mkdir($upload_path, 0777, TRUE);
			}
			$config['upload_path'] = $upload_path;
			$config['allowed_types'] = 'gif|jpg|png';
			$config['file_name'] = $product_data['product_name'];
			$this->upload->initialize($config);
			/*If image is not valid*/
			if (!$this->upload->do_upload('uploadedImage')) {
				$image_error[] = $this->upload->display_errors();
			} else {
				/*If image is valid*/
				$image_data[] = $this->upload->data();
			}
		}

		/*If image_error[] is empty it means all image(s) is valid*/
		if (!empty($image_error)) {
			$data['response'] = array(
				'newCsrfToken' => $this->security->get_csrf_hash(),
				'status' => 'error',
				'message' => 'Failed to add the product. Please check the images.',
				'product_data' => $product_data,
				'image_error' => $image_error
			);
			echo json_encode($data);
			return;
		} else {
			/*TODO: Proceed to adding in database*/
			$data['response'] = array(
				'newCsrfToken' => $this->security->get_csrf_hash(),
				'status' => 'success',
				'message' => 'Product added successfully!',
				'product_data' => $product_data,
				'image_data' => $image_data
			);
			echo json_encode($data);
		}
	}
}
