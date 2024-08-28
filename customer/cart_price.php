<?php
session_start();
include('../conn.php');

if(isset($_SESSION['uid'])){
    $uid = $_SESSION['uid'];
    $selected_cid = $_POST['selected_cid'];
    $cid_array = explode(',', $selected_cid);
    $cid_array = array_filter($cid_array, 'is_numeric'); // Ensure all values are numeric
    $cid_str = implode(',', array_map('intval', $cid_array)); // Convert to integer and concatenate

    // Fetch cart items based on selected CIDs
    $cart = "SELECT * FROM cart 
             JOIN furniture ON cart.pid = furniture.pid
             WHERE cart.cid IN ($cid_str) AND cart.uid = '$uid'";
    $cartres = mysqli_query($conn, $cart);

    $items = [];
    $total = 0;

    if($cartres && mysqli_num_rows($cartres) > 0){
        while($cartrow = mysqli_fetch_assoc($cartres)){
            $item_name = $cartrow['pname'];
            $qty = $cartrow['qty'];
            $price = $cartrow['total_price'];
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
    echo json_encode(array("error" => "User session not found."));
}
?>
