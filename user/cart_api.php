<?php
session_start();
header('Content-Type: application/json');

try {
    $raw = file_get_contents('php://input');
    if ($raw === false) {
        echo json_encode(['success' => false, 'message' => 'No payload']);
        exit;
    }
    $payload = json_decode($raw, true);
    if (!is_array($payload) || !isset($payload['cart']) || !is_array($payload['cart'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid payload']);
        exit;
    }

    // Normalize and save
    $_SESSION['cart'] = [];
    foreach ($payload['cart'] as $item) {
        $id = isset($item['id']) ? (int)$item['id'] : 0;
        $name = isset($item['name']) ? trim($item['name']) : '';
        $price = isset($item['price']) ? (float)$item['price'] : 0.0;
        $qty = isset($item['qty']) ? (int)$item['qty'] : 1;
        $img = isset($item['img']) ? trim($item['img']) : '';

        if ($id <= 0 || $name === '' || $price <= 0 || $qty <= 0) {
            continue;
        }

        // Ensure cart.php path compatibility: it renders ./admin/{$img}
        // store.php passes filename from DB; build "upload/filename"
        if ($img !== '' && strpos($img, 'upload/') === false) {
            $img = 'upload/' . ltrim($img, '/');
        }

        $_SESSION['cart'][] = [
            'id' => $id,
            'name' => $name,
            'price' => $price,
            'qty' => $qty,
            'img' => $img,
        ];
    }

    // Mirror alt key used in navbar
    $_SESSION['CART'] = $_SESSION['cart'];

    $totalDistinct = isset($_SESSION['CART']) ? count($_SESSION['CART']) : 0;
    echo json_encode(['success' => true, 'count' => $totalDistinct]);
} catch (Throwable $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
