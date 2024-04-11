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
    <!--    <script src="../assets/js/global/admin_categories.js"></script> -->
    <script>
        $(document).ready(function() {
            $("#add_category_form").submit(function(e) {
                e.preventDefault();
                if ($("#category_image").val() == "") {
                    alert("Please add an image");
                }

                let formData = new FormData(this);
                $.ajax({
                    url: "<?= base_url(''); ?>CategoriesController/process_add_category",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: "json",
                    success: function(response) {
                        console.log(response);
                        csrfName = response.csrfName;
                        $("input[name='<?= $this->security->get_csrf_token_name() ?>']").val(response.newCsrfToken);
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error:", status, error);
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
                            <th>
                                <h3>All Categories</h3>
                            </th>
                            <th>ID #</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Created on</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <span>
                                    <img src="../assets/images/food.png" alt="#">
                                    Vegetables
                                </span>
                            </td>
                            <td><span>123</span></td>
                            <td><span>$ 10</span></td>
                            <td><span>Vegetable</span></td>
                            <td><span>123</span></td>
                            <td>
                                <span>
                                    <button class="edit_product">Edit</button>
                                    <button class="delete_product">X</button>
                                </span>
                                <form class="delete_product_form" action="process.php" method="post">
                                    <p>Are you sure you want to remove this item?</p>
                                    <button type="button" class="cancel_remove">Cancel</button>
                                    <button type="submit">Remove</button>
                                </form>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
        <!-- NOTE: This is modal for Adding a Category -->
        <div class="modal fade form_modal" id="add_category_modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <button data-dismiss="modal" aria-label="Close" class="close_modal"></button>
                    <form id="add_category_form" action="<?= base_url("CategoriesController/process_add_category") ?>" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>" />
                        <h2>Add a Category</h2>
                        <ul>
                            <li>
                                <input type="text" name="name" required>
                                <label>Category Name</label>
                            </li>
                            <li>
                                <textarea name="description" required></textarea>
                                <label>Description</label>
                            </li>
                            <label>Upload Images (5 Max)</label>
                            <input type="file" name="image" id="category_image" accept="image/*">
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
