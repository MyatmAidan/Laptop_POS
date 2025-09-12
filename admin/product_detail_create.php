<?php
require '../database/dbrequire.php';
require './layouts/common.php';
require '../database/common_function.php';

$brand_res = selectData('brand', $mysql, "", "*", "");
$category_res = selectData('category', $mysql, "", "*", "");
$error = false;
$error_message =
    $name_error =
    $name = '';
if (isset($_POST['form_sub']) && $_POST['form_sub'] == '1') {
    $name = $mysql->real_escape_string($_POST['name']);
    $brand_id = $mysql->real_escape_string($_POST['brand_id']);
    $category_id = $mysql->real_escape_string($_POST['category']);
    $description = $mysql->real_escape_string($_POST['description']);
    $qty = $mysql->real_escape_string($_POST['qty']);
    $price = $mysql->real_escape_string($_POST['price']);


    if ($brand_id === '' || strlen($brand_id) === 0) {
        $error = true;
        $brand_error = "Please select Brand Name.";
    } else if ($category_id === '' || strlen($category_id) === 0) {
        $error = true;
        $category_error = "Please select Category Name.";
    }

    if ($qty === '' || strlen($qty) === 0) {
        $error = true;
        $qty_error = "Please fill Quantity .";
    } else if (!is_numeric($qty) || intval($qty) < 0) {
        $error = true;
        $qty_error = "Quantity must be a non-negative number.";
    }

    if ($price === '' || strlen($price) === 0) {
        $error = true;
        $price_error = "Please fill Price .";
    } else if (!is_numeric($price) || intval($price) < 0) {
        $error = true;
        $price_error = "Price must be a non-negative number.";
    }

    // Folder path
    $uploadDir =  "upload/";

    // Create folder if not exists
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true); // 0777 full permission, recursive
    }

    $imageName = null;
    if (isset($_FILES['img']) && $_FILES['img']['error'] == 0) {
        $ext = pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION);
        $imageName = uniqid('img_') . "." . strtolower($ext);
        $targetFile = $uploadDir . $imageName;

        if (move_uploaded_file($_FILES['img']['tmp_name'], $targetFile)) {
            // ✅ success
        } else {
            $error = true;
            $error_message = "Image upload failed!";
        }
    }

    if (!$error) {
        $data = [
            'product_id' => $_GET['id'],
            'brand_id' => $brand_id,
            'category_id' => $category_id,
            'description' => $description,
            'qty' => $qty,
            'price' => $price,
            'img' => $imageName // save filename to DB
        ];


        $result = insertData('product_detail', $mysql, $data);
        if ($result) {
            $url = $admin_base_url . 'product_detail_list.php?success=Created Success';
            header("Location: $url");
            exit;
        } else {
            $error = true;
            $error_message = "Product Create Fail.";
        }
    }
}
require './layouts/header.php';
?>

<!--**********************************
            Content body start
        ***********************************-->
<div class="content-body">
    <div class="container-fluid">
        <div class="d-flex justify-content-between">
            <h1>Product Details Create</h1>
            <div class="">
                <a href="<?= $admin_base_url . 'category_list.php' ?>" class="btn btn-dark">
                    Back
                </a>
            </div>
        </div>
        <div class="d-flex justify-content-center">
            <div class="col-md-6 col-sm-10 col-12">
                <?php if ($error && $error_message) { ?>
                    <div class="alert alert-danger">
                        <?= $error_message ?>
                    </div>
                <?php } ?>
                <div class="card">
                    <div class="card-body">
                        <form action="" enctype="multipart/form-data" method="POST">
                            <div class="form-group">
                                <label>Brand</label>
                                <select name="brand_id" class="form-control">
                                    <option value=""> Select Brand </option>
                                    <?php
                                    $brands = $mysql->query("SELECT brand_id, brand_name FROM brand");
                                    while ($row = $brands->fetch_assoc()) {
                                        $selected = ($row['brand_id'] == $brand_id) ? 'selected' : '';
                                        echo "<option value='{$row['brand_id']}' {$selected}>{$row['brand_name']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Category</label>
                                <select name="category_id" class="form-control">
                                    <option value=""> Select Category </option>
                                    <?php
                                    $categories = $mysql->query("SELECT category_id, category_name FROM category");
                                    while ($row = $categories->fetch_assoc()) {
                                        $selected = ($row['category_id'] == $category_id) ? 'selected' : '';
                                        echo "<option value='{$row['category_id']}' {$selected}>{$row['category_name']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="" class="form-label">Description</label>
                                <input type="text" name="description" class="form-control" id="description" value="" />
                                <?php if ($error && $description_error) { ?>
                                    <span class="text-danger"><?= $description_error ?></span>
                                <?php } ?>
                            </div>
                            <div class="form-group">
                                <label for="" class="form-label">Quantity</label>
                                <input type="text" name="qty" class="form-control" id="qty" value="<?= $qty ?>" />
                                <?php if ($error && $qty_error) { ?>
                                    <span class="text-danger"><?= $qty_error ?></span>
                                <?php } ?>
                            </div>
                            <div class="form-group">
                                <label for="" class="form-label">Price</label>
                                <input type="text" name="price" class="form-control" id="price" value="<?= $price ?>" />
                                <?php if ($error && $price_error) { ?>
                                    <span class="text-danger"><?= $price_error ?></span>
                                <?php } ?>
                            </div>
                            <div class="form-group">
                                <label for="img" class="form-label">Product Image</label>
                                <input type="file" name="img" class="form-control" id="img" accept="image/*" />
                                <?php if ($error && $img_error) { ?>
                                    <span class="text-danger"><?= $img_error ?></span>
                                <?php } ?>
                            </div>
                            <input type="hidden" name="form_sub" value="1" />
                            <button type="submit" class="btn btn-primary w-100">Create</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- #/ container -->
</div>
<!--**********************************
            Content body end
        ***********************************-->

<?php
require './layouts/footer.php';
