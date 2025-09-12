<?php
session_start();
header('Content-Type: application/json');

// Ensure cart array exists
if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Expected params: product_detail_id, name, price, img, qty (optional)
$productId = isset($_POST['product_detail_id']) ? (int)$_POST['product_detail_id'] : 0;
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$price = isset($_POST['price']) ? (float)$_POST['price'] : 0;
$img = isset($_POST['img']) ? trim($_POST['img']) : '';
$qty = isset($_POST['qty']) ? (int)$_POST['qty'] : 1;

if ($productId <= 0 || $name === '' || $price <= 0) {
    echo json_encode(['success' => false, 'message' => 'Missing or invalid product data']);
    exit;
}

// If item exists, increment qty; else add new
$found = false;
foreach ($_SESSION['cart'] as &$item) {
    if ((int)$item['id'] === $productId) {
        $item['qty'] += $qty;
        $found = true;
        break;
    }
}
unset($item);

if (!$found) {
    $_SESSION['cart'][] = [
        'id' => $productId,
        'name' => $name,
        'price' => $price,
        'qty' => $qty,
        // cart.php expects to render "./admin/{$img}" so store path under admin folder (e.g., upload/filename.jpg)
        'img' => $img,
    ];
}

// Compute cart count and totals
$totalItems = 0;
$grandTotal = 0;
foreach ($_SESSION['cart'] as $ci) {
    $totalItems += (int)$ci['qty'];
    $grandTotal += ((float)$ci['price']) * ((int)$ci['qty']);
}

// Also mirror alternate session keys some parts of app might use
$_SESSION['CART'] = $_SESSION['cart'];


echo json_encode([
    'success' => true,
    'message' => 'Added to cart',
    'cart_count' => $totalItems,
    'grand_total' => $grandTotal,
]);
