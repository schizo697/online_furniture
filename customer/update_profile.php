<?php
session_start();
include '../conn.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uid = $_SESSION['uid'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $postal = $_POST['postal'];
    
    // Using prepared statements for security
    $update_sql = "UPDATE userinfo SET firstname=?, lastname=?, email=?, contact=?, address=?, city=?, postal=? WHERE infoid=?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("sssssssi", $firstname, $lastname, $email, $contact, $address, $city, $postal, $uid);

    if ($stmt->execute()) {
        $url = "checkout.php?selected_pid=" . $pid;
        echo "<script>window.location.href='" . $url . "'</script>";
    } else {
        $url = "checkout.php?selected_pid=" . $pid;
        echo "<script>window.location.href='" . $url . "'</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
