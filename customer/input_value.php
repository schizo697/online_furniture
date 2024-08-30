<?php
session_start();
include('../conn.php');

if(isset($_SESSION['uid']) && isset($_POST['cid']) && isset($_POST['qty'])){
    $uid = $_SESSION['uid'];
    $cid = $_POST['cid'];
    $qty = $_POST['qty'];

    $updatecart = "UPDATE cart SET qty = '$qty' WHERE uid = '$uid' AND cid = '$cid'";
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
