<?php
session_start();
include ('../conn.php');
if(isset($_SESSION['uid'])){
    $uid = $_SESSION['uid'];
    $pid = $_POST['pid'];

    $cart_check = "SELECT * FROM cart WHERE uid = '$uid' AND pid = '$pid'";
    $cart_check_res = mysqli_query($conn, $cart_check);

    if($cart_check_res && mysqli_num_rows($cart_check_res) > 0){
        $row = mysqli_fetch_assoc($cart_check_res);
        $qty = $row['qty'];

        $cart_update = "UPDATE cart SET qty = $qty + 1 WHERE pid = '$pid' AND uid = '$uid'";
        $cart_update_res = mysqli_query($conn, $cart_update);

        if($cart_update_res){
            echo 'Cart updated successfully!';
        } else {
            echo 'Failed to update cart!';
        }
    } else {
        $cart = "INSERT INTO cart (pid, uid, qty) VALUES ('$pid', '$uid', 1)";
        $cartres = mysqli_query($conn, $cart);
        
        if($cartres){
            echo 'Product added successfully!';
        } else {
            echo 'Failed to add product to cart!';
        }
    }
}
?>
