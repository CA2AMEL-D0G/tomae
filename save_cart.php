<?php
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    if (is_array($data) && isset($data['cart'])) {
        $_SESSION['cart'] = $data['cart'];
        echo json_encode(['status' => 'success']);
    } else {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid cart data']);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
    echo json_encode($cart);
} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
}
