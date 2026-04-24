<?php
include 'db.php'; // Adjust if your db.php is outside products/ folder

$product_image = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_name = $_POST['product_name'];
    $description = $_POST['description'];
    $prices = $_POST['prices'];

    // Handle file upload
    if (!empty($_FILES['product_image']['name'])) {
        $image_name = time() . "_" . basename($_FILES['product_image']['name']);
        $target_dir = "../images/"; // Correct path to /images/ folder in project root
        $target_file = $target_dir . $image_name;

        if (move_uploaded_file($_FILES['product_image']['tmp_name'], $target_file)) {
            $product_image = $image_name;
        }
    }

    // Insert product
    $stmt = $conn->prepare("INSERT INTO products (product_name, description, image_url) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $product_name, $description, $product_image);
    if ($stmt->execute()) {
        $product_id = $stmt->insert_id;

        // Insert product prices for each selected size
        $stmt_price = $conn->prepare("INSERT INTO product_sizes (product_id, size_id, price) VALUES (?, ?, ?)");
        foreach ($prices as $size_id => $price) {
            $price = floatval($price);
            if ($price > 0) {
                $stmt_price->bind_param("iid", $product_id, $size_id, $price);
                $stmt_price->execute();
            }
        }

        echo "<p style='color: green;'>✅ Product successfully added with prices per size!</p>";
    } else {
        echo "<p style='color: red;'>❌ Error: " . $stmt->error . "</p>";
    }
    $stmt->close();
}

// Fetch sizes
$sizes = [];
$result = $conn->query("SELECT * FROM sizes");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $sizes[] = $row;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Product - Milk Tea Shop</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        input, textarea {
            width: 300px;
            margin-bottom: 10px;
        }
        img {
            max-width: 200px;
            margin-top: 10px;
            display: none;
        }
    </style>
</head>
<body>
    <h2>Add New Product</h2>
    <form id="productForm" method="POST" enctype="multipart/form-data">
        <label>Product Name:</label><br>
        <input type="text" name="product_name" id="product_name" required><br>

        <label>Description:</label><br>
        <textarea name="description" id="description" required></textarea><br>

        <label>Product Image:</label><br>
        <input type="file" name="product_image" id="product_image" accept="image/*"><br>
        <img id="image_preview" src="#" alt="Image Preview"><br>

        <h4>Set Price Per Size:</h4>
        <?php foreach ($sizes as $size): ?>
            <label><?= htmlspecialchars($size['size_name']) ?> (₱):</label>
            <input type="number" step="0.01" min="0" name="prices[<?= $size['size_id'] ?>]" class="price-input" placeholder="e.g. 89.00"><br>
        <?php endforeach; ?>

        <br>
        <input type="submit" value="Add Product">
    </form>

    <script>
        // Live image preview
        document.getElementById('product_image').addEventListener('change', function () {
            const file = this.files[0];
            const preview = document.getElementById('image_preview');
            if (file) {
                preview.src = URL.createObjectURL(file);
                preview.style.display = 'block';
            } else {
                preview.style.display = 'none';
            }
        });

        // Client-side validation
        document.getElementById('productForm').addEventListener('submit', function (e) {
            const name = document.getElementById('product_name').value.trim();
            const desc = document.getElementById('description').value.trim();
            const priceInputs = document.querySelectorAll('.price-input');
            let hasPrice = false;

            priceInputs.forEach(input => {
                if (input.value && parseFloat(input.value) > 0) hasPrice = true;
            });

            if (!name || !desc || !hasPrice) {
                alert("⚠️ Please enter a product name, description, and at least one valid price.");
                e.preventDefault();
            }
        });
    </script>
    <a href="manage_products.php">← Back to Dashboard</a>
</body>
</html>
