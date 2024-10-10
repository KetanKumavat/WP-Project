<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

include 'cart.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['item'])) {
    removeFromCart($_POST['item']);
    echo json_encode(['status' => 'success', 'message' => 'Item removed from cart']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method or missing item']);
}
?>