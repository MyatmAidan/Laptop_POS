<?php
require '../database/dbrequire.php';
require './layouts/common.php';
require '../database/common_function.php';

$error = false;
$error_message = $name_error = $name = '';

// -------------------- GET PRODUCT DATA --------------------
if (isset($_GET['id']) && $_GET['id'] !== '') {
    $id = intval($_GET['id']);

    $query = "
        SELECT 
            pd.product_detail_id,
            pd.description,
            pd.qty,
            pd.price,
            pd.img,
            p.product_id,
            p.product_name,
            b.brand_id,
            c.category_id
        FROM product_detail pd
        LEFT JOIN product p ON pd.product_id = p.product_id
        LEFT JOIN brand b ON pd.brand_id = b.brand_id
        LEFT JOIN category c ON pd.category_id = c.category_id
        WHERE pd.product_detail_id = '$id'
        LIMIT 1
    ";
    $select_res = $mysql->query($query);

    if ($select_res && $select_res->num_rows > 0) {
        $data = $select_res->fetch_assoc();
        $product_id    = $data['product_id'];
        $brand_id      = $data['brand_id'];
        $category_id   = $data['category_id'];
        $description   = $data['description'];
        $qty           = $data['qty'];
        $price         = $data['price'];
        $img           = $data['img'];
        $product_name  = $data['product_name'];
    } else {
        header("Location: {$admin_base_url}product_detail_list.php?error=Id Not Found");
        exit;
    }
} else {
    header("Location: {$admin_base_url}product_detail_list.php?error=Id Not Found");
    exit;
}

// -------------------- FORM SUBMIT --------------------
if (isset($_POST['form_sub']) && $_POST['form_sub'] == '1') {
    $brand_id    = $mysql->real_escape_string($_POST['brand_id']);
    $category_id = $mysql->real_escape_string($_POST['category_id']);
    $description = $mysql->real_escape_string($_POST['description']);
    $qty         = $mysql->real_escape_string($_POST['qty']);
    $price       = $mysql->real_escape_string($_POST['price']);
    $name        = $mysql->real_escape_string($_POST['name']);

    $imageName = $img;
    if (isset($_FILES['img']) && $_FILES['img']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['img']['tmp_name'];
        $fileName = $_FILES['img']['name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowedfileExtensions = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($fileExtension, $allowedfileExtensions)) {
            $uploadFileDir = './upload/';
            if (!is_dir($uploadFileDir)) {
                mkdir($uploadFileDir, 0777, true);
            }
            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
            $dest_path = $uploadFileDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                $imageName = $newFileName;
                if ($img && file_exists($uploadFileDir . $img)) {
                    unlink($uploadFileDir . $img);
                }
            } else {
                $error = true;
                $error_message = 'Error moving the uploaded file.';
            }
        } else {
            $error = true;
            $error_message = 'Allowed file types: ' . implode(',', $allowedfileExtensions);
        }
    }

    if ($name === '') {
        $error = true;
        $name_error = "Please fill Product Name.";
    }

    if (!$error) {
        try {
            $mysql->begin_transaction();

            $product_data = ['product_name' => $name];
            $product_where = ['product_id' => $product_id];
            if (!updateData('product', $mysql, $product_data, $product_where)) {
                throw new Exception("Product Update Fail.");
            }

            $data = [
                'product_id'   => $product_id,
                'brand_id'     => $brand_id,
                'category_id'  => $category_id,
                'description'  => $description,
                'qty'          => $qty,
                'price'        => $price,
                'img'          => $imageName
            ];
            $where = ['product_detail_id' => $id];
            if (!updateData('product_detail', $mysql, $data, $where)) {
                throw new Exception("Product Detail Update Fail.");
            }

            $mysql->commit();
            header("Location: {$admin_base_url}product_detail_list.php?success=Product updated successfully.");
            exit;
        } catch (Exception $e) {
            $mysql->rollback();
            $error = true;
            $error_message = $e->getMessage();
        }
    }
}

require './layouts/header.php';
?>

<div class="content-body">
    <div class="container-fluid">
        <div class="d-flex justify-content-between">
            <h1>Product Details Update</h1>
            <div class="">
                <a href="<?= $admin_base_url . 'product_detail_list.php' ?>" class="btn btn-dark">Back</a>
            </div>
        </div>
        <br>
        <br>
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-edit"></i> Edit Product</h5>
            </div>
            <div class="card-body">
                <form action="<?= './product_edit.php?id=' . $id ?>" method="POST" enctype="multipart/form-data">
                    <div class="row g-4">
                        <!-- Left column -->
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="fw-bold">Product Name</label>
                                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($product_name) ?>">
                            </div>
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
                            <div class="form-group mb-3">
                                <label class="fw-bold">Description</label>
                                <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($description) ?></textarea>
                            </div>
                        </div>

                        <!-- Right column -->
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="fw-bold">Quantity</label>
                                <input type="number" name="qty" class="form-control" value="<?= htmlspecialchars($qty) ?>">
                            </div>
                            <div class="form-group mb-3">
                                <label class="fw-bold">Price</label>
                                <input type="number" step="0.01" name="price" class="form-control" value="<?= htmlspecialchars($price) ?>">
                            </div>
                            <div class="form-group mb-3">
                                <label class="fw-bold">Image</label>
                                <?php if (!empty($img)) { ?>
                                    <div class="mb-2">
                                        <img src="./upload/<?= $img ?>" width="120" class="border rounded shadow-sm">
                                    </div>
                                <?php } ?>
                                <input type="file" name="img" class="form-control">
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="form_sub" value="1">
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-save"></i> Update Product
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

<?php require './layouts/footer.php'; ?>