<?php
session_start();
include('../conn.php');

function sendJsonResponse() {
    echo json_encode([]);
    exit();
}

if (!isset($_SESSION['uid'])) {
    sendJsonResponse();
}

$uid = $_SESSION['uid'];
$pid = $_POST['pid'];

$cart_check_query = "SELECT * FROM cart WHERE uid = $uid AND pid = $pid";
$cart_check_res = mysqli_query($conn, $cart_check_query);

if (mysqli_num_rows($cart_check_res) > 0) {
    $row = mysqli_fetch_assoc($cart_check_res);
    $qty = $row['qty'] + 1;

    $cart_update_query = "UPDATE cart SET qty = $qty WHERE pid = $pid AND uid = $uid";
    if (mysqli_query($conn, $cart_update_query)) {
        sendJsonResponse();
    } else {
        sendJsonResponse();
    }
} else {

    $materials = "SELECT * FROM furniture WHERE pid = $pid";
    $materials_res = mysqli_query($conn, $materials);

    if(mysqli_num_rows($materials_res) > 0){
        $materials_row = mysqli_fetch_assoc($materials_res);
        $color = $materials_row['color'];
        $height = $materials_row['height'];
        $width = $materials_row['width'];
        $length = $materials_row['length'];

        $cart_insert_query = "INSERT INTO cart (pid, uid, qty, color, height, width, length) VALUES ($pid, $uid, 1, '$color', $height, $width, $length)";
        if (mysqli_query($conn, $cart_insert_query)) {
            sendJsonResponse();
        } else {
            sendJsonResponse();
        }
    }
}

mysqli_close($conn);
?>
