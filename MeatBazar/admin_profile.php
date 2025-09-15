<?php
session_start();
include("db.php");
if (!isset($_SESSION['role']) || $_SESSION['role'] !== "Admin") {
    header("Location: login.php");
    exit();
}
if (!isset($_SESSION['user_name'])) {
    header("Location: login.php");
    exit();
}
$user_name = $_SESSION['user_name'];
$sql = "SELECT full_name, email, user_name, phone_number, dob, password FROM admin WHERE user_name = ? LIMIT 1";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Database error: " . $conn->error);
}
$stmt->bind_param("s", $user_name);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();
$stmt->close();
if ($admin && empty($_SESSION['full_name'])) {
    $_SESSION['full_name'] = $admin['full_name'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Profile - Meat Bazar</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --HEADER-H: 65px;
            --GAP-H: 2px;
            --NAV-H: 45px;
            --SIDEBAR-W: 220px;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #fff;
        }

        .header {
            height: var(--HEADER-H);
            background: red;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 20px;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
        }

        .header img {
            position: absolute;
            left: 20px;
            height: 50px
        }

        .header h1 {
            margin: 0;
            font-size: 30px;
            font-weight: bold
        }

        .header h4 {
            margin: 0;
            position: absolute;
            right: 50px;
            top: 50%;
            transform: translateY(-50%)
        }

        .gap {
            height: var(--GAP-H);
            background: #fff;
            position: fixed;
            top: var(--HEADER-H);
            left: 0;
            width: 100%;
            z-index: 999;
        }

        .nav {
            background: #FF0000;
            color: #fff;
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

        .sidebar {
            position: fixed;
            top: calc(var(--HEADER-H) + var(--GAP-H) + var(--NAV-H));
            left: calc(-1 * var(--SIDEBAR-W));
            width: var(--SIDEBAR-W);
            height: calc(100vh - (var(--HEADER-H) + var(--GAP-H) + var(--NAV-H)));
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
            text-align: left;
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
            font-size: 16px
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: #FF0000
        }

        .logout {
            background: #B22222;
            text-align: center;
            font-weight: bold;
            padding: 15px 25px
        }

        .logout:hover {
            background: #FF0000
        }

        .container {
            position: relative
        }

        .content {
            padding: 20px;
            transition: margin-left .4s ease;
            margin-left: 0;
            margin-top: calc(var(--HEADER-H) + var(--GAP-H) + var(--NAV-H));
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

        .profile-container {
            background: #fff;
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 8px rgba(0, 0, 0, .1);
        }

        .profile-container h2 {
            margin: 0 0 20px;
            text-align: center
        }

        .profile-field {
            margin-bottom: 15px
        }

        .profile-field label {
            font-weight: bold;
            display: block;
            margin-bottom: 6px
        }

        .profile-field input {
            width: 95%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 15px;
            background: #fafafa
        }

        .password-wrapper {
            display: flex;
            align-items: center
        }

        .password-wrapper input {
            flex: 1
        }

        .password-wrapper button {
            margin-left: 10px;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 16px
        }

        .back-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background: black;
            color: #fff;
            text-decoration: none;
            border-radius: 6px;
        }

        .back-btn:hover {
            opacity: 0.8;
        }
    </style>
</head>

<body>

    <input type="checkbox" id="check">

    <div class="header">
        <img src="logo.png" alt="Logo">
        <h1>Meat Bazar - Admin Panel</h1>
        <h4>Welcome Admin, <?php echo htmlspecialchars($_SESSION['full_name'] ?? $user_name); ?>!</h4>
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

        <aside class="sidebar">
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
        </aside>

        <div class="content">
            <div class="profile-container">
                <h2>Admin Profile</h2>

                <?php if ($admin): ?>
                    <div class="profile-field">
                        <label>Full Name</label>
                        <input type="text" value="<?php echo htmlspecialchars($admin['full_name']); ?>" readonly>
                    </div>
                    <div class="profile-field">
                        <label>Email</label>
                        <input type="text" value="<?php echo htmlspecialchars($admin['email']); ?>" readonly>
                    </div>
                    <div class="profile-field">
                        <label>Username</label>
                        <input type="text" value="<?php echo htmlspecialchars($admin['user_name']); ?>" readonly>
                    </div>
                    <div class="profile-field">
                        <label>Phone Number</label>
                        <input type="text" value="<?php echo htmlspecialchars($admin['phone_number']); ?>" readonly>
                    </div>
                    <div class="profile-field">
                        <label>Date of Birth</label>
                        <input type="text" value="<?php echo htmlspecialchars($admin['dob']); ?>" readonly>
                    </div>
                    <div class="profile-field">
                        <label>Password</label>
                        <div class="password-wrapper">
                            <input type="password" id="password" value="<?php echo htmlspecialchars($admin['password']); ?>" readonly>
                            <button type="button" onclick="togglePassword()" title="Show/Hide">👁</button>
                        </div>
                    </div>
                <?php else: ?>
                    <p>No admin data found.</p>
                <?php endif; ?>

                <a href="admin_dashboard.php" class="back-btn">Back</a>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            var p = document.getElementById('password');
            if (p) {
                p.type = (p.type === 'password') ? 'text' : 'password';
            }
        }
    </script>
</body>

</html>