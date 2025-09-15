<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== "User") {
    header("Location: login.php");
    exit();
}

$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

if ($order_id <= 0) {
    header("Location: home.php");
    exit();
}

require_once 'db.php';

// Get order details
$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ? AND customer_name = ?");
$stmt->bind_param("is", $order_id, $_SESSION['full_name']);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();

if (!$order) {
    header("Location: home.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Order Confirmation - Meat Bazar</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --HEADER-H: 65px;
            --GAP-H: 2px;
            --NAV-H: 45px;
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

        .content {
            margin-top: calc(var(--HEADER-H) + var(--GAP-H) + var(--NAV-H) + 40px);
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
            padding: 20px;
        }

        .success-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            overflow: hidden;
            text-align: center;
        }

        .success-header {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 40px 20px;
        }

        .success-header i {
            font-size: 4em;
            margin-bottom: 20px;
        }

        .success-header h2 {
            margin: 0;
            font-size: 2.2em;
        }

        .success-header p {
            margin: 10px 0 0 0;
            font-size: 1.1em;
            opacity: 0.9;
        }

        .success-body {
            padding: 40px 30px;
        }

        .order-details {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 25px;
            margin: 20px 0;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #dee2e6;
        }

        .detail-row:last-child {
            border-bottom: none;
            font-weight: bold;
            font-size: 1.2em;
            color: #FF0000;
        }

        .detail-row strong {
            color: #333;
        }

        .action-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
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

        .btn-success {
            background-color: #28a745;
            color: white;
        }

        .btn-success:hover {
            background-color: #218838;
        }

        .info-box {
            background: #d1ecf1;
            border: 1px solid #bee5eb;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }

        .info-box i {
            color: #0c5460;
            margin-right: 10px;
        }
    </style>
</head>

<body>
    <div class="header">
        <img src="logo.png" alt="Logo">
        <h1>Meat Bazar</h1>
        <h4>Welcome, <?php echo htmlspecialchars($_SESSION['full_name']); ?>!</h4>
    </div>
    <div class="gap"></div>
    <div class="nav">
        <a href="home.php">Home</a>
        <a href="beef.php">Beef</a>
        <a href="mutton.php">Mutton</a>
        <a href="chicken.php">Chicken</a>
        <a href="cart.php">
            <i class="fa fa-shopping-cart"></i> Cart
        </a>
    </div>
    
    <div class="content">
        <div class="success-container">
            <div class="success-header">
                <i class="fa fa-check-circle"></i>
                <h2>Order Confirmed!</h2>
                <p>Thank you for your order. We'll prepare it for you soon.</p>
            </div>
            
            <div class="success-body">
                <div class="order-details">
                    <h3 style="margin-top: 0; color: #333; text-align: left;">Order Details</h3>
                    <div class="detail-row">
                        <span><strong>Order ID:</strong></span>
                        <span>#<?php echo $order['id']; ?></span>
                    </div>
                    <div class="detail-row">
                        <span><strong>Order Date:</strong></span>
                        <span><?php echo date('F j, Y g:i A', strtotime($order['created_at'])); ?></span>
                    </div>
                    <div class="detail-row">
                        <span><strong>Payment Method:</strong></span>
                        <span><?php echo $order['payment_type'] === 'cod' ? 'Cash on Delivery' : 'Online Payment'; ?></span>
                    </div>
                    <div class="detail-row">
                        <span><strong>Delivery Address:</strong></span>
                        <span><?php echo htmlspecialchars($order['customer_address']); ?></span>
                    </div>
                    <div class="detail-row">
                        <span><strong>Total Amount:</strong></span>
                        <span>৳<?php echo number_format($order['total_amount'], 2); ?></span>
                    </div>
                </div>

                <?php if ($order['payment_type'] === 'cod'): ?>
                <div class="info-box">
                    <i class="fa fa-truck"></i>
                    <strong>Cash on Delivery:</strong> Please have the exact amount ready when our delivery person arrives.
                </div>
                <?php else: ?>
                <div class="info-box">
                    <i class="fa fa-credit-card"></i>
                    <strong>Payment Successful:</strong> Your payment has been processed successfully.
                </div>
                <?php endif; ?>

                <div class="action-buttons">
                    <a href="home.php" class="btn btn-secondary">
                        <i class="fa fa-home"></i> Home
                    </a>
                    <a href="beef.php" class="btn btn-primary">
                        <i class="fa fa-shopping-bag"></i> Continue Shopping
                    </a>
                    <a href="history.php" class="btn btn-success">
                        <i class="fa fa-history"></i> Order History
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>