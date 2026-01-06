<?php
session_start();
header('Content-Type: application/json');
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'You must be logged in to delete products']);
    exit();
}

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit();
}

// Get product ID
$product_id = $_POST['product_id'] ?? null;

if (empty($product_id)) {
    echo json_encode(['success' => false, 'message' => 'Product ID is required']);
    exit();
}

// Validate product ID is numeric
if (!is_numeric($product_id)) {
    echo json_encode(['success' => false, 'message' => 'Invalid product ID']);
    exit();
}

$user_id = $_SESSION['user_id'];

// First, get the product details to check permission and retrieve media_url
$stmt = $connection->prepare("SELECT media_url FROM products WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $product_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Product not found or you do not have permission to delete it']);
    exit();
}

$row = $result->fetch_assoc();
$media_url = $row['media_url'];

// Delete the product
$delete_stmt = $connection->prepare("DELETE FROM products WHERE id = ? AND user_id = ?");
$delete_stmt->bind_param("ii", $product_id, $user_id);

if ($delete_stmt->execute()) {
    // If the product had a media file, delete it from the server
    if ($media_url && file_exists($media_url)) {
        unlink($media_url); // Delete the file from server
    }

    echo json_encode(['success' => true, 'message' => 'Product deleted successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $delete_stmt->error]);
}

$delete_stmt->close();
$connection->close();
?>