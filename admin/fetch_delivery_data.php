<?php
session_start();
include('../conn.php'); // Include your DB connection

// Check if the user is logged in
if (!isset($_SESSION['uid'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit();
}

// Get filter value
$furnitureType = isset($_GET['furniture_type']) ? $_GET['furniture_type'] : '';

// Query to fetch inventory data and calculate total sold
$sql = "SELECT furniture_type.type, furniture.image, furniture.pname, furniture.quantity, SUM(orders.qty) AS total_sold
        FROM furniture 
        JOIN furniture_type ON furniture.fid = furniture_type.fid 
        LEFT JOIN orders ON orders.pid = furniture.pid 
        WHERE furniture.status = 'Active' AND orders.osid = 3";

// Apply filter if a furniture type is selected
if (!empty($furnitureType)) {
    $sql .= " AND furniture_type.type = '$furnitureType'";
}

$sql .= " GROUP BY furniture_type.type, furniture.pname 
          ORDER BY furniture_type.type";

$result = mysqli_query($conn, $sql);

$data = [];

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = [
            'type' => $row['type'],
            'image' => $row['image'],
            'pname' => $row['pname'],
            'quantity' => $row['quantity'],
            'total_sold' => isset($row['total_sold']) ? $row['total_sold'] : 0,
        ];
    }
}

echo json_encode($data);
?>
