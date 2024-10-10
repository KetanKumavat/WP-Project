<?php
session_start();
include 'db.php';

function addToCart($item) {
    global $conn;

    $stmt = $conn->prepare("SELECT quantity FROM cart_items WHERE item_name = ?");
    $stmt->bind_param("s", $item);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Item exists, update quantity
        $stmt->bind_result($quantity);
        $stmt->fetch();
        $quantity++;
        $stmt->close();

        $stmt = $conn->prepare("UPDATE cart_items SET quantity = ? WHERE item_name = ?");
        $stmt->bind_param("is", $quantity, $item);
    } else {
        // Item does not exist, insert new record
        $quantity = 1;
        $stmt->close();

        $stmt = $conn->prepare("INSERT INTO cart_items (item_name, quantity) VALUES (?, ?)");
        $stmt->bind_param("si", $item, $quantity);
    }

    if ($stmt->execute() === false) {
        die("Execute failed: " . $stmt->error);
    }
    $stmt->close();
}

function removeFromCart($item) {
    global $conn;

    $stmt = $conn->prepare("DELETE FROM cart_items WHERE item_name = ?");
    $stmt->bind_param("s", $item);
    if ($stmt->execute() === false) {
        die("Execute failed: " . $stmt->error);
    }
    $stmt->close();
}

function getCartItems() {
    global $conn;

    $stmt = $conn->prepare("SELECT item_name, quantity FROM cart_items");
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->execute();
    $result = $stmt->get_result();

    $items = [];
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }
    $stmt->close();

    return $items;
}
?>