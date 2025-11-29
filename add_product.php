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
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit();
}

// Get form data
$name = trim($_POST['name'] ?? '');
$category = trim($_POST['category'] ?? '');
$price = trim($_POST['price'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$location = trim($_POST['location'] ?? '');
$description = trim($_POST['description'] ?? '');

// Validate required fields
if (empty($name) || empty($price) || empty($phone) || empty($location) || empty($description)) {
    echo json_encode(['success' => false, 'message' => 'All required fields must be filled']);
    exit;
}

// Prepare defaults
$media_url = null;
$media_type = null;

// Handle file upload if provided
if (isset($_FILES['media']) && $_FILES['media']['error'] !== UPLOAD_ERR_NO_FILE) {
    // Check upload error
    if ($_FILES['media']['error'] !== UPLOAD_ERR_OK) {
        $upload_errors = [
            UPLOAD_ERR_INI_SIZE => 'File exceeds upload_max_filesize',
            UPLOAD_ERR_FORM_SIZE => 'File exceeds MAX_FILE_SIZE',
            UPLOAD_ERR_PARTIAL => 'File was only partially uploaded',
            UPLOAD_ERR_NO_FILE => 'No file was uploaded',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary directory',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
            UPLOAD_ERR_EXTENSION => 'File upload stopped by extension'
        ];
        $msg = $upload_errors[$_FILES['media']['error']] ?? 'Unknown upload error';
        error_log("Upload error: $msg");
        echo json_encode(['success' => false, 'message' => "Upload error: $msg"]);
        exit;
    }

    $tmp = $_FILES['media']['tmp_name'];
    $orig_name = $_FILES['media']['name'] ?? '';
    $file_size = $_FILES['media']['size'] ?? 0;

    // size limit (2MB)
    if ($file_size > 2 * 1024 * 1024) {
        echo json_encode(['success' => false, 'message' => 'File size exceeds 2MB limit']);
        exit;
    }

    // Determine MIME reliably
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($tmp) ?: ($_FILES['media']['type'] ?? '');

    $allowed_types = [
        'image/jpeg', 'image/png', 'image/gif',
        'video/mp4', 'video/webm', 'video/ogg'
    ];
    if (!in_array($mime, $allowed_types, true)) {
        echo json_encode(['success' => false, 'message' => 'Invalid file type. Allowed: JPG, PNG, GIF, MP4, WEBM, OGG']);
        exit;
    }

    // Validate extension as additional check
    $ext = strtolower(pathinfo($orig_name, PATHINFO_EXTENSION));
    $valid_ext = ['jpg','jpeg','png','gif','mp4','webm','ogg'];
    if (!in_array($ext, $valid_ext, true)) {
        echo json_encode(['success' => false, 'message' => 'Invalid file extension']);
        exit;
    }

    // If image, extra validation
    if (strpos($mime, 'image/') === 0) {
        if (@getimagesize($tmp) === false) {
            echo json_encode(['success' => false, 'message' => 'Uploaded file is not a valid image']);
            exit;
        }
    }

    // Build absolute upload directory and ensure it's writable
    $upload_dir_rel = 'uploads';
    $upload_dir_abs = __DIR__ . DIRECTORY_SEPARATOR . $upload_dir_rel;

    if (!is_dir($upload_dir_abs)) {
        if (!@mkdir($upload_dir_abs, 0755, true)) {
            error_log("Could not create upload directory: $upload_dir_abs");
            echo json_encode(['success' => false, 'message' => 'Server error creating upload folder', 'debug' => ['path' => $upload_dir_abs]]);
            exit;
        }
        @chmod($upload_dir_abs, 0755);
    }

    // Try to make it writable if not already
    if (!is_writable($upload_dir_abs)) {
        @chmod($upload_dir_abs, 0775);
        // re-check
        if (!is_writable($upload_dir_abs)) {
            // detailed debug for troubleshooting (remove in production)
            $ownerId = @fileowner($upload_dir_abs);
            $ownerName = function_exists('posix_getpwuid') && $ownerId !== false
                ? posix_getpwuid($ownerId)['name'] : ($ownerId === false ? 'unknown' : (string)$ownerId);
            $perms = substr(sprintf('%o', @fileperms($upload_dir_abs)), -4);
            error_log("Upload directory not writable: {$upload_dir_abs} owner: {$ownerName} perms: {$perms} PHP uid: " . getmyuid());
            echo json_encode([
                'success' => false,
                'message' => 'Server upload folder not writable',
                'debug' => ['path' => $upload_dir_abs, 'owner' => $ownerName, 'perms' => $perms]
            ]);
            exit;
        }
    }

    // Directory is writable, proceed with file upload
    // Create safe filename and move file
    $base_name = pathinfo($orig_name, PATHINFO_FILENAME);
    $sanitized_base = preg_replace('/[^a-zA-Z0-9-_]/', '_', $base_name);
    $file_name = $sanitized_base . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
    $dest_abs = $upload_dir_abs . DIRECTORY_SEPARATOR . $file_name;
    $dest_rel = $upload_dir_rel . '/' . $file_name; // what we store in DB / return to clients

    if (!move_uploaded_file($tmp, $dest_abs)) {
        error_log("move_uploaded_file failed. tmp: $tmp dest: $dest_abs");
        echo json_encode(['success' => false, 'message' => 'Failed to save uploaded file']);
        exit;
    }

    $media_url = $dest_rel;
    $media_type = $mime;
}

// Insert product into database with user_id
$user_id = $_SESSION['user_id'];

$stmt = $connection->prepare("INSERT INTO products (user_id, name, category, price, media_url, media_type, phone, location, description) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
if ($stmt === false) {
    echo json_encode(['success' => false, 'message' => 'Database prepare error: ' . $connection->error]);
    exit;
}

// bind_param types: i (user_id) + 8 strings
$stmt->bind_param("issssssss", $user_id, $name, $category, $price, $media_url, $media_type, $phone, $location, $description);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Product added successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $stmt->error]);
}

$stmt->close();
$connection->close();
?>