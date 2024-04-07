<?php defined("BASEPATH") or exit("No direct script access allowed");

class AdminsController extends CI_Controller {
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
            $this->session->set_flashdata('user_input', $signupInput);
            $this->load->view("admin/signup_view");
        }
    }
    public function process_login_form() {
        $loginInput = array(
            "email_address" => $this->input->post("email_address"),
            "user_password" => $this->input->post("password"),
        );
        var_dump($loginInput);
        /* $this->AdminModel->validate_login($loginInput); */
    }
}
