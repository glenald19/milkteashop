<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

include 'db.php';

// Fetch products
$product_query = "SELECT * FROM products ORDER BY created_at DESC";
$product_result = mysqli_query($conn, $product_query);

// Fetch sizes
$sizes_result = mysqli_query($conn, "SELECT * FROM sizes");
$sizes = [];
while ($row = mysqli_fetch_assoc($sizes_result)) {
    $sizes[$row['size_id']] = $row['size_name'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Manage Products</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <style>
        /* Reset & base */
        * {
            box-sizing: border-box;
        }
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f9;
            color: #333;
            padding: 20px;
        }
        a {
            text-decoration: none;
            color: #007bff;
            transition: color 0.3s ease;
        }
        a:hover {
            color: #0056b3;
        }

        header {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            gap: 10px;
        }
        header h1 {
            margin: 0;
            font-weight: 700;
            font-size: 2rem;
            color: #212529;
        }
        nav a {
            display: inline-block;
            margin-left: 10px;
            padding: 10px 18px;
            border-radius: 6px;
            background-color: #007bff;
            color: white;
            font-weight: 600;
            box-shadow: 0 3px 6px rgba(0,123,255,0.4);
            user-select: none;
        }
        nav a:hover {
            background-color: #0056b3;
        }
        nav a:first-child {
            background-color: transparent;
            color: #007bff;
            box-shadow: none;
            padding: 0;
            font-weight: 500;
        }
        nav a:first-child:hover {
            background-color: transparent;
            text-decoration: underline;
            color: #0056b3;
        }

        .product-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }
        .product-card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.06);
            padding: 20px;
            display: flex;
            flex-direction: column;
            transition: transform 0.2s ease;
        }
        .product-card:hover {
            transform: translateY(-5px);
        }
        .product-card div:first-child {
            width: 100%;
            height: 180px;
            border-radius: 8px;
            overflow: hidden;
            background: #e9ecef;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 15px;
        }
        .product-card img {
            width: auto;
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            display: block;
        }
        .product-card p {
            flex-grow: 1;
            margin-bottom: 10px;
            white-space: pre-wrap;
            line-height: 1.4;
            color: #333;
        }
        .product-card h3 {
            margin-top: 0;
            margin-bottom: 12px;
            color: #212529;
            font-weight: 700;
        }
        .product-card .actions {
            display: flex;
            justify-content: flex-end;
            gap: 15px;
        }
        .product-card .actions a {
            font-weight: 600;
            padding: 8px 14px;
            border-radius: 6px;
            background-color: #007bff;
            color: white;
            box-shadow: 0 2px 6px rgba(0,123,255,0.4);
            user-select: none;
            transition: background-color 0.3s ease;
        }
        .product-card .actions a:hover {
            background-color: #0056b3;
        }
        .product-card .actions a:last-child {
            background-color: #dc3545;
            box-shadow: 0 2px 6px rgba(220,53,69,0.4);
        }
        .product-card .actions a:last-child:hover {
            background-color: #a71d2a;
        }

        @media (max-width: 480px) {
            header {
                flex-direction: column;
                align-items: flex-start;
            }
            nav a {
                margin-left: 0;
                margin-top: 8px;
            }
            .product-card div:first-child {
                height: 140px;
            }
        }
    </style>
</head>
<body>
<header>
    <h1>Manage Products</h1>
    <nav>
        <a href="admin_dashboard.php">← Back to Dashboard</a>
        <a href="add_product.php" class="button">➕ Add New Product</a>
    </nav>
</header>

<div class="product-container">
    <?php while ($product = mysqli_fetch_assoc($product_result)): ?>
        <div class="product-card">
            <div>
                <?php if ($product['image_url']): ?>
                    <img src="../images/<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['product_name']) ?>" />
                <?php else: ?>
                    <p>No Image</p>
                <?php endif; ?>
            </div>
            <h3><?= htmlspecialchars($product['product_name']) ?></h3>
            <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
            <p>
                <?php
                    $product_id = $product['product_id'];
                    $price_query = "SELECT * FROM product_sizes WHERE product_id = $product_id";
                    $price_result = mysqli_query($conn, $price_query);
                    if (mysqli_num_rows($price_result) > 0) {
                        while ($price = mysqli_fetch_assoc($price_result)) {
                            $size_name = $sizes[$price['size_id']] ?? 'Unknown Size';
                            echo "<strong>$size_name:</strong> ₱" . number_format($price['price'], 2) . "<br>";
                        }
                    } else {
                        echo "<em>No prices set</em>";
                    }
                ?>
            </p>
            <div class="actions">
                <a href="edit_product.php?id=<?= $product_id ?>" title="Edit Product">✏️ Edit</a>
                <a href="delete_product.php?id=<?= $product_id ?>" onclick="return confirm('Are you sure?');" title="Delete Product">🗑 Delete</a>
            </div>
        </div>
    <?php endwhile; ?>
</div>
</body>
</html>
<?php mysqli_close($conn); ?>
