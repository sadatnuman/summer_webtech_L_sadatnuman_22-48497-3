<?php
session_start();
include("db.php");
if (!isset($_SESSION['role']) || $_SESSION['role'] !== "Distributor") {
    header("Location: login.php");
    exit();
}
if (!isset($_SESSION['user_name'])) {
    header("Location: login.php");
    exit();
}

$user_name = $_SESSION['user_name'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name    = trim($_POST['full_name'] ?? '');
    $phone_number = trim($_POST['phone_number'] ?? '');
    $dob          = trim($_POST['dob'] ?? '');
    $password     = trim($_POST['password'] ?? '');

    $sqlU = "UPDATE distributor SET full_name = ?, phone_number = ?, dob = ?, password = ? WHERE user_name = ?";
    $stmtU = $conn->prepare($sqlU);
    if (!$stmtU) {
        die("Database error: " . $conn->error);
    }
    $stmtU->bind_param("sssss", $full_name, $phone_number, $dob, $password, $user_name);
    $stmtU->execute();
    $stmtU->close();
}

$sql = "SELECT full_name, email, user_name, phone_number, dob, password FROM distributor WHERE user_name = ? LIMIT 1";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Database error: " . $conn->error);
}
$stmt->bind_param("s", $user_name);
$stmt->execute();
$result = $stmt->get_result();
$distributor = $result->fetch_assoc();
$stmt->close();

if ($distributor && empty($_SESSION['full_name'])) {
    $_SESSION['full_name'] = $distributor['full_name'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Change Distributor Personal Info - Meat Bazar</title>
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
            text-align: center;
            margin: 0 0 20px;
        }

        .profile-field {
            margin-bottom: 15px;
        }

        .profile-field label {
            font-weight: bold;
            display: block;
            margin-bottom: 6px;
        }

        .field-row {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .field-row input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 15px;
            background: #fafafa;
        }

        .edit-btn {
            background: #ff0000;
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 10px 14px;
            cursor: pointer;
            font-weight: 600;
        }

        .edit-btn:disabled {
            opacity: .6;
            cursor: not-allowed;
        }

        .btn-row {
            display: flex;
            gap: 12px;
            justify-content: center;
            margin-top: 20px;
        }

        .back-btn {
            display: inline-block;
            padding: 10px 20px;
            background: black;
            color: #fff;
            text-decoration: none;
            border-radius: 6px;
        }

        .back-btn:hover {
            opacity: .85;
        }

        .save-btn {
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

        .save-btn:hover {
            opacity: .85;
        }

        .note {
            font-size: 12px;
            color: #666;
            margin-top: 0px;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>

    <input type="checkbox" id="check">

    <div class="header">
        <img src="logo.png" alt="Logo">
        <h1>Meat Bazar - Distributor Panel</h1>
        <h4>Welcome Distributor, <?php echo htmlspecialchars($_SESSION['full_name'] ?? $user_name); ?>!</h4>
    </div>

    <div class="gap"></div>

    <div class="nav">
        <label for="check"><i class="fa fa-bars menu-toggle"></i></label>
        <a href="distributor_dashboard.php">Home</a>
        <a href="all_orders.php">All Orders</a>
        <a href="assigned_orders.php">Assigned Orders</a>
        <a href="distributor_inventory.php">Inventory</a>
    </div>

    <div class="container">

        <aside class="sidebar">
            <header>
                Side Menu
                <label for="check"><i class="fa fa-times close-btn"></i></label>
            </header>
            <div class="menu-links">
                <a href="distributor_profile.php"><i class="fa fa-user"></i> Distributor Profile</a>
                <a href="change_distributor_personal_info.php" class="active"><i class="fa fa-edit"></i> Change Personal Info</a>
                <a href="user_info_for_distributor.php"><i class="fa fa-users"></i> All Users</a>
            </div>
            <a href="logout.php" class="logout"><i class="fa fa-sign-out-alt"></i> Log Out</a>
        </aside>

        <div class="content">
            <div class="profile-container">
                <h2>Change Personal Info</h2>

                <form method="post" action="">
                    <div class="profile-field">
                        <label>Full Name</label>
                        <div class="field-row">
                            <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($distributor['full_name']); ?>" placeholder="Enter full name" readonly>
                            <button type="button" class="edit-btn" data-target="full_name">Edit</button>
                        </div>
                    </div>

                    <div class="profile-field">
                        <label>Email</label>
                        <div class="field-row">
                            <input type="email" id="email" value="<?php echo htmlspecialchars($distributor['email']); ?>" placeholder="Email" readonly>
                            <button type="button" class="edit-btn" disabled>Edit</button>
                        </div>
                        <div class="note">Email cannot be changed</div>
                    </div>

                    <div class="profile-field">
                        <label>Username</label>
                        <div class="field-row">
                            <input type="text" id="user_name" value="<?php echo htmlspecialchars($distributor['user_name']); ?>" placeholder="Username" readonly>
                            <button type="button" class="edit-btn" disabled>Edit</button>
                        </div>
                        <div class="note">Username cannot be changed</div>
                    </div>

                    <div class="profile-field">
                        <label>Phone Number</label>
                        <div class="field-row">
                            <input type="text" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($distributor['phone_number']); ?>" placeholder="Enter phone number" readonly>
                            <button type="button" class="edit-btn" data-target="phone_number">Edit</button>
                        </div>
                    </div>

                    <div class="profile-field">
                        <label>Date of Birth</label>
                        <div class="field-row">
                            <input type="date" id="dob" name="dob" value="<?php echo htmlspecialchars($distributor['dob']); ?>" placeholder="YYYY-MM-DD" readonly>
                            <button type="button" class="edit-btn" data-target="dob">Edit</button>
                        </div>
                    </div>

                    <div class="profile-field">
                        <label>Password</label>
                        <div class="field-row">
                            <input type="password" id="password" name="password" value="<?php echo htmlspecialchars($distributor['password']); ?>" placeholder="Enter new password" readonly>
                            <button type="button" class="edit-btn" data-target="password">Edit</button>
                        </div>
                    </div>

                    <div class="btn-row">
                        <a href="distributor_dashboard.php" class="back-btn">Back</a>
                        <button type="submit" class="save-btn">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.edit-btn[data-target]').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var id = this.getAttribute('data-target');
                var input = document.getElementById(id);
                if (!input) return;
                if (input.hasAttribute('readonly')) {
                    input.removeAttribute('readonly');
                    input.focus();
                    if (input.type === 'password') input.type = 'text';
                    this.textContent = 'Done';
                } else {
                    input.setAttribute('readonly', 'readonly');
                    if (id === 'password') input.type = 'password';
                    this.textContent = 'Edit';
                }
            });
        });
    </script>
</body>