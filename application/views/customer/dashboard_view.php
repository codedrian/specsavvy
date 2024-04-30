<?php defined('BASEPATH') or exit('No direct script access allowed.')?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Products</title>

	<link rel="shortcut icon" href="../assets/images/organic_shop_fav.ico" type="image/x-icon">

	<script src="../assets/js/vendor/jquery.min.js"></script>
	<script src="../assets/js/vendor/popper.min.js"></script>
	<script src="../assets/js/vendor/bootstrap.min.js"></script>
	<script src="../assets/js/vendor/bootstrap-select.min.js"></script>
	<link rel="stylesheet" href="../assets/css/vendor/bootstrap.min.css">
	<link rel="stylesheet" href="../assets/css/vendor/bootstrap-select.min.css">
	<link rel="stylesheet" href="../assets/css/custom/global.css">
	<link rel="stylesheet" href="../assets/css/custom/product_dashboard.css">
</head>

<script>
	$(document).ready(function() {
		displayProductDetail()
		getCartProductCount();

		function displayProductDetail() {
			$.ajax({
				url: "<?=base_url('')?>ProductsController/fetch_all_product",
				type: 'GET',
				dataType: 'json',
				success: function(response) {
					let product_count = response.products.length;
					$('#product_count').append(`All Product (${product_count})`);
					$.each(response.products, function(index, product) {
						let productCard = `<li>
											<a href="<?=base_url('ProductsController/view_product_details/');?>${product.product_id}">
												<img src="<?=base_url('${product.image_url}');?>" alt="#">
												<h3>${product.name}</h3>
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
									  </li>`;
						$('.product_list').append(productCard);
					});
				},
				error: function(jqXHR, textStatus, errorThrown ) {
					console.log('AJAX Error:', textStatus, errorThrown);
				}
			});
		}
		function getCartProductCount() {
			/*This function etch cart product count*/
			$.ajax({
				url: "<?=base_url('ProductsController/getCartProductCount');?>",
				type: 'GET',
				dataType: 'json',
				success: function(response) {
					$('.show_cart').text(`Cart (${response.response[0].total_product})`);
				},
				error: function(jqXHR, textStatus, errorThrown) {
					console.log('AJAX Error:', textStatus, errorThrown);
				}
			});
		}
	})
</script>
<body>
<div class="wrapper">
	<?php
	$this->load->view('partials/header');
	?>
	<aside>
		<a href="products_dashboard.html"><img src="../assets/images/organic_shop_logo.svg" alt="Organic Shop"></a>
		 <ul>
			<li class="active"><a href="#"></a></li>
			<li><a href="#"></a></li>
		</ul>
	</aside>
	<section >
		<form action="process.php" method="post" class="search_form">
			<input type="text" name="search" placeholder="Search Products">
		</form>
		<a class="show_cart" href="<?=base_url('ProductsController/view_cart');?>">Cart (0)</a>
		<form action="process.php" method="post" class="categories_form">
			<h3>Categories</h3>
			<ul>
				<li>
					<button type="submit">
						<span>36</span><img src="../assets/images/apple.png" alt="#"><h4>Vegetables</h4>
					</button>
				</li>
				<li>
					<button type="submit">
						<span>36</span><img src="../assets/images/apple.png" alt="#"><h4>Fruits</h4>
					</button>
				</li>
				<li>
					<button type="submit">
						<span>36</span><img src="../assets/images/apple.png" alt="#"><h4>Pork</h4>
					</button>
				</li>
				<li>
					<button type="submit">
						<span>36</span><img src="../assets/images/apple.png" alt="#"><h4>Beef</h4>
					</button>
				</li>
				<li>
					<button type="submit">
						<span>36</span><img src="../assets/images/apple.png" alt="#"><h4>Chicken</h4>
					</button>
				</li>
			</ul>
		</form>
		<div>
			<h3 id="product_count"></h3>
			<ul class="product_list">
			</ul>
		</div>
	</section>
</div>
</body>
</html>
