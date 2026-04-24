<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];

    $update_query = "UPDATE orders SET status = '$status' WHERE order_id = $order_id";
    if ($conn->query($update_query) === TRUE) {
        header("Location: manage_orders.php");
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>
