<?php
session_start();
require_once '../auth.php';
require '../database/dbrequire.php';
require '../admin/layouts/common.php';
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
        $url = $user_base_url . "profile.php?error=Id Not Found";
        header("Location: $url");
    }
} else {
    $url = $user_base_url . "profile.php?error=Id Not Found";
    header("Location: $url");
}
if (isset($_POST['form_sub']) && $_POST['form_sub'] == '1') {
    $name = $mysql->real_escape_string($_POST['name']);
    $email = $data['email'];
    $role = $data['role'];
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
            $url = $user_base_url . 'profile.php?user_id=' . $id . '&success=Profile Update Successfully';
            header("Location: $url");
            exit;
        } else {
            $error = true;
            $error_message = "Update Fail.";
        }
    }
}
?>

<?php include 'includes/header.php'; ?>
<?php include 'includes/nav.php'; ?>


<style>
    /* Custom Styles */
    body {
        background-color: #f8f9fa;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .navbar {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        box-shadow: var(--shadow-md);
    }

    .navbar-brand {
        font-size: 1.75rem;
        font-weight: bold;
        color: white !important;
    }

    .nav-link {
        color: white !important;
        font-weight: 500;
        transition: color 0.3s ease;
    }

    .nav-link:hover,
    .nav-link.active {
        color: var(--accent-color) !important;
    }

    .card {
        border: none;
        border-radius: 0.5rem;
        box-shadow: var(--shadow-md);
    }

    .card-header {
        background-color: var(--primary-color);
        color: white;
        border-bottom: none;
        border-top-left-radius: 0.5rem;
        border-top-right-radius: 0.5rem;
    }

    .form-control:focus {
        border-color: var(--accent-color);
        box-shadow: 0 0 0 0.2rem rgba(100, 149, 237, 0.25);
    }

    .btn-success {
        background-color: var(--accent-color);
        border: none;
    }

    .btn-success:hover {
        background-color: grey;
    }
</style>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Edit Profile</h4>
                </div>
                <div class="card-body">
                    <?php if ($error && $error_message !== ''): ?>
                        <div class="alert alert-danger">
                            <?php echo htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8'); ?>
                        </div>
                    <?php endif; ?>
                    <form method="POST" action="<?= './profile_edit.php?id=' . $id ?>">
                        <input type="hidden" name="form_sub" value="1">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control <?php echo $name_error ? 'is-invalid' : ''; ?>" id="name" name="name" value="<?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?>">
                            <?php if ($name_error): ?>
                                <div class="invalid-feedback">
                                    <?php echo htmlspecialchars($name_error, ENT_QUOTES, 'UTF-8'); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>" readonly>
                        </div>
                        <button type="submit" class="btn btn-success">Update Profile</button>
                        <a href="<?= $admin_base_url . 'profile.php?id=' . $id ?>" class="btn btn-secondary ms-2">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<!--**********************************
            Content body end
        ***********************************-->

<?PHP include 'includes/footer.php'; ?>