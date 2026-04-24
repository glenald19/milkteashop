<?php 
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$municipality = $_POST['municipality'];
$barangay = $_POST['barangay'];
$address_line = $_POST['address_line'];
$payment_method = $_POST['payment_method'];
$shipping_fee = isset($_POST['shipping_fee']) ? (float)$_POST['shipping_fee'] : 0; // ✅ Step 2: Get shipping fee

// Insert into shipping_addresses
$stmt = $conn->prepare("INSERT INTO shipping_addresses (user_id, municipality, barangay, address_line) VALUES (?, ?, ?, ?)");
$stmt->bind_param("isss", $user_id, $municipality, $barangay, $address_line);
$stmt->execute();
$shipping_address_id = $stmt->insert_id;

// Fetch cart items and calculate total
$stmt = $conn->prepare("
    SELECT ci.cart_item_id, ci.product_size_id, ci.sugar_level_id, ci.quantity, ps.price
    FROM cart_items ci
    JOIN product_sizes ps ON ci.product_size_id = ps.product_size_id
    WHERE ci.user_id = ? AND ci.order_id IS NULL
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$total_amount = 0;
$cart_data = [];
while ($row = $result->fetch_assoc()) {
    $subtotal = $row['price'] * $row['quantity'];
    $total_amount += $subtotal;
    $cart_data[] = array_merge($row, ['subtotal' => $subtotal]);
}

$total_amount += $shipping_fee; // ✅ Step 2: Add shipping fee to total amount

// Insert into orders
$stmt = $conn->prepare("INSERT INTO orders (user_id, shipping_address_id, total_amount) VALUES (?, ?, ?)");
$stmt->bind_param("iid", $user_id, $shipping_address_id, $total_amount);
$stmt->execute();
$order_id = $stmt->insert_id;

// Insert into order_items and update cart_items
foreach ($cart_data as $item) {
    // Insert into order_items
    $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_size_id, sugar_level_id, quantity, subtotal) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iiiid", $order_id, $item['product_size_id'], $item['sugar_level_id'], $item['quantity'], $item['subtotal']);
    $stmt->execute();

    // Update cart_items with order_id
    $stmt = $conn->prepare("UPDATE cart_items SET order_id = ? WHERE cart_item_id = ?");
    $stmt->bind_param("ii", $order_id, $item['cart_item_id']);
    $stmt->execute();
}

// Insert into payments
$stmt = $conn->prepare("INSERT INTO payments (order_id, payment_method) VALUES (?, ?)");
$stmt->bind_param("is", $order_id, $payment_method);
$stmt->execute();

// Redirect to order details page
header("Location: order_details.php?order_id=$order_id");
exit;
?>
