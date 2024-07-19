<?php
session_start();
include('../conn.php');

// Function to send JSON response
function sendJsonResponse() {
    echo json_encode([]);
    exit();
}

if (!isset($_SESSION['uid'])) {
    sendJsonResponse();
}

$uid = $_SESSION['uid'];
$pid = $_POST['pid'];

// Check if the product is already in the cart
$cart_check_query = "SELECT * FROM cart WHERE uid = ? AND pid = ?";
$stmt = $conn->prepare($cart_check_query);
$stmt->bind_param("ii", $uid, $pid);
$stmt->execute();
$cart_check_res = $stmt->get_result();

if ($cart_check_res->num_rows > 0) {
    // Product already in cart, update the quantity
    $row = $cart_check_res->fetch_assoc();
    $qty = $row['qty'] + 1;

    $cart_update_query = "UPDATE cart SET qty = ? WHERE pid = ? AND uid = ?";
    $update_stmt = $conn->prepare($cart_update_query);
    $update_stmt->bind_param("iii", $qty, $pid, $uid);
    if ($update_stmt->execute()) {
        sendJsonResponse();
    } else {
        sendJsonResponse();
    }
} else {
    // Product not in cart, insert new entry
    $cart_insert_query = "INSERT INTO cart (pid, uid, qty) VALUES (?, ?, 1)";
    $insert_stmt = $conn->prepare($cart_insert_query);
    $insert_stmt->bind_param("ii", $pid, $uid);
    if ($insert_stmt->execute()) {
        sendJsonResponse();
    } else {
        sendJsonResponse();
    }
}

$stmt->close();
$conn->close();
?>
