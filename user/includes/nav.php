<body>
  <div class="wrap">
    <!-- Modern Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
      <div class="container">
        <!-- Brand Logo -->
        <a class="navbar-brand" href="index.php">
          <i class="fas fa-laptop-code me-2"></i>
          Laptop Store
        </a>

        <!-- Mobile Toggle Button -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navigation Items -->
        <div class="collapse navbar-collapse" id="navbarMain">
          <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
            <li class="nav-item px-2">
              <a class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], 'index.php') !== false) ? 'active' : ''; ?>" href="index.php">
                Home
              </a>
            </li>
            <li class="nav-item px-2">
              <a class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], 'store.php') !== false) ? 'active' : ''; ?>" href="store.php">
                Store
              </a>
            </li>
            <li class="nav-item px-2">
              <a class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], 'contact.php') !== false) ? 'active' : ''; ?>" href="contact.php">
                Contact
              </a>
            </li>
          </ul>

          <!-- Right Side Navigation -->
          <ul class="navbar-nav ms-auto">
            <!-- Cart Icon -->
            <li class="nav-item">
              <a class="nav-link cart-icon mx-3" href="cart.php" title="Shopping Cart">
                <i class="fas fa-shopping-cart fa-lg"></i>
                <span class="cart-badge">
                  <?php echo isset($_SESSION['CART']) ? count($_SESSION['CART']) : 0; ?>
                </span>
              </a>
            </li>

            <!-- User Menu -->
            <?php if (function_exists('isLoggedIn') ? isLoggedIn() : isset($_SESSION['SESS_USERNAME'])) { ?>
              <li class="nav-item dropdown user-dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="fas fa-user-circle me-1"></i>
                  Hi, <?php echo htmlspecialchars($_SESSION['SESS_USERNAME'], ENT_QUOTES, 'UTF-8'); ?>!
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                  <?php if (function_exists('isAdmin') ? isAdmin() : (isset($_SESSION['role']) && $_SESSION['role'] === 'admin')) { ?>
                    <li>
                      <a class="dropdown-item" href="../admin/">
                        <i class="fas fa-cog me-2"></i>Administration
                      </a>
                    </li>
                    <li>
                      <hr class="dropdown-divider">
                    </li>
                  <?php } ?>
                  <li>
                    <a class="dropdown-item <?php echo (strpos($_SERVER['REQUEST_URI'], 'profile.php') !== false) ? 'active' : ''; ?>"
                      href="profile.php?user_id=<?php echo urlencode($_SESSION['user_id']); ?>">
                      <i class="fas fa-user me-2"></i>Profile
                    </a>
                  </li>
                  <li>
                    <hr class="dropdown-divider">
                  </li>
                  <li>
                    <a class="dropdown-item" href="../logout.php">
                      <i class="fas fa-sign-out-alt me-2"></i>Logout
                    </a>
                  </li>
                </ul>
              </li>
            <?php } else { ?>
              <li class="nav-item">
                <a class="btn btn-login" href="../login.php">
                  <i class="fas fa-sign-in-alt me-1"></i>Login
                </a>
              </li>
            <?php } ?>
          </ul>
        </div>
      </div>
    </nav>

    <!-- Messages Container -->
    <div class="container" id="messages">
      <?php if (isset($_SESSION['MSGS']) && is_array($_SESSION['MSGS']) && count($_SESSION['MSGS']) > 0) { ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <i class="fas fa-check-circle me-2"></i>
          <ul class="list-unstyled mb-0">
            <?php foreach ($_SESSION['MSGS'] as $msg) {
              echo '<li>' . htmlspecialchars($msg) . '</li>';
            } ?>
          </ul>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['MSGS']); ?>
      <?php } ?>

      <?php if (isset($_SESSION['ERR_MSGS']) && is_array($_SESSION['ERR_MSGS']) && count($_SESSION['ERR_MSGS']) > 0) { ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
          <i class="fas fa-exclamation-triangle me-2"></i>
          <ul class="list-unstyled mb-0">
            <?php foreach ($_SESSION['ERR_MSGS'] as $msg) {
              echo '<li>' . htmlspecialchars($msg) . '</li>';
            } ?>
          </ul>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['ERR_MSGS']); ?>
      <?php } ?>
    </div>