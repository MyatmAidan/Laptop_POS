<?php
session_start();
require_once 'database/dbrequire.php';
require_once 'auth.php';

// Redirect if already logged in
redirectIfLoggedIn();

$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $name = trim($_POST['name'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $password = $_POST['password'] ?? '';
  $confirm_password = $_POST['confirm_password'] ?? '';
  $role = $_POST['role'] ?? 'customer';

  // Validation
  $errors = [];

  if (empty($name)) {
    $errors[] = 'Name is required';
  } elseif (strlen($name) < 3) {
    $errors[] = 'Name must be at least 3 characters long';
  }

  if (empty($email)) {
    $errors[] = 'Email is required';
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Please enter a valid email address';
  }

  if (empty($password)) {
    $errors[] = 'Password is required';
  } elseif (strlen($password) < 6) {
    $errors[] = 'Password must be at least 6 characters long';
  }

  if ($password !== $confirm_password) {
    $errors[] = 'Passwords do not match';
  }

  // Check if email already exists
  if (empty($errors)) {
    $stmt = $mysql->prepare("SELECT id FROM user WHERE email = ? LIMIT 1");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      $errors[] = 'Email address is already registered';
    }
  }

  // Check if username already exists
  if (empty($errors)) {
    $stmt = $mysql->prepare("SELECT id FROM user WHERE name = ? LIMIT 1");
    $stmt->bind_param('s', $name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      $errors[] = 'Username is already taken';
    }
  }

  if (empty($errors)) {
    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert user
    $stmt = $mysql->prepare("INSERT INTO user (name, email, password, confirm_password, role) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param('sssss', $name, $email, $hashed_password, $hashed_password, $role);

    if ($stmt->execute()) {
      $success_message = 'Registration successful! Please login with your credentials.';
      // Clear form data
      $name = $email = '';
    } else {
      $error_message = 'Registration failed. Please try again.';
    }
  } else {
    $error_message = implode('<br>', $errors);
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register - Laptop Store</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px 0;
    }

    .register-card {
      background: white;
      border-radius: 15px;
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
      overflow: hidden;
      max-width: 500px;
      width: 100%;
    }

    .register-header {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      padding: 30px;
      text-align: center;
    }

    .register-body {
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

    .btn-register {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      border: none;
      border-radius: 10px;
      padding: 12px;
      font-weight: 600;
      transition: all 0.3s ease;
    }

    .btn-register:hover {
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

    .form-select {
      border-radius: 10px;
      border: 2px solid #e9ecef;
      padding: 12px 15px;
    }

    .form-select:focus {
      border-color: #667eea;
      box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
  </style>
</head>

<body>
  <div class="register-card">
    <div class="register-header">
      <h2><i class="fas fa-laptop"></i> Laptop Store</h2>
      <p class="mb-0">Create your account to get started</p>
    </div>
    <div class="register-body">
      <?php if ($error_message): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <i class="fas fa-exclamation-triangle"></i> <?php echo $error_message; ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      <?php endif; ?>

      <?php if ($success_message): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <i class="fas fa-check-circle"></i> <?php echo $success_message; ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      <?php endif; ?>

      <form method="POST">
        <div class="mb-3">
          <label class="form-label">Full Name</label>
          <div class="input-group">
            <span class="input-group-text">
              <i class="fas fa-user"></i>
            </span>
            <input type="text" class="form-control with-icon" name="name" value="<?php echo htmlspecialchars($name ?? ''); ?>" required>
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label">Email Address</label>
          <div class="input-group">
            <span class="input-group-text">
              <i class="fas fa-envelope"></i>
            </span>
            <input type="email" class="form-control with-icon" name="email" value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label">Account Type</label>
          <select class="form-select" name="role">
            <option value="customer">Customer</option>
            <option value="admin">Admin</option>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Password</label>
          <div class="input-group">
            <span class="input-group-text">
              <i class="fas fa-lock"></i>
            </span>
            <input type="password" class="form-control with-icon" name="password" required>
          </div>
        </div>

        <div class="mb-4">
          <label class="form-label">Confirm Password</label>
          <div class="input-group">
            <span class="input-group-text">
              <i class="fas fa-lock"></i>
            </span>
            <input type="password" class="form-control with-icon" name="confirm_password" required>
          </div>
        </div>

        <button type="submit" class="btn btn-primary btn-register w-100 mb-3">
          <i class="fas fa-user-plus"></i> Create Account
        </button>

        <div class="text-center">
          <p class="mb-0">Already have an account?
            <a href="login.php" class="text-decoration-none">Sign in here</a>
          </p>
        </div>
      </form>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>