<?php
session_start();
include '../conn.php';

if (isset($_SESSION['uid'])) {
    $uid = $_SESSION['uid'];
    if (isset($_POST['btnSubmit'])) {
        $order_id = $_POST['order_id'];
        $reason = $_POST['reason'];
        $desc = $_POST['description'];
        $status = 'Pending'; // Assuming you want to set the status as 'Pending' initially

        // Check if a file was uploaded and there were no errors
        if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
            $uploadDir = 'return/';
            $fileTmpPath = $_FILES['image']['tmp_name'];
            $fileName = $_FILES['image']['name'];
            $fileSize = $_FILES['image']['size'];
            $fileType = $_FILES['image']['type'];
            $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif']; // Define allowed file types
            $maxFileSize = 5 * 1024 * 1024; // 5MB

            // Check file extension and size
            if (in_array($fileExtension, $allowedExtensions) && $fileSize <= $maxFileSize) {
                $newFileName = uniqid('img_', true) . '.' . $fileExtension;
                $uploadFile = $uploadDir . $newFileName;

                // Move uploaded file to the destination directory
                if (move_uploaded_file($fileTmpPath, $uploadFile)) {
                    // Prepare the SQL statement
                    $stmt = $conn->prepare("INSERT INTO order_return (order_id, reason, description, img, return_status) VALUES (?, ?, ?, ?, ?)");
                    $stmt->bind_param("sssss", $order_id, $reason, $desc, $newFileName, $status);

                    if ($stmt->execute()) {
                        $stmt->close();

                        // Update order status
                        $stmt = $conn->prepare("UPDATE orders SET osid = 4 WHERE order_id = ?");
                        $stmt->bind_param("i", $order_id);
                        if ($stmt->execute()) {
                            $stmt->close();
                            ?>
                            <script>
                                window.location.href = 'purchase.php';
                            </script>
                            <?php
                            exit;
                        } else {
                            $stmt->close();
                            ?>
                            <script>
                                alert('Error updating order status.');
                                window.location.href = 'purchase.php';
                            </script>
                            <?php
                            exit;
                        }

                        $notification_message = "A product return has been requested. See details";
                        $notification_status = "unread"; // Set the initial status as unread
                        
                        // Insert the notification into the database
                        $insert_notification_query = "INSERT INTO notification (uid, message, status, timestamp) 
                                                    VALUES ('$uid', '$notification_message', '$notification_status', NOW())";
                        
                        $notif = mysqli_query($conn, $insert_notification_query);

                    } else {
                        $stmt->close();
                        ?>
                        <script>
                            alert('Error submitting return request.');
                            window.location.href = 'purchase.php';
                        </script>
                        <?php
                        exit;
                    }
                } else {
                    echo "Possible file upload attack!";
                }
            } else {
                ?>
                <script>
                    alert('Invalid file type or file size too large.');
                    window.history.back();
                </script>
                <?php
                exit;
            }
        } else {
            // Handle case when no file is uploaded
            ?>
            <script>
                alert('No file uploaded or there was an error with the upload.');
                window.history.back();
            </script>
            <?php
            exit;
        }
    }
} else {
    ?>
    <script>
        window.location.href = '../login.php';
    </script>
    <?php
    exit();
}
?>
