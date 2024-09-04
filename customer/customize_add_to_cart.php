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
$material = $_POST['material'];
$foam = $_POST['foam'];
$fabric = $_POST['fabric'];
$spring = $_POST['spring'];
$color = $_POST['color'];
$width = $_POST['width'];
$height = $_POST['height'];
$length = $_POST['length'];
$footpart = $_POST['footPart'];
$totalprice = $_POST['totalPrice'];

if ($pid == 0) {
    sendJsonResponse(['error' => 'Invalid product ID']);
}

// Completed check query
$cart_check_query = "SELECT * FROM cart WHERE uid = '$uid' AND pid = '$pid' AND color = '$color' AND height = '$height'
AND width = '$width' AND length = '$length' AND materials = '$material' AND foot_part = '$footpart' 
AND foam = '$foam' AND fabric = '$fabric' AND spring = '$spring'";
$cart_check_res = mysqli_query($conn, $cart_check_query);

if (mysqli_num_rows($cart_check_res) > 0) {
    $row = mysqli_fetch_assoc($cart_check_res);
    $qty = $row['qty'] + 1;

    $cart_update_query = "UPDATE cart SET qty = $qty WHERE pid = $pid AND uid = $uid";
    if (mysqli_query($conn, $cart_update_query)) {
        header("Location: shop.php?update=true");
        exit();
    } else {
        sendJsonResponse(['error' => 'Failed to update cart']);
    }
} else {
    // Completed insert query
    $cart_insert_query = "INSERT INTO cart (pid, uid, qty, color, height, width, length, materials, foot_part, foam, fabric, spring, total_price) 
        VALUES ($pid, $uid, 1, '$color', $height, $width, $length, '$material', '$footpart', '$foam', '$fabric', '$spring', '$totalprice')";
    if (mysqli_query($conn, $cart_insert_query)) {
        header("Location: shop.php?success=true");
        exit();
    } else {
        sendJsonResponse(['error' => 'Failed to add to cart']);
    }
}

mysqli_close($conn);
?>
