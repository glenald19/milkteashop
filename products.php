<?php
session_start();
include 'db.php';  // Include database connection

// Fetch products and their prices
$productQuery = "
    SELECT p.product_id, p.product_name, p.description, p.image_url,
           MIN(ps.price) AS product_price
    FROM products p
    JOIN product_sizes ps ON p.product_id = ps.product_id
    GROUP BY p.product_id
";
$products = $conn->query($productQuery);
if (!$products) {
    die("Product query failed: " . $conn->error);
}

// Fetch size options and prices per product
$sizeQuery = "
    SELECT ps.product_id, s.size_id, s.size_name, ps.price
    FROM product_sizes ps
    JOIN sizes s ON ps.size_id = s.size_id
";
$sizes = $conn->query($sizeQuery);
if (!$sizes) {
    die("Size query failed: " . $conn->error);
}

// Fetch sugar level options
$sugarQuery = "SELECT * FROM sugar_levels";
$sugar_levels = $conn->query($sugarQuery);
if (!$sugar_levels) {
    die("Sugar query failed: " . $conn->error);
}

// Store size options in an associative array
$sizeOptions = [];
while ($row = $sizes->fetch_assoc()) {
    $sizeOptions[$row['product_id']][] = $row;
}

// Store sugar level options in an array
$sugarOptions = [];
while ($row = $sugar_levels->fetch_assoc()) {
    $sugarOptions[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Milk Tea Menu</title>
    <link rel="stylesheet" href="stylesss.css"> <!-- Your custom CSS -->
    <link rel="stylesheet" href="fancybox.css">
    <script src="jquery-3.6.0.min.js"></script>
    <script src="fancybox.umd.js"></script>
    <script src="sweetalert2.js"></script>

    <link href='https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>


    <script src="script.js" defer></script> <!-- Custom JS -->
</head>

<body>

<header>
    <h1>Our Milk Tea</h1>
    <nav>
        
        <a href="order_history.php">Order History</a>
        <a href="logout.php">Logout</a>
        <a href="cart.php">Cart <span id="cart-count">(0)</span></a>
    </nav>
</header>

<main>
    <div class="product-grid">
        <?php while ($product = $products->fetch_assoc()): ?>
            <div class="product-card">
                <a href="../images/<?php echo htmlspecialchars($product['image_url']); ?>" data-fancybox="gallery" data-caption="<?php echo htmlspecialchars($product['product_name']); ?>">
                    <img src="../images/<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>">
                </a>
                <h3><?php echo htmlspecialchars($product['product_name']); ?></h3>
                <p class="price">₱<?php echo number_format($product['product_price'], 2); ?></p>

                <p class="description"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>

                <form class="add-to-cart-form" data-product-id="<?php echo $product['product_id']; ?>" method="post">
                    <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">

                    <label for="size">Size:</label>
                    <select name="product_size_id" class="size-select" required onchange="updatePrice(this)">
                        <option value="">Select Size</option>
                        <?php foreach ($sizeOptions[$product['product_id']] as $size): ?>
                            <option value="<?php echo $size['size_id']; ?>" data-price="<?php echo $size['price']; ?>">
                                <?php echo htmlspecialchars($size['size_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <label for="sugar">Sugar Level:</label>
                    <select name="sugar_level_id" class="sugar-select" required>
                        <option value="">Select Sugar</option>
                        <?php foreach ($sugarOptions as $sugar): ?>
                            <option value="<?php echo $sugar['sugar_level_id']; ?>">
                                <?php echo htmlspecialchars($sugar['sugar_level_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <button type="submit">
                        <i class='bx bx-cart-add'></i> Add to Cart
                    </button>

                </form>
            </div>
        <?php endwhile; ?>
    </div>
</main>



</body>
</html>

<?php $conn->close(); ?>
