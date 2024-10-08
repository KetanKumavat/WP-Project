<?php
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type");
    exit(0);
}

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

include 'cart.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['item'])) {
    addToCart($_POST['item']);
    // header('Location: ../frontend/cart.html');
    exit();
} else {
    echo "Invalid request method or missing item.";
}
?>