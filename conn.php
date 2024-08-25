<?php 
$serverename = "localhost";
$username = "root";
$password = "";
$dbname = "mpmdb";

$conn = new mysqli($serverename, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connecton Failed: " . $conn->connect_error);

    
}
?>