<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');  // Update with your DB username
define('DB_PASS', 'root');      // Update with your DB password
define('DB_NAME', 'marketplace_db');

// Create connection
$connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}
?>