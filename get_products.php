<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once 'config.php';

$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';

$sql = "SELECT id, user_id, name, category, price, media_url, media_type, phone, location, description, farmer_name, created_at FROM products WHERE 1=1";
if ($filter !== 'all') {
    $sql .= " AND category = ?";
}

$stmt = $connection->prepare($sql);

if ($filter !== 'all') {
    $stmt->bind_param("s", $filter);
}

$stmt->execute();
$result = $stmt->get_result();

$products = array();
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

echo json_encode($products);

$connection->close();
?>