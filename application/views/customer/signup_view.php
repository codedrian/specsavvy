<?php defined("BASEPATH") or exit("No direct script access allowed.");
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Organic Shop: Let’s order fresh items for you.</title>
	<script src="../assets/js/vendor/jquery.min.js"></script>
	<script src="../assets/js/vendor/popper.min.js"></script>
	<script src="../assets/js/vendor/bootstrap.min.js"></script>
	<script src="../assets/js/vendor/bootstrap-select.min.js"></script>
	<link rel="stylesheet" href="../assets/css/vendor/bootstrap.min.css">
	<link rel="stylesheet" href="../assets/css/vendor/bootstrap-select.min.css">
	<script src="../assets/js/global/global.js"></script>
	<!-- toastr cdn -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" integrity="sha512-3pIirOrwegjM6erE5gPSwkUzO+3cTjpnV9lexlNZqvupR64iZBnOOTiiLPb9M36zpMScbmUNIcHUqKD47M719g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
	<!-- NOTE: Custom css -->
	<link rel="stylesheet" href="../assets/css/custom/global.css">
	<link rel="stylesheet" href="../assets/css/custom/signup.css">
</head>
<script>
	/*TODO: Use ajax for signning up*/
	 $(document).ready(function() {
		$("form").submit(function(e) {
			e.preventDefault();

			let formData = new FormData(this);
			$.ajax({
				url: '<?=base_url('')?>AccountsController/process_signup_form',
				type: 'POST',
				data: formData,
				dataType: 'json',
				processData: false,
				contentType: false,
				success: function(response) {
					console.log(response);
					$("input[name='<?= $this->security->get_csrf_token_name() ?>']").val(response.response.newCsrfToken);

					/*TODO: Append a span tag with the form error if status is fail*/
					if (response.response.status === 'fail') {
						toastr["error"](response.response.message);
						$.each(response.response.error, function(field, error) {
							$('.' + field).html(error);
						});
					} else {
						toastr["success"](response.response.message);
					}
				},
				error:  function(jqXHR, textStatus, errorThrown) {
					console.error("AJAX Error:", textStatus, errorThrown);
				}
			});
		});
	});
</script>

<body>
<?php $this->load->view("partials/flash_messages.php"); ?>
<div class="wrapper">
	<!--<a href="/dashboard"><img src="../assets/images/organic_shop_logo_large.svg" alt="Organic Shop"></a>-->
	<form action="<?= base_url("AccountsController/process_signup_form") ?>" method="post">
		<input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

		<h2>Signup to order.</h2>
		<a href="<?= base_url("AccountsController/view_login_form");?>">Already a member? Login here.</a>
		<ul>
			<li>
				<input type="text" name="first_name" value="<?= set_value('first_name') ?>" required>
				<label>First Name</label>
				<span class="first_name_error"></span>
			</li>
			<li>
				<input type="text" name="last_name" value="<?= set_value('last_name') ?>" required>
				<label>Last Name</label>
				<span class="last_name_error"></span>
			</li>
			<li>
				<input type="email" name="email_address" value="<?= set_value('email_address') ?>" required>
				<label>Email</label>
				<span class="email_error"></span>
			</li>
			<li>
				<input type="password" name="password" value="<?= set_value('password') ?>" required>
				<label>Password</label>
				<span class="password_error"></span>
			</li>
			<li>
				<input type="password" name="confirm_password" value="<?= set_value('confirm_password') ?>" required>
				<label>Confirm Password</label>
				<span class="confirm_password_error"></span>
			</li>
		</ul>
		<button class="signup_btn" type="submit">Signup</button>
		<!-- <input type="hidden" name="action" value="signup"> -->
	</form>
</div>
</body>

</html>
