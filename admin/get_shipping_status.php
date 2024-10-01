<?php
session_start();
include('../conn.php'); // Include database connection

if (!isset($_SESSION['uid'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderCode = mysqli_real_escape_string($conn, $_POST['orderCode']);
    
    $query = "SELECT order_code, expected_date, shipping_status, rider_name, rider_number FROM shipping WHERE order_code = '$orderCode'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo json_encode(['success' => true, 'data' => $row]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No data found put Status first']);
    }
}
?>
