<?php 
session_start();
include ('../conn.php');

$uid = $_POST['uid'];
$pid = explode(',', $_POST['pid']);
$payMethod = $_POST['payMethod'];
$totalorder = $_POST['totalorder'];
$date_of_order = date('Y-m-d');

function generateOrderId() {
    return rand(10000, 99999);
}

$order_code = generateOrderId();

foreach ($pid as $prodid) {
    $cartqty = "SELECT qty FROM cart WHERE pid = $prodid AND uid = $uid";
    $cartqtyres = mysqli_query($conn, $cartqty);
    
    if($cartqtyres && mysqli_num_rows($cartqtyres) > 0){
        while($qtyrow = mysqli_fetch_assoc($cartqtyres)){
            $quantity = $qtyrow['qty'];
    
            $order = "INSERT INTO orders (order_code, pid, uid, qty, mop, date, osid, total) 
                      VALUES ('$order_code', '$prodid', '$uid', '$quantity', '$payMethod', '$date_of_order', '1', '$totalorder')";
            $orderres = mysqli_query($conn, $order);
    
            if($orderres){
                $update_furniture = "UPDATE furniture SET quantity = quantity - $quantity WHERE pid = '$prodid'";
                $update_furnitureres = mysqli_query($conn, $update_furniture);
    
                if(!$update_furnitureres){
                    echo 'Error updating furniture qty';
                    exit();
                } 
            } else {
                echo 'Error inserting order';
                exit();
            }
        }
    }
}

foreach ($pid as $prodid) {
    $qtydelete = "DELETE FROM cart WHERE pid = '$prodid' AND uid = '$uid'";
    $qtydeleteres = mysqli_query($conn, $qtydelete);
    if(!$qtydeleteres){
        echo "Error deleting qty";
        exit();
    }
}

$url = "purchase.php?success=true";
echo "<script>window.location.href = '$url'; </script>";
exit();
?>
