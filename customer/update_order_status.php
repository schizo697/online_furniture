<?php
session_start();
include('../conn.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_code'])) {
    $orderCode = $_POST['order_code'];

    // Update query to set osid to 3 based on order_code
    $updateQuery = "UPDATE orders SET osid = 3 WHERE order_code = ?";
    
    $stmt = $conn->prepare($updateQuery);
    
    if (!$stmt) {
        // Error preparing statement
        echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
    } else {
        // Bind parameters
        $stmt->bind_param("s", $orderCode);

        // Execute statement
        if ($stmt->execute()) {
            // Check if any rows were affected
            if ($stmt->affected_rows > 0) {
                echo "Order status updated successfully.";
            } else {
                echo "Order code not found or already updated.";
            }
        } else {
            echo "Error updating order status: " . $stmt->error;
        }

        // Close statement
        $stmt->close();
    }
} else {
    echo "Invalid request.";
}
?>
