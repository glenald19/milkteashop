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

// Delete product (product_sizes cascade deletion handled by DB foreign keys)
$conn->query("DELETE FROM products WHERE product_id = $product_id");

echo "<script>alert('Product deleted successfully.'); window.location='manage_products.php';</script>";
?>
