<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$query = "
    SELECT ci.cart_item_id, ci.quantity, ps.price, p.product_name, p.image_url, s.size_name, sl.sugar_level_name
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
$result = $stmt->get_result();

$total = 0;
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Your Cart</title>
    <script src="script.js" defer></script> <!-- Custom JS -->
    <link rel="stylesheet" href="stylesss.css">  
</head>
<body>

<header>
    <h1>Your Cart</h1>
    <nav>
        <a href="products.php">← Back to Products</a>
    </nav>
</header>

<main>
    <?php if ($result->num_rows > 0): ?>
        <div class="cart-container">
            <?php while ($row = $result->fetch_assoc()):
                $subtotal = $row['price'] * $row['quantity'];
                $total += $subtotal;
            ?>
                <div class="cart-card">
                    <img src="../images/<?= htmlspecialchars($row['image_url']) ?>" alt="<?= htmlspecialchars($row['product_name']) ?>">
                    <h3><?= htmlspecialchars($row['product_name']) ?></h3>
                    <p><strong>Size:</strong> <?= htmlspecialchars($row['size_name']) ?></p>
                    <p><strong>Sugar:</strong> <?= htmlspecialchars($row['sugar_level_name']) ?></p>
                    <p><strong>Price:</strong> ₱<?= number_format($row['price'], 2) ?></p>
                    <p><strong>Qty:</strong> <?= $row['quantity'] ?></p>
                    <p class="subtotal">Subtotal: ₱<?= number_format($subtotal, 2) ?></p>
                    
                    <!-- Remove Button -->
                    <form action="remove_from_cart.php" method="post" onsubmit="return confirm('Remove this item from cart?');">
                        <input type="hidden" name="cart_item_id" value="<?= $row['cart_item_id'] ?>">
                        <button type="submit" class="remove-button"> Remove</button>
                    </form>
                </div>
            <?php endwhile; ?>
        </div>

        <div class="total-box">
            <h2>Total: ₱<?= number_format($total, 2) ?></h2>
            <a href="checkout.php" class="checkout-button">Proceed to Checkout</a>
        </div>
    <?php else: ?>
        <p class="empty-message">Your cart is empty. <a href="products.php">Start shopping</a> 🛒</p>
    <?php endif; ?>
</main>

</body>
</html>

<?php $conn->close(); ?>
