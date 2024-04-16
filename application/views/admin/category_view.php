<?php
defined("BASEPATH") or exit("No direct script access allowed");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories</title>
    <script src="../assets/js/vendor/jquery.min.js"></script>
    <!--  <script src="https://code.jquery.com/jquery-2.1.3.min.js"></script> -->
    <script src="../assets/js/vendor/popper.min.js"></script>
    <script src="../assets/js/vendor/bootstrap.min.js"></script>
    <script src="../assets/js/vendor/bootstrap-select.min.js"></script>
    <link rel="stylesheet" href="../assets/css/vendor/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/vendor/bootstrap-select.min.css">
    <link rel="stylesheet" href="../assets/css/custom/admin_global.css">
	<!--DataTable css and js-->
	<link rel="stylesheet" href="https://cdn.datatables.net/2.0.3/css/dataTables.dataTables.min.css">
	<script src="https://cdn.datatables.net/2.0.3/js/dataTables.min.js"></script>
    <!-- toastr cdn -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" integrity="sha512-3pIirOrwegjM6erE5gPSwkUzO+3cTjpnV9lexlNZqvupR64iZBnOOTiiLPb9M36zpMScbmUNIcHUqKD47M719g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="../assets/js/global/admin_categories.js"></script>
    <script>
		$(document).ready(function() {
			// Function to fetch and initialize DataTable
			function fetchAndInitializeDataTable() {
				$.get('<?=base_url('CategoriesController/fetch_category')?>', function(response) {
					console.log(response);
					$('.products_table').DataTable({
						ajax: {
							url: '<?=base_url('CategoriesController/fetch_category')?>',
							dataSrc: "category"
						},
						columns: [
							{ data: 'image_url',
								render: function(data, type, row)
								{
									if (type === 'display' && data) {
										let image_url = '../' + data;
										return '<span>' +
													'<img src="' + image_url + '" alt="' + row.name + '" width="100">' +
												'</span>';
									} else {
										return data;
									}
								}
							},
							{ data: 'category_id',
								render: function(data, type, row) {
									return '<span>' + data + '</span>';
								}
							},
							{ data: 'name',
								render: function (data, type, row)
								{
									return '<span>' + data + '</span>';
								}
							},
							{ data: 'description',
								render: function (data, type, row)
								{
									return '<span>' + data + '</span>';
								}
							},
							{ data: 'created_at',
								render: function (data, type, row)
								{
									return '<span>' + data + '</span>';
								}
							},
							{ data: null,
								render: function(data, type, row) {
									return '<span>' +
												'<button class="btn btn-primary btn-sm delete-btn edit_product" data-id="' + row.category_id + '">Edit</button>' +
												'<button class="btn btn-danger btn-sm delete-btn delete_product" data-id="' + row.category_id + '">X</button>' +
											'</span>';
								}
							}
						]
					});
				}, 'json');
			}

			// Initial DataTable initialization
			fetchAndInitializeDataTable();

			// Form submission handler
			$("#add_category_form").submit(function(e) {
				e.preventDefault();
				let formData = new FormData(this);
				$.ajax({
					url: "<?= base_url(''); ?>CategoriesController/process_add_category",
					type: "POST",
					data: formData,
					processData: false,
					contentType: false,
					dataType: "json",
					success: function(response) {
						if (response.response.status === "success") {
							$("input[name='<?= $this->security->get_csrf_token_name() ?>']").val(response.response.newCsrfToken);
							$('.clearable').val('');
							toastr["success"](response.response.message);

							// Reload DataTable after successful form submission
							$('.products_table').DataTable().ajax.reload();
						} else {
							$("input[name='<?= $this->security->get_csrf_token_name() ?>']").val(response.response.newCsrfToken);
							$.each(response.response.error, function(field, error) {
								$("#" + field).html(error).addClass('text-danger');;
							});
						}
					},
					error: function(jqXHR, textStatus, errorThrown) {
						console.error("AJAX Error:", textStatus, errorThrown);
					}
				});
				return false;
			});
		});

	</script>

</head>
<script>

</script>

<body>
    <div class="wrapper">
        <header>
            <h1>Let’s provide fresh items for everyone.</h1>

            <!-- <p class="text-danger">Welcome, <?= $this->session->userdata("adminFirstName"); ?></p> -->
            <h2 class="">Category</h2>
            <div>
                <a class="switch" href="catalogue.html">Switch to Shop View</a>
                <button class="profile">
                    <img src="../assets/images/profile.png" alt="#">
                </button>
            </div>

            <div class="dropdown show">
                <a class="btn btn-secondary dropdown-toggle profile_dropdown" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></a>
                <div class="dropdown-menu admin_dropdown" aria-labelledby="dropdownMenuLink">
                    <a class="dropdown-item" href="<?= base_url("AdminsController/process_logout") ?>">Logout</a>
                    <a class="dropdown-item" href="<?= base_url("AdminsController/view_login_form") ?>">Settings</a>
                </div>
            </div>
        </header>
        <aside>
            <a href="#"><img src="../assets/images/organi_shop_logo_dark.svg" alt="Organic Shop"></a>
            <ul>
                <li><a href="admin_orders.html">Orders</a></li>
                <li><a href="<?= base_url("AdminsController/view_product") ?>"">Products</a></li>
                <li class=" active"><a href="">Category</a></li>
            </ul>
        </aside>
        <section>
            <form action="process.php" method="post" class="search_form">
                <input type="text" name="search" placeholder="Search Category">
            </form>
            <button class="add_category" data-toggle="modal" data-target="#add_category_modal">Add Category</button>
            <!-- NOTE: This is the sidebar button -->
            <!--  <form action="process.php" method="post" class="status_form">
                <h3>Categories</h3>
                <ul>
                    <li>
                        <button type="submit" class="active">
                            <span>36</span><img src="../assets/images/all_orders_icon.svg" alt="#">
                            <h4>All Products</h4>
                        </button>
                    </li>
                    <li>
                        <button type="submit">
                            <span>36</span><img src="../assets/images/pending_icon.svg" alt="#">
                            <h4>Pending</h4>
                        </button>
                    </li>
                </ul>
            </form> -->
            <!-- NOTE: INSERT category here using AJAX -->
            <div>
                <table class="products_table">
                    <thead>
                        <tr>
                           <!-- <th>
                                <h3>All Categories</h3>
                            </th>-->
                            <th>ID #</th>
							<th></th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Created on</th>
							<th></th>
                        </tr>
                    </thead>
					<tbody class="no-padding-table">
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					</tbody>
                </table>
            </div>
        </section>

        <div class="modal fade form_modal" id="add_category_modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <button data-dismiss="modal" aria-label="Close" class="close_modal"></button>
                    <form id="add_category_form" action="<?= base_url("CategoriesController/process_add_category") ?>" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>" />
                        <h2>Add a Category</h2>
                        <ul>
                            <li>
                                <input type="text" name="name" class='clearable' required>
                                <label>Category Name</label>
                                <span id="name_error"></span>
                            </li>

                            <li>
                                <textarea name="description" class='clearable' required></textarea>
                                <label>Description</label>
                                <span id="description_error"></span>
                            </li>
                            <label>Upload Image</label>
                            <input type="file" name="image" id="category_image" class='clearable' accept="image/*">
                            <span id="image_error"></span>
                        </ul>
                        <!-- FIXME: center the content of this last li tag -->
                        <button type="button" data-dismiss="modal" aria-label="Close">Cancel</button>
                        <button type="submit">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="popover_overlay"></div>
</body>

</html>
