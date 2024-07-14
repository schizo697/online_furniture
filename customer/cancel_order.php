<?php
session_start();
include('../conn.php'); 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle POST request to cancel order
    if (isset($_POST['order_code'])) {
        $orderCode = $_POST['order_code'];
        // Perform database update to set osid to 0 for the given order_code
        $cancel_query = "UPDATE orders SET osid = 0 WHERE order_code = ?";
        $stmt = $conn->prepare($cancel_query);
        $stmt->bind_param("s", $orderCode);
        if ($stmt->execute()) {
            echo "Order canceled successfully."; // Return success message if needed
        } else {
            echo "Failed to cancel order."; // Return error message if needed
        }
        exit;
    }
}
?>
