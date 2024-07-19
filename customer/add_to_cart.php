<?php
session_start();
include('../conn.php');

// Function to send JSON response
function sendJsonResponse($status, $message) {
    echo json_encode(['status' => $status, 'message' => $message]);
    exit();
}

if (!isset($_SESSION['uid'])) {
    sendJsonResponse('not_logged_in', 'User is not logged in.');
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
        sendJsonResponse('success', 'Product quantity updated in cart.');
    } else {
        sendJsonResponse('error', 'Failed to update cart.');
    }
} else {
    // Product not in cart, insert new entry
    $cart_insert_query = "INSERT INTO cart (pid, uid, qty) VALUES (?, ?, 1)";
    $insert_stmt = $conn->prepare($cart_insert_query);
    $insert_stmt->bind_param("ii", $pid, $uid);
    if ($insert_stmt->execute()) {
        sendJsonResponse('success', 'Product added to cart.');
    } else {
        sendJsonResponse('error', 'Failed to add product to cart.');
    }
}

$stmt->close();
$conn->close();
?>
