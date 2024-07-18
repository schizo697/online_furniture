<?php
session_start();
include('../conn.php'); // Include database connection

$response = [];

if (isset($_POST['archiveSupply'])) {
    $supid = $_POST['supid'];

    $sql = "UPDATE supplies SET status = 0 WHERE supid = '$supid'";

    if (mysqli_query($conn, $sql)) {
        $response['status'] = 'success';
        $response['message'] = 'Supply has been archived successfully';
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Something went wrong!';
    }
    echo json_encode($response);
    exit();
}
?>
