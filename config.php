<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');  // Update with your DB username
define('DB_PASS', 'root');      // Update with your DB password
define('DB_NAME', 'marketplace_db');

// Set appropriate file upload limits
ini_set('upload_max_filesize', '2M');
ini_set('post_max_size', '8M');
ini_set('max_execution_time', 60);
ini_set('memory_limit', '256M');

// Create connection
$connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}
?>