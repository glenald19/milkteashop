<?php
// user/get_order_status.php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['order_id'])) {
    http_response_code(403);
    exit;
}

$order_id = intval($_GET['order_id']);
$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT status FROM orders WHERE order_id = ? AND user_id = ?");
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$stmt->bind_result($status);

if ($stmt->fetch()) {
    echo htmlspecialchars($status);
} else {
    http_response_code(404);
}
$stmt->close();
$conn->close();
?>