<?php
session_start();
include '../conn.php';

if (isset($_SESSION['uid'])) {
    $uid = $_SESSION['uid'];
    if (isset($_POST['btnSubmit'])) {
        $order_code = $_POST['order_code'];
        $reason = $_POST['reason'];
        $desc = $_POST['description'];

        if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
            $uploadDir = 'return/';
            
            $fileTmpPath = $_FILES['image']['tmp_name'];
            $fileName = $_FILES['image']['name'];
            $fileSize = $_FILES['image']['size'];
            $fileType = $_FILES['image']['type'];
            $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

            // Generate a unique filename using a combination of a unique ID and the original file extension
            $newFileName = uniqid('img_', true) . '.' . $fileExtension;
            $uploadFile = $uploadDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $uploadFile)) {
                $return = "INSERT INTO order_return (order_code, reason, description, img) VALUES ('$order_code', '$reason', '$desc', '$newFileName')";
                $returnres = mysqli_query($conn, $return);
                
                if ($returnres) {
                    $order_update = "UPDATE orders SET osid = 4 WHERE order_code = '$order_code'";
                    $order_updateres = mysqli_query($conn, $order_update);
                    ?>
                    <script>
                        window.location.href = 'purchase.php?order_code=<?php echo $order_code ?>&success=true';
                    </script>
                    <?php
                    exit;
                } else {
                    ?>
                    <script>
                        window.location.href = 'purchase.php?order_code=<?php echo $order_code ?>&success=false';
                    </script>
                    <?php
                    exit;
                }
            } else {
                echo "Possible file upload attack!\n";
            }
        }
        
        // You can now proceed with other processing like saving the return request details to the database
    }
}
?>
