<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== "User") {
    header("Location: login.php");
    exit();
}

require_once 'db.php';

if ($conn->connect_error) {
    $dbError = "Connection failed: " . $conn->connect_error;
    $tableExists = false;
    $orders = null;
    $totalOrders = 0;
} else {
    $dbError = null;

    $tableCheck = $conn->query("SHOW TABLES LIKE 'orders'");
    $tableExists = $tableCheck && $tableCheck->num_rows > 0;

    if ($tableExists) {
        $stmt = $conn->prepare("SELECT * FROM orders WHERE customer_name = ? ORDER BY created_at DESC");
        $stmt->bind_param("s", $_SESSION['full_name']);
        $stmt->execute();
        $orders = $stmt->get_result();

        $totalOrdersResult = $conn->query("SELECT COUNT(*) as total FROM orders");
        $totalOrders = $totalOrdersResult ? $totalOrdersResult->fetch_assoc()['total'] : 0;
    } else {
        $orders = null;
        $totalOrders = 0;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Order History - Meat Bazar</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --HEADER-H: 65px;
            --GAP-H: 2px;
            --NAV-H: 45px;
            --SIDEBAR-W: 220px;
        }

        * {
            box-sizing: border-box
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }

        .header {
            background-color: red;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 15px 20px;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
        }

        .header img {
            position: absolute;
            left: 20px;
            height: 50px;
        }

        .header h1 {
            margin: 0;
            font-size: 30px;
            font-weight: bold;
        }

        .header h4 {
            margin: 0;
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
        }

        .gap {
            background-color: white;
            height: 2px;
            position: fixed;
            top: var(--HEADER-H);
            left: 0;
            width: 100%;
            z-index: 999;
        }

        .nav {
            background-color: #FF0000;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 10px 0;
            position: fixed;
            top: calc(var(--HEADER-H) + var(--GAP-H));
            left: 0;
            width: 100%;
            z-index: 998;
        }

        .nav a {
            color: white;
            text-decoration: none;
            margin: 0 25px;
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .nav a:hover {
            text-decoration: underline;
        }

        .cart-link {
            position: relative;
        }

        .cart-count {
            background-color: #fff;
            color: #FF0000;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 12px;
            font-weight: bold;
            margin-left: 5px;
            min-width: 18px;
            text-align: center;
            display: inline-block;
        }

        #check {
            display: none;
        }

        .menu-toggle {
            position: absolute;
            left: 32px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 24px;
            color: white;
            cursor: pointer;
        }

        .container {
            position: relative;
        }

        .sidebar {
            position: fixed;
            top: calc(var(--HEADER-H) + var(--GAP-H) + var(--NAV-H));
            left: calc(-1 * var(--SIDEBAR-W));
            height: calc(100vh - (var(--HEADER-H) + var(--GAP-H) + var(--NAV-H)));
            width: var(--SIDEBAR-W);
            background-color: darkred;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: left .4s ease;
            z-index: 2;
        }

        .sidebar header {
            font-size: 20px;
            text-align: center;
            margin: 0;
            padding: 15px;
            font-weight: bold;
            background: #8B0000;
            position: relative;
        }

        .close-btn {
            position: absolute;
            right: 15px;
            top: 15px;
            font-size: 20px;
            color: white;
            cursor: pointer;
            display: none;
        }

        .menu-links {
            flex-grow: 1;
            overflow-y: auto;
        }

        .sidebar a {
            display: block;
            padding: 15px 25px;
            color: white;
            text-decoration: none;
            font-size: 16px;
        }

        .sidebar a:hover {
            background-color: #FF0000;
        }

        .logout {
            background-color: #B22222;
            text-align: center;
            font-weight: bold;
            padding: 15px 25px;
        }

        .logout:hover {
            background-color: #FF0000;
        }

        .content {
            flex: 1;
            padding: 20px;
            margin-left: 0;
            transition: margin-left .4s ease;
            margin-top: calc(var(--HEADER-H) + var(--GAP-H) + var(--NAV-H));
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
        }

        #check:checked~.container .sidebar {
            left: 0;
        }

        #check:checked~.container .content {
            margin-left: var(--SIDEBAR-W);
        }

        #check:checked~.nav label .menu-toggle {
            display: none;
        }

        #check:checked~.container .sidebar .close-btn {
            display: block;
        }

        .history-container {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        .history-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .history-header h2 {
            color: #333;
            font-size: 2.2em;
            margin: 0;
        }

        .order-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 20px;
            background: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .order-header {
            background: #f8f9fa;
            padding: 20px;
            border-bottom: 1px solid #ddd;
            border-radius: 8px 8px 0 0;
        }

        .order-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .info-item {
            display: flex;
            flex-direction: column;
        }

        .info-label {
            font-size: 0.9em;
            color: #666;
            margin-bottom: 5px;
        }

        .info-value {
            font-weight: bold;
            color: #333;
        }

        .order-status {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.9em;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-confirmed {
            background-color: #d4edda;
            color: #155724;
        }

        .status-processing {
            background-color: #d1ecf1;
            color: #0c5460;
        }

        .status-delivered {
            background-color: #d1f2eb;
            color: #0c5460;
        }

        .status-cancelled {
            background-color: #f8d7da;
            color: #721c24;
        }

        .order-body {
            padding: 20px;
        }

        .order-items {
            margin-bottom: 15px;
        }

        .order-item {
            display: flex;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }

        .order-item:last-child {
            border-bottom: none;
        }

        .item-image {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 6px;
            margin-right: 15px;
        }

        .item-details {
            flex-grow: 1;
        }

        .item-name {
            font-weight: bold;
            color: #333;
            margin-bottom: 3px;
        }

        .item-price {
            color: #666;
            font-size: 0.9em;
        }

        .item-quantity {
            color: #FF0000;
            font-weight: bold;
            margin-left: 15px;
        }

        .order-total {
            text-align: right;
            font-size: 1.2em;
            font-weight: bold;
            color: #FF0000;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 2px solid #eee;
        }

        .empty-history {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-history i {
            font-size: 4em;
            color: #ccc;
            margin-bottom: 20px;
        }

        .empty-history .fa-database {
            color: #dc3545;
        }

        .empty-history h3 {
            color: #666;
            margin-bottom: 15px;
        }

        .empty-history p {
            color: #999;
            margin-bottom: 25px;
        }

        .btn {
            padding: 12px 25px;
            border: none;
            border-radius: 6px;
            font-size: 1.1em;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background-color: #FF0000;
            color: white;
        }

        .btn-primary:hover {
            background-color: #cc0000;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #545b62;
        }

        .back-btn {
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <input type="checkbox" id="check">
    <div class="header">
        <img src="logo.png" alt="Logo">
        <h1>Meat Bazar</h1>
        <h4>Welcome, <?php echo htmlspecialchars($_SESSION['full_name']); ?>!</h4>
    </div>
    <div class="gap"></div>
    <div class="nav">
        <label for="check"><i class="fa fa-bars menu-toggle"></i></label>
        <a href="home.php">Home</a>
        <a href="beef.php">Beef</a>
        <a href="#">Mutton</a>
        <a href="#">Chicken</a>
        <a href="cart.php" class="cart-link">
            <i class="fa fa-shopping-cart"></i> Cart
            <span id="cart-count" class="cart-count">0</span>
        </a>
    </div>
    <div class="container">
        <div class="sidebar">
            <header>
                Side Menu
                <label for="check"><i class="fa fa-times close-btn"></i></label>
            </header>
            <div class="menu-links">
                <a href="profile.php"><i class="fa fa-user"></i> Profile</a>
                <a href="change_user_personal_info.php"><i class="fa fa-edit"></i> Change Personal Info</a>
                <a href="history.php"><i class="fa fa-history"></i> History</a>
                <a href="contact.php"><i class="fa fa-envelope"></i> Contact</a>
            </div>
            <a href="logout.php" class="logout"><i class="fa fa-sign-out-alt"></i> Log Out</a>
        </div>
        <div class="content">
            <button onclick="goBack()" class="btn btn-secondary back-btn">
                <i class="fa fa-arrow-left"></i> Back
            </button>

            <div class="history-container">
                <div class="history-header">
                    <h2><i class="fa fa-history"></i> Order History</h2>
                    <?php if ($dbError): ?>
                        <p style="color: #dc3545; text-align: center;">
                            ❌ Database connection error: <?php echo htmlspecialchars($dbError); ?>
                        </p>
                    <?php elseif (!$tableExists): ?>
                        <p style="color: #dc3545; text-align: center;">
                            ⚠️ Orders table not found. Please run database setup first.
                            <br><a href="setup_database.php" style="color: #007bff;">Click here to setup database</a>
                        </p>
                    <?php elseif ($totalOrders == 0): ?>
                        <p style="color: #6c757d; text-align: center;">
                            📊 Debug: No orders in database yet (Total orders: <?php echo $totalOrders; ?>)
                        </p>
                    <?php else: ?>
                        <p style="color: #28a745; text-align: center;">
                            📊 Debug: Found <?php echo $totalOrders; ?> total orders in database
                            <br>Searching for orders by: <?php echo htmlspecialchars($_SESSION['full_name']); ?>
                        </p>
                    <?php endif; ?>
                </div>

                <?php if ($dbError): ?>
                    <div class="empty-history">
                        <i class="fa fa-exclamation-triangle"></i>
                        <h3>Database Connection Error</h3>
                        <p>Cannot connect to the database. Please check your XAMPP MySQL service.</p>
                        <a href="http://localhost/phpmyadmin" class="btn btn-primary" target="_blank">
                            <i class="fa fa-database"></i> Open phpMyAdmin
                        </a>
                    </div>
                <?php elseif (!$tableExists): ?>
                    <div class="empty-history">
                        <i class="fa fa-database"></i>
                        <h3>Database Setup Required</h3>
                        <p>The orders table doesn't exist yet. Please run the database setup.</p>
                        <a href="setup_database.php" class="btn btn-primary">
                            <i class="fa fa-cogs"></i> Setup Database
                        </a>
                    </div>
                <?php elseif (!$orders || $orders->num_rows === 0): ?>
                    <div class="empty-history">
                        <i class="fa fa-clipboard-list"></i>
                        <h3>No orders yet</h3>
                        <p>You haven't placed any orders. Start shopping to see your order history!</p>
                        <a href="beef.php" class="btn btn-primary">
                            <i class="fa fa-shopping-cart"></i> Start Shopping
                        </a>
                    </div>
                <?php else: ?>
                    <?php while ($order = $orders->fetch_assoc()): ?>
                        <?php $items = json_decode($order['items'], true); ?>
                        <div class="order-card">
                            <div class="order-header">
                                <div class="order-info">
                                    <div class="info-item">
                                        <span class="info-label">Order ID</span>
                                        <span class="info-value">#<?php echo $order['id']; ?></span>
                                    </div>
                                    <div class="info-item">
                                        <span class="info-label">Order Date</span>
                                        <span class="info-value"><?php echo date('M j, Y', strtotime($order['created_at'])); ?></span>
                                    </div>
                                    <div class="info-item">
                                        <span class="info-label">Payment Method</span>
                                        <span class="info-value"><?php echo $order['payment_type'] === 'cod' ? 'Cash on Delivery' : 'Online Payment'; ?></span>
                                    </div>
                                    <div class="info-item">
                                        <span class="info-label">Status</span>
                                        <span class="order-status status-<?php echo $order['order_status']; ?>">
                                            <?php echo ucfirst($order['order_status']); ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="order-body">
                                <div class="order-items">
                                    <?php foreach ($items as $item): ?>
                                        <div class="order-item">
                                            <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="item-image">
                                            <div class="item-details">
                                                <div class="item-name"><?php echo htmlspecialchars($item['name']); ?></div>
                                                <div class="item-price">৳<?php echo number_format($item['price'], 2); ?>/kg</div>
                                            </div>
                                            <div class="item-quantity"><?php echo $item['quantity']; ?> kg</div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <div class="order-total">
                                    Total: ৳<?php echo number_format($order['total_amount'], 2); ?>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        let cart = JSON.parse(localStorage.getItem('meatBazarCart')) || [];

        function updateCartCount() {
            const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
            const cartCountElement = document.getElementById('cart-count');
            if (cartCountElement) {
                cartCountElement.textContent = totalItems;
            }
        }

        function goBack() {
            if (window.history.length > 1) {
                window.history.back();
            } else {
                window.location.href = 'home.php';
            }
        }

        function applyHeights() {
            var header = document.querySelector('.header');
            var gap = document.querySelector('.gap');
            var nav = document.querySelector('.nav');
            if (header) document.documentElement.style.setProperty('--HEADER-H', header.getBoundingClientRect().height + 'px');
            if (gap) document.documentElement.style.setProperty('--GAP-H', gap.getBoundingClientRect().height + 'px');
            if (nav) document.documentElement.style.setProperty('--NAV-H', nav.getBoundingClientRect().height + 'px');
        }

        window.addEventListener('load', function() {
            applyHeights();
            updateCartCount();
        });

        window.addEventListener('resize', applyHeights);
        const ro = new ResizeObserver(applyHeights);
        ro.observe(document.body);
    </script>
</body>

</html>