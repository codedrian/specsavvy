<?php defined("BASEPATH") or exit("No direct script access allowed.");

class ProductsController extends CI_Controller
{
	public function __construct() {
		parent:: __construct();
		$this->load->model('ProductModel');
	}
    public function process_add_product()
	{
		/*Note: This array contains the form inputs*/
		$product_form_input = array(
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
		} else {
			/*comment: If form input is valid add it to database*/
			$data['result'] = $this->ProductModel->add_product($product_form_input);
			/*NOTE: This variable returns the product id*/
			$product_id = $data['result']['product_id'];

			if ($data['result']['is_upload_successful']) {
				/*Note:Image configuration: Loop each image */
				$image_count = count($_FILES['uploadedImages']['name']);
				/*contains each image data*/
				$image_url = [];
				$image_error = [];
				/*Check if each image*/
				for ($i=0; $i <$image_count; $i++ ) {
					$_FILES['uploadedImage']['name'] = $_FILES['uploadedImages']['name'][$i];
					$_FILES['uploadedImage']['type'] = $_FILES['uploadedImages']['type'][$i];
					$_FILES['uploadedImage']['size'] = $_FILES['uploadedImages']['size'][$i];
					$_FILES['uploadedImage']['tmp_name'] = $_FILES['uploadedImages']['tmp_name'][$i];
					$_FILES['uploadedImage']['error'] = $_FILES['uploadedImages']['error'][$i];
					$upload_path = './assets/images/products/';
					/*Note: If forectory is not found, create it*/
					if (!is_dir($upload_path)) {
						mkdir($upload_path, 0777, TRUE);
					}
					/*NOTE: Next, validate it*/
					$config['upload_path'] = $upload_path;
					$config['allowed_types'] = 'gif|jpg|png';
					$config['file_name'] = $product_form_input['product_name'];
					$this->upload->initialize($config);
					/*If image is not valid*/
					if (!$this->upload->do_upload('uploadedImage')) {
						$image_error[] = $this->upload->display_errors();
					} else {
						/*If image is valid*/
						$image_file_name = $this->upload->data('file_name');
						$image_url[] = $upload_path . $image_file_name;
					}
				}
				/*Comment: After validation, loop in the image url array and add it individually to the database*/
				foreach($image_url as $url) {
					$data['result'] = $this->ProductModel->add_image($product_id, $url);
				}
				$data['response'] = array(
					'newCsrfToken' => $this->security->get_csrf_hash(),
					'status' => 'success',
					'message' => 'Product added successfully!',
					'form_input' => $product_form_input,
					'image_url' => $image_url
				);
				echo json_encode($data);
			}
		}
	}
	public function fetch_all_product() {
		$data['products'] = $this->ProductModel->fetch_all_product();
		echo json_encode($data);
	}
	public function view_product_details($productId) {
		$data['productId'] = $productId;
		$data['customerId'] = $this->session->userdata('customer_id');
		$this->load->view('customer/product_view', $data);
	}
	public function fetch_product_details($productId) {
		$data['productData'] = $this->ProductModel->fetch_single_product($productId);
		echo json_encode($data);
		exit();
		/*TODO: delete exit*/
	}
	public function fetch_product_image($productId) {
		$data['images'] = $this->ProductModel->fetch_image($productId);
		echo json_encode($data);
		exit();
	}
	public function process_product_add_to_cart() {

		$cartData = array(
			'customer_id' => $this->input->post('customer_id', TRUE),
			'product_id' => $this->input->post('product_id', TRUE),
			'quantity' => $this->input->post('quantity', TRUE)
		);
		$this->form_validation->set_rules('customer_id', 'Customer ID', 'required|trim|numeric');
		$this->form_validation->set_rules('product_id', 'Product ID', 'required|trim|numeric');
		$this->form_validation->set_rules('quantity', 'Quantity', 'required|trim|numeric');

		if ($this->form_validation->run() == FALSE) {
			$data['response'] = array(
				'status' => 'error'
			);
			echo json_encode($data);
			exit();
		} else {
			$data['result'] = $this->ProductModel->process_product_add_to_cart($cartData);

			if($data['result']['is_submitted_successfully'] == TRUE) {
				$data['response'] = array(
					'status' => 'success'
				);
				echo json_encode($data);
			}
		}
	}
	public function getCartProductCount() {
		$data['response'] = $this->ProductModel->getCartProductCount();
		echo json_encode($data);
	}
	public function view_cart() {
		$this->load->view('customer/cart_view');
	}
}
