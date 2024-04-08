<?php defined("BASEPATH") or exit("No direct script access allowed");

class AdminsController extends CI_Controller
    {
    public function __construct() {
        parent::__construct();
        $this->load->model("AdminModel");
    }
    /* cSpell: disable */
    public function view_signup_form() {
        $this->load->view("admin/signup_view");
    }
    public function view_login_form() {
        $this->load->view("admin/login_view");
    }
    public function process_signup_form() {
        /* TODO: sanitze user input. If I set TRUE the xss clean in config.php, it automatically apply xss clean without doing this and applyingxss_clean. TEST IT. */
        $signupInput = array(
            "first_name" => $this->input->post("first_name", TRUE),
            "last_name" => $this->input->post("last_name", TRUE),
            "email_address" => $this->input->post("email_address", TRUE),
            "password" => $this->input->post("password", TRUE),
            "confirm_password" => $this->input->post("confirm_password", TRUE)
        );
        $result = $this->AdminModel->validate_signin($signupInput);

        if ($result == "valid") {
            $success = $this->AdminModel->create_admin($signupInput);

            if ($success == TRUE) {
                /* proceed to dashboard */
            } else {
                $errors = array("An error occurred while trying to create your account. Please try again.");
                $this->session->set_flashdata("errors", $errors);
                $this->load->view("admin/signup_view");
            }
        } else {
            $errors = array(validation_errors());
            $this->session->set_flashdata('errors', $errors);
            $this->load->view("admin/signup_view");
        }
    }
    public function process_login_form() {
        $loginInput = array(
            "email_address" => $this->input->post("email_address", TRUE),
            "password" => $this->input->post("password", TRUE),
        );

        $result = $this->AdminModel->validate_login_form($loginInput);
        if ($result === FALSE) {
            $errors = array(validation_errors());
            $this->session->set_flashdata("errors", $errors);
            $this->load->view("admin/login_view");
        } else {
           /*  NOTE: Verify the password and proceed to login if inputted password is correct */
            $isLoggedIn = $this->AdminModel->process_login_form($loginInput);
            if ($isLoggedIn["success"] == TRUE) {
                /*  NOTE: Store Admin datas in session */
                $this->session->set_userdata("adminId", $isLoggedIn["adminId"]);
                $this->session->set_userdata("adminFirstName", $isLoggedIn["adminFirstName"]);
                $this->session->set_userdata("logged_in", TRUE);
                redirect("AdminsController/view_product");
            } else {
                $this->session->set_flashdata("errors", array("message" => "Wrong credentials. Please try again."));
                $this->load->view("admin/login_view");
            }
        }
    }
    public function view_product() {
        if (!$this->session->userdata('logged_in')) {
            redirect('AdminsController/view_login_form');
        }  else {
            $this->load->view("admin/product_view");
        }
    }
    public function view_category()
    {
        if (!$this->session->userdata("logged_in")) {
            redirect('AdminsController/view_login_form');
        }
        $this->load->view("admin/category_view");
    }
    public function process_logout() {
        $this->session->sess_destroy();
        redirect("AdminsController/view_login_form");
    }
}
