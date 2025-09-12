<?php
session_start();
require_once 'database/dbrequire.php';
require_once 'auth.php';


// Redirect if already logged in
redirectIfLoggedIn();

$error_message = '';
$success_message = '';

// Handle logout message
if (isset($_GET['message'])) {
  $success_message = $_GET['message'];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $emailOrUser = $_POST['email'] ?? '';
  $password = $_POST['password'] ?? '';

  if ($emailOrUser === '' || $password === '') {
    $error_message = 'Please fill in all fields';
  } else {
    // Support username or email
    $stmt = $mysql->prepare("SELECT id, name, email, password, role FROM user WHERE email = ? OR name = ? LIMIT 1");
    $stmt->bind_param('ss', $emailOrUser, $emailOrUser);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
      $user = $result->fetch_assoc();
      if (password_verify($password, $user['password'])) {
        $_SESSION['SESS_USER_ID'] = (int)$user['id'];
        $_SESSION['SESS_USERNAME'] = $user['name'];
        $_SESSION['SESS_IS_ADMIN'] = ($user['role'] === 'admin') ? 1 : 0;
        $_SESSION['user_id'] = (int)$user['id'];

        $redirect = isset($_SESSION['REDIRECT_AFTER_LOGIN']) ? $_SESSION['REDIRECT_AFTER_LOGIN'] : 'index.php';
        unset($_SESSION['REDIRECT_AFTER_LOGIN']);

        // Redirect based on role
        if ($user['role'] === 'admin') {
          header('Location: admin/index.php');
        } else {
          header('Location: user/index.php');
        }
        exit;
      }
    }
    $error_message = 'Invalid credentials';
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Laptop Store</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .login-card {
      background: white;
      border-radius: 15px;
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
      overflow: hidden;
      max-width: 400px;
      width: 100%;
    }

    .login-header {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      padding: 30px;
      text-align: center;
    }

    .login-body {
      padding: 40px 30px;
    }

    .form-control {
      border-radius: 10px;
      border: 2px solid #e9ecef;
      padding: 12px 15px;
      transition: all 0.3s ease;
    }

    .form-control:focus {
      border-color: #667eea;
      box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    .btn-login {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      border: none;
      border-radius: 10px;
      padding: 12px;
      font-weight: 600;
      transition: all 0.3s ease;
    }

    .btn-login:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    }

    .input-group-text {
      background: transparent;
      border: 2px solid #e9ecef;
      border-right: none;
      border-radius: 10px 0 0 10px;
    }

    .form-control.with-icon {
      border-left: none;
      border-radius: 0 10px 10px 0;
    }
  </style>
</head>

<body>
  <div class="login-card">
    <div class="login-header">
      <h2><i class="fas fa-laptop"></i> Laptop Store</h2>
      <p class="mb-0">Welcome back! Please sign in to your account.</p>
    </div>
    <div class="login-body">
      <?php if ($error_message): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <i class="fas fa-exclamation-triangle"></i> <?php echo htmlspecialchars($error_message); ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      <?php endif; ?>

      <?php if ($success_message): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success_message); ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      <?php endif; ?>

      <form method="POST">
        <div class="mb-3">
          <label class="form-label">Username or Email</label>
          <div class="input-group">
            <span class="input-group-text">
              <i class="fas fa-user"></i>
            </span>
            <input type="text" class="form-control with-icon" name="email" required>
          </div>
        </div>

        <div class="mb-4">
          <label class="form-label">Password</label>
          <div class="input-group">
            <span class="input-group-text">
              <i class="fas fa-lock"></i>
            </span>
            <input type="password" class="form-control with-icon" name="password" required>
          </div>
        </div>

        <button type="submit" class="btn btn-primary btn-login w-100 mb-3">
          <i class="fas fa-sign-in-alt"></i> Sign In
        </button>

        <div class="text-center">
          <p class="mb-0">Don't have an account?
            <a href="register.php" class="text-decoration-none">Register here</a>
          </p>
        </div>
      </form>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>