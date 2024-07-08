<?php
session_start();
include('../conn.php');

if (isset($_POST['pid']) && isset($_SESSION['uid'])) {
    $pid = $_POST['pid'];
    $uid = $_SESSION['uid'];

    $query = "DELETE FROM cart WHERE pid = '$pid' AND uid = '$uid'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Item removed successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to remove item.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
?>
