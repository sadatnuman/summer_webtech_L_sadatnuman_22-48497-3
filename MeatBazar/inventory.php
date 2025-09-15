<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header('Location: login.php');
    exit();
}
require_once "db.php";


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    if (isset($_POST['action'])) {
        $response = ['success' => false, 'message' => ''];
        $category = $_POST['category'];
        $name = $_POST['name'];
        $description = isset($_POST['description']) ? $_POST['description'] : '';
        $price = intval($_POST['price']);
        $image = $_POST['image'];
        
        try {
            if ($_POST['action'] === 'add') {
                $stmt = $conn->prepare("INSERT INTO inventory (category, name, description, price, image) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("sssis", $category, $name, $description, $price, $image);
                if ($stmt->execute()) {
                    $response = ['success' => true, 'message' => 'Product added successfully', 'id' => $conn->insert_id];
                }
                $stmt->close();
            } elseif ($_POST['action'] === 'update' && isset($_POST['id'])) {
                $id = intval($_POST['id']);
                $stmt = $conn->prepare("UPDATE inventory SET name=?, description=?, price=?, image=? WHERE id=?");
                $stmt->bind_param("ssisi", $name, $description, $price, $image, $id);
                if ($stmt->execute()) {
                    $response = ['success' => true, 'message' => 'Product updated successfully'];
                }
                $stmt->close();
            } elseif ($_POST['action'] === 'delete' && isset($_POST['id'])) {
                $id = intval($_POST['id']);
                $stmt = $conn->prepare("DELETE FROM inventory WHERE id=?");
                $stmt->bind_param("i", $id);
                if ($stmt->execute()) {
                    $response = ['success' => true, 'message' => 'Product deleted successfully'];
                }
                $stmt->close();
            }
        } catch (Exception $e) {
            $response = ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
        
        echo json_encode($response);
        exit();
    }
}

$tab = isset($_GET['tab']) ? $_GET['tab'] : 'featured';
$categories = [
    'featured' => 'Featured Products',
    'beef' => 'Fresh Beef Products',
    'mutton' => 'Fresh Mutton Products',
    'chicken' => 'Fresh Chicken Products'
];

$products = [];
$stmt = $conn->prepare("SELECT * FROM inventory WHERE category=? ORDER BY id DESC");
$stmt->bind_param("s", $tab);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Inventory Management - Meat Bazar</title>
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

        .inventory-section {
            max-width: 1200px;
            margin: 0 auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
            padding: 30px;
        }

        .inventory-section h2 {
            text-align: center;
            color: #e41e31;
            margin-bottom: 30px;
            font-size: 2em;
        }

        .inventory-tabs {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
        }

        .inventory-tab {
            background: #eee;
            color: #e41e31;
            border: none;
            padding: 12px 30px;
            border-radius: 6px 6px 0 0;
            font-size: 1em;
            font-weight: bold;
            cursor: pointer;
            margin: 0 8px;
            transition: background 0.2s;
        }

        .inventory-tab.active {
            background: #e41e31;
            color: #fff;
        }

        .inventory-list {
            margin-top: 10px;
        }

        .inventory-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .inventory-table th,
        .inventory-table td {
            border: 1px solid #e0e0e0;
            padding: 12px;
            text-align: center;
        }

        .inventory-table th {
            background: #f8f8f8;
            color: #e41e31;
            font-weight: bold;
        }

        .inventory-table td img {
            height: 50px;
            border-radius: 6px;
        }

        .inventory-actions button {
            background: #FF0000;
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
            margin: 0 4px;
        }

        .inventory-actions button.update {
            background: #e41e31;
        }

        .inventory-actions button.remove {
            background: #222;
        }

        .inventory-actions button:hover {
            opacity: 0.8;
        }

        .add-form {
            display: flex;
            gap: 12px;
            margin-bottom: 20px;
            justify-content: center;
        }

        .add-form input,
        .add-form select {
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ccc;
            font-size: 1em;
        }

        .add-form button {
            background: #e41e31;
            color: #fff;
            border: none;
            padding: 8px 20px;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
        }

        .add-form button:hover {
            background: #FF0000;
        }

        @media (max-width: 700px) {
            .inventory-section {
                padding: 10px;
            }

            .add-form {
                flex-direction: column;
                gap: 8px;
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
            <div class="inventory-section">
                <h2>Inventory Management</h2>
                <div class="inventory-tabs">
                    <?php foreach ($categories as $key => $label) { ?>
                        <a href="?tab=<?php echo $key; ?>"
                            class="inventory-tab<?php echo $tab === $key ? ' active' : ''; ?>"><?php echo $label; ?></a>
                    <?php } ?>
                </div>
                <form class="add-form" id="addProductForm">
                    <input type="hidden" name="action" value="add">
                    <input type="hidden" name="category" value="<?php echo $tab; ?>">
                    <input type="text" name="name" placeholder="Product Name" required>
                    <input type="text" name="description" placeholder="Short Description" required>
                    <input type="number" name="price" placeholder="Price" required>
                    <input type="text" name="image" placeholder="Image Filename" required>
                    <button type="submit">Add Product</button>
                </form>
                <table class="inventory-table">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $item) { ?>
                            <tr>
                                <td><img src="<?php echo htmlspecialchars($item['image']); ?>"
                                        alt="<?php echo htmlspecialchars($item['name']); ?>"></td>
                                <td><?php echo htmlspecialchars($item['name']); ?></td>
                                <td><?php echo htmlspecialchars($item['description']); ?></td>
                                <td>৳<?php echo htmlspecialchars($item['price']); ?>/kg</td>
                                <td class="inventory-actions">
                                    <form class="product-form" data-id="<?php echo $item['id']; ?>" style="display:flex; flex-direction:column; min-width:220px; gap:8px;">
                                        <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                                        <input type="hidden" name="category" value="<?php echo $tab; ?>">
                                        <div style="display:flex; align-items:center; gap:8px;">
                                            <label>Name:</label>
                                            <input type="text" name="name" value="<?php echo htmlspecialchars($item['name']); ?>" required>
                                        </div>
                                        <div style="display:flex; align-items:center; gap:8px;">
                                            <label>Description:</label>
                                            <input type="text" name="description" value="<?php echo htmlspecialchars($item['description']); ?>" required>
                                        </div>
                                        <div style="display:flex; align-items:center; gap:8px;">
                                            <label>Price:</label>
                                            <input type="number" name="price" value="<?php echo htmlspecialchars($item['price']); ?>" required>
                                        </div>
                                        <div style="display:flex; align-items:center; gap:8px;">
                                            <label>Image:</label>
                                            <input type="text" name="image" value="<?php echo htmlspecialchars($item['image']); ?>" required>
                                        </div>
                                        <div style="display:flex; flex-direction:row; gap:8px; align-items:center; justify-content:flex-start;">
                                            <button type="button" class="update" onclick="updateProduct(this.closest('form'))">Update</button>
                                            <button type="button" class="remove" onclick="deleteProduct(<?php echo $item['id']; ?>)">Remove</button>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
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

        // AJAX Form Submission
        document.getElementById('addProductForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            fetch('inventory.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    location.reload(); 
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while processing your request');
            });
        });

        function updateProduct(form) {
            const formData = new FormData(form);
            formData.append('action', 'update');
            
            fetch('inventory.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while processing your request');
            });
        }

        function deleteProduct(id) {
            if (!confirm('Are you sure you want to remove this product?')) {
                return;
            }

            const formData = new FormData();
            formData.append('action', 'delete');
            formData.append('id', id);
            formData.append('category', document.querySelector('input[name="category"]').value);

            fetch('inventory.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    const row = document.querySelector(`form[data-id="${id}"]`).closest('tr');
                    row.remove();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while processing your request');
            });
        }
    </script>
</body>

</html>
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
    // ...existing code...
</script>
</body>

</html>