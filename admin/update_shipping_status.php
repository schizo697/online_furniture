<?php
session_start();
include('../conn.php'); // Include database connection

if (!isset($_SESSION['uid'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Use prepared statements for better security
    $orderCode = $_POST['orderCode'];
    $expectedDate = $_POST['expectedDate'];
    $shippingStatus = $_POST['shippingStatus'];

    // Check if a record with the given order_code exists
    $check_query = $conn->prepare("SELECT shipping_id FROM shipping WHERE order_code = ?");
    $check_query->bind_param("s", $orderCode);
    $check_query->execute();
    $check_result = $check_query->get_result();

    if ($check_result->num_rows > 0) {
        // Record exists, update it
        $update_query = $conn->prepare("UPDATE shipping SET expected_date = ?, shipping_status = ? WHERE order_code = ?");
        $update_query->bind_param("sss", $expectedDate, $shippingStatus, $orderCode);
        if ($update_query->execute()) {
            sendNotification($conn, $orderCode);
            echo json_encode(['success' => true, 'message' => 'Shipping status updated successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Update failed: ' . $conn->error]);
        }
    } else {
        // Record does not exist, insert it
        $insert_query = $conn->prepare("INSERT INTO shipping (order_code, expected_date, shipping_status) VALUES (?, ?, ?)");
        $insert_query->bind_param("sss", $orderCode, $expectedDate, $shippingStatus);
        if ($insert_query->execute()) {
            sendNotification($conn, $orderCode);
            echo json_encode(['success' => true, 'message' => 'Shipping status inserted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Insert failed: ' . $conn->error]);
        }
    }
}

// Function to send notification
function sendNotification($conn, $orderCode) {
    // Fetch the customer id
    $customer_query = $conn->prepare("SELECT uid FROM orders WHERE order_code = ?");
    $customer_query->bind_param("s", $orderCode);
    $customer_query->execute();
    $customer_result = $customer_query->get_result();

    if ($customer_result->num_rows > 0) {
        $row = $customer_result->fetch_assoc();
        $uid = $row['uid'];

        $notification_message = "Order Code: $orderCode has been updated successfully!";
        $notification_status = "unread";

        // Insert the notification into the database
        $notif_query = $conn->prepare("INSERT INTO notification (uid, message, status, timestamp) VALUES (?, ?, ?, NOW())");
        $notif_query->bind_param("sss", $uid, $notification_message, $notification_status);
        if (!$notif_query->execute()) {
            // Log the error instead of exposing it
            error_log("Notification insert failed: " . $notif_query->error);
        }
    }
}
?>
