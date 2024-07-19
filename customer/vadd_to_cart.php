<?php
session_start();
include '../conn.php';

if (!isset($_SESSION['uid'])) {
    ?>
    <script>
        window.location.href = '../login.php';
    </script>
    <?php
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_SESSION['uid'])) {
        $uid = $_SESSION['uid'];
        $pid = $_POST['pid'];
        $color = $_POST['color'];
        $width = $_POST['width'];
        $height = $_POST['height'];
        $qty = $_POST['quantity'];

        $cart_check = "SELECT * FROM cart WHERE uid = '$uid' AND pid = '$pid'";
        $cart_check_res = mysqli_query($conn, $cart_check);

        if ($cart_check_res && mysqli_num_rows($cart_check_res) > 0) {
            $row = mysqli_fetch_assoc($cart_check_res);
            $qty = $row['qty'];
        
            $cart_update = "UPDATE cart SET qty = $qty + 1 WHERE pid = '$pid' AND uid = '$uid'";
            $cart_update_res = mysqli_query($conn, $cart_update);
        
            if ($cart_update_res) {
                ?>
                <script>
                    window.location.href = 'view_product.php?id=<?php echo $pid ?>&success=true';
                </script>
                <?php
                exit; 
            } else {
                ?>
                <script>
                    window.location.href = 'view_product.php?id=<?php echo $pid ?>&success=false';
                </script>
                <?php
                exit; 
            }
        } else {
            $cart = "INSERT INTO cart (pid, uid, qty, color, width, height) VALUES ('$pid', '$uid', '$qty', '$color', '$width', '$height')";
            $cartres = mysqli_query($conn, $cart);
    
            if ($cartres) {
                ?>
                <script>
                    window.location.href = 'view_product.php?id=<?php echo $pid ?>&success=true';
                </script>
                <?php
                exit; 
            } else {
                ?>
                <script>
                    window.location.href = 'view_product.php?id=<?php echo $pid ?>&success=false';
                </script>
                <?php
                exit; 
            }
        }
    } 
}
?>