<?php
session_start();
include("db.php");  // Include the database connection file

if (!isset($_SESSION['role']) || $_SESSION['role'] !== "Admin") {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name    = $_POST['fullName'];
    $email        = $_POST['email'];
    $username     = $_POST['username'];
    $phone_number = $_POST['phone'];
    $dob          = $_POST['dob'];
    $password     = $_POST['regPassword'];

    // Check if username exists
    $checkUserSql = "SELECT user_name FROM distributor WHERE user_name = ?";
    $checkUserStmt = $conn->prepare($checkUserSql);
    $checkUserStmt->bind_param("s", $username);
    $checkUserStmt->execute();
    $checkUserStmt->store_result();
    if ($checkUserStmt->num_rows > 0) {
        echo "<script>alert('❌ Username already exists. Please choose another one.'); window.history.back();</script>";
        $checkUserStmt->close();
        exit;
    }
    $checkUserStmt->close();

    // Check if email exists
    $checkEmailSql = "SELECT email FROM distributor WHERE email = ?";
    $checkEmailStmt = $conn->prepare($checkEmailSql);
    $checkEmailStmt->bind_param("s", $email);
    $checkEmailStmt->execute();
    $checkEmailStmt->store_result();
    if ($checkEmailStmt->num_rows > 0) {
        echo "<script>alert('❌ Email already exists. Please use another email.'); window.history.back();</script>";
        $checkEmailStmt->close();
        exit;
    }
    $checkEmailStmt->close();

    // Insert new distributor
    $sql = "INSERT INTO distributor (full_name, email, user_name, phone_number, dob, password) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $full_name, $email, $username, $phone_number, $dob, $password);
    if ($stmt->execute()) {
        echo "<script>alert('✅ Distributor account created successfully!'); </script>";
    } else {
        echo "<script>alert('❌ Error: Something went wrong.'); window.history.back();</script>";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Add Distributor - Meat Bazar</title>
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
            z-index: 999;
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
            position: relative;
        }

        .sidebar {
            position: fixed;
            top: calc(var(--HEADER-H) + var(--NAV-H));
            left: calc(-1 * var(--SIDEBAR-W));
            height: calc(100vh - (var(--HEADER-H) + var(--NAV-H)));
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
            margin-top: calc(var(--HEADER-H) + var(--NAV-H));
            display: flex;
            justify-content: center;
            align-items: flex-start;
            flex-direction: column;
            min-height: 80%;
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

        .form-container {
            width: 500px;
            padding: 50px;
            border: 2px solid red;
            border-radius: 10px;
            background: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            align-self: center;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #000;
        }

        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #000;
        }

        input {
            width: 100%;
            padding: 10px;
            border: 1px solid red;
            border-radius: 5px;
            color: black;
        }

        .password-container {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            right: 10px;
            top: 10px;
            cursor: pointer;
            color: #000;
        }

        .btn {
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
        }

        .btn-create {
            background: red;
            color: white;
            width: 100%;
        }

        .btn-create:hover {
            background: darkred;
        }

        .btn-back {
            background: black;
            color: white;
            margin-top: 10px;
            margin-bottom: 40px;
        }

        .btn-back:hover {
            background: #333;
        }

        .error {
            color: #b00020;
            margin-bottom: 12px;
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
            <div class="form-container">
                <h2>Create Distributor Account</h2>
                <form onsubmit="return validateRegister()" method="POST" action="add_distributor.php">
                    <div class="form-group">
                        <label for="fullName">Full Name</label>
                        <input id="fullName" name="fullName" type="text" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input id="email" name="email" type="email" required>
                    </div>
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input id="username" name="username" type="text" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input id="phone" name="phone" type="text" required>
                    </div>
                    <div class="form-group">
                        <label for="dob">Date of Birth</label>
                        <input id="dob" name="dob" type="date" required>
                    </div>
                    <div class="form-group">
                        <label for="regPassword">Password</label>
                        <div class="password-container">
                            <input type="password" id="regPassword" name="regPassword" required>
                            <span class="toggle-password" onclick="togglePassword('regPassword')">👁</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="confirmPassword">Retype Password</label>
                        <input type="password" id="confirmPassword" name="confirmPassword" required>
                    </div>
                    <button type="submit" class="btn btn-create">Create Distributor Account</button>
                </form>
                <div style="display: flex; justify-content: center;">
                    <button type="button" class="btn btn-back" onclick="window.location='manage_distributor.php'">Back</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        function isValidPassword(pw) {
            return (
                typeof pw === 'string' &&
                pw.length >= 8 &&
                /[a-z]/.test(pw) &&
                /[A-Z]/.test(pw) &&
                /\d/.test(pw) &&
                /[^A-Za-z0-9]/.test(pw)
            );
        }

        function isAdult(dobStr) {
            if (!dobStr) return false;
            const dob = new Date(dobStr + "T00:00:00");
            if (isNaN(dob.getTime())) return false;
            const today = new Date();
            const eighteen = new Date(
                dob.getFullYear() + 18,
                dob.getMonth(),
                dob.getDate()
            );
            return today >= eighteen;
        }

        function isValidEmailLower(e) {
            if (!e) return false;
            const trimmed = e.trim();
            if (trimmed !== trimmed.toLowerCase()) return false;
            if (!trimmed.includes("@") || !trimmed.includes(".")) return false;
            const re = /^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/;
            return re.test(trimmed);
        }

        function isValidUsername(u) {
            if (!u) return false;
            const trimmed = u.trim();
            if (trimmed !== trimmed.toLowerCase()) return false;
            return /^[a-z0-9]+$/.test(trimmed);
        }

        function isValidPhone(ph) {
            if (!ph) return false;
            const trimmed = ph.trim();
            return /^\d+$/.test(trimmed);
        }

        function togglePassword(id) {
            const input = document.getElementById(id);
            input.type = (input.type === "password") ? "text" : "password";
        }

        function validateRegister() {
            const email = document.getElementById("email").value.trim();
            const username = document.getElementById("username").value.trim();
            const phone = document.getElementById("phone").value.trim();
            const dob = document.getElementById("dob").value;
            const pass = document.getElementById("regPassword").value;
            const confirm = document.getElementById("confirmPassword").value;

            if (!isValidPassword(pass)) {
                alert("Password must be at least 8 characters and include:\n• a lowercase letter\n• an uppercase letter\n• a number\n• a symbol");
                return false;
            }
            if (pass !== confirm) {
                alert("Passwords do not match.");
                return false;
            }
            if (!isAdult(dob)) {
                alert("You must be at least 18 years old to create an account.");
                return false;
            }
            if (!isValidEmailLower(email)) {
                alert("Email must be all lowercase and in a valid format.");
                return false;
            }
            if (!isValidUsername(username)) {
                alert("Username must be all lowercase and contain only letters and numbers.");
                return false;
            }
            if (!isValidPhone(phone)) {
                alert("Phone number must contain digits only.");
                return false;
            }
            return true;
        }
    </script>
</body>

</html>