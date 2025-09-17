<?php
require '../database/dbrequire.php';
require './layouts/common.php';
require '../database/common_function.php';

$error = false;
$error_message =
    $name_error =
    $name = '';
if (isset($_POST['form_sub']) && $_POST['form_sub'] == '1') {
    $name = $mysql->real_escape_string(trim($_POST['name']));

    if ($name === '' || strlen($name) === 0) {
        $error = true;
        $name_error = "Please Fill Brand Name.";
    } else if (strlen($name) < 2) {
        $error = true;
        $name_error = "Brand name must be fill greater then 2.";
    } else if (strlen($name) > 100) {
        $error = true;
        $name_error = "Brand name must be fill less then 100.";
    }

    if (!$error) {
        // normalize to lowercase for checking
        $check_sql = "SELECT brand_id FROM brand WHERE LOWER(brand_name) = LOWER('$name') LIMIT 1";
        $check_res = $mysql->query($check_sql);

        if ($check_res && $check_res->num_rows > 0) {
            $error = true;
            $error_message = "Brand already exists.";
        } else {
            $data = ['brand_name' => $name];
            $result = insertData('brand', $mysql, $data);

            if ($result) {
                $url = $admin_base_url . 'brand_list.php?success=Created Success';
                header("Location: $url");
                exit;
            } else {
                $error = true;
                $error_message = "Brand Create Fail.";
            }
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
            <h1>Brand Create</h1>
            <div class="">
                <a href="<?= $admin_base_url . 'brand_list.php' ?>" class="btn btn-dark">
                    Back
                </a>
            </div>
        </div>
        <div class="d-flex justify-content-center">
            <div class="col-md-6 col-sm-10 col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="" method="POST">
                            <div class="form-group">
                                <label for="" class="form-label">Name</label>
                                <input type="text" name="name" class="form-control" id="name" value="<?= htmlspecialchars($name) ?>" />
                                <?php if ($error && $name_error) { ?>
                                    <span class="text-danger"><?= $name_error ?></span>
                                <?php } ?>
                            </div>
                            <input type="hidden" name="form_sub" value="1" />
                            <button type="submit" class="btn btn-primary w-100">Add</button>
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

<?php
require './layouts/footer.php';
?>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php if ($error && $error_message && !$name_error) { ?>
    <script>
        Swal.fire({
            icon: 'warning',
            title: 'Duplicate Brand',
            text: '<?= $error_message ?>',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
        })
    </script>
<?php } ?>