<?php defined('BASEPATH') or exit('No direct script access allowed.');
?>
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
		/*NOTE: This is the clicked product ID*/
		let productId = "<?=$productId?>"
		/*$('.total_amount').text(23);*/

		displayProductImage();
		displayProductData();
		initializeCustomerId();
		process_product_add_to_cart()
		getCartProductCount();

		function displayProductData() {
			$.ajax({
				url: `<?=base_url("");?>ProductsController/fetch_product_details/${productId}`,
				type: "GET",
				dataType: "json",
				success: function(response) {
					$.each(response.productData, function (index, product) {
						let image_path = "<?=base_url('');?>" + product.image_url;
						$('.product_name').html(product.name);
						$('.amount').text('₱' + product.price);
						$('.description').html(product.description);
						$('.product_thumbnail').prepend(`<img src='${image_path}' alt="food">`);
						$('#product_id').val(product.product_id);
						$('.total_amount').text('₱' + product.price);
						/*Pass product price to this function*/
						increaseQuantity(product.price);
						decreaseQuantity(product.price);
					})
				},
				error: function(jqXHR, textStatus, errorThrown) {
					console.log('AJAX Error:', textStatus, errorThrown);
				}
			})
		/*	$("#add_to_cart").click(function () {
				$("<span class='added_to_cart'>Added to cart succesfully!</span>")
					.insertAfter(this)
					.fadeIn()
					.delay(1000)
					.fadeOut(function () {
						$(this).remove();
					});
				return false;
			});*/
		}
		function displayProductImage() {
			$.ajax({
				url: `<?=base_url("");?>ProductsController/fetch_product_image/${productId}`,
				type: "GET",
				dataType: "json",
				success: function(response) {
					$.each(response.images, function(index, image) {
						let image_path = "<?=base_url('');?>" + image.image_url;
						$('.product_gallery').append(`<li><button><img src='${image_path}'></button></li>`);
					});

				},
				error: function(jqXHR, textStatus, errorThrown) {
					console.log('AJAX Error:', textStatus, errorThrown);
				}
			})
		}
		function initializeCustomerId() {
			let customerId = "<?=$customerId?>";
			$('#customer_id').val(customerId);
		}
		function increaseQuantity(price) {
			$('.increase_quantity').on('click', function() {

				$('#quantity').attr('value', function(index, oldValue) {
					return parseInt(oldValue, 10) + 1;
				});
				updateTotal(price);
			});
		}
		function decreaseQuantity(price) {
			$('.decrease_quantity').on('click', function() {
				let quantity = $('#quantity').val();
				if (quantity > 1) {
					$('#quantity').attr('value', function(index, oldValue) {
						return parseInt(oldValue, 10) - 1;
					});
					updateTotal(price)
				}
			});
		}
		function updateTotal(price) {
			let quantity = parseInt($('#quantity').val(), 10);
			let total = quantity * price;
			$('.total_amount').text(total)
		}
		function process_product_add_to_cart() {
			$('#add_to_cart_form').submit(function(e) {
				e.preventDefault();
				const formAction = $(this).attr('action');
				let formData = new FormData(this);

				$.ajax({
					url: formAction,
					type: 'POST',
					data: formData,
					processData: false,
					contentType: false,
					dataType: 'json',
					success: function(response) {
						getCartProductCount();
					},
					error: function(jgXHR, textStatus, errorThrown) {
						console.error('AJAX Error:', textStatus, errorThrown);
					}
				});
			return false;
			});
		}
		function getCartProductCount() {
		/*This function etch cart product count*/
			$.ajax({
				url: "<?=base_url('ProductsController/getCartProductCount');?>",
				type: 'GET',
				dataType: 'json',
				success: function(response) {
					console.log(response);
					$('.show_cart').text(`Cart (${response.response[0].total_product})`);
				},
				error: function(jqXHR, textStatus, errorThrown) {
					console.log('AJAX Error:', textStatus, errorThrown);
				}
			});
		}

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
		<a class="show_cart" href="<?=base_url('ProductsController/view_cart');?>"></a>
		<a href="<?=base_url('AccountsController/view_dashboard');?>">Go Back</a>
		<ul>
			<li class="product_thumbnail">
			<!--Display the thumbnail here-->
				<ul class="product_gallery">
				<!--Display product images here-->
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
				<span class="amount" id="amount"></span>
				<p class="description"></p>
				<form action="<?=base_url('ProductsController/process_product_add_to_cart');?>" method="post" id="add_to_cart_form">
					<input type='hidden' name='<?=$this->security->get_csrf_token_name();?>' value='<?=$this->security->get_csrf_hash()?>'>
					<input type='hidden' name='customer_id' id='customer_id' value="">
					<input type='hidden' name='product_id' id='product_id'>
					<ul>
						<li>
							<label>Quantity</label>
							<input type="text" min-value="1" value="1" id="quantity" name="quantity">
							<ul>
								<li><button type="button" class="increase_quantity" data-quantity-ctrl="1"></button></li>
								<li><button type="button" class="decrease_quantity" data-quantity-ctrl="0"></button></li>
							</ul>
						</li>
						<li>
							<label>Total Amount</label>
							<span class="total_amount"></span>
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

