<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== "User") {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Payment - Meat Bazar</title>
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

        .content {
            margin-top: calc(var(--HEADER-H) + var(--GAP-H) + var(--NAV-H) + 20px);
            max-width: 1000px;
            margin-left: auto;
            margin-right: auto;
            padding: 20px;
        }

        .payment-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .payment-header {
            background: linear-gradient(135deg, #FF0000, #cc0000);
            color: white;
            padding: 25px;
            text-align: center;
        }

        .payment-header h2 {
            margin: 0;
            font-size: 2.2em;
        }

        .payment-body {
            padding: 30px;
        }

        .section {
            margin-bottom: 30px;
            padding: 25px;
            border: 1px solid #eee;
            border-radius: 8px;
        }

        .section h3 {
            margin-top: 0;
            color: #333;
            border-bottom: 2px solid #FF0000;
            padding-bottom: 10px;
        }

        .order-summary {
            background: #f8f9fa;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
        }

        .summary-item:last-child {
            border-bottom: none;
            font-weight: bold;
            font-size: 1.2em;
            color: #FF0000;
        }

        .payment-options {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .payment-option {
            border: 2px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .payment-option:hover {
            border-color: #FF0000;
            background: #fff5f5;
        }

        .payment-option.selected {
            border-color: #FF0000;
            background: #fff5f5;
        }

        .payment-option i {
            font-size: 2.5em;
            margin-bottom: 15px;
            color: #FF0000;
        }

        .payment-option h4 {
            margin: 0 0 10px 0;
            color: #333;
        }

        .payment-option p {
            margin: 0;
            color: #666;
            font-size: 0.9em;
        }

        .online-payment-form {
            display: none;
            margin-top: 20px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 1em;
        }

        .form-row {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 15px;
        }

        .customer-info {
            background: #fff;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .confirm-btn {
            width: 100%;
            padding: 15px;
            background-color: #FF0000;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.2em;
            font-weight: bold;
            cursor: pointer;
            margin-top: 20px;
            transition: background-color 0.3s ease;
        }

        .confirm-btn:hover {
            background-color: #cc0000;
        }

        .confirm-btn:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }

        .back-btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #6c757d;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .back-btn:hover {
            background-color: #545b62;
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
        <a href="cart.php" class="cart-link">
            <i class="fa fa-shopping-cart"></i> Cart 
            <span id="cart-count" class="cart-count">0</span>
        </a>
    </div>
    
    <div class="content">
        <a href="cart.php" class="back-btn">
            <i class="fa fa-arrow-left"></i> Back to Cart
        </a>
        
        <div class="payment-container">
            <div class="payment-header">
                <h2><i class="fa fa-credit-card"></i> Complete Your Order</h2>
            </div>
            
            <div class="payment-body">
                <!-- Customer Information -->
                <div class="section customer-info">
                    <h3><i class="fa fa-user"></i> Customer Information</h3>
                    <div class="info-item">
                        <span><strong>Name:</strong></span>
                        <span><?php echo htmlspecialchars($_SESSION['full_name']); ?></span>
                    </div>
                    <div class="info-item">
                        <span><strong>Phone:</strong></span>
                        <span><?php echo htmlspecialchars($_SESSION['phone_number'] ?? 'Not provided'); ?></span>
                    </div>
                    <div class="info-item">
                        <span><strong>Address:</strong></span>
                        <span><?php echo htmlspecialchars($_SESSION['address'] ?? 'Please provide delivery address'); ?></span>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="section order-summary">
                    <h3><i class="fa fa-list"></i> Order Summary</h3>
                    <div id="order-items">
                        <!-- Order items will be loaded here -->
                    </div>
                </div>

                <!-- Payment Options -->
                <div class="section">
                    <h3><i class="fa fa-payment"></i> Payment Method</h3>
                    <div class="payment-options">
                        <div class="payment-option" onclick="selectPaymentMethod('cod')">
                            <i class="fa fa-truck"></i>
                            <h4>Cash on Delivery</h4>
                            <p>Pay when you receive your order</p>
                        </div>
                        <div class="payment-option" onclick="selectPaymentMethod('online')">
                            <i class="fa fa-credit-card"></i>
                            <h4>Pay Online</h4>
                            <p>Credit/Debit Card, Mobile Banking</p>
                        </div>
                    </div>

                    <!-- Online Payment Form -->
                    <div id="online-payment-form" class="online-payment-form">
                        <h4><i class="fa fa-lock"></i> Secure Payment</h4>
                        <div class="form-group">
                            <label>Payment Method:</label>
                            <select id="online-method">
                                <option value="">Choose Payment Method</option>
                                <option value="card">Credit/Debit Card</option>
                                <option value="bkash">bKash</option>
                                <option value="nagad">Nagad</option>
                                <option value="rocket">Rocket</option>
                            </select>
                        </div>
                        <div id="card-form" style="display: none;">
                            <div class="form-group">
                                <label>Card Number:</label>
                                <input type="text" id="card-number" placeholder="1234 5678 9012 3456" maxlength="19">
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Expiry Date:</label>
                                    <input type="text" id="expiry" placeholder="MM/YY" maxlength="5">
                                </div>
                                <div class="form-group">
                                    <label>CVV:</label>
                                    <input type="text" id="cvv" placeholder="123" maxlength="3">
                                </div>
                            </div>
                        </div>
                        <div id="mobile-form" style="display: none;">
                            <div class="form-group">
                                <label>Mobile Number:</label>
                                <input type="text" id="mobile-number" placeholder="01XXXXXXXXX">
                            </div>
                            <div class="form-group">
                                <label>PIN:</label>
                                <input type="password" id="mobile-pin" placeholder="Enter your PIN">
                            </div>
                        </div>
                    </div>
                </div>

                <button id="confirm-order-btn" class="confirm-btn" disabled onclick="confirmOrder()">
                    <i class="fa fa-check"></i> Confirm Order
                </button>
            </div>
        </div>
    </div>

    <script>
        let cart = JSON.parse(localStorage.getItem('meatBazarCart')) || [];
        let selectedPaymentMethod = '';
        let totalAmount = 0;

        function updateCartCount() {
            const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
            const cartCountElement = document.getElementById('cart-count');
            if (cartCountElement) {
                cartCountElement.textContent = totalItems;
            }
        }

        function displayOrderSummary() {
            const orderItems = document.getElementById('order-items');
            
            if (cart.length === 0) {
                window.location.href = 'cart.php';
                return;
            }

            let itemsHtml = '';
            totalAmount = 0;
            
            cart.forEach(item => {
                totalAmount += item.total;
                itemsHtml += `
                    <div class="summary-item">
                        <span>${item.name} (${item.quantity} kg)</span>
                        <span>৳${item.total}</span>
                    </div>
                `;
            });
            
            const deliveryFee = 50;
            const grandTotal = totalAmount + deliveryFee;
            
            itemsHtml += `
                <div class="summary-item">
                    <span>Subtotal:</span>
                    <span>৳${totalAmount}</span>
                </div>
                <div class="summary-item">
                    <span>Delivery Fee:</span>
                    <span>৳${deliveryFee}</span>
                </div>
                <div class="summary-item">
                    <span>Total Amount:</span>
                    <span>৳${grandTotal}</span>
                </div>
            `;
            
            orderItems.innerHTML = itemsHtml;
        }

        function selectPaymentMethod(method) {
            selectedPaymentMethod = method;
            
            // Remove selected class from all options
            document.querySelectorAll('.payment-option').forEach(option => {
                option.classList.remove('selected');
            });
            
            // Add selected class to clicked option
            event.currentTarget.classList.add('selected');
            
            // Show/hide online payment form
            const onlineForm = document.getElementById('online-payment-form');
            if (method === 'online') {
                onlineForm.style.display = 'block';
            } else {
                onlineForm.style.display = 'none';
            }
            
            // Enable confirm button
            document.getElementById('confirm-order-btn').disabled = false;
        }

        // Handle online payment method selection
        document.getElementById('online-method').addEventListener('change', function() {
            const method = this.value;
            const cardForm = document.getElementById('card-form');
            const mobileForm = document.getElementById('mobile-form');
            
            if (method === 'card') {
                cardForm.style.display = 'block';
                mobileForm.style.display = 'none';
            } else if (['bkash', 'nagad', 'rocket'].includes(method)) {
                cardForm.style.display = 'none';
                mobileForm.style.display = 'block';
            } else {
                cardForm.style.display = 'none';
                mobileForm.style.display = 'none';
            }
        });

        // Format card number input
        document.getElementById('card-number').addEventListener('input', function() {
            let value = this.value.replace(/\s/g, '').replace(/[^0-9]/gi, '');
            let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
            this.value = formattedValue;
        });

        // Format expiry date input
        document.getElementById('expiry').addEventListener('input', function() {
            let value = this.value.replace(/\D/g, '');
            if (value.length >= 2) {
                value = value.substring(0, 2) + '/' + value.substring(2, 4);
            }
            this.value = value;
        });

        function confirmOrder() {
            if (!selectedPaymentMethod) {
                alert('Please select a payment method');
                return;
            }

            if (selectedPaymentMethod === 'online') {
                const onlineMethod = document.getElementById('online-method').value;
                if (!onlineMethod) {
                    alert('Please select an online payment method');
                    return;
                }
                
                // Process online payment
                processOnlinePayment(onlineMethod);
            } else {
                // Process cash on delivery
                processCashOnDelivery();
            }
        }

        function processOnlinePayment(method) {
            // Simulate payment processing
            document.getElementById('confirm-order-btn').disabled = true;
            document.getElementById('confirm-order-btn').innerHTML = '<i class="fa fa-spinner fa-spin"></i> Processing Payment...';
            
            setTimeout(() => {
                alert('Payment successful! Processing your order...');
                submitOrder('online', method);
            }, 2000);
        }

        function processCashOnDelivery() {
            submitOrder('cod', 'cash_on_delivery');
        }

        function submitOrder(paymentType, paymentMethod) {
            const orderData = {
                customer_name: '<?php echo htmlspecialchars($_SESSION['full_name']); ?>',
                customer_phone: '<?php echo htmlspecialchars($_SESSION['phone_number'] ?? ''); ?>',
                customer_address: '<?php echo htmlspecialchars($_SESSION['address'] ?? 'Address to be provided'); ?>',
                items: cart,
                subtotal: totalAmount,
                delivery_fee: 50,
                total_amount: totalAmount + 50,
                payment_type: paymentType,
                payment_method: paymentMethod,
                order_status: 'pending'
            };

            // Send order to server
            fetch('process_order.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(orderData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Clear cart
                    localStorage.removeItem('meatBazarCart');
                    
                    // Redirect to success page
                    window.location.href = `order_success.php?order_id=${data.order_id}`;
                } else {
                    alert('Error placing order: ' + data.message);
                    document.getElementById('confirm-order-btn').disabled = false;
                    document.getElementById('confirm-order-btn').innerHTML = '<i class="fa fa-check"></i> Confirm Order';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error placing order. Please try again.');
                document.getElementById('confirm-order-btn').disabled = false;
                document.getElementById('confirm-order-btn').innerHTML = '<i class="fa fa-check"></i> Confirm Order';
            });
        }

        // Initialize page
        window.addEventListener('load', function() {
            updateCartCount();
            displayOrderSummary();
        });
    </script>
</body>

</html>