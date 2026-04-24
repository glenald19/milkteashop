<?php
include 'db.php';

$orders_query = "SELECT o.*, u.first_name, u.email, s.municipality, s.barangay, s.address_line 
                 FROM orders o
                 JOIN users u ON o.user_id = u.user_id
                 JOIN shipping_addresses s ON o.shipping_address_id = s.address_id
                 ORDER BY o.order_date DESC";
$orders_result = $conn->query($orders_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Orders</title>
    <!-- Styled Back Link -->
        <a href="admin_dashboard.php" class="back-link">← Back to Dashboard</a>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        body {
            margin: 0;
            padding: 20px;
            background: #f4f6f9;
            color: #333;
        }
        .dashboard {
            max-width: 1100px;
            margin: auto;
        }
        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #212529;
        }
        .order-card {
            background-color: #fff;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
            transition: transform 0.2s;
        }
        .order-card:hover {
            transform: translateY(-2px);
        }
        .order-card h2 {
            margin-top: 0;
            color: #007bff;
        }
        .order-card h3 {
            margin-bottom: 10px;
        }
        .order-card ul {
            list-style-type: disc;
            margin-left: 20px;
            margin-bottom: 15px;
        }
        .order-card p {
            margin: 5px 0;
        }
        form {
            margin-top: 15px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            align-items: center;
        }
        select {
            padding: 8px 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        button {
            padding: 8px 14px;
            background-color: #007bff;
            border: none;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }
        button:hover {
            background-color: #0056b3;
        }

        .back-link {
            display: inline-block;
            margin-top: 30px;
            padding: 10px 18px;
            background-color: #343a40;
            color: #ffffff;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 500;
            transition: background-color 0.3s ease, transform 0.2s ease;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
        }

        .back-link:hover {
            background-color: #212529;
            transform: translateY(-2px);
            text-decoration: none;
        }

        @media (max-width: 768px) {
            .order-card {
                padding: 15px;
            }
            form {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <h1>Manage Orders</h1>

        <?php while($order = $orders_result->fetch_assoc()): ?>
            <div class="order-card">
                <h2>Order #<?= $order['order_id'] ?> - <?= ucfirst($order['status']) ?></h2>
                <p><strong>Customer:</strong> <?= $order['first_name'] ?> (<?= $order['email'] ?>)</p>
                <p><strong>Shipping Address:</strong> <?= $order['municipality'] ?>, <?= $order['barangay'] ?>, <?= $order['address_line'] ?></p>
                <p><strong>Order Date:</strong> <?= $order['order_date'] ?></p>
                <p><strong>Total Amount:</strong> ₱<?= number_format($order['total_amount'], 2) ?></p>

                <!-- Order Items -->
                <h3>Items:</h3>
                <ul>
                    <?php
                    $items_query = "SELECT oi.*, p.product_name, sz.size_name, sl.sugar_level_name
                                    FROM order_items oi
                                    JOIN product_sizes ps ON oi.product_size_id = ps.product_size_id
                                    JOIN products p ON ps.product_id = p.product_id
                                    JOIN sizes sz ON ps.size_id = sz.size_id
                                    JOIN sugar_levels sl ON oi.sugar_level_id = sl.sugar_level_id
                                    WHERE oi.order_id = ".$order['order_id'];
                    $items_result = $conn->query($items_query);
                    while($item = $items_result->fetch_assoc()):
                    ?>
                        <li>
                            <?= $item['product_name'] ?> (<?= $item['size_name'] ?>, <?= $item['sugar_level_name'] ?>) 
                            - Qty: <?= $item['quantity'] ?> 
                            - Subtotal: ₱<?= number_format($item['subtotal'], 2) ?>
                        </li>
                    <?php endwhile; ?>
                </ul>

                <!-- Payment -->
                <?php
                $payment_query = "SELECT * FROM payments WHERE order_id = ".$order['order_id']." LIMIT 1";
                $payment_result = $conn->query($payment_query);
                $payment = $payment_result->fetch_assoc();
                ?>
                <p><strong>Payment Method:</strong> <?= $payment ? $payment['payment_method'] : 'N/A' ?></p>
                <p><strong>Payment Status:</strong> <?= $payment ? $payment['payment_status'] : 'N/A' ?></p>

                <!-- Update Status -->
                <form action="update_order_status.php" method="POST">
                    <input type="hidden" name="order_id" value="<?= $order['order_id'] ?>">
                    <select name="status" required>
                        <option value="pending" <?= $order['status']=='pending'?'selected':'' ?>>Pending</option>
                        <option value="processing" <?= $order['status']=='processing'?'selected':'' ?>>Processing</option>
                        <option value="completed" <?= $order['status']=='completed'?'selected':'' ?>>Completed</option>
                        <option value="cancelled" <?= $order['status']=='cancelled'?'selected':'' ?>>Cancelled</option>
                    </select>
                    <button type="submit">Update Status</button>
                </form>
            </div>
        <?php endwhile; ?>

        
    </div>
</body>
</html>
