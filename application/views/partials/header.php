<?php defined('BASEPATH') or exit('No direct script access allowed.')?>
<header>
	<h1>Let’s order fresh items for you.</h1>
	<div>
		<?php if ($this->session->userdata('is_log_in')) : ?>
			<div class="btn-group">
				<button class="profile mr-1">
					<img src="/thrifted-threads/assets/images/profile.png" alt="#">
				</button>
				<button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<span class="sr-only">Toggle Dropdown</span>
				</button>
				<div class="dropdown-menu">
					<a class="dropdown-item" href="<?= base_url("AccountsController/process_logout") ?>">Logout</a>
					<a class="dropdown-item" href="<?php /*= base_url("") */?>">Settings</a>
				</div>
			</div>
		<?php else: ?>
			<a class="signup_btn" href="signup.html">Signup</a>
			<a class="login_btn" href="<?=base_url('AccountsController/view_login_form');?>">Login</a>
		<?php endif; ?>
	</div>
</header>
