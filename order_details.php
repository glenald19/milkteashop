<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['order_id'])) {
    header("Location: login.php");
    exit;
}

$order_id = intval($_GET['order_id']);
$user_id = $_SESSION['user_id'];

// Fetch order details
$stmt = $conn->prepare("
    SELECT o.order_id, o.total_amount, o.order_date, o.status,
           sa.municipality, sa.barangay, sa.address_line,
           p.payment_method
    FROM orders o
    JOIN shipping_addresses sa ON o.shipping_address_id = sa.address_id
    JOIN payments p ON o.order_id = p.order_id
    WHERE o.order_id = ? AND o.user_id = ?
");
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$order_result = $stmt->get_result();

if ($order_result->num_rows === 0) {
    echo "<p style='text-align:center;margin-top:50px;'>Order not found or you are not authorized to view it.</p>";
    exit;
}

$order = $order_result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Order #<?= $order_id ?> Details</title>
    <link rel="stylesheet" href="stylesss.css">
</head>
<body>

<header>
    <h1>Order #<?= $order['order_id'] ?> Details</h1>
    <nav>
        <a href="order_history.php">← Back to Order History</a>
        <a href="products.php">← Back to Products</a>
    </nav>
</header>

<div class="container">

    <div class="section">
        <h2>Order Summary</h2>
        <p><strong>Status:</strong> <span id="order-status"><?= ucfirst($order['status']) ?></span></p>
        <p><strong>Date:</strong> <?= date("F j, Y, g:i A", strtotime($order['order_date'])) ?></p>
        <p><strong>Payment Method:</strong> <?= ucfirst($order['payment_method']) ?></p>
    </div>

    <div class="section">
        <h2>Shipping Address</h2>
        <p><strong>Address:</strong> <?= htmlspecialchars($order['address_line']) ?>, <?= htmlspecialchars($order['barangay']) ?>, <?= htmlspecialchars($order['municipality']) ?></p>
    </div>

    <div class="section">
        <h2>Items in This Order</h2>
        <div class="item-list">
            <?php
            $stmt = $conn->prepare("
                SELECT p.product_name, ps.price, s.size_name, sl.sugar_level_name,
                       oi.quantity, oi.subtotal
                FROM order_items oi
                JOIN product_sizes ps ON oi.product_size_id = ps.product_size_id
                JOIN products p ON ps.product_id = p.product_id
                JOIN sizes s ON ps.size_id = s.size_id
                JOIN sugar_levels sl ON oi.sugar_level_id = sl.sugar_level_id
                WHERE oi.order_id = ?
            ");
            $stmt->bind_param("i", $order_id);
            $stmt->execute();
            $items = $stmt->get_result();

            $item_total = 0;
            while ($item = $items->fetch_assoc()):
                $item_total += $item['subtotal'];
            ?>
                <div class="item-card">
                    <h3><?= htmlspecialchars($item['product_name']) ?></h3>
                    <p><strong>Size:</strong> <?= htmlspecialchars($item['size_name']) ?></p>
                    <p><strong>Sugar Level:</strong> <?= htmlspecialchars($item['sugar_level_name']) ?></p>
                    <p><strong>Price:</strong> ₱<?= number_format($item['price'], 2) ?></p>
                    <p><strong>Quantity:</strong> <?= $item['quantity'] ?></p>
                    <p><strong>Subtotal:</strong> ₱<?= number_format($item['subtotal'], 2) ?></p>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <?php
        $shipping_fee = 50.00;
        $grand_total = $order['total_amount'];
    ?>

    <div class="section total">
        <h2>Order Total</h2>
        <p><strong>Items Subtotal:</strong> ₱<?= number_format($item_total, 2) ?></p>
        <p><strong>Shipping Fee:</strong> ₱<?= number_format($shipping_fee, 2) ?></p>
        <p><strong>Grand Total:</strong> <span style="font-size: 1.2em;">₱<?= number_format($grand_total, 2) ?></span></p>
    </div>

</div>

<script>
const orderId = <?= $order['order_id'] ?>;
function fetchStatus() {
    fetch('get_order_status.php?order_id=' + orderId)
        .then(response => response.ok ? response.text() : Promise.reject())
        .then(status => {
            if (status) {
                document.getElementById('order-status').textContent = status.charAt(0).toUpperCase() + status.slice(1);
            }
        });
}
setInterval(fetchStatus, 5000); // Poll every 5 seconds
</script>

</body>
</html>

<?php $conn->close(); ?>
