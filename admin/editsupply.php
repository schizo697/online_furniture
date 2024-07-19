<?php
session_start();
include('../conn.php'); // Include database connection

if (isset($_POST['editSupply'])) {
    $supid = $_POST['supid'];
    $supplier = $_POST['supplier'];
    $pname = $_POST['pname'];
    $quantity = $_POST['quantity'];
    $delivery = $_POST['delivery'];
    $user = $_POST['user'];
    $unit = $_POST['unit'];

    $sql = "UPDATE supplies 
            SET sid = '$supplier', item = '$pname', quantity = '$quantity', unit = '$unit', deliverydate = '$delivery', approvedby = '$user'
            WHERE supid = '$supid'";

    $result = mysqli_query($conn, $sql);

    if ($result) {
        header("Location: supplies.php?update=true");
    } else {
        header("Location: supplies.php?error=true");
    }
    exit();
}
?>
