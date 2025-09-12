<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$change = isset($_POST['change']) ? (int)$_POST['change'] : 0;

if ($id <= 0 || $change === 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid parameters']);
    exit;
}

$updated = false;
foreach ($_SESSION['cart'] as $idx => $item) {
    if ((int)$item['id'] === $id) {
        $newQty = ((int)$item['qty']) + $change;
        if ($newQty <= 0) {
            unset($_SESSION['cart'][$idx]);
            $_SESSION['cart'] = array_values($_SESSION['cart']);
        } else {
            $_SESSION['cart'][$idx]['qty'] = $newQty;
        }
        $updated = true;
        break;
    }
}

if (!$updated) {
    echo json_encode(['success' => false, 'message' => 'Item not found in cart']);
    exit;
}

// Mirror alt key
$_SESSION['CART'] = $_SESSION['cart'];

// Recalculate totals
$grandTotal = 0.0;
$distinctCount = count($_SESSION['cart']);
$currentQty = 0;
$currentTotal = 0.0;
foreach ($_SESSION['cart'] as $it) {
    $line = ((float)$it['price']) * ((int)$it['qty']);
    $grandTotal += $line;
    if ((int)$it['id'] === $id) {
        $currentQty = (int)$it['qty'];
        $currentTotal = $line;
    }
}

echo json_encode([
    'success' => true,
    'qty' => $currentQty,
    'line_total' => number_format($currentTotal, 2, '.', ''),
    'grand_total' => number_format($grandTotal, 2, '.', ''),
    'count' => $distinctCount,
]);
