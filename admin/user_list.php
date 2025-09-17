<?php
session_start();
require '../database/dbrequire.php';
require './layouts/common.php';
require '../database/common_function.php';

$success = isset($_GET['success']) ? $_GET['success'] : '';
$error = isset($_GET['error']) ? $_GET['error'] : '';
$res = selectData('user', $mysql, "", "*", "");
$delete_id = isset($_GET['delete_id']) ?  $_GET['delete_id'] : '';
if ($delete_id !== '') {
    $res = deleteData('category', $mysql, "id=$delete_id");
    if ($res) {
        $url = $admin_base_url . "user_list.php?success=Delete Category Success";
        header("Location: $url");
    }
}
$current_user_id = $_SESSION['user_id'] ?? 0;

require './layouts/header.php';
?>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="content-body">
    <div class="container-fluid">
        <div class="d-flex justify-content-between">
            <h1>User List</h1>
            <div class="">
                <a href="javascript:history.back()" class="btn btn-secondary">
                    Back
                </a>
            </div>
        </div>

        <div class="row">
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
            <div class="col-12">

                <div class="card">
                    <div class="card-body">
                        <table class="table table-hover table-sm">
                            <thead>
                                <tr>
                                    <th class="col-2">No.</th>
                                    <th class="col-3">Name</th>
                                    <th class="col-3">Email</th>
                                    <th class="col-2">Role</th>
                                    <th class="col-2">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($res->num_rows > 0) {
                                    while ($row = $res->fetch_assoc()) { ?>
                                        <tr>
                                            <td><?= $row['id'] ?></td>
                                            <td><?= $row['name'] ?></td>
                                            <td><?= $row['email'] ?></td>
                                            <td><?= $row['role'] ?></td>
                                            <td>
                                                <a href="<?= $admin_base_url . 'app-profile.php?id=' . $row['id'] ?>" class="btn btn-sm btn-info">
                                                    <i class="fa-solid fa-eye"></i>
                                                </a>

                                                <?php if ($row['id'] != $current_user_id): ?>
                                                    <button data-id="<?= $row['id'] ?>" class="btn btn-sm btn-danger delete_btn">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                <?php endif; ?>
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
    </div>
    <!-- #/ container -->
</div>
<!--**********************************
            Content body end
        ***********************************-->
<script>
    $(document).ready(function() {
        $('.delete_btn').click(function() {
            console.log('click');
            const id = $(this).data('id')
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
                    window.location.href = 'user_list.php?delete_id=' + id
                }
            });
        })

    })
</script>
<?php
require './layouts/footer.php';
