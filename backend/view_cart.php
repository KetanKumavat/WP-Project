<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

include 'cart.php';

header('Content-Type: application/json');
echo json_encode(getCartItems());
?>