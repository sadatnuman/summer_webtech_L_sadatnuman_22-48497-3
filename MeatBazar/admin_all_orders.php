<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== "Admin") {
    header("Location: login.php");
    exit();
}

include 'db.php';

$orders = [];
$error_message = null;

try {
    $sql = "SELECT * FROM orders ORDER BY created_at DESC";

    $result = $conn->query($sql);

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }
    } else {
        $error_message = "Error fetching orders: " . $conn->error;
    }
} catch (Exception $e) {
    $error_message = "Error fetching orders: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>All Orders - Meat Bazar Admin</title>
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
            background: #fff;
        }

        .header {
            background: red;
            color: #fff;
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
            background: #fff;
            height: 2px;
            position: fixed;
            top: var(--HEADER-H);
            left: 0;
            width: 100%;
            z-index: 999;
        }

        .nav {
            background: #FF0000;
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
            color: #fff;
            text-decoration: none;
            margin: 0 25px;
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .nav a:hover {
            text-decoration: underline
        }

        .nav a.active {
            background-color: rgba(255, 255, 255, 0.2);
            padding: 8px 16px;
            border-radius: 4px;
        }

        #check {
            display: none
        }

        .menu-toggle {
            position: absolute;
            left: 32px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 24px;
            color: #fff;
            cursor: pointer;
        }

        .container {
            position: relative
        }

        .sidebar {
            position: fixed;
            top: calc(var(--HEADER-H) + var(--GAP-H) + var(--NAV-H));
            left: calc(-1 * var(--SIDEBAR-W));
            height: calc(100vh - (var(--HEADER-H) + var(--GAP-H) + var(--NAV-H)));
            width: var(--SIDEBAR-W);
            background: darkred;
            color: #fff;
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
            color: #fff;
            cursor: pointer;
            display: none;
        }

        .menu-links {
            flex-grow: 1;
            overflow-y: auto
        }

        .sidebar a {
            display: block;
            padding: 15px 25px;
            color: #fff;
            text-decoration: none;
            font-size: 16px;
        }

        .sidebar a:hover {
            background: #FF0000
        }

        .logout {
            background: #B22222;
            text-align: center;
            font-weight: bold;
            padding: 15px 25px;
        }

        .logout:hover {
            background: #FF0000
        }

        .content {
            padding: 20px;
            transition: margin-left .4s ease;
            margin-left: 0;
            margin-top: calc(var(--HEADER-H) + var(--GAP-H) + var(--NAV-H));
        }

        #check:checked~.container .sidebar {
            left: 0
        }

        #check:checked~.container .content {
            margin-left: var(--SIDEBAR-W)
        }

        #check:checked~.nav label .menu-toggle {
            display: none
        }

        #check:checked~.container .sidebar .close-btn {
            display: block
        }

        .page-title {
            color: #333;
            font-size: 2.2em;
            text-align: center;
            margin-bottom: 30px;
        }

        .orders-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .orders-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .stat-card h3 {
            margin: 0 0 10px 0;
            color: #333;
            font-size: 1.1em;
        }

        .stat-card .number {
            font-size: 2em;
            font-weight: bold;
            color: #FF0000;
            margin: 0;
        }

        .orders-table {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-top: 20px;
        }

        .table-header {
            background: #FF0000;
            color: white;
            padding: 15px 20px;
            font-size: 1.2em;
            font-weight: bold;
        }

        .orders-list {
            max-height: 600px;
            overflow-y: auto;
        }

        .order-item {
            border-bottom: 1px solid #e0e0e0;
            padding: 20px;
            transition: background-color 0.3s ease;
        }

        .order-item:hover {
            background-color: #f9f9f9;
        }

        .order-item:last-child {
            border-bottom: none;
        }

        .order-header {
            display: flex;
            justify-content: between;
            align-items: center;
            margin-bottom: 15px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .order-id {
            font-weight: bold;
            color: #FF0000;
            font-size: 1.1em;
        }

        .order-date {
            color: #666;
            font-size: 0.9em;
        }

        .order-status {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8em;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-processing {
            background-color: #d1ecf1;
            color: #0c5460;
        }

        .status-confirmed {
            background-color: #d1ecf1;
            color: #0c5460;
        }

        .status-delivered {
            background-color: #d4edda;
            color: #155724;
        }

        .status-cancelled {
            background-color: #f8d7da;
            color: #721c24;
        }

        .order-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-top: 15px;
        }

        .customer-info {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
        }

        .customer-info h4 {
            margin: 0 0 10px 0;
            color: #333;
            font-size: 1em;
        }

        .customer-info p {
            margin: 5px 0;
            color: #666;
            font-size: 0.9em;
        }

        .order-items {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
        }

        .order-items h4 {
            margin: 0 0 10px 0;
            color: #333;
            font-size: 1em;
        }

        .item-list {
            max-height: 150px;
            overflow-y: auto;
        }

        .item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 5px 0;
            border-bottom: 1px solid #dee2e6;
        }

        .item:last-child {
            border-bottom: none;
        }

        .item-name {
            font-weight: 500;
            color: #333;
        }

        .item-details {
            color: #666;
            font-size: 0.9em;
        }

        .order-total {
            text-align: right;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 2px solid #FF0000;
        }

        .total-amount {
            font-size: 1.3em;
            font-weight: bold;
            color: #FF0000;
        }

        .order-actions {
            margin-top: 15px;
            text-align: right;
        }

        .action-btn {
            background: #FF0000;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            margin-left: 10px;
            font-size: 0.9em;
        }

        .action-btn:hover {
            background: #cc0000;
        }

        .action-btn.secondary {
            background: #6c757d;
        }

        .action-btn.secondary:hover {
            background: #545b62;
        }

        .no-orders {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }

        .no-orders i {
            font-size: 4em;
            color: #ccc;
            margin-bottom: 20px;
        }

        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
        }

        @media (max-width: 768px) {
            .order-details {
                grid-template-columns: 1fr;
            }

            .order-header {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>

<body>
    <input type="checkbox" id="check">
    <div class="header">
        <img src="logo.png" alt="Logo">
        <h1>Meat Bazar - Admin Panel</h1>
        <h4>Welcome Admin, <?php echo htmlspecialchars($_SESSION['full_name']); ?>!</h4>
    </div>
    <div class="gap"></div>
    <div class="nav">
        <label for="check"><i class="fa fa-bars menu-toggle"></i></label>
        <a href="admin_dashboard.php">Home</a>
        <a href="admin_all_orders.php" class="active">All Orders</a>
        <a href="assign_orders.php">Assign Orders</a>
        <a href="inventory.php">Inventory</a>
    </div>
    <div class="container">
        <div class="sidebar">
            <header>
                Side Menu
                <label for="check"><i class="fa fa-times close-btn"></i></label>
            </header>
            <div class="menu-links">
                <a href="admin_profile.php"><i class="fa fa-user-shield"></i> Admin Profile</a>
                <a href="change_admin_personal_info.php"><i class="fa fa-edit"></i> Change Personal Info</a>
                <a href="manage_admin.php"><i class="fa fa-user-shield"></i> Manage Admin</a>
                <a href="manage_distributor.php"><i class="fa fa-user-tie"></i> Manage Distributor</a>
                <a href="manage_user.php"><i class="fa fa-users"></i> Manage Users</a>

            </div>
            <a href="logout.php" class="logout"><i class="fa fa-sign-out-alt"></i> Log Out</a>
        </div>
        <div class="content">
            <div class="orders-container">
                <h2 class="page-title">Admin - All Orders Management</h2>

                <?php if (isset($error_message)): ?>
                    <div class="error-message">
                        <i class="fa fa-exclamation-triangle"></i>
                        <?php echo htmlspecialchars($error_message); ?>
                    </div>
                <?php endif; ?>

                <div class="orders-stats">
                    <div class="stat-card">
                        <h3>Total Orders</h3>
                        <p class="number"><?php echo count($orders); ?></p>
                    </div>
                    <div class="stat-card">
                        <h3>Pending Orders</h3>
                        <p class="number"><?php echo count(array_filter($orders, function ($order) {
                                                return $order['order_status'] === 'pending';
                                            })); ?></p>
                    </div>
                    <div class="stat-card">
                        <h3>Processing Orders</h3>
                        <p class="number"><?php echo count(array_filter($orders, function ($order) {
                                                return $order['order_status'] === 'processing';
                                            })); ?></p>
                    </div>
                    <div class="stat-card">
                        <h3>Delivered Orders</h3>
                        <p class="number"><?php echo count(array_filter($orders, function ($order) {
                                                return $order['order_status'] === 'delivered';
                                            })); ?></p>
                    </div>
                    <div class="stat-card">
                        <h3>Total Revenue</h3>
                        <p class="number">৳<?php echo number_format(array_sum(array_column($orders, 'total_amount')), 2); ?></p>
                    </div>
                </div>

                <div class="orders-table">
                    <div class="table-header">
                        <i class="fa fa-shopping-cart"></i> Orders Management
                    </div>
                    <div class="orders-list">
                        <?php if (empty($orders)): ?>
                            <div class="no-orders">
                                <i class="fa fa-shopping-cart"></i>
                                <h3>No Orders Found</h3>
                                <p>There are currently no orders in the system.</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($orders as $order): ?>
                                <div class="order-item">
                                    <div class="order-header">
                                        <div>
                                            <span class="order-id">Order #<?php echo htmlspecialchars($order['id']); ?></span>
                                            <span class="order-date"><?php echo date('M d, Y - H:i', strtotime($order['created_at'])); ?></span>
                                        </div>
                                        <span class="order-status status-<?php echo htmlspecialchars($order['order_status']); ?>">
                                            <?php echo htmlspecialchars(ucfirst($order['order_status'])); ?>
                                        </span>
                                    </div>

                                    <div class="order-details">
                                        <div class="customer-info">
                                            <h4><i class="fa fa-user"></i> Customer Information</h4>
                                            <p><strong>Name:</strong> <?php echo htmlspecialchars($order['customer_name']); ?></p>
                                            <p><strong>Phone:</strong> <?php echo htmlspecialchars($order['customer_phone']); ?></p>
                                            <p><strong>Address:</strong> <?php echo htmlspecialchars($order['customer_address']); ?></p>
                                            <p><strong>Payment:</strong> <?php echo htmlspecialchars(ucfirst($order['payment_type']) . ' - ' . $order['payment_method']); ?></p>
                                        </div>

                                        <div class="order-items">
                                            <h4><i class="fa fa-list"></i> Order Items</h4>
                                            <div class="item-list">
                                                <?php
                                                $order_items = json_decode($order['items'], true);

                                                if ($order_items && is_array($order_items)):
                                                ?>
                                                    <?php foreach ($order_items as $item): ?>
                                                        <div class="item">
                                                            <div class="item-name"><?php echo htmlspecialchars($item['name'] ?? 'Unknown Item'); ?></div>
                                                            <div class="item-details">
                                                                Qty: <?php echo htmlspecialchars($item['quantity'] ?? '0'); ?> |
                                                                ৳<?php echo htmlspecialchars($item['price'] ?? '0'); ?>/kg |
                                                                Total: ৳<?php echo htmlspecialchars($item['total'] ?? '0'); ?>
                                                            </div>
                                                        </div>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <p>Items data: <?php echo htmlspecialchars($order['items']); ?></p>
                                                    <p>JSON Error: <?php echo json_last_error_msg(); ?></p>
                                                    <p>No items found or invalid JSON format</p>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="order-total">
                                        <span class="total-amount">Total: ৳<?php echo htmlspecialchars($order['total_amount']); ?></span>
                                    </div>

                                    <div class="order-actions">
                                        <?php if ($order['order_status'] === 'pending'): ?>
                                            <button class="action-btn" onclick="updateOrderStatus(<?php echo $order['id']; ?>, 'confirmed')">Confirm Order</button>
                                            <button class="action-btn secondary" onclick="updateOrderStatus(<?php echo $order['id']; ?>, 'cancelled')">Cancel Order</button>
                                        <?php elseif ($order['order_status'] === 'confirmed'): ?>
                                            <button class="action-btn" onclick="updateOrderStatus(<?php echo $order['id']; ?>, 'processing')">Start Processing</button>
                                            <button class="action-btn secondary" onclick="updateOrderStatus(<?php echo $order['id']; ?>, 'cancelled')">Cancel Order</button>
                                        <?php elseif ($order['order_status'] === 'processing'): ?>
                                            <button class="action-btn" onclick="updateOrderStatus(<?php echo $order['id']; ?>, 'delivered')">Mark as Delivered</button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function applyHeights() {
            var header = document.querySelector('.header');
            var gap = document.querySelector('.gap');
            var nav = document.querySelector('.nav');
            if (header) {
                document.documentElement.style.setProperty('--HEADER-H', header.getBoundingClientRect().height + 'px');
            }
            if (gap) {
                document.documentElement.style.setProperty('--GAP-H', gap.getBoundingClientRect().height + 'px');
            }
            if (nav) {
                document.documentElement.style.setProperty('--NAV-H', nav.getBoundingClientRect().height + 'px');
            }
        }
        window.addEventListener('load', applyHeights);
        window.addEventListener('resize', applyHeights);
        const ro = new ResizeObserver(applyHeights);
        ro.observe(document.body);

        function updateOrderStatus(orderId, newStatus) {
            if (confirm('Are you sure you want to update this order status to "' + newStatus + '"?')) {
                fetch('update_order_status.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            order_id: orderId,
                            status: newStatus
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert('Error updating order status: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error updating order status');
                    });
            }
        }
    </script>
</body>

</html>