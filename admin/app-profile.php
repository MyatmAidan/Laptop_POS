<?php

session_start();

require_once '../database/dbrequire.php';
require_once '../database/common_function.php';


if (isset($_GET['id']) && $_GET['id'] !== '') {
    $id = $_GET['id'];
    $profile_sql = selectData('user', $mysql, "", "*", "WHERE id = $id");

    if ($profile_sql->num_rows > 0) {
        $profile = $profile_sql->fetch_assoc();
        $name = $profile['name'] ?? '';
        $email = $profile['email'] ?? '';
        $role = $profile['role'] ?? '';
        $joined_date = date('d M Y', strtotime($profile['created_at']));
        $fixed_at = date('M d Y', strtotime($profile['updated_at']));
    } else {
        $url = $admin_base_url . "user_list.php?error=Id Not Found";
        header("Location: $url");
        exit;
    }
} else {
    $url = $admin_base_url . "user_list.php?error=Id Not Found";
    header("Location: $url");
    exit;
}


require_once('./layouts/header.php');
?>

<!--**********************************
            Content body start
        ***********************************-->
<div class="content-body">

    <!-- row -->

    <div class="container-fluid">
        <!-- <div class="row"> -->
        <div class="col-lg-12 col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="media align-items-center mb-4">
                        <img class="mr-3" src="images/avatar/11.png" width="80" height="80" alt="">
                        <div class="media-body">
                            <h3 class="mb-0"><?= $name ?></h3>
                            <?php
                            $roleBadge = ($role === 'customer') ? 'badge bg-warning text-dark' : (($role === 'admin') ? 'badge bg-success' : 'badge bg-secondary');
                            ?>
                            <h5><span class="<?= $roleBadge ?>"><?= ucfirst($role) ?></span></h5>

                        </div>
                    </div>

                    <div class="row mb-5">
                        <div class="col">
                            <div class="card card-profile text-center">
                                <span class="mb-1 text-primary"><i class="icon-clock"></i></span>
                                <h3 class="mb-0"><?= $joined_date ?></h3>
                                <p class="text-muted px-4">Joined on</p>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card card-profile text-center">
                                <span class="mb-1 text-warning"><i class="icon-refresh"></i></span>
                                <h3 class="mb-0"><?= $fixed_at ?></h3>
                                <p class="text-muted">Updated</p>
                            </div>
                        </div>

                    </div>

                    <h4>About Me</h4>
                    <p class="text-muted">Hi, I'm Pikamy, has been the industry standard dummy text ever
                        since the 1500s.</p>
                    <ul class="card-profile__info">
                        <li class="mb-1"><strong class="text-dark mr-4">Mobile</strong>
                            <span>01793931609</span>
                        </li>
                        <li><strong class="text-dark mr-4">Email</strong> <span><?= $email ?></span></li>
                    </ul>
                    <?php
                    $current_user_id = $_SESSION['user_id'] ?? 0;
                    ?>
                    <div class="col-12 text-right">
                        <?php if ($current_user_id == $id): ?>
                            <a href="<?= $admin_base_url . 'app-profile_edit.php?id=' . $id ?>"
                                class="btn btn-success px-4">
                                Fix Profile <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <button type="button" onclick="history.back()" class="btn btn-secondary px-4 ml-2">
                                Back
                            </button>
                        <?php else: ?>
                            <button type="button" onclick="history.back()" class="btn btn-secondary px-4">
                                Back
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-xl-12">
            <div class="card">
                <div class="card-body">
                    <form action="#" class="form-profile">
                        <div class="form-group">
                            <textarea class="form-control" name="textarea" id="textarea" cols="30" rows="2"
                                placeholder="Post a new message"></textarea>
                        </div>
                        <div class="d-flex align-items-center">
                            <ul class="mb-0 form-profile__icons">
                                <li class="d-inline-block">
                                    <button class="btn btn-transparent p-0 mr-3"><i
                                            class="fa fa-user"></i></button>
                                </li>
                                <li class="d-inline-block">
                                    <button class="btn btn-transparent p-0 mr-3"><i
                                            class="fa fa-paper-plane"></i></button>
                                </li>
                                <li class="d-inline-block">
                                    <button class="btn btn-transparent p-0 mr-3"><i
                                            class="fa fa-camera"></i></button>
                                </li>
                                <li class="d-inline-block">
                                    <button class="btn btn-transparent p-0 mr-3"><i
                                            class="fa fa-smile"></i></button>
                                </li>
                            </ul>
                            <button class="btn btn-primary px-3 ml-4">Send</button>
                        </div>
                    </form>
                </div>
            </div>


        </div>
        <!-- </div> -->

    </div>
    <!-- #/ container -->
</div>
<!--**********************************
            Content body end
        ***********************************-->


<!--**********************************
            Footer start
        ***********************************-->
<div class="footer">
    <div class="copyright">
        <p>Copyright &copy; Designed & Developed by <a href="https://themeforest.net/user/quixlab">Quixlab</a>
            2018</p>
    </div>
</div>
<!--**********************************
            Footer end
        ***********************************-->
</div>
<!--**********************************
        Main wrapper end
    ***********************************-->

<!--**********************************
        Scripts
    ***********************************-->
<script src="plugins/common/common.min.js"></script>
<script src="js/custom.min.js"></script>
<script src="js/settings.js"></script>
<script src="js/gleek.js"></script>
<script src="js/styleSwitcher.js"></script>

</body>

</html>