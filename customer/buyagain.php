<?php
session_start();
include('../conn.php'); 


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['order_code'])) {
    // Sanitize inputs if necessary
    $orderCode = $_POST['order_code'];

    // Update query
    $update_query = "UPDATE orders SET osid = 1 WHERE order_code = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("s", $orderCode); 

    if ($stmt->execute()) {
        // Update successful
        echo json_encode(['status' => 'success', 'message' => 'Order status updated successfully.']);
    } else {
        // Update failed
        echo json_encode(['status' => 'error', 'message' => 'Error updating order status: ' . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
} else {
    // Invalid request
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
}
?>
