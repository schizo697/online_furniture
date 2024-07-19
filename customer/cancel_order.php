<?php
session_start();
include('../conn.php'); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['order_code'])) {
        $orderCode = $_POST['order_code'];
        
        // Start transaction
        $conn->begin_transaction();

        try {
            // Get order details
            $order_query = "SELECT pid, qty FROM orders WHERE order_code = ?";
            $stmt = $conn->prepare($order_query);
            $stmt->bind_param("s", $orderCode);
            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                $pid = $row['pid'];
                $qty = $row['qty'];

                // Update product quantity
                $update_qty_query = "UPDATE furniture SET quantity = quantity + ? WHERE pid = ?";
                $stmt_update = $conn->prepare($update_qty_query);
                $stmt_update->bind_param("ii", $qty, $pid);
                $stmt_update->execute();
            }

            // Perform database update to set osid to 0 for the given order_code
            $cancel_query = "UPDATE orders SET osid = 0 WHERE order_code = ?";
            $stmt_cancel = $conn->prepare($cancel_query);
            $stmt_cancel->bind_param("s", $orderCode);
            $stmt_cancel->execute();

            // Commit transaction
            $conn->commit();

            echo "Order canceled successfully.";
        } catch (Exception $e) {
            // Rollback transaction on error
            $conn->rollback();
            echo "Failed to cancel order.";
        }

        exit;
    }
}
?>
