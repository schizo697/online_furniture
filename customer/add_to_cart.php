<?php
session_start();
include('../conn.php');

function sendJsonResponse($data = []) {
    echo json_encode($data);
    exit();
}

if (!isset($_SESSION['uid'])) {
    sendJsonResponse(['error' => 'User not logged in']);
}

$uid = $_SESSION['uid'];
$pid = isset($_POST['pid']) ? intval($_POST['pid']) : 0; // Ensure pid is an integer

if ($pid == 0) {
    sendJsonResponse(['error' => 'Invalid product ID']);
}

$cart_check_query = "SELECT * FROM cart WHERE uid = $uid AND pid = $pid";
$cart_check_res = mysqli_query($conn, $cart_check_query);

if (mysqli_num_rows($cart_check_res) > 0) {
    $row = mysqli_fetch_assoc($cart_check_res);
    $qty = $row['qty'] + 1;

    $cart_update_query = "UPDATE cart SET qty = $qty WHERE pid = $pid AND uid = $uid";
    if (mysqli_query($conn, $cart_update_query)) {
        sendJsonResponse(['success' => 'Product quantity updated']);
    } else {
        sendJsonResponse(['error' => 'Failed to update cart']);
    }
} else {
    $mats_query = "SELECT * FROM furniture WHERE pid = $pid";
    $matsres = mysqli_query($conn, $mats_query);

    if(mysqli_num_rows($matsres) > 0) {
        $matsrow = mysqli_fetch_assoc($matsres);
        $color = $matsrow['color'];
        $height = $matsrow['height'];
        $width = $matsrow['width'];
        $length = $matsrow['length'];
        $price = $matsrow['price'];
        $materials = $matsrow['material'];
        $fp = $matsrow['foot_part'];

        $cart_insert_query = "
            INSERT INTO cart (pid, uid, qty, color, height, width, length, materials, foot_part, total_price) 
            VALUES ($pid, $uid, 1, '$color', $height, $width, $length, '$materials', '$fp', '$price')";
        if (mysqli_query($conn, $cart_insert_query)) {
            sendJsonResponse(['success' => 'Product added to cart']);
        } else {
            sendJsonResponse(['error' => 'Failed to add to cart']);
        }
    } else {
        sendJsonResponse(['error' => 'No product found']);
    }
}

mysqli_close($conn);

?>
