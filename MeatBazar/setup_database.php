<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Database Setup - Meat Bazar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            color: #FF0000;
            margin-bottom: 30px;
        }
        .result {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
            border-left: 4px solid #28a745;
        }
        .error {
            border-left-color: #dc3545;
            background: #f8d7da;
        }
        .btn {
            display: inline-block;
            padding: 12px 25px;
            background-color: #FF0000;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin: 10px 5px;
        }
        .btn:hover {
            background-color: #cc0000;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🗄️ Database Setup - Meat Bazar</h1>
            <p>Setting up required database tables and columns...</p>
        </div>

        <?php
        require_once 'db.php';

        echo "<h2>Step 1: Adding Address Column</h2>";
        
        
        $addAddressColumn = "ALTER TABLE registration ADD COLUMN address TEXT DEFAULT 'No address provided'";
        
        if ($conn->query($addAddressColumn) === TRUE) {
            echo '<div class="result">✅ Address column added successfully to registration table.</div>';
        } else {
            if (strpos($conn->error, "Duplicate column name") !== false) {
                echo '<div class="result">✅ Address column already exists in registration table.</div>';
            } else {
                echo '<div class="result error">❌ Error adding address column: ' . $conn->error . '</div>';
            }
        }

        echo "<h2>Step 2: Creating Orders Table</h2>";

        
        $createOrdersTable = "CREATE TABLE IF NOT EXISTS orders (
            id INT AUTO_INCREMENT PRIMARY KEY,
            customer_name VARCHAR(100) NOT NULL,
            customer_phone VARCHAR(15) NOT NULL,
            customer_address TEXT NOT NULL,
            items JSON NOT NULL,
            subtotal DECIMAL(10,2) NOT NULL,
            delivery_fee DECIMAL(10,2) NOT NULL,
            total_amount DECIMAL(10,2) NOT NULL,
            payment_type ENUM('cod', 'online') NOT NULL,
            payment_method VARCHAR(50) NOT NULL,
            order_status ENUM('pending', 'confirmed', 'processing', 'delivered', 'cancelled') DEFAULT 'pending',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";

        if ($conn->query($createOrdersTable) === TRUE) {
            echo '<div class="result">✅ Orders table created successfully or already exists.</div>';
        } else {
            echo '<div class="result error">❌ Error creating orders table: ' . $conn->error . '</div>';
        }

        echo "<h2>Step 3: Creating Order Items Table</h2>";

        
        $createOrderItemsTable = "CREATE TABLE IF NOT EXISTS order_items (
            id INT AUTO_INCREMENT PRIMARY KEY,
            order_id INT NOT NULL,
            product_name VARCHAR(100) NOT NULL,
            product_price DECIMAL(10,2) NOT NULL,
            product_image VARCHAR(255) NOT NULL,
            quantity INT NOT NULL,
            item_total DECIMAL(10,2) NOT NULL,
            FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
        )";

        if ($conn->query($createOrderItemsTable) === TRUE) {
            echo '<div class="result">✅ Order items table created successfully or already exists.</div>';
        } else {
            echo '<div class="result error">❌ Error creating order items table: ' . $conn->error . '</div>';
        }

        echo "<h2>Step 4: Verifying Tables</h2>";

        
        $tables = ['registration', 'orders', 'order_items'];
        foreach ($tables as $table) {
            $result = $conn->query("SHOW TABLES LIKE '$table'");
            if ($result && $result->num_rows > 0) {
                echo "<div class=\"result\">✅ Table '$table' exists and is ready.</div>";
            } else {
                echo "<div class=\"result error\">❌ Table '$table' is missing!</div>";
            }
        }

        
        $result = $conn->query("SHOW COLUMNS FROM registration LIKE 'address'");
        if ($result && $result->num_rows > 0) {
            echo '<div class="result">✅ Address column exists in registration table.</div>';
        } else {
            echo '<div class="result error">❌ Address column is missing from registration table!</div>';
        }

        $conn->close();
        ?>

        <h2>🎉 Setup Complete!</h2>
        <p>Your database is now ready for the Meat Bazar order system.</p>
        
        <div style="text-align: center; margin-top: 30px;">
            <a href="home.php" class="btn">🏠 Go to Home</a>
            <a href="beef.php" class="btn">🥩 Start Shopping</a>
            <a href="admin_dashboard.php" class="btn">👨‍💼 Admin Dashboard</a>
        </div>
    </div>
</body>
</html>