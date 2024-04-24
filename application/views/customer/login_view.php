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
	<!-- toastr cdn -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" integrity="sha512-3pIirOrwegjM6erE5gPSwkUzO+3cTjpnV9lexlNZqvupR64iZBnOOTiiLPb9M36zpMScbmUNIcHUqKD47M719g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
	<script src="../assets/js/global/dashboard.js"></script>
	<link rel="stylesheet" href="../assets/css/custom/global.css">
	<link rel="stylesheet" href="../assets/css/custom/signup.css">
</head>
<script>
	   $(document).ready(function() {
		$("form").submit(function(e) {
			e.preventDefault();
			let formData = new FormData(this);
			$.ajax({
				url: "<?= base_url("") ?>AccountsController/process_login_form",
				type: 'POST',
				data: formData,
				processData: false,
				contentType: false,
				dataType: 'json',
				success: function(response) {
					console.log(response);
					$("input[name='<?= $this->security->get_csrf_token_name() ?>']").val(response.response.newCsrfToken);
					if (response.response.status === 'success') {
						/*PROCEED TO DASHBOARD*/
						window.location.href = '<?= base_url('')?>AccountsController/view_dashboard'
					}
					else {
						toastr['error'](response.response.message);
						$.each(response.response.error, function(field, error) {
							$('.' + field).html(error);
						});
					}
				},
				error: function(jqXHR, textStatus, errorThrown) {
					console.error("AJAX Error:", textStatus, errorThrown);
				}
			});
		});

		/*$(".login_btn").click(function() {
			window.location.href = "catalogue.html";
		});*/
	});
</script>

<body>
<!-- <?= var_dump($this->session->all_userdata()); ?> -->
<div class="wrapper">
	<!--TODO: Insert logo here-->
	<form method="post" class="login_form">
		<!-- Note: CSRF token -->
		<input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
		<h2>Login to order.</h2>
		<a href="<?= base_url("AdminsController/view_signup_form"); ?>">New User? Register here.</a>
		<ul>
			<li>
				<input type="text" name="email_address">
				<label>Email</label>
				<span class="email_error"></span>
			</li>
			<li>
				<input type="password" name="password">
				<label>Password</label>
				<span class="password_error"></span>
			</li>
		</ul>
		<button type="submit" class="login_btn">Login</button>
		<input type="hidden" name="action" value="login">
	</form>
</div>
</body>

</html>
