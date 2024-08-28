<?php 
session_start();
include('../conn.php');
if(isset($_SESSION['uid'])){
    $uid = $_SESSION['uid'];
    $cid = $_POST['cid'];

    $cart = "SELECT * FROM cart WHERE uid = '$uid' AND cid = '$cid'";
    $cartres = mysqli_query($conn, $cart);

    if($cartres && mysqli_num_rows($cartres) > 0){
        $cartrow = mysqli_fetch_assoc($cartres);
        $qty = $cartrow['qty'];

        $updatecart = "UPDATE cart SET qty = $qty + 1 WHERE uid = '$uid' AND cid = '$cid'";
        $updateres = mysqli_query($conn, $updatecart);
    }
}
?>