<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

include 'db.php';

if (!isset($_GET['id'])) {
    header("Location: manage_products.php");
    exit();
}

$product_id = intval($_GET['id']);

// Fetch product info
$product_result = $conn->query("SELECT * FROM products WHERE product_id = $product_id");
if ($product_result->num_rows !== 1) {
    echo "Product not found.";
    exit();
}
$product = $product_result->fetch_assoc();

// Fetch sizes
$sizes_result = $conn->query("SELECT * FROM sizes");
$sizes = [];
while ($row = $sizes_result->fetch_assoc()) {
    $sizes[$row['size_id']] = $row['size_name'];
}

// Fetch prices for this product
$prices_result = $conn->query("SELECT * FROM product_sizes WHERE product_id = $product_id");
$product_prices = [];
while ($price_row = $prices_result->fetch_assoc()) {
    $product_prices[$price_row['size_id']] = $price_row['price'];
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_name = $_POST['product_name'];
    $description = $_POST['description'];
    $prices = $_POST['prices'];

    // Image handling
    $image_url = $product['image_url'];
    if (!empty($_FILES['product_image']['name'])) {
        $image_name = time() . "_" . basename($_FILES['product_image']['name']);
        $target_dir = "../images/";
        $target_file = $target_dir . $image_name;
        if (move_uploaded_file($_FILES['product_image']['tmp_name'], $target_file)) {
            $image_url = $image_name;
        }
    }

    // Update product
    $stmt = $conn->prepare("UPDATE products SET product_name=?, description=?, image_url=? WHERE product_id=?");
    $stmt->bind_param("sssi", $product_name, $description, $image_url, $product_id);
    $stmt->execute();

    // Update prices
    foreach ($prices as $size_id => $price) {
        $price = floatval($price);
        $size_id = intval($size_id);
        $check = $conn->query("SELECT * FROM product_sizes WHERE product_id=$product_id AND size_id=$size_id");
        if ($check->num_rows > 0) {
            $conn->query("UPDATE product_sizes SET price=$price WHERE product_id=$product_id AND size_id=$size_id");
        } else {
            $conn->query("INSERT INTO product_sizes (product_id, size_id, price) VALUES ($product_id, $size_id, $price)");
        }
    }

    echo "<script>alert('✅ Product updated successfully!'); window.location='manage_products.php';</script>";
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Product</title>
    <style>
        input, textarea { width: 300px; margin-bottom: 10px; }
        img { max-width: 200px; margin-top: 10px; }
    </style>
</head>
<body>
<h2>Edit Product</h2>
<form method="POST" enctype="multipart/form-data">
    <label>Product Name:</label><br>
    <input type="text" name="product_name" value="<?= htmlspecialchars($product['product_name']) ?>" required><br>

    <label>Description:</label><br>
    <textarea name="description" required><?= htmlspecialchars($product['description']) ?></textarea><br>

    <label>Product Image:</label><br>
    <input type="file" name="product_image" accept="image/*"><br>
    <?php if ($product['image_url']): ?>
        <img src="../images/<?= $product['image_url'] ?>" alt=""><br>
    <?php endif; ?>

    <h4>Prices Per Size:</h4>
    <?php foreach ($sizes as $size_id => $size_name): ?>
        <label><?= htmlspecialchars($size_name) ?> (₱):</label>
        <input type="number" step="0.01" name="prices[<?= $size_id ?>]" 
               value="<?= isset($product_prices[$size_id]) ? $product_prices[$size_id] : '0.00' ?>"><br>
    <?php endforeach; ?>

    <br>
    <input type="submit" value="Save Changes">
</form>
</body>
</html>
