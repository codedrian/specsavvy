<?php defined("BASEPATH") or exit("No direct script access allowed.");

class AdminModel extends CI_Model {
    /* cSpell: disable */
    public function validate_signin($signupInput) {
        $this->load->library("form_validation");
        $this->form_validation->set_rules("first_name", "First name", "trim|required");
        $this->form_validation->set_rules("last_name", "Last name", "trim|required");
        $this->form_validation->set_rules("email_address", "Email", "trim|required|valid_email|is_unique[admin.email_address]", array("is_unique" => "The email is already exists. Please proceed to login."));
        $this->form_validation->set_rules("password", "Password", "trim|required|min_length[8]");
        $this->form_validation->set_rules("confirm_password", "Confirm password", "trim|required|matches[password]");

        if ($this->form_validation->run() == FALSE) {
            return validation_errors();
        } else {
            return "valid";
        }
    }
    public function create_admin($signupInput) {
        $hashed_password = password_hash($signupInput["password"], PASSWORD_DEFAULT);

        $sql = "INSERT INTO `admin` (`first_name`, `last_name`, `email_address`, `hashed_password`, `created_at`, `updated_at`) VALUES(?, ?, ?, ?, NOW(), NOW())";
        $this->db->query($sql, array(
            $signupInput["first_name"],
            $signupInput["last_name"],
            $signupInput["email_address"],
            $hashed_password
        ));

        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            $error_message = $this->db->error(); // Get the database error message
            log_message('error', 'Error inserting admin: ' . $error_message); // Log the error
            return FALSE;
        }
    }
    public function validate_login_form($loginInput) {
        $this->load->library('form_validation');
        $this->form_validation->set_rules("email_address", "Email", "required|trim|valid_email");
        $this->form_validation->set_rules("password", "Password", "required");

        if ($this->form_validation->run() == TRUE) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    public function process_login_form($loginInput) {
        $email = $loginInput["email_address"];
        $password = $loginInput["password"];

        $sql = "SELECT * FROM admin WHERE email_address = ?";
        $result = $this->db->query($sql, array($email));

        if ($result && $result->num_rows() > 0) {
            $row = $result->row_array();
            if (password_verify($password, $row["hashed_password"]) == TRUE) {
                return array(
                    "success" => TRUE,
                    "adminId" => $row["admin_id"],
                    "adminFirstName" => $row["first_name"],
                );
            }
        }
        return array(
            "success" => FALSE,
        );
    }
}
