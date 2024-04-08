<?php
defined("BASEPATH") or exit("No direct script access allowed")
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organic Shop: Let’s order fresh items for you.</title>

    <link rel="shortcut icon" href="../assets/images/organic_shop_favicon.ico" type="image/x-icon">

    <script src="../assets/js/vendor/jquery.min.js"></script>
    <script src="../assets/js/vendor/popper.min.js"></script>
    <script src="../assets/js/vendor/bootstrap.min.js"></script>
    <script src="../assets/js/vendor/bootstrap-select.min.js"></script>
    <link rel="stylesheet" href="../assets/css/vendor/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/vendor/bootstrap-select.min.css">

    <script src="../assets/js/global/dashboard.js"></script>
    <link rel="stylesheet" href="../assets/css/custom/global.css">
    <link rel="stylesheet" href="../assets/css/custom/signup.css">
</head>
<script>
    /*   $(document).ready(function() {
        $("input[name=email]").focus();
        $("form").submit(function(event) {
            event.preventDefault();
            return false;
        });

        $(".login_btn").click(function() {
            window.location.href = "catalogue.html";
        });
    }); */
</script>

<body>
    <?php $this->load->view("partials/flash_messages"); ?>
    <?= var_dump($this->session->all_userdata()); ?>
    <div class="wrapper">
        <a href="/dashboard"><img src="../assets/images/organic_shop_logo_large.svg" alt="Organic Shop"></a>
        <form action="<?= base_url("AdminsController/process_login_form") ?>" method="post" class="login_form">
            <!-- Note: This generated a CSRF token -->
            <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
            <h2>Login to order.</h2>
            <a href="<?= base_url("AdminsController/view_signup_form"); ?>">New Admin? Register here.</a>
            <ul>
                <li>
                    <input type="text" name="email_address">
                    <label>Email</label>
                </li>
                <li>
                    <input type="password" name="password">
                    <label>Password</label>
                </li>
            </ul>
            <button type="submit" class="login_btn">Login</button>
            <input type="hidden" name="action" value="login">
        </form>
    </div>
</body>

</html>
