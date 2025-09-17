<?php
require_once __DIR__ . '/../config.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title>Laptop Store - Premium Laptops & Accessories</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Discover premium laptops, accessories, and tech gadgets at Laptop Store. Best prices and quality guaranteed.">

  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="css/styles.css?v=<?= time(); ?>">
  <link rel="icon" href="favicon.ico" type="image/x-icon">

  <style>
    :root {
      --primary-color: #2563eb;
      --secondary-color: #1e40af;
      --accent-color: #f59e0b;
      --success-color: #10b981;
      --warning-color: #f59e0b;
      --danger-color: #ef4444;
      --dark-color: #1f2937;
      --light-color: #f8fafc;
      --gray-color: #6b7280;
      --border-color: #e5e7eb;
      --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
      --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
      --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
      --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      line-height: 1.6;
      color: var(--dark-color);
      background-color: var(--light-color);
    }

    .navbar {
      background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
      box-shadow: var(--shadow-lg);
      padding: 1rem 0;
    }

    .navbar-brand {
      font-size: 1.75rem;
      font-weight: 800;
      color: white !important;
      text-decoration: none;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .navbar-brand:hover {
      color: var(--accent-color) !important;
      transform: scale(1.05);
      transition: all 0.3s ease;
    }

    .navbar-nav .nav-link {
      color: rgba(255, 255, 255, 0.9) !important;
      font-weight: 500;
      padding: 0.5rem 1rem;
      border-radius: 0.5rem;
      transition: all 0.3s ease;
      position: relative;
    }

    .navbar-nav .nav-link:hover,
    .navbar-nav .nav-link.active {
      color: white !important;
      background-color: rgba(255, 255, 255, 0.1);
      transform: translateY(-2px);
    }

    .navbar-nav .nav-link.active::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 50%;
      transform: translateX(-50%);
      width: 20px;
      height: 3px;
      background-color: var(--accent-color);
      border-radius: 2px;
    }

    .cart-icon {
      position: relative;
      color: white;
      text-decoration: none;
      padding: 0.5rem;
      border-radius: 0.5rem;
      transition: all 0.3s ease;
    }

    .cart-icon:hover {
      background-color: rgba(255, 255, 255, 0.1);
      color: var(--accent-color);
      transform: scale(1.1);
    }

    .cart-badge {
      position: absolute;
      top: -5px;
      right: -5px;
      background-color: var(--accent-color);
      color: white;
      border-radius: 50%;
      padding: 0.25rem 0.5rem;
      font-size: 0.75rem;
      font-weight: 600;
      min-width: 20px;
      text-align: center;
      animation: pulse 2s infinite;
    }

    @keyframes pulse {
      0% {
        transform: scale(1);
      }

      50% {
        transform: scale(1.1);
      }

      100% {
        transform: scale(1);
      }
    }

    .user-dropdown .dropdown-toggle {
      color: white !important;
      background-color: rgba(255, 255, 255, 0.1);
      border: 1px solid rgba(255, 255, 255, 0.2);
      border-radius: 0.5rem;
      padding: 0.5rem 1rem;
      transition: all 0.3s ease;
    }

    .user-dropdown .dropdown-toggle:hover {
      background-color: rgba(255, 255, 255, 0.2);
      transform: translateY(-2px);
    }

    .dropdown-menu {
      background: white;
      border: none;
      border-radius: 0.75rem;
      box-shadow: var(--shadow-xl);
      padding: 0.5rem 0;
      margin-top: 0.5rem;
    }

    .dropdown-item {
      padding: 0.75rem 1.5rem;
      color: var(--dark-color);
      transition: all 0.2s ease;
      border-radius: 0.5rem;
      margin: 0 0.5rem;
    }

    .dropdown-item:hover {
      background-color: var(--primary-color);
      color: white;
      transform: translateX(5px);
    }

    .btn-login {
      background: linear-gradient(135deg, var(--accent-color) 0%, #f97316 100%);
      border: none;
      color: white;
      padding: 0.5rem 1.5rem;
      border-radius: 0.5rem;
      font-weight: 600;
      transition: all 0.3s ease;
      text-decoration: none;
    }

    .btn-login:hover {
      transform: translateY(-2px);
      box-shadow: var(--shadow-lg);
      color: white;
    }

    .alert {
      border: none;
      border-radius: 0.75rem;
      box-shadow: var(--shadow-sm);
      margin: 1rem 0;
    }

    .alert-success {
      background: linear-gradient(135deg, var(--success-color) 0%, #059669 100%);
      color: white;
    }

    .alert-warning {
      background: linear-gradient(135deg, var(--warning-color) 0%, #d97706 100%);
      color: white;
    }

    .btn-close {
      filter: invert(1);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      .navbar-brand {
        font-size: 1.5rem;
      }

      .navbar-nav .nav-link {
        padding: 0.75rem 1rem;
        margin: 0.25rem 0;
      }
    }

    /* Smooth scrolling */
    html {
      scroll-behavior: smooth;
    }

    /* Custom scrollbar */
    ::-webkit-scrollbar {
      width: 8px;
    }

    ::-webkit-scrollbar-track {
      background: #f1f1f1;
      border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb {
      background: var(--primary-color);
      border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
      background: var(--secondary-color);
    }
  </style>

  <!-- Bootstrap 5 JavaScript -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

</head>