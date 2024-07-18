<?php
session_start();
include('../conn.php'); // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['order_code']) && !empty($_POST['order_code'])) {
        $order_code = $_POST['order_code'];
        $uid = $_SESSION['uid']; 

      
        $order_query = "SELECT * FROM orders WHERE order_code = ? AND uid = ?";
        $stmt = $conn->prepare($order_query);
        $stmt->bind_param("si", $order_code, $uid);
        $stmt->execute();
        $order_res = $stmt->get_result();

        if ($order_res && $order_res->num_rows > 0) {
           
            $order_row = $order_res->fetch_assoc();

            
            $update_query = "UPDATE orders SET osid = 4 WHERE order_code = ? AND uid = ?";
            $stmt = $conn->prepare($update_query);
            $stmt->bind_param("si", $order_code, $uid);

            if ($stmt->execute()) {
                
                $_SESSION['message'] = "Order returned successfully.";
                $_SESSION['msg_type'] = "success";
            } else {
                // Failure, redirect to orders page with an error message
                $_SESSION['message'] = "Failed to return the order. Please try again.";
                $_SESSION['msg_type'] = "danger";
            }
        } else {
           
            $_SESSION['message'] = "Invalid order.";
            $_SESSION['msg_type'] = "danger";
        }
    } else {
        // Order code is not set
        $_SESSION['message'] = "No order code provided.";
        $_SESSION['msg_type'] = "danger";
    }

    // Redirect to the orders page
    header("Location: orders.php");
    exit();
} else {
    // Invalid request method
    $_SESSION['message'] = "Invalid request method.";
    $_SESSION['msg_type'] = "danger";
    header("Location: orders.php");
    exit();
}
?>
