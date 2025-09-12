<?php
session_start();
require_once('../database/dbrequire.php');
require_once('../database/common_function.php');

// Require login
$userId = 0;
if (isset($_SESSION['SESS_USER_ID'])) {
    $userId = (int)$_SESSION['SESS_USER_ID'];
} elseif (isset($_SESSION['user_id'])) {
    $userId = (int)$_SESSION['user_id'];
}
if ($userId <= 0) {
    $_SESSION['ERR_MSGS'] = ['Please login to checkout.'];
    header('Location: login.php');
    exit;
}

// Require cart
$cart = isset($_SESSION['cart']) && is_array($_SESSION['cart']) ? $_SESSION['cart'] : [];
if (empty($cart)) {
    $_SESSION['ERR_MSGS'] = ['Your cart is empty.'];
    header('Location: cart.php');
    exit;
}

// Calculate totals
$subtotal = 0.0;
foreach ($cart as $it) {
    $subtotal += ((float)$it['price']) * ((int)$it['qty']);
}
$grandTotal = $subtotal;

// Get shipping info from form
$address  = trim($_POST['shipping_address'] ?? '');
$city     = trim($_POST['shipping_city'] ?? '');
$state    = trim($_POST['shipping_state'] ?? '');
$zip      = trim($_POST['shipping_zip'] ?? '');
$country  = trim($_POST['shipping_country'] ?? '');

// Begin transaction
$mysql->begin_transaction();

try {
    // Create order

    $order_data = [
        'user_id' => $userId,
        'total_amount' => $grandTotal,
        'shipping_address' => $address,
        'shipping_city' => $city,
        'shipping_state' => $state,
        'shipping_zip' => $zip,
        'shipping_country' => $country,
        'status' => 'pending',
        'created_at' => date('Y-m-d H:i:s')
    ];

    // var_dump($order_data);
    // die;

    $order_sql = insertData('orders', $mysql, $order_data);
    if (!$order_sql) {
        throw new Exception("Order insert failed: " . $mysql->error);
    }
    $orderId = $mysql->insert_id;

    $method_id = 2; //default to credit card for now

    // Prepare order items & stock update
    $item_stmt   = $mysql->prepare("INSERT INTO order_items (order_id, product_detail_id, quantity, price) VALUES (?, ?, ?, ?)");
    $update_stmt = $mysql->prepare("UPDATE product_detail SET qty = qty - ? WHERE product_detail_id = ? AND qty >= ?");

    $payment_stmt = $mysql->prepare("
                INSERT INTO payment (user_id, order_id, method_id, grand_total, date, created_at) 
                VALUES (?, ?, ?, ?, NOW(), NOW())
            ");

    if ($payment_stmt) {
        $payment_stmt->bind_param('iiid', $userId, $orderId, $method_id, $grandTotal);
        if (!$payment_stmt->execute()) {
            throw new Exception("Payment insert failed: " . $payment_stmt->error);
        }
    } else {
        throw new Exception("Payment prepare failed: " . $mysql->error);
    }


    if (!$item_stmt || !$update_stmt) {
        throw new Exception("Statement prepare failed: " . $mysql->error);
    }

    foreach ($cart as $it) {
        $pid   = (int)$it['id'];
        $qty   = (int)$it['qty'];
        $price = (float)$it['price'];

        if ($qty <= 0 || $pid <= 0) continue;

        // Insert order item
        $item_stmt->bind_param('iiid', $orderId, $pid, $qty, $price);
        if (!$item_stmt->execute()) {
            throw new Exception("Order item insert failed: " . $item_stmt->error);
        }

        // Update stock
        $update_stmt->bind_param('iii', $qty, $pid, $qty);
        if (!$update_stmt->execute() || $update_stmt->affected_rows === 0) {
            throw new Exception("Failed to purchase (maybe not enough stock) for $pname.");
        }
    }

    // Commit transaction
    $mysql->commit();

    // Clear cart
    unset($_SESSION['cart'], $_SESSION['CART']);

    $_SESSION['MSGS'] = ['Order placed successfully.'];
    header('Location: order_success.php?order_id=' . $orderId);
    exit;
} catch (Exception $e) {
    // Rollback transaction if error
    $mysql->rollback();

    $_SESSION['ERR_MSGS'] = ['Checkout failed: ' . $e->getMessage()];
    header('Location: cart.php');
    exit;
}
