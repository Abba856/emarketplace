<?php
require_once 'config.php';

// SQL to create users table
$sql_users = "CREATE TABLE IF NOT EXISTS users (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($connection->query($sql_users) === TRUE) {
    echo "Table 'users' created successfully or already exists.\n";
} else {
    echo "Error creating table: " . $connection->error . "\n";
}

// Check if farmer_name column exists, and add it if it doesn't
$check_column = "SHOW COLUMNS FROM products LIKE 'farmer_name'";
$result = $connection->query($check_column);

if ($result->num_rows == 0) {
    $add_column = "ALTER TABLE products ADD COLUMN farmer_name VARCHAR(100)";
    if ($connection->query($add_column) === TRUE) {
        echo "Column 'farmer_name' added successfully.\n";
    } else {
        echo "Error adding column: " . $connection->error . "\n";
    }
}

// SQL to create products table
$sql_products = "CREATE TABLE IF NOT EXISTS products (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT(6) UNSIGNED,
    name VARCHAR(255) NOT NULL,
    category VARCHAR(50) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    media_url VARCHAR(500),
    media_type VARCHAR(50),
    phone VARCHAR(20) NOT NULL,
    location VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    farmer_name VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
)";

if ($connection->query($sql_products) === TRUE) {
    echo "Table 'products' created successfully or already exists.\n";
} else {
    echo "Error creating table: " . $connection->error . "\n";
}

// Insert some sample data if no products exist yet
$result = $connection->query("SELECT COUNT(*) as count FROM products");
$row = $result->fetch_assoc();
$product_count = $row['count'];

if ($product_count == 0) {
    // Insert some sample data
    $sample_products = [
        [
            'name' => 'Fresh Tomatoes',
            'category' => 'vegetable',
            'price' => 1200.00,
            'media_url' => 'https://source.unsplash.com/400x300/?vegetables,fresh',
            'media_type' => 'image',
            'phone' => '080-1234-5678',
            'location' => 'Lagos',
            'description' => 'Locally grown, organic tomatoes. Perfect for salads and cooking.'
        ],
        [
            'name' => 'Oriental Oranges',
            'category' => 'fruit',
            'price' => 2500.00,
            'media_url' => 'https://source.unsplash.com/400x300/?fruit,fresh',
            'media_type' => 'image',
            'phone' => '080-2345-6789',
            'location' => 'Ogun',
            'description' => 'Sweet and juicy oranges, rich in Vitamin C. Perfect for juicing.'
        ],
        [
            'name' => 'Local Rice',
            'category' => 'grain',
            'price' => 15000.00,
            'media_url' => 'https://source.unsplash.com/400x300/?rice,grain',
            'media_type' => 'image',
            'phone' => '080-3456-7890',
            'location' => 'Kano',
            'description' => 'High-quality locally grown rice. Unpolished and nutritious.'
        ],
        [
            'name' => 'Fresh Milk',
            'category' => 'dairy',
            'price' => 800.00,
            'media_url' => 'https://source.unsplash.com/400x300/?dairy,milk',
            'media_type' => 'image',
            'phone' => '080-4567-8901',
            'location' => 'Kaduna',
            'description' => 'Cow milk collected this morning. Pure and nutritious.'
        ]
    ];

    foreach ($sample_products as $product) {
        $stmt = $connection->prepare("INSERT INTO products (name, category, price, media_url, media_type, phone, location, description) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdsssss", $product['name'], $product['category'], $product['price'], $product['media_url'], $product['media_type'], $product['phone'], $product['location'], $product['description']);
        
        if ($stmt->execute()) {
            echo "Sample product added: " . $product['name'] . "\n";
        } else {
            echo "Error adding sample product: " . $stmt->error . "\n";
        }
        $stmt->close();
    }
} else {
    echo "Products table already has data. Skipping sample data insertion.\n";
}

echo "Database setup completed successfully!\n";
$connection->close();
?>