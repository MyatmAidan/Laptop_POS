<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
function isLoggedIn()
{
    return isset($_SESSION['SESS_USER_ID']) || isset($_SESSION['user_id']);
}

// Check if user is admin
function isAdmin()
{
    return isset($_SESSION['SESS_IS_ADMIN']) && $_SESSION['SESS_IS_ADMIN'] == 1;
}

// Get current user ID
function getCurrentUserId()
{
    if (isset($_SESSION['SESS_USER_ID'])) {
        return (int)$_SESSION['SESS_USER_ID'];
    } elseif (isset($_SESSION['user_id'])) {
        return (int)$_SESSION['user_id'];
    }
    return 0;
}

// Get current user data
function getCurrentUser()
{
    global $mysql;
    $uid = getCurrentUserId();
    if ($uid > 0) {
        $stmt = $mysql->prepare("SELECT id, name, email, role FROM user WHERE id = ? LIMIT 1");
        $stmt->bind_param('i', $uid);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 1) {
            return $result->fetch_assoc();
        }
    }
    return null;
}

// Require authentication
function requireAuth()
{
    if (!isLoggedIn()) {
        // remember where the user wanted to go
        $_SESSION['REDIRECT_AFTER_LOGIN'] = $_SERVER['REQUEST_URI'];
        header('Location: login.php');
        exit;
    }
}

// Require admin authentication
function requireAdmin()
{
    requireAuth();
    if (!isAdmin()) {
        header('Location: ../user/index.php?error=Access denied. Admin privileges required.');
        exit;
    }
}

// Redirect if already logged in
function redirectIfLoggedIn()
{
    if (isLoggedIn()) {
        if (isAdmin()) {
            header("Location: admin/index.php");
        } else {
            header("Location: user/index.php");
        }
        exit;
    }
}

// Logout function
function logout()
{
    session_start();
    session_destroy();
    header('Location: login.php?message=Logged out successfully');
    exit;
}
