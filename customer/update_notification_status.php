<?php
   // Include database connection
   include('../conn.php');

   // Get the user ID from the form submission
   $uid = $_POST['uid'];

   // Update all unread notifications for the user to "read"
   $update_query = "UPDATE notification SET status = 'read' WHERE uid = $uid AND status = 'unread'";
   if (mysqli_query($conn, $update_query)) {
       // Redirect to notifications.php
       header('Location: notifications.php');
       exit();
   } else {
       // Handle error
       echo "Error updating notification status";
   }
   ?>