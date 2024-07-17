<?php
session_start();
include('../conn.php');

$uid = $_POST['uid'];
$pid = explode(',', $_POST['pid']);
$payMethod = $_POST['payMethod'];
$totalorder = $_POST['totalorder'];
$date_of_order = date('Y-m-d');
$gcashrec = '';

if (isset($_FILES['gcashrec']) && $_FILES['gcashrec']['error'] == UPLOAD_ERR_OK) {
    $uploadDir = '../customer/gcash/';
    $fileName = basename($_FILES['gcashrec']['name']);
    $uploadFile = $uploadDir . $fileName;
    
    if (move_uploaded_file($_FILES['gcashrec']['tmp_name'], $uploadFile)) {
        $gcashrec = $fileName; 
    } else {
        echo 'Error uploading GCash receipt';
        exit();
    }
}

function generateOrderId() {
    return rand(10000, 99999);
}

$order_code = generateOrderId();
$payid = uniqid('', true);

foreach ($pid as $prodid) {
    $cartqty = "SELECT qty FROM cart WHERE pid = $prodid AND uid = $uid";
    $cartqtyres = mysqli_query($conn, $cartqty);

    if ($cartqtyres && mysqli_num_rows($cartqtyres) > 0) {
        while ($qtyrow = mysqli_fetch_assoc($cartqtyres)) {
            $quantity = $qtyrow['qty'];

            if ($payMethod == 'cod') {
                $order = "INSERT INTO orders (order_code, pid, uid, total, qty, mop, date, osid) 
                            VALUES ('$order_code', '$prodid', '$uid', '$totalorder', '$quantity', '$payMethod', '$date_of_order', '1')";
                $orderres = mysqli_query($conn, $order);

                if ($orderres) {
                    $update_furniture = "UPDATE furniture SET quantity = quantity - $quantity WHERE pid = '$prodid'";
                    $update_furnitureres = mysqli_query($conn, $update_furniture);

                    if (!$update_furnitureres) {
                        echo 'Error updating furniture qty';
                        exit();
                    }
                }
            } elseif ($payMethod == 'gcash') {
                $receipt = "INSERT INTO gcash_rec (order_code, receipt) VALUES ('$order_code', '$gcashrec')";
                $receiptres = mysqli_query($conn, $receipt);

                if ($receiptres) {
                    $order = "INSERT INTO orders (order_code, pid, uid, total, qty, mop, date, osid) 
                                VALUES ('$order_code', '$prodid', '$uid', '$totalorder', '$quantity', '$payMethod', '$date_of_order', '1')";
                    $orderres = mysqli_query($conn, $order);

                    if ($orderres) {
                        $update_furniture = "UPDATE furniture SET quantity = quantity - $quantity WHERE pid = '$prodid'";
                        $update_furnitureres = mysqli_query($conn, $update_furniture);

                        if (!$update_furnitureres) {
                            echo 'Error updating furniture qty';
                            exit();
                        }
                    }
                } else {
                    echo 'Error inserting GCash receipt';
                    exit();
                }
            }
        }
    }
}

foreach ($pid as $prodid) {
    $qtydelete = "DELETE FROM cart WHERE pid = '$prodid' AND uid = '$uid'";
    $qtydeleteres = mysqli_query($conn, $qtydelete);
    if (!$qtydeleteres) {
        echo "Error deleting qty";
        exit();
    }
}

$url = "purchase.php?success=true";
echo "<script>window.location.href = '$url'; </script>";
exit();
?>
