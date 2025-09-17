<?php
require '../database/dbrequire.php';
select_database($mysql);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = intval($_POST['order_id'] ?? 0);
    $status   = $_POST['status'] ?? '';

    if ($order_id > 0 && $status !== '') {
        $stmt = $mysql->prepare("UPDATE orders SET status = ? WHERE order_id = ?");
        $stmt->bind_param("si", $status, $order_id);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Database update failed']);
        }
        $stmt->close();
    } else {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Invalid input']);
    }
    exit;
}

http_response_code(405);
echo json_encode(['success' => false, 'error' => 'Method not allowed']);
