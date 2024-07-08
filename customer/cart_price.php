<?php
session_start();
include('../conn.php');

if(isset($_SESSION['uid'])){
    $uid = $_SESSION['uid'];
    $selected_pid = $_POST['selected_pid'];
    $pid = explode(',', $selected_pid);
    $pid_str = implode(',', $pid);

    // Fetch cart items based on selected PIDs
    $cart = "SELECT * FROM cart 
             JOIN furniture ON cart.pid = furniture.pid
             WHERE cart.pid IN ($pid_str) AND cart.uid = '$uid'";
    $cartres = mysqli_query($conn, $cart);

    $items = [];
    $total = 0;

    if($cartres && mysqli_num_rows($cartres) > 0){
        while($cartrow = mysqli_fetch_assoc($cartres)){
            $item_name = $cartrow['pname'];
            $qty = $cartrow['qty'];
            $price = $cartrow['price'];
            $subtotal = $price * $qty; 

            $items[] = array(
                'item_name' => $item_name,
                'qty' => $qty,
                'price' => $price
            );

            $total += $subtotal;
        }
    }

    // Return JSON response
    $response = array(
        'items' => $items,
        'total' => $total
    );

    echo json_encode($response);
    exit();
} else {
    echo "User session not found.";
}
?>
