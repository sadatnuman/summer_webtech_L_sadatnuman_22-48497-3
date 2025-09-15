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
    <title>Shopping Cart - Meat Bazar</title>
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

        .cart-container {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        .cart-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .cart-header h2 {
            color: #333;
            font-size: 2.2em;
            margin: 0;
        }

        .cart-items {
            margin-bottom: 30px;
        }

        .cart-item {
            display: flex;
            align-items: center;
            padding: 20px;
            border-bottom: 1px solid #eee;
            background: #fafafa;
            margin-bottom: 15px;
            border-radius: 8px;
        }

        .cart-item img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            margin-right: 20px;
        }

        .item-details {
            flex-grow: 1;
        }

        .item-name {
            font-size: 1.2em;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }

        .item-price {
            color: #e41e31;
            font-weight: bold;
            font-size: 1.1em;
        }

        .quantity-controls {
            display: flex;
            align-items: center;
            margin: 0 20px;
        }

        .quantity-btn {
            background-color: #FF0000;
            color: white;
            border: none;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .quantity-btn:hover {
            background-color: #cc0000;
        }

        .quantity {
            margin: 0 15px;
            font-size: 1.2em;
            font-weight: bold;
            min-width: 30px;
            text-align: center;
        }

        .item-total {
            font-size: 1.3em;
            font-weight: bold;
            color: #e41e31;
            min-width: 100px;
            text-align: right;
        }

        .remove-btn {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
            margin-left: 15px;
        }

        .remove-btn:hover {
            background-color: #c82333;
        }

        .cart-summary {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 8px;
            margin-top: 30px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            font-size: 1.1em;
        }

        .total-row {
            border-top: 2px solid #ddd;
            padding-top: 15px;
            font-size: 1.4em;
            font-weight: bold;
            color: #e41e31;
        }

        .action-buttons {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            justify-content: center;
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

        .empty-cart {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-cart i {
            font-size: 4em;
            color: #ccc;
            margin-bottom: 20px;
        }

        .empty-cart h3 {
            color: #666;
            margin-bottom: 15px;
        }

        .empty-cart p {
            color: #999;
            margin-bottom: 25px;
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
        <a href="mutton.php">Mutton</a>
        <a href="chicken.php">Chicken</a>
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
                <a href="#"><i class="fa fa-history"></i> History</a>
                <a href="contact.php"><i class="fa fa-envelope"></i> Contact</a>
            </div>
            <a href="logout.php" class="logout"><i class="fa fa-sign-out-alt"></i> Log Out</a>
        </div>
        <div class="content">
            <div class="cart-container">
                <div class="cart-header">
                    <h2><i class="fa fa-shopping-cart"></i> Your Shopping Cart</h2>
                </div>

                <div id="cart-content">

                </div>

                <div class="action-buttons">
                    <a href="#" onclick="goBackToPreviousPage()" class="btn btn-secondary">
                        <i class="fa fa-arrow-left"></i> Continue Shopping
                    </a>
                    <button id="checkout-btn" class="btn btn-primary" style="display: none;">
                        <i class="fa fa-credit-card"></i> Proceed to Checkout
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let cart = JSON.parse(localStorage.getItem('meatBazarCart')) || [];

        cart.forEach(item => {
            if (!item.total) {
                item.total = item.quantity * item.price;
            }
        });

        if (cart.length > 0) {
            localStorage.setItem('meatBazarCart', JSON.stringify(cart));
        }

        function updateCartCount() {
            const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
            const cartCountElement = document.getElementById('cart-count');
            if (cartCountElement) {
                cartCountElement.textContent = totalItems;
            }
        }

        function updateQuantity(productName, change) {
            const item = cart.find(item => item.name === productName);
            if (item) {
                item.quantity += change;
                if (item.quantity <= 0) {
                    removeFromCart(productName);
                } else {
                    item.total = item.quantity * item.price;
                    localStorage.setItem('meatBazarCart', JSON.stringify(cart));
                    displayCart();
                    updateCartCount();
                }
            }
        }

        function removeFromCart(productName) {
            cart = cart.filter(item => item.name !== productName);
            localStorage.setItem('meatBazarCart', JSON.stringify(cart));
            displayCart();
            updateCartCount();
        }

        function clearCart() {
            if (confirm('Are you sure you want to clear your cart?')) {
                cart = [];
                localStorage.setItem('meatBazarCart', JSON.stringify(cart));
                displayCart();
                updateCartCount();
            }
        }

        function displayCart() {
            const cartContent = document.getElementById('cart-content');
            const checkoutBtn = document.getElementById('checkout-btn');

            if (cart.length === 0) {
                cartContent.innerHTML = `
                    <div class="empty-cart">
                        <i class="fa fa-shopping-cart"></i>
                        <h3>Your cart is empty</h3>
                        <p>Add some delicious products to your cart!</p>
                        <a href="#" onclick="goBackToPreviousPage()" class="btn btn-primary">
                            <i class="fa fa-plus"></i> Start Shopping
                        </a>
                    </div>
                `;
                checkoutBtn.style.display = 'none';
                return;
            }

            let totalAmount = 0;
            let itemsHtml = '<div class="cart-items">';

            cart.forEach(item => {
                if (!item.total) {
                    item.total = item.quantity * item.price;
                }
                totalAmount += item.total;
                itemsHtml += `
                    <div class="cart-item">
                        <img src="${item.image}" alt="${item.name}">
                        <div class="item-details">
                            <div class="item-name">${item.name}</div>
                            <div class="item-price">৳${item.price}/kg</div>
                        </div>
                        <div class="quantity-controls">
                            <button class="quantity-btn" onclick="updateQuantity('${item.name}', -1)">
                                <i class="fa fa-minus"></i>
                            </button>
                            <span class="quantity">${item.quantity}</span>
                            <button class="quantity-btn" onclick="updateQuantity('${item.name}', 1)">
                                <i class="fa fa-plus"></i>
                            </button>
                        </div>
                        <div class="item-total">৳${item.total}</div>
                        <button class="remove-btn" onclick="removeFromCart('${item.name}')">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                `;
            });

            itemsHtml += '</div>';

            const summaryHtml = `
                <div class="cart-summary">
                    <div class="summary-row">
                        <span>Subtotal:</span>
                        <span>৳${totalAmount}</span>
                    </div>
                    <div class="summary-row">
                        <span>Delivery Fee:</span>
                        <span>৳50</span>
                    </div>
                    <div class="summary-row total-row">
                        <span>Total:</span>
                        <span>৳${totalAmount + 50}</span>
                    </div>
                    <div style="text-align: center; margin-top: 20px;">
                        <button onclick="clearCart()" class="btn btn-secondary">
                            <i class="fa fa-trash"></i> Clear Cart
                        </button>
                    </div>
                </div>
            `;

            cartContent.innerHTML = itemsHtml + summaryHtml;
            checkoutBtn.style.display = 'inline-block';
        }

        function applyHeights() {
            var header = document.querySelector('.header');
            var gap = document.querySelector('.gap');
            var nav = document.querySelector('.nav');
            if (header) document.documentElement.style.setProperty('--HEADER-H', header.getBoundingClientRect().height + 'px');
            if (gap) document.documentElement.style.setProperty('--GAP-H', gap.getBoundingClientRect().height + 'px');
            if (nav) document.documentElement.style.setProperty('--NAV-H', nav.getBoundingClientRect().height + 'px');
        }

        function goBackToPreviousPage() {
            if (window.history.length > 1) {
                window.history.back();
            } else {
                window.location.href = 'home.php';
            }
        }

        window.addEventListener('load', function() {
            applyHeights();
            displayCart();
            updateCartCount();

            if (window.history.replaceState) {
                window.history.replaceState({
                    page: 'cart'
                }, 'Cart - Meat Bazar', 'cart.php');
            }
        });

        window.addEventListener('resize', applyHeights);
        const ro = new ResizeObserver(applyHeights);
        ro.observe(document.body);

        document.getElementById('checkout-btn').addEventListener('click', function() {
            if (cart.length === 0) {
                alert('Your cart is empty!');
                return;
            }
            window.location.href = 'payment.php';
        });
    </script>
</body>

</html>