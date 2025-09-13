<?php 
include 'db.php';

// Get product ID from URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: products.php");
    exit();
}

$productId = intval($_GET['id']);

// Check if product exists
$sql = "SELECT name FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $productId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location: products.php");
    exit();
}

$product = $result->fetch_assoc();

// Delete product
$sql = "DELETE FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $productId);

if ($stmt->execute()) {
    // Redirect with success message
    header("Location: products.php?deleted=1&product=" . urlencode($product['name']));
} else {
    // Redirect with error message
    header("Location: products.php?error=1");
}
exit();
?> 