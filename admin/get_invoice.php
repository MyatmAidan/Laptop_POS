<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '../database/dbrequire.php';
select_database($mysql);

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['order_id'])) {
    $order_id = (int) $_GET['order_id'];

    // Query 1: get order info
    $order_sql = "SELECT 
        o.order_id,
        u.name AS user_name,
        u.email,
        o.total_amount,
        o.shipping_address,
        o.shipping_city,
        o.shipping_state,
        o.shipping_zip,
        o.shipping_country,
        o.status,
        o.created_at
    FROM orders o
    JOIN user u ON o.user_id = u.id
    WHERE o.order_id = ?";

    $stmt1 = $mysql->prepare($order_sql);
    if (!$stmt1) {
        http_response_code(500);
        echo json_encode(['error' => 'Prepare failed: ' . $mysql->error]);
        exit;
    }
    $stmt1->bind_param('i', $order_id);
    $stmt1->execute();
    $order_res = $stmt1->get_result();

    if (!$order_res || $order_res->num_rows === 0) {
        http_response_code(404);
        echo json_encode(['error' => 'Order not found']);
        exit;
    }
    $order = $order_res->fetch_assoc();
    $stmt1->close();

    // Query 2: get order items
    $items_sql = "SELECT 
        p.product_name,
        b.brand_name,
        oi.quantity,
        pd.price,
        (oi.quantity * pd.price) AS total
    FROM order_items oi
    JOIN product_detail pd ON oi.product_detail_id = pd.product_detail_id
    JOIN product p ON pd.product_id = p.product_id
    JOIN brand b ON pd.brand_id = b.brand_id
    WHERE oi.order_id = ?";

    $stmt2 = $mysql->prepare($items_sql);
    if (!$stmt2) {
        http_response_code(500);
        echo json_encode(['error' => 'Prepare failed: ' . $mysql->error]);
        exit;
    }
    $stmt2->bind_param('i', $order_id);
    $stmt2->execute();
    $items_res = $stmt2->get_result();

    $items = [];
    while ($row = $items_res->fetch_assoc()) {
        $items[] = $row;
    }
    $stmt2->close();

    echo json_encode([
        'status' => 'success',
        'order' => $order,
        'items' => $items
    ]);
    exit;
}

http_response_code(400);
echo json_encode(['error' => 'Invalid request']);
exit;
