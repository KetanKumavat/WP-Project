<?php
include 'db.php';

function addToCart($item) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO cart_items (item_name, quantity) VALUES (?, ?)");
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }
    $quantity = 1; // Default quantity
    $stmt->bind_param("si", $item, $quantity);
    if ($stmt->execute() === false) {
        die("Execute failed: " . $stmt->error);
    }
    $stmt->close();
}

function removeFromCart($item) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM cart_items WHERE item_name = ?");
    $stmt->bind_param("s", $item);
    $stmt->execute();
    $stmt->close();
}

function getCartItems() {
    global $conn;
    $result = $conn->query("SELECT item_name, quantity FROM cart_items");
    $items = [];
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }
    return $items;
}
?>