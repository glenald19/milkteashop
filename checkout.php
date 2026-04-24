<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Get cart items
$query = "
    SELECT ci.cart_item_id, ci.quantity, ps.price, p.product_name, s.size_name, sl.sugar_level_name
    FROM cart_items ci
    JOIN product_sizes ps ON ci.product_size_id = ps.product_size_id
    JOIN products p ON ps.product_id = p.product_id
    JOIN sizes s ON ps.size_id = s.size_id
    JOIN sugar_levels sl ON ci.sugar_level_id = sl.sugar_level_id
    WHERE ci.user_id = ? AND ci.order_id IS NULL
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$cart = $stmt->get_result();

$cart_items = [];
$total = 0;
while ($item = $cart->fetch_assoc()) {
    $item['subtotal'] = $item['price'] * $item['quantity'];
    $total += $item['subtotal'];
    $cart_items[] = $item;
}

// Add shipping fee
$shipping_fee = 50; // Set your shipping fee here
$grand_total = $total + $shipping_fee;
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <link rel="stylesheet" href="stylesss.css"> 
</head>
<body>

<header>
    <h1>Checkout</h1>
    <nav>
        <a href="products.php">← Back to Products</a>
        <a href="cart.php">🛒 Cart</a>
    </nav>
</header>

<main class="checkout-container">

    <form action="place_order.php" method="POST" class="checkout-form">

        <section class="form-section">
            <h2>Shipping Address</h2>
            <input type="text" name="municipality" placeholder="Municipality" required>
            <input type="text" name="barangay" placeholder="Barangay" required>
            <input type="text" name="address_line" placeholder="Address Line">
        </section>

        <section class="form-section">
            <h2>Payment Method</h2>
            <select name="payment_method" required>
                <option value="">-- Select Payment Method --</option>
                <option value="COD">Cash on Delivery</option>
                <option value="GCash">GCash</option>
            </select>
        </section>

        <section class="form-section">
            <h2>Your Cart</h2>
            <ul class="cart-summary">
                <?php foreach ($cart_items as $item): ?>
                    <li>
                        <?= htmlspecialchars($item['product_name']) ?> - <?= $item['size_name'] ?> - <?= $item['sugar_level_name'] ?> 
                        x <?= $item['quantity'] ?> = <strong>₱<?= number_format($item['subtotal'], 2) ?></strong>
                    </li>
                <?php endforeach; ?>
            </ul>
            <p class="total">Subtotal: <strong>₱<?= number_format($total, 2) ?></strong></p>
            <p class="total">Shipping Fee: <strong>₱<?= number_format($shipping_fee, 2) ?></strong></p>
            <p class="total">Grand Total: <strong>₱<?= number_format($grand_total, 2) ?></strong></p>
            <input type="hidden" name="shipping_fee" value="<?= $shipping_fee ?>">
        </section>


        <button type="submit" class="checkout-button">Place Order</button>

    </form>

</main>

</body>
</html>
