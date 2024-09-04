<?php
session_start();
include('../conn.php'); // Include database connection

if (!isset($_SESSION['uid'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderCode = mysqli_real_escape_string($conn, $_POST['orderCode']);
    $expectedDate = mysqli_real_escape_string($conn, $_POST['expectedDate']);
    $shippingStatus = mysqli_real_escape_string($conn, $_POST['shippingStatus']);
    
    // Check if a record with the given order_code already exists
    $check_query = "SELECT shipping_id FROM shipping WHERE order_code = '$orderCode'";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        // Record exists, update it
        $update_query = "UPDATE shipping SET expected_date = '$expectedDate', shipping_status = '$shippingStatus' WHERE order_code = '$orderCode'";
        $result = mysqli_query($conn, $update_query);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Shipping status updated successfully']);

            // Get customer id
            $customer = "SELECT * FROM orders WHERE order_code = '$orderCode'";
            $fetch = mysqli_query($conn, $customer);
            if ($fetch && mysqli_num_rows($fetch) > 0) {
                $row = mysqli_fetch_assoc($fetch);
                $uid = $row['uid'];

                $notification_message = "Order Code: $order_code has been shipped successfully!";
                $notification_status = "unread"; // Set the initial status as unread
                    
                // Insert the notification into the database
                $insert_notification_query = "INSERT INTO notification (uid, message, status, timestamp) 
                                                VALUES ('$uid', '$notification_message', '$notification_status', NOW())";
                    
                $notif = mysqli_query($conn, $insert_notification_query);
            }
    } else {
        // Record does not exist, insert it
        $insert_query = "INSERT INTO shipping (order_code, expected_date, shipping_status) VALUES ('$orderCode', '$expectedDate', '$shippingStatus')";
        $result = mysqli_query($conn, $insert_query);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Shipping status inserted successfully']);

            // Get customer id
            $customer = "SELECT * FROM orders WHERE order_code = '$orderCode'";
            $fetch = mysqli_query($conn, $customer);
            if ($fetch && mysqli_num_rows($fetch) > 0) {
                $row = mysqli_fetch_assoc($fetch);
                $uid = $row['uid'];

                $notification_message = "Order Code: $order_code has been shipped successfully!";
                $notification_status = "unread"; // Set the initial status as unread
                    
                // Insert the notification into the database
                $insert_notification_query = "INSERT INTO notification (uid, message, status, timestamp) 
                                                VALUES ('$uid', '$notification_message', '$notification_status', NOW())";
                    
                $notif = mysqli_query($conn, $insert_notification_query);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Insert failed: ' . mysqli_error($conn)]);
        }
    }
}
}
?>
