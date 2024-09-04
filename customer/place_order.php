<?php
session_start();
include('../conn.php');

$order_code = $_POST['orderCode'];
$uid = $_POST['uid'];
$cid = explode(',', $_POST['cid']);
$payMethod = $_POST['payMethod'];
$totalorder = $_POST['totalorder'];
$date_of_order = date('Y-m-d');
$gcashrec = $_POST['gcashRec'];

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



foreach ($cid as $prodid) {
    $cartqty = "SELECT pid, qty FROM cart WHERE cid = $prodid AND uid = $uid";
    $cartqtyres = mysqli_query($conn, $cartqty);

    if ($cartqtyres && mysqli_num_rows($cartqtyres) > 0) {
        while ($qtyrow = mysqli_fetch_assoc($cartqtyres)) {
            $quantity = $qtyrow['qty'];
            $pid = $qtyrow['pid'];

            if ($payMethod == 'cod') {
                $order = "INSERT INTO orders (order_code, cid, uid, pid, total, qty, mop, date, osid) 
                            VALUES ('$order_code', '$prodid', '$uid', '$pid', '$totalorder', '$quantity', '$payMethod', '$date_of_order', '1')";
                $orderres = mysqli_query($conn, $order);

                if ($orderres) {
                    $update_furniture = "UPDATE furniture SET quantity = quantity - $quantity WHERE pid = '$pid'";
                    $update_furnitureres = mysqli_query($conn, $update_furniture);

                    $notification_message = "New order placed. Order ID: $order_code";
                    $notification_status = "unread"; // Set the initial status as unread
                    
                    // Insert the notification into the database
                    $insert_notification_query = "INSERT INTO notification (uid, message, status, timestamp) 
                                                VALUES ('$uid', '$notification_message', '$notification_status', NOW())";
                    
                    $notif = mysqli_query($conn, $insert_notification_query);

            } elseif ($payMethod == 'gcash') {
                $receipt = "INSERT INTO gcash_rec (order_code, receipt) VALUES ('$order_code', '$gcashrec')";
                $receiptres = mysqli_query($conn, $receipt);

                if ($receiptres) {
                    $order = "INSERT INTO orders (order_code, cid, uid, pid, total, qty, mop, date, osid) 
                                VALUES ('$order_code', '$prodid', '$uid', '$pid', '$totalorder', '$quantity', '$payMethod', '$date_of_order', '1')";
                    $orderres = mysqli_query($conn, $order);

                    if ($orderres) {
                        $update_furniture = "UPDATE furniture SET quantity = quantity - $quantity WHERE pid = '$pid'";
                        $update_furnitureres = mysqli_query($conn, $update_furniture);

                        if (!$update_furnitureres) {
                            echo 'Error updating furniture qty';
                            exit();
                        }

                        $notification_message = "New order placed. Order ID: $order_code";
                        $notification_status = "unread"; // Set the initial status as unread
                        
                        // Insert the notification into the database
                        $insert_notification_query = "INSERT INTO notification (uid, message, status, timestamp) 
                                                    VALUES ('$uid', '$notification_message', '$notification_status', NOW())";
                        
                        $notif = mysqli_query($conn, $insert_notification_query);
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

foreach ($cid as $prodid) {
    $qtyupdate = "UPDATE cart SET qty = 0 WHERE cid = '$prodid' AND uid = '$uid'";
    $qtyupdateres = mysqli_query($conn, $qtyupdate);
    if (!$qtyupdateres) {
        echo "Error updating qty";
        exit();
    }
}


$url = "purchase.php?success=true";
echo "<script>window.location.href = '$url'; </script>";
exit();
?>
