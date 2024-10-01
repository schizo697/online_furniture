<?php
session_start();
include('../conn.php'); // Include database connection

if (!isset($_SESSION['uid'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate inputs
    if (empty($_POST['orderCode']) || empty($_POST['expectedDate']) || empty($_POST['shippingStatus']) || empty($_POST['riderName']) || empty($_POST['riderNumber'])) {
        echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
        exit();
    }

    $orderCode = $_POST['orderCode'];
    $expectedDate = $_POST['expectedDate'];
    $shippingStatus = $_POST['shippingStatus'];
    $riderName = $_POST['riderName'];
    $riderNumber = $_POST['riderNumber'];

    // Start transaction to ensure atomicity
    $conn->begin_transaction();

    try {
        // Check if a record with the given order_code exists
        $check_query = $conn->prepare("SELECT shipping_id FROM shipping WHERE order_code = ?");
        $check_query->bind_param("s", $orderCode);
        $check_query->execute();
        $check_result = $check_query->get_result();

        if ($check_result->num_rows > 0) {
            // Record exists, update it
            $update_query = $conn->prepare("UPDATE shipping SET expected_date = ?, shipping_status = ?, rider_name = ?, rider_number = ? WHERE order_code = ?");
            $update_query->bind_param("sssss", $expectedDate, $shippingStatus, $riderName, $riderNumber, $orderCode);
            if ($update_query->execute()) {
                sendNotification($conn, $orderCode);
                echo json_encode(['success' => true, 'message' => 'Shipping status updated successfully']);
            } else {
                throw new Exception('Update failed: ' . $conn->error);
            }
        } else {
            // Record does not exist, insert it
            $insert_query = $conn->prepare("INSERT INTO shipping (order_code, expected_date, shipping_status, rider_name, rider_number) VALUES (?, ?, ?, ?, ?)");
            $insert_query->bind_param("sssss", $orderCode, $expectedDate, $shippingStatus, $riderName, $riderNumber);
            if ($insert_query->execute()) {
                sendNotification($conn, $orderCode);
                echo json_encode(['success' => true, 'message' => 'Shipping status inserted successfully']);
            } else {
                throw new Exception('Insert failed: ' . $conn->error);
            }
        }

        // Commit the transaction
        $conn->commit();
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}

// Function to send notification (if needed)
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
