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
    <title>Contact - Meat Bazar</title>
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
            background-color: white;
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
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 70vh;
        }

        .contact-card {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
            padding: 40px 30px;
            text-align: center;
            max-width: 400px;
            width: 100%;
        }

        .contact-logo {
            display: block;
            margin: 0 auto 20px auto;
            height: 80px;
        }

        .contact-title {
            font-size: 2em;
            color: #e41e31;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .contact-admin {
            font-size: 1.1em;
            color: #333;
            margin-bottom: 10px;
        }

        .contact-info {
            font-size: 1em;
            color: #555;
            margin-bottom: 8px;
        }

        .contact-label {
            font-weight: bold;
            color: #e41e31;
        }

        .back-btn {
            display: inline-block;
            margin-top: 25px;
            background: #222;
            color: #fff;
            border: none;
            padding: 12px 30px;
            border-radius: 6px;
            font-size: 1em;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.2s;
        }

        .back-btn:hover {
            background: #444;
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
                <a href="history.php"><i class="fa fa-history"></i> History</a>
                <a href="contact.php"><i class="fa fa-envelope"></i> Contact</a>
            </div>
            <a href="logout.php" class="logout"><i class="fa fa-sign-out-alt"></i> Log Out</a>
        </div>
        <div class="content">
            <div class="contact-card">
                <img src="logo.png" alt="Meat Bazar Logo" class="contact-logo">
                <div class="contact-title">Meat Bazar</div>
                <div class="contact-admin"><span class="contact-label">Admin:</span> Md. Nazmus Sadat Numan</div>
                <div class="contact-info"><span class="contact-label">Phone:</span> 01724972425</div>
                <div class="contact-info"><span class="contact-label">Email:</span> nazmussadatnuman92@gmail.com</div>
                <div class="contact-info"><span class="contact-label">WhatsApp:</span> 01724972425</div>
                <a href="home.php" class="back-btn"><i class="fa fa-arrow-left"></i> Back</a>
            </div>
        </div>
    </div>
    <script>
        function applyHeights() {
            var header = document.querySelector('.header');
            var gap = document.querySelector('.gap');
            var nav = document.querySelector('.nav');
            if (header) document.documentElement.style.setProperty('--HEADER-H', header.getBoundingClientRect().height + 'px');
            if (gap) document.documentElement.style.setProperty('--GAP-H', gap.getBoundingClientRect().height + 'px');
            if (nav) document.documentElement.style.setProperty('--NAV-H', nav.getBoundingClientRect().height + 'px');
        }
        window.addEventListener('load', applyHeights);
        window.addEventListener('resize', applyHeights);
        const ro = new ResizeObserver(applyHeights);
        ro.observe(document.body);

        function updateCartCount() {
            const cart = JSON.parse(localStorage.getItem('meatBazarCart') || '[]');
            const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
            const cartCountElement = document.getElementById('cart-count');
            if (cartCountElement) {
                cartCountElement.textContent = totalItems;
            }
        }
        document.addEventListener('DOMContentLoaded', function() {
            updateCartCount();
        });
        window.addEventListener('storage', function(e) {
            if (e.key === 'meatBazarCart') {
                updateCartCount();
            }
        });
    </script>
</body>

</html>