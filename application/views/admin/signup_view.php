<?php defined("BASEPATH") or exit("No direct script access allowed.");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organic Shop: Let’s order fresh items for you.</title>

    <link rel="shortcut icon" href="assets/images/organic_shop_favicon.ico" type="image/x-icon">

    <script src="../assets/js/vendor/jquery.min.js"></script>
    <script src="../assets/js/vendor/popper.min.js"></script>
    <script src="../assets/js/vendor/bootstrap.min.js"></script>
    <script src="../assets/js/vendor/bootstrap-select.min.js"></script>
    <link rel="stylesheet" href="../assets/css/vendor/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/vendor/bootstrap-select.min.css">

    <script src="../assets/js/global/global.js"></script>
    <!-- NOTE: Custom css -->
    <link rel="stylesheet" href="../assets/css/custom/global.css">
    <link rel="stylesheet" href="../assets/css/custom/signup.css">
</head>
<script>
    /* $(document).ready(function() {
        $("form").submit(function(event) {
            event.preventDefault();
            return false;
        });
        prototype add
        $(".signup_btn").click(function() {
            window.location.href = "catalogue.html";
        });
    }); */
</script>

<body>
    <?php $this->load->view("partials/flash_messages.php"); ?>
    <div class="wrapper">
        <a href="/dashboard"><img src="../assets/images/organic_shop_logo_large.svg" alt="Organic Shop"></a>
        <form action="<?= base_url("AdminsController/process_signup_form") ?>" method="post">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

            <h2>Signup to order.</h2>
            <a href="<?= base_url("AdminsController/view_login_form");?>">Already a member? Login here.</a>
            <ul>
                <li>
                    <input type="text" name="first_name" value="<?= set_value('first_name') ?>">
                    <label>First Name</label>
                </li>
                <li>
                    <input type="text" name="last_name" value="<?= set_value('last_name') ?>">
                    <label>Last Name</label>
                </li>
                <li>
                    <input type="email" name="email_address" value="<?= set_value('email_address') ?>">
                    <label>Email</label>
                </li>
                <li>
                    <input type="password" name="password" value="<?= set_value('password') ?>">
                    <label>Password</label>
                </li>
                <li>
                    <input type="password" name="confirm_password" value="<?= set_value('confirm_password') ?>">
                    <label>Confirm Password</label>
                </li>
            </ul>
            <button class="signup_btn" type="submit">Signup</button>
            <!-- <input type="hidden" name="action" value="signup"> -->
        </form>
    </div>
</body>

</html>
