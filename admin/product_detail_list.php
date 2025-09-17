<?php
require '../database/dbrequire.php';
require './layouts/common.php';
require '../database/common_function.php';

$success = isset($_GET['success']) ? $_GET['success'] : '';
$error = isset($_GET['error']) ? $_GET['error'] : '';
$delete_id = isset($_GET['delete_id']) ? $_GET['delete_id'] : '';

if ($delete_id !== '') {
    $res = deleteData('product_detail', $mysql, "product_detail_id=$delete_id");
    if ($res) {
        header("Location: {$admin_base_url}product_detail_list.php?success=Product deleted successfully.");
        exit;
    } else {
        header("Location: {$admin_base_url}product_detail_list.php?error=Delete failed!");
        exit;
    }
}

$query = "
    SELECT pd.product_detail_id, pd.qty, pd.price, pd.img,
           p.product_name, c.category_name, b.brand_name
    FROM product_detail pd
    JOIN product p ON pd.product_id = p.product_id
    JOIN category c ON pd.category_id = c.category_id
    JOIN brand b ON pd.brand_id = b.brand_id
    ORDER BY pd.product_detail_id DESC
";
$res = $mysql->query($query);

require './layouts/header.php';
?>

<style>
    .glass-panel {
        background: rgba(255, 255, 255, 0.18);
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.18);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        border-radius: 18px;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .glass-table th,
    .glass-table td {
        background: rgba(255, 255, 255, 0.10) !important;
        border: 1px solid rgba(255, 255, 255, 0.18) !important;
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="content-body">
    <div class="container-fluid">
        <div class="d-flex justify-content-between">
            <h1>Products List</h1>
            <div class="">
                <a href="<?= $admin_base_url . 'Product_create.php' ?>" class="btn btn-primary">
                    Create New Product
                </a>
            </div>
        </div>
        <div class="col-md-4 offset-md-8 col-sm-6 offset-sm-6">

            <?php if ($success !== '') { ?>
                <div class="alert alert-success">
                    <?= $success ?>
                </div>
            <?php } ?>
            <?php if ($error !== '') { ?>
                <div class="alert alert-danger">
                    <?= $error ?>
                </div>
            <?php } ?>
        </div>
        <div class="card">
            <div class="glass-panel card-body">
                <table class="table table-hover table-sm">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Name</th>
                            <th>Brand</th>
                            <th>Category</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Image</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($res->num_rows > 0) {
                            while ($row = $res->fetch_assoc()) { ?>
                                <tr>
                                    <td><?= $row['product_detail_id'] ?></td>
                                    <td><?= htmlspecialchars($row['product_name']) ?></td>
                                    <td><?= htmlspecialchars($row['brand_name']) ?></td>
                                    <td><?= htmlspecialchars($row['category_name']) ?></td>
                                    <td><?= $row['qty'] ?></td>
                                    <td><?= $row['price'] ?></td>
                                    <td>
                                        <?php if ($row['img']) { ?>
                                            <img src="./upload/<?= $row['img'] ?>" width="100">
                                        <?php } else { ?>
                                            No Image
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <a href="<?= $admin_base_url . 'product_edit.php?id=' . $row['product_detail_id'] ?>" class="btn btn-sm btn-primary"><i class="fa fa-pen"></i></a>
                                        <button data-id="<?= $row['product_detail_id'] ?>" class="btn btn-sm btn-danger delete_btn"><i class="fa fa-trash"></i></button>
                                    </td>
                                </tr>
                        <?php }
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- SweetAlert success/error messages -->
<?php if ($success): ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: '<?= $success ?>',
            confirmButtonColor: '#3085d6'
        });
    </script>
<?php endif; ?>
<?php if ($error): ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '<?= $error ?>',
            confirmButtonColor: '#d33'
        });
    </script>
<?php endif; ?>

<script>
    $(function() {
        $('.delete_btn').click(function() {
            const id = $(this).data('id');
            Swal.fire({
                title: 'Are you sure?',
                text: "This action cannot be undone.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'product_detail_list.php?delete_id=' + id
                }
            });
        });
    });
</script>

<?php require './layouts/footer.php'; ?>