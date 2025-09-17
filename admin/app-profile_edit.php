<?php
require '../database/dbrequire.php';
require './layouts/common.php';
require '../database/common_function.php';


$error = false;
$error_message =
    $name_error =
    $name = '';

if (isset($_GET['id']) && $_GET['id'] !== '') {
    $id = $_GET['id'];
    $select_res = selectData('user', $mysql, "WHERE id='$id'");

    if ($select_res->num_rows > 0) {
        $data = $select_res->fetch_assoc();
        $name = $data['name'];
        $email = $data['email'];
        $role = $data['role'];
    } else {
        $url = $admin_base_url . "app-profile.php?error=Id Not Found";
        header("Location: $url");
    }
} else {
    $url = $admin_base_url . "app-profile.php?error=Id Not Found";
    header("Location: $url");
}

if (isset($_POST['form_sub']) && $_POST['form_sub'] == '1') {
    $name = $mysql->real_escape_string($_POST['name']);
    $email = $data['email'];
    $role = $mysql->real_escape_string($_POST['role']);
    if ($name === '' || strlen($name) === 0) {
        $error = true;
        $name_error = "Please Fill Name.";
    } else if (strlen($name) < 2) {
        $error = true;
        $name_error = "Name must be fill greater then 3.";
    } else if (strlen($name) > 100) {
        $error = true;
        $name_error = "Name must be fill less then 100.";
    }
    if (!$error) {
        $data = [
            'name' => $name,
            'email' => $email,
            'role' => $role,
        ];
        $where = [
            'id' => $id,
        ];
        $result = updateData('user', $mysql, $data, $where);
        if ($result) {
            $url = $admin_base_url . 'app-profile.php?id=' . $id . '&success=Profile Update Successfully';
            header("Location: $url");
            exit;
        } else {
            $error = true;
            $error_message = "Update Fail.";
        }
    }
}
require './layouts/header.php';
?>

<style>
    .role-selector {
        display: flex;
        gap: 10px;
        margin-top: 8px;
    }

    .role-option {
        flex: 1;
        padding: 15px 20px;
        background: rgba(255, 255, 255, 0.15);
        border: 2px solid rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        text-align: center;
        backdrop-filter: blur(8px);
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
    }

    .role-option:hover {
        background: rgba(255, 255, 255, 0.25);
        border-color: rgba(255, 255, 255, 0.4);
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .role-option.active {
        background: rgba(13, 110, 253, 0.2);
        border-color: rgba(13, 110, 253, 0.5);
        box-shadow: 0 0 15px rgba(13, 110, 253, 0.3);
    }

    .role-option i {
        font-size: 24px;
        color: #333;
    }

    .role-option.active i {
        color: #0d6efd;
    }

    .role-option span {
        font-weight: 600;
        color: #333;
        font-size: 14px;
    }

    .role-option.active span {
        color: #0d6efd;
    }

    .profile-image-preview {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid rgba(255, 255, 255, 0.3);
    }
</style>

<!--**********************************
            Content body start
        ***********************************-->
<div class="content-body">
    <div class="container-fluid">
        <div class="d-flex justify-content-between">
            <h1>User Information Update</h1>
            <div class="">
                <a href="<?= $admin_base_url . 'user_list.php' ?>" class="btn btn-dark">
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
                        <form action="<?= './app-profile_edit.php?id=' . $id ?>" method="POST">
                            <div class="form-group">
                                <label for="" class="form-label">Name</label>
                                <input type="text" name="name" class="form-control" id="name" value="<?= $name ?>" />
                                <?php if ($error && $name_error) { ?>
                                    <span class="text-danger"><?= $name_error ?></span>
                                <?php } ?>
                            </div>
                            <div class="form-group">
                                <label for="" class="form-label">Email</label>
                                <input type="text" name="email" class="form-control" id="email" placeholder="<?= $email ?>" />
                                <?php if ($error && $name_error) { ?>
                                    <span class="text-danger"><?= $name_error ?></span>
                                <?php } ?>
                            </div>

                            <div class="form-group mb-3">
                                <label for="role" class="form-label">Role</label>
                                <div class="role-selector">
                                    <div class="role-option <?= $role == 'customer' ? 'active' : '' ?>" value="customer">
                                        <i class="fas fa-user"></i>
                                        <span>Customer</span>
                                    </div>
                                    <div class="role-option <?= $role == 'admin' ? 'active' : '' ?>" value="admin">
                                        <i class="fas fa-user-shield"></i>
                                        <span>Admin</span>
                                    </div>
                                    <input type="hidden" name="role" id="role_input" value="<?= $role ?>" />
                                </div>
                            </div>

                            <input type="hidden" name="form_sub" value="1" />
                            <button type="submit" class="btn btn-primary w-100">Update</button>
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
?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const roleOptions = document.querySelectorAll('.role-option');
        const roleInput = document.getElementById('role');

        roleOptions.forEach(option => {
            option.addEventListener('click', function() {
                // Remove active class from all options
                roleOptions.forEach(opt => opt.classList.remove('active'));

                // Add active class to clicked option
                this.classList.add('active');

                // Update hidden input value
                roleInput.value = this.getAttribute('data-value');
            });
        });
    });
</script>