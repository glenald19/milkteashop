<?php
session_start();
include 'db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];
$product_id = $_POST['product_id'];
$size_id = $_POST['product_size_id'];
$sugar_level_id = $_POST['sugar_level_id'];

// Get product_size_id from product_id and size_id
$stmt = $conn->prepare("SELECT product_size_id FROM product_sizes WHERE product_id = ? AND size_id = ?");
$stmt->bind_param("ii", $product_id, $size_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    echo json_encode(['error' => 'Invalid size selection']);
    exit;
}
$product_size = $result->fetch_assoc();
$product_size_id = $product_size['product_size_id'];

// Check if item already exists in cart
$stmt = $conn->prepare("SELECT cart_item_id, quantity FROM cart_items WHERE user_id = ? AND product_size_id = ? AND sugar_level_id = ? AND order_id IS NULL");
$stmt->bind_param("iii", $user_id, $product_size_id, $sugar_level_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Update quantity
    $row = $result->fetch_assoc();
    $new_quantity = $row['quantity'] + 1;
    $stmt = $conn->prepare("UPDATE cart_items SET quantity = ? WHERE cart_item_id = ?");
    $stmt->bind_param("ii", $new_quantity, $row['cart_item_id']);
    $stmt->execute();
} else {
    // Insert new cart item
    $stmt = $conn->prepare("INSERT INTO cart_items (user_id, product_size_id, sugar_level_id, quantity) VALUES (?, ?, ?, 1)");
    $stmt->bind_param("iii", $user_id, $product_size_id, $sugar_level_id);
    $stmt->execute();
}

// Get updated cart count
$stmt = $conn->prepare("SELECT COUNT(*) AS count FROM cart_items WHERE user_id = ? AND order_id IS NULL");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$cartCount = $result->fetch_assoc()['count'];

echo json_encode(['success' => true, 'cartCount' => $cartCount]);
?>
