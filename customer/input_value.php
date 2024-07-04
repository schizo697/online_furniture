<?php
session_start();
include('../conn.php');

if(isset($_SESSION['uid']) && isset($_POST['pid']) && isset($_POST['qty'])){
    $uid = $_SESSION['uid'];
    $pid = $_POST['pid'];
    $qty = $_POST['qty'];

    $updatecart = "UPDATE cart SET qty = '$qty' WHERE uid = '$uid' AND pid = '$pid'";
    $updateres = mysqli_query($conn, $updatecart);

    if($updateres){
        echo "Quantity updated successfully.";
    } else {
        echo "Failed to update quantity.";
    }
} else {
    echo "Invalid request.";
}
?>
