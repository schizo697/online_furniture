<?php
session_start();
include('../conn.php'); // Include database connection

// Check if the user is logged in
if (!isset($_SESSION['uid'])) {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('includes/topbar.php'); ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <?php include('includes/sidebar.php'); ?>
    <?php include('includes/header.php'); ?>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <br> <br>
                <br> <br> <br>
                <div class="card">
                    <div class="card-header">
                        <h2 class="mb-0">Notifications</h2>
                    </div>
                    <div class="card-body">
                        <?php
                        $notifications_query = "SELECT * FROM notification ORDER BY timestamp DESC";
                        $notifications_result = mysqli_query($conn, $notifications_query);

                        // Check if there are notifications
                        if (mysqli_num_rows($notifications_result) > 0) {
                        ?>
                            <ul class="list-group">
                                <?php
                                while ($notification = mysqli_fetch_assoc($notifications_result)) {
                                ?>
                                    <li class="list-group-item">
                                        <div class="justify-content-between align-items-center">
                                            <div class="message"><?php echo $notification['message']; ?></div>
                                            <div class="timestamp">
                                                <?php 
                                                // Assuming the timestamp is in a format like 'YYYY-MM-DD HH:MM:SS'
                                                $formattedTimestamp = date('F d, Y h:i A', strtotime($notification['timestamp']));
                                                echo $formattedTimestamp; 
                                                ?>
                                            </div>
                                        </div>
                                    </li>
                                <?php
                                }
                                ?>
                            </ul>
                        <?php
                        } else {
                            // Display a message when there are no notifications
                        ?>
                            <div class="alert alert-info">You have no notifications.</div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js"></script>
</body>
</html>