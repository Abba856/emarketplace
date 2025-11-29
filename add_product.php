<?php
session_start();
header('Content-Type: application/json');
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'You must be logged in to add products']);
    exit();
}

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $name = $_POST['name'] ?? '';
    $category = $_POST['category'] ?? '';
    $price = $_POST['price'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $location = $_POST['location'] ?? '';
    $description = $_POST['description'] ?? '';
    
    // Validate required fields
    if (empty($name) || empty($price) || empty($phone) || empty($location) || empty($description)) {
        echo json_encode(['success' => false, 'message' => 'All required fields must be filled']);
        exit;
    }
    
    // Handle file upload if provided
    $media_url = '';
    $media_type = '';
    
    if (isset($_FILES['media']) && $_FILES['media']['error'] == 0) {
        $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'video/mp4', 'video/webm', 'video/ogg'];
        $file_type = $_FILES['media']['type'];
        $file_size = $_FILES['media']['size'];
        
        if (in_array($file_type, $allowed_types)) {
            if ($file_size <= 2097152) { // 2MB limit to match PHP config
                $upload_dir = 'uploads/';
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                
                // Get file extension safely
                $file_extension = strtolower(pathinfo($_FILES['media']['name'], PATHINFO_EXTENSION));
                if (empty($file_extension)) {
                    // If no extension found, try to guess from MIME type
                    switch ($_FILES['media']['type']) {
                        case 'image/jpeg':
                            $file_extension = 'jpg';
                            break;
                        case 'image/png':
                            $file_extension = 'png';
                            break;
                        case 'image/gif':
                            $file_extension = 'gif';
                            break;
                        case 'video/mp4':
                            $file_extension = 'mp4';
                            break;
                        case 'video/webm':
                            $file_extension = 'webm';
                            break;
                        case 'video/ogg':
                            $file_extension = 'ogg';
                            break;
                        default:
                            $file_extension = 'unknown';
                    }
                }
                $file_name = uniqid() . '.' . $file_extension;
                $file_path = $upload_dir . $file_name;
                
                if (move_uploaded_file($_FILES['media']['tmp_name'], $file_path)) {
                    $media_url = $file_path;
                    $media_type = $file_type;
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to upload file']);
                    exit;
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'File size exceeds 2MB limit']);
                exit;
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid file type']);
            exit;
        }
    }
    
    // Insert product into database with user_id
    $user_id = $_SESSION['user_id'];
    
    // Handle potential null values for media
    $media_url_val = !empty($media_url) ? $media_url : null;
    $media_type_val = !empty($media_type) ? $media_type : null;
    
    $stmt = $connection->prepare("INSERT INTO products (user_id, name, category, price, media_url, media_type, phone, location, description) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssssss", $user_id, $name, $category, $price, $media_url_val, $media_type_val, $phone, $location, $description);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Product added successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $stmt->error]);
    }
    
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

$connection->close();
?>