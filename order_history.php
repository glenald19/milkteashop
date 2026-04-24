<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch all orders
$stmt = $conn->prepare("SELECT order_id, total_amount, order_date, status FROM orders WHERE user_id = ? ORDER BY order_date DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Your Order History</title>
    <link rel="stylesheet" href="stylesss.css"> <!-- Your custom CSS -->
</head>
<body>

<header>
    <h1>Your Order History</h1>
    <nav>
        <a href="products.php">← Back to Products</a>
    </nav>
</header>

<main>
    <?php if ($result->num_rows > 0): ?>
        <div class="order-container">
            <?php while ($row = $result->fetch_assoc()): ?>
                <?php
                    $shipping_fee = 50.00; // Fixed shipping fee
                    $subtotal = $row['total_amount'] - $shipping_fee;
                ?>
                <div class="order-card">
                    <h2>Order #<?= $row['order_id'] ?></h2>
                    <p><strong>Date:</strong> <?= date("F j, Y g:i A", strtotime($row['order_date'])) ?></p>
                    <p><strong>Subtotal:</strong> ₱<?= number_format($subtotal, 2) ?></p>
                    <p><strong>Shipping Fee:</strong> ₱<?= number_format($shipping_fee, 2) ?></p>
                    <p><strong>Total:</strong> ₱<?= number_format($row['total_amount'], 2) ?></p>
                    <p><strong>Status:</strong> <?= ucfirst($row['status']) ?></p>
                    <a class="details-link" href="order_details.php?order_id=<?= $row['order_id'] ?>">More Details</a>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p class="empty-message">You have no orders yet. <a href="products.php">Start shopping</a> 🛒</p>
    <?php endif; ?>
</main>

</body>
</html>

<?php $conn->close(); ?>
