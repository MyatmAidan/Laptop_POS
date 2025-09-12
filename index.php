<?php
require_once 'auth.php';

// If user is logged in, redirect to appropriate area
if (isLoggedIn()) {
    if (isAdmin()) {
        header('Location: admin/index.php');
    } else {
        header('Location: user/index.php');
    }
    exit;
}

// If not logged in, redirect to login page
header('Location: login.php');
exit;
