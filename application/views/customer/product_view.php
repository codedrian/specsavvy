<?php defined('BASEPATH') or exit('No direct script access allowed.')?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Products</title>

	<script src="/thrifted-threads/assets/js/vendor/jquery.min.js"></script>
	<script src="/thrifted-threads/assets/js/vendor/popper.min.js"></script>
	<script src="/thrifted-threads/assets/js/vendor/bootstrap.min.js"></script>
	<script src="/thrifted-threads/assets/js/vendor/bootstrap-select.min.js"></script>
	<link rel="stylesheet" href="/thrifted-threads/assets/css/vendor/bootstrap.min.css">
	<link rel="stylesheet" href="/thrifted-threads/assets/css/vendor/bootstrap-select.min.css">

	<link rel="stylesheet" href="/thrifted-threads/assets/css/custom/global.css">
	<link rel="stylesheet" href="<?=base_url('assets/css/custom/product_view.css');?>">
</head>

<script>
	$(document).ready(function() {
		$("#add_to_cart").click(function () {
			$("<span class='added_to_cart'>Added to cart succesfully!</span>")
				.insertAfter(this)
				.fadeIn()
				.delay(1000)
				.fadeOut(function () {
					$(this).remove();
				});
			return false;
		});
		/*NOTE: This code fetches the product datas*/
		let productData = <?= $product_json; ?>;
		$.each(productData, function (index, product) {

			$('.product_name').html(product.name);
			$('.amount').html(product.price);
			$('.description').html(product.description);
		})
		/*NOTE: This code fetches the product image*/
		let productImage = <?= $image_json; ?>;
		$.each(productImage, function(index, image) {
			let image_path = "<?=base_url('');?>" + image.image_url;
			/*echo <?=base_url('image.image_url');?>;*/
			/*<li class="active"><button class="show_image"><img src="<?=base_url('image.image_url');?>" alt="food"></button></li>"*/
			$('.product_gallery').append(`<li><button><img src='${image_path}'></button></li>`);
		});
	});
</script>
<body>
<div class="wrapper">
	<?php
		$this->load->view('partials/header');
	?>
	<aside>
		<a href="catalogue.html"><img src="/thrifted-threads/assets/images/organic_shop_logo.svg" alt="Organic Shop"></a>
		<!-- <ul>
			<li class="active"><a href="#"></a></li>
			<li><a href="#"></a></li>
		</ul> -->
	</aside>
	<section >
		<form action="process.php" method="post" class="search_form">
			<input type="text" name="search" placeholder="Search Products">
		</form>
		<a class="show_cart" href="cart.html">Cart (0)</a>
		<a href="<?=base_url('AccountsController/view_dashboard');?>">Go Back</a>
		<ul>
			<li>
				<img src="/thrifted-threads/assets/images/burger.png" alt="food">
				<ul class="product_gallery">
					<!--<li class="active"><button class="show_image"><img src="/thrifted-threads/assets/images/burger.png" alt="food"></button></li>
					<li><button class="show_image"><img src="/thrifted-threads/assets/images/burger.png" alt="food"></button></li>
					<li><button class="show_image"><img src="/thrifted-threads/assets/images/burger.png" alt="food"></button></li>
					<li><button class="show_image"><img src="/thrifted-threads/assets/images/burger.png" alt="food"></button></li>-->
				</ul>
			</li>
			<li>
				<h2 class="product_name"></h2>
				<ul class="rating">
					<li></li>
					<li></li>
					<li></li>
					<li></li>
					<li></li>
				</ul>
				<span>36 Rating</span>
				<span class="amount"></span>
				<p class="description"></p>
				<form action="" method="post" id="add_to_cart_form">
					<ul>
						<li>
							<label>Quantity</label>
							<input type="text" min-value="1" value="1">
							<ul>
								<li><button type="button" class="increase_decrease_quantity" data-quantity-ctrl="1"></button></li>
								<li><button type="button" class="increase_decrease_quantity" data-quantity-ctrl="0"></button></li>
							</ul>
						</li>
						<li>
							<label>Total Amount</label>
							<span class="total_amount">$ 10</span>
						</li>
						<li><button type="submit" id="add_to_cart">Add to Cart</button></li>
					</ul>
				</form>
			</li>
		</ul>
		<section>
			<h3>Similar Items</h3>
			<ul>
				<li>
					<a href="product_view.html">
						<img src="/thrifted-threads/assets/images/food.png" alt="#">
						<h3>Vegetables</h3>
						<ul class="rating">
							<li></li>
							<li></li>
							<li></li>
							<li></li>
							<li></li>
						</ul>
						<span>36 Rating</span>
						<span class="price">$ 10</span>
					</a>
				</li>
				<li>
					<a href="product_view.html">
						<img src="/thrifted-threads/assets/images/food.png" alt="#">
						<h3>Vegetables</h3>
						<ul class="rating">
							<li></li>
							<li></li>
							<li></li>
							<li></li>
							<li></li>
						</ul>
						<span>36 Rating</span>
						<span class="price">$ 10</span>
					</a>
				</li>
				<li>
					<a href="product_view.html">
						<img src="/thrifted-threads/assets/images/food.png" alt="#">
						<h3>Vegetables</h3>
						<ul class="rating">
							<li></li>
							<li></li>
							<li></li>
							<li></li>
							<li></li>
						</ul>
						<span>36 Rating</span>
						<span class="price">$ 10</span>
					</a>
				</li>
			</ul>
		</section>
	</section>
</div>
</body>
</html>

