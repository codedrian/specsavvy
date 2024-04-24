<?php defined('BASEPATH') or exit('No direct script access allowed.');

class CustomersController extends CI_Controller
{
	public function __construct() {
		parent:: __construct();
		$this->load->model('CustomerModel');
	}
	public function view_signup_form() {
		$this->load->view('customer/signup_view');
	}
	public function process_signup_form() {
		$signupInput = array(
			'first_name' => $this->input->post('first_name', TRUE),
			'last_name' => $this->input->post('last_name', TRUE),
			'email' => $this->input->post('email_address', TRUE),
			'password' => $this->input->post('password', TRUE),
			'confirm_password' => $this->input->post('confirm_password', TRUE)
		);
		/*Validate it*/
		$this->load->library('form_validation');
		$this->form_validation->set_rules('first_name', 'First name', 'required|trim');
		$this->form_validation->set_rules('last_name', 'Last name', 'required|trim');
		$this->form_validation->set_rules('email_address', 'Email address', 'required|trim|valid_email|is_unique[customer.email]');
		$this->form_validation->set_rules('password', 'Password', 'required|trim|min_length[8]');
		$this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|trim|matches[password]');

		if ($this->form_validation->run() == FALSE)
		{
			$data['response'] = array(
				'status' => 'fail',
				'error' => array(
					'first_name_error' => form_error('first_name'),
					'last_name_error' => form_error('last_name'),
					'email_error' => form_error('email_address'),
					'password_error' => form_error('password'),
					'confirm_password_error' => form_error('confirm_password')
				),
				'newCsrfToken' => $this->security->get_csrf_hash(),
				'message' => 'Oops! There were errors in your form. Please review.'
			);
			echo json_encode($data);
			return;
		}
		else {
			/*comment: if inputs are valid, create an account*/
			$result = $this->CustomerModel->create_customer($signupInput);
			if ($result) {
				/*COMMENT: If success proceed to login*/
				$data['response'] = array(
					'status' => 'success',
					'message' => 'Account created successfully! Please login.'
				);
				echo json_encode($data);
			}
		}

	}
	public function view_login_form() {
		$this->load->view('customer/login_view');
	}
	public function process_login_form() {
		$loginInput = array(
			'email_address' => $this->input->post("email_address", TRUE),
			'password' => $this->input->post("password", TRUE)
		);
		/*Validate it*/
		$this->load->library('form_validation');
		$this->form_validation->set_rules("email_address", "Email", "required|trim|valid_email");
		$this->form_validation->set_rules("password", "Password", "required");

		if ($this->form_validation->run() == FALSE) {
			$data['response'] = array(
				'status' => 'fail',
				'error' => array(
					'email_error' => form_error('email_address'),
					'password_error' => form_error('password')
				),
				'newCsrfToken' => $this->security->get_csrf_hash(),
				'message' => 'Oops! There were errors in your form. Please review.'
			);
			echo json_encode($data);
			return;
		}
		else {
			/*  NOTE: Verify the password and proceed to login if inputted password is correct */
			$isLoggedIn = $this->CustomerModel->process_login_form($loginInput);

			if ($isLoggedIn['result'] == TRUE) {
				$data['response'] = array(
					'newCsrfToken' => $this->security->get_csrf_hash(),
					'status' => 'success'
				);
				$this->session->set_userdata('customer_id', $isLoggedIn['id']);
				$this->session->set_userdata('first_name', $isLoggedIn['first_name']);
				$this->session->set_userdata('is_log_in', TRUE);
				echo json_encode($data);
			} else {
				$data['response'] = array(
					'status' => 'fail',
					'message' => 'Wrong credentials. Please try again.',
					'newCsrfToken' => $this->security->get_csrf_hash()
				);
				echo json_encode($data);
			}
		}
	}
	public function view_dashboard() {
			$this->load->view('customer/dashboard_view');
	}
	public function process_logout() {
		$this->session->sess_destroy();
		redirect("CustomersController/view_login_form");
	}
}
