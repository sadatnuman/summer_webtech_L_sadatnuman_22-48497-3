<?php
session_start();
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: Sat, 01 Jan 2000 00:00:00 GMT");
if (!isset($_SESSION['role']) || $_SESSION['role'] !== "User") {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Meat Bazar</title>
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
        }

        .content h2 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
            font-size: 2.2em;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
            padding: 20px 0;
            max-width: 1200px;
            margin: 0 auto;
        }

        .product-card {
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            background: white;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 420px;
            display: flex;
            flex-direction: column;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.15);
        }

        .product-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .product-info {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .product-details {
            flex-grow: 1;
        }

        .product-card h3 {
            margin: 10px 0;
            color: #333;
            font-size: 1.2em;
            font-weight: bold;
        }

        .product-card p {
            color: #666;
            margin: 8px 0;
            font-size: 0.9em;
            line-height: 1.4;
        }

        .product-card .price {
            color: #e41e31;
            font-size: 1.4em;
            font-weight: bold;
            margin: 15px 0 10px 0;
        }

        .product-card button {
            background-color: #FF0000;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            font-size: 1em;
            transition: background-color 0.3s ease;
            width: 100%;
            margin-top: auto;
        }

        .product-card button:hover {
            background-color: #cc0000;
        }

        .welcome-section {
            background: linear-gradient(135deg, #FF0000, #CC0000);
            color: white;
            padding: 40px 20px;
            border-radius: 15px;
            margin-bottom: 30px;
            text-align: center;
        }

        .welcome-section h2 {
            font-size: 28px;
            margin-bottom: 15px;
            color: white;
        }

        .welcome-section p {
            font-size: 18px;
            margin: 0;
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
            <div class="welcome-section">
                <h2>Welcome to Meat Bazar!</h2>
                <p>Your one-stop destination for fresh, premium quality beef, mutton, and chicken</p>
            </div>
            
            <h2>Featured Products</h2>
            <div class="products-grid">
                <?php
                require_once "db.php";
                $products = [];
                $stmt = $conn->prepare("SELECT * FROM inventory WHERE category='featured' ORDER BY id DESC");
                $stmt->execute();
                $result = $stmt->get_result();
                while ($row = $result->fetch_assoc()) {
                    $products[] = $row;
                }
                $stmt->close();
                foreach ($products as $item) {
                ?>
                <div class="product-card">
                    <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                    <div class="product-info">
                        <div class="product-details">
                            <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                                <p><?php echo htmlspecialchars($item['description']); ?></p>
                        </div>
                        <div class="price">৳<?php echo htmlspecialchars($item['price']); ?>/kg</div>
                        <button onclick="addToCart('<?php echo htmlspecialchars($item['name']); ?>', <?php echo htmlspecialchars($item['price']); ?>, '<?php echo htmlspecialchars($item['image']); ?>')">Add to Cart</button>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <script>
        
        let cart = JSON.parse(localStorage.getItem('meatBazarCart')) || [];

        function addToCart(productName, price, image) {
            const existingItem = cart.find(item => item.name === productName);
            
            if (existingItem) {
                existingItem.quantity += 1;
                existingItem.total = existingItem.quantity * existingItem.price;
            } else {
                cart.push({
                    name: productName,
                    price: price,
                    image: image,
                    quantity: 1,
                    total: price
                });
            }
            
            localStorage.setItem('meatBazarCart', JSON.stringify(cart));
            
            
            updateCartCount();
        }

        function updateCartCount() {
            const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
            const cartCountElement = document.getElementById('cart-count');
            if (cartCountElement) {
                cartCountElement.textContent = totalItems;
            }
            console.log(`Cart now has ${totalItems} items`);
        }

        // Initialize cart count on page load
        window.addEventListener('load', function() {
            updateCartCount();
        });

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

        // Store the current page in session storage for proper back navigation
        if (typeof(Storage) !== "undefined") {
            sessionStorage.setItem("currentPage", "home.php");
        }

        // Handle proper navigation history
        window.addEventListener('load', function() {
            // Replace the current history state to ensure proper back navigation
            if (window.history.replaceState) {
                window.history.replaceState({page: 'home'}, 'Home - Meat Bazar', 'home.php');
            }
        });
    </script>
</body>

</html>