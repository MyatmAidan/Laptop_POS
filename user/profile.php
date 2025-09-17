<?php
session_start();
require_once '../auth.php';
require_once '../database/dbrequire.php';
require_once '../database/common_function.php';
require '../admin/layouts/common.php';
include 'includes/header.php';
include 'includes/nav.php';


if (isset($_GET['user_id']) && $_GET['user_id'] !== '') {
  $id = $_GET['user_id'];
  $profile_sql = selectData('user', $mysql, "", "*", "WHERE id = $id");

  if ($profile_sql->num_rows > 0) {
    $profile = $profile_sql->fetch_assoc();
    $name = $profile['name'] ?? '';
    $email = $profile['email'] ?? '';
    $role = $profile['role'] ?? '';
    $joined_date = date('d M Y', strtotime($profile['created_at']));
    $fixed_at = date('M d Y', strtotime($profile['updated_at']));
  } else {
    $url = $user_base_url . "index.php?error=Id Not Found";
    header("Location: $url");
    exit;
  }
} else {
  $url = $user_base_url . "profile.php?error=Id Not Found";
  header("Location: $url");
  exit;
}
?>

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

  .cart-icon {
    position: relative;
    color: white !important;
  }

  .cart-badge {
    position: absolute;
    top: -8px;
    right: -10px;
    background-color: var(--danger-color);
    color: white;
    border-radius: 50%;
    padding: 2px 6px;
    font-size: 0.75rem;
    font-weight: bold;
  }

  .user-dropdown .dropdown-menu {
    border-radius: 0.5rem;
    box-shadow: var(--shadow-md);
  }

  .user-dropdown .dropdown-item:hover {
    background-color: var(--light-color);
    color: var(--primary-color) !important;
  }

  .card {
    border: none;
    border-radius: 1rem;
    box-shadow: var(--shadow-lg);
  }
</style>


<!--**********************************
            Content body start
        ***********************************-->
<br>
<div class="content-body">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-md-8 col-lg-6">
        <div class="card">
          <div class="card-body">

            <!-- Profile Header -->
            <div class="media align-items-center mb-4">
              <img class="mr-3 rounded-circle" src="../admin/images/avatar/11.png" width="80" height="80" alt="">
              <div class="media-body">
                <h3 class="mb-0"><?= $name ?></h3>
              </div>
            </div>

            <!-- Joined / Updated -->
            <div class="row mb-5 justify-content-center">
              <div class="col-6 col-md-5 mb-3">
                <div class="card card-profile text-center">
                  <span class="mb-1 text-primary"><i class="fa-solid fa-clock"></i></span>
                  <h3 class="mb-0"><?= $joined_date ?></h3>
                  <p class="text-muted px-2">Joined on</p>
                </div>
              </div>
              <div class="col-6 col-md-5 mb-3">
                <div class="card card-profile text-center">
                  <span class="mb-1 text-warning"><i class="fa-solid fa-rotate-right"></i></span>
                  <h3 class="mb-0"><?= $fixed_at ?></h3>
                  <p class="text-muted px-2">Updated</p>
                </div>
              </div>
            </div>

            <!-- About Me -->
            <h4>About Me</h4>
            <p class="text-muted">
              Hi, I'm Pikamy, has been the industry standard dummy text ever since the 1500s.
            </p>
            <ul class="card-profile__info mb-4">
              <li class="mb-1"><strong class="text-dark mr-4">Mobile</strong> <span>01793931609</span></li>
              <li><strong class="text-dark mr-4">Email</strong> <span><?= $email ?></span></li>
            </ul>

            <!-- Buttons -->
            <div class="text-end">
              <a href="<?= $user_base_url . 'profile_edit.php?id=' . $id ?>"
                class="btn btn-success px-4">
                Fix Profile <i class="fa-solid fa-pen-to-square"></i>
              </a>
              <button type="button" onclick="history.back()" class="btn btn-secondary px-4 ms-2">Back</button>
            </div>
          </div>
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

<!--**********************************
        Scripts
    ***********************************-->
<script src="plugins/common/common.min.js"></script>
<script src="js/custom.min.js"></script>
<script src="js/settings.js"></script>
<script src="js/gleek.js"></script>
<script src="js/styleSwitcher.js"></script>

<?php include 'includes/footer.php'; ?>