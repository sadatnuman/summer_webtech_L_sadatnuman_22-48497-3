<?php
session_start();
include("db.php");
if (!isset($_SESSION['role']) || $_SESSION['role'] !== "Admin") {
    header("Location: login.php");
    exit();
}

$admins = [];
$result = $conn->query("SELECT * FROM admin");
while ($row = $result->fetch_assoc()) {
    $admins[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manage Admin - Meat Bazar</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --HEADER-H: 65px;
            --GAP-H: 2px;
            --NAV-H: 45px;
            --SIDEBAR-W: 220px;
        }

        * {
            box-sizing: border-box;
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
            text-decoration: underline;
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
            color: #fff;
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
            overflow-y: auto;
        }

        .sidebar a {
            display: block;
            padding: 15px 25px;
            color: #fff;
            text-decoration: none;
            font-size: 16px;
        }

        .sidebar a:hover {
            background: #FF0000;
        }

        .logout {
            background: #B22222;
            text-align: center;
            font-weight: bold;
            padding: 15px 25px;
        }

        .logout:hover {
            background: #FF0000;
        }

        .content {
            padding: 20px;
            transition: margin-left .4s ease;
            margin-left: 0;
            margin-top: calc(var(--HEADER-H) + var(--GAP-H) + var(--NAV-H));
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 8px 12px rgba(0, 0, 0, 0.1);
        }

        .content h2 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: red;
            color: white;
        }

        .btn-row {
            display: flex;
            gap: 12px;
            justify-content: flex-start;
            margin-top: 30px;
        }

        .black-btn {
            display: inline-block;
            padding: 10px 20px;
            background: black;
            color: #fff;
            text-decoration: none;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-weight: 600;
        }

        .black-btn:hover {
            opacity: .85;
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
            <h2>All Admins</h2>
            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Username</th>
                        <th>Phone</th>
                        <th>DOB</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $counter = 1; ?>
                    <?php foreach ($admins as $admin): ?>
                        <tr>
                            <td><?php echo $counter++; ?></td>
                            <td><?php echo htmlspecialchars($admin['full_name']); ?></td>
                            <td><?php echo htmlspecialchars($admin['email']); ?></td>
                            <td><?php echo htmlspecialchars($admin['user_name']); ?></td>
                            <td><?php echo htmlspecialchars($admin['phone_number']); ?></td>
                            <td><?php echo htmlspecialchars($admin['dob']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="btn-row">
                <a href="admin_dashboard.php" class="black-btn">Back</a>
                <a href="add_admin.php" class="black-btn">Add Admin</a>
                <a href="delete_admin.php" class="black-btn">Delete Admin</a>
                <a href="edit_admin.php" class="black-btn">Edit</a>
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
    </script>
</body>

</html>