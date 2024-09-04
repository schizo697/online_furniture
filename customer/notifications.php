<?php
session_start();
include '../conn.php';

if (isset($_SESSION['uid'])) {
    $user_id = $_SESSION['uid'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<?php include('includes/topbar.php'); ?>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        /* Basic styling for the notifications page */
        body {
            font-family: sans-serif;
        }

        .container {
            margin-top: 20px;
        }

        .notification-list {
            list-style: none;
            padding: 0;
        }

        .notification-item {
            padding: 15px;
            border-bottom: 1px solid #eee;
        }

        .notification-item .message {
            font-weight: bold;
        }

        .notification-item .timestamp {
            color: #777;
            font-size: 14px;
        }
    </style>
</head>
<body>
     <!-- Page Header -->
     <div class="container-fluid page-header py-5">
        <h1 class="text-center text-white display-6">Notifications</h1>
    </div>
    <!-- End Page Header --> 
    <div class="container">

        <?php
       
        $uid = $_SESSION['uid'];
        $notifications_query = "SELECT * FROM notification WHERE uid = $uid ORDER BY timestamp DESC";
        $notifications_result = mysqli_query($conn, $notifications_query);

        // Check if there are notifications
        if(mysqli_num_rows($notifications_result) > 0) {
        ?>
            <ul class="notification-list">
                <?php
                while ($notification = mysqli_fetch_assoc($notifications_result)) {
                ?>
                    <li class="notification-item">
                        <div class="message"><?php echo $notification['message']; ?></div>
                        <div class="timestamp"><?php echo $notification['timestamp']; ?></div>
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

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js"></script>
</body>
</html>