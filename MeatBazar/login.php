<?php
session_start();
include("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $role     = $_POST['role'];

    if (!empty($username) && !empty($password)) {
        if ($role === "User") {
            // ✅ User login
            $sql = "SELECT * FROM registration WHERE user_name = ? AND password = ?";
            $stmt = $conn->prepare($sql);

            if ($stmt === false) {
                die("SQL Error: " . $conn->error);
            }

            $stmt->bind_param("ss", $username, $password);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();

                $_SESSION['user_name']    = $row['user_name'];
                $_SESSION['full_name']    = $row['full_name'];
                $_SESSION['email']        = $row['email'];
                $_SESSION['phone_number'] = $row['phone_number'];
                $_SESSION['address']      = $row['address'] ?? 'No address provided';
                $_SESSION['dob']          = $row['dob'];
                $_SESSION['role']         = 'User';

                header("Location: home.php");
                exit();
            } else {
                echo "<script>alert('❌ Invalid username or password'); window.history.back();</script>";
            }

            $stmt->close();
        } 
        else if ($role === "Distributor") {
            // ✅ Distributor login
            $sql = "SELECT * FROM distributor WHERE user_name = ? AND password = ?";
            $stmt = $conn->prepare($sql);

            if ($stmt === false) {
                die("SQL Error: " . $conn->error);
            }

            $stmt->bind_param("ss", $username, $password);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();

                $_SESSION['user_name']    = $row['user_name'];
                $_SESSION['full_name']    = $row['full_name'];
                $_SESSION['email']        = $row['email'];
                $_SESSION['phone_number'] = $row['phone_number'];
                $_SESSION['address']      = $row['address'] ?? 'No address provided';
                $_SESSION['dob']          = $row['dob'];
                $_SESSION['role']         = 'Distributor';

                header("Location: distributor_dashboard.php");
                exit();
            } else {
                echo "<script>alert('❌ Invalid distributor credentials'); window.history.back();</script>";
            }

            $stmt->close();
        }
        else {
            // ✅ Admin login
            $sql = "SELECT * FROM admin WHERE user_name = ? AND password = ?";
            $stmt = $conn->prepare($sql);

            if ($stmt === false) {
                die("SQL Error: " . $conn->error);
            }

            $stmt->bind_param("ss", $username, $password);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();

                $_SESSION['user_name']    = $row['user_name'];
                $_SESSION['full_name']    = $row['full_name'];
                $_SESSION['email']        = $row['email'];
                $_SESSION['phone_number'] = $row['phone_number'];
                $_SESSION['address']      = $row['address'] ?? 'No address provided';
                $_SESSION['dob']          = $row['dob'];
                $_SESSION['role']         = 'Admin';

                header("Location: admin_dashboard.php");
                exit();
            } else {
                echo "<script>alert('❌ Invalid admin credentials'); window.history.back();</script>";
            }

            $stmt->close();
        }
    } else {
        echo "<script>alert('⚠️ Please enter username and password'); window.history.back();</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Meat Bazar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            width: 350px;
            padding: 30px 25px 40px 25px;
            border: 2px solid red;
            border-radius: 10px;
            background: #fff;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .logo {
            width: 120px;
            margin-bottom: 15px;
        }

        h2 {
            color: red;
            margin-bottom: 25px;
        }

        .role-group {
            text-align: left;
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .role-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: black;
        }

        .role-options {
            display: flex;
            gap: 20px;
        }

        .role-options label {
            display: flex;
            align-items: center;
            font-weight: normal;
            cursor: pointer;
        }

        .role-options input {
            width: auto;
            margin-right: 5px;
        }

        .form-group {
            text-align: left;
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: black;
            font-weight: bold;
        }

        input[type="text"],
        input[type="password"] {
            width: 95%;
            padding: 10px;
            border: 1px solid red;
            border-radius: 5px;
            font-size: 14px;
        }

        input:focus {
            border-color: darkred;
            outline: none;
        }

        .password-container {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }

        .form-links {
            display: flex;
            justify-content: space-between;
            margin-bottom: 25px;
        }

        .form-links a {
            color: red;
            text-decoration: none;
            font-size: 14px;
        }

        .btn {
            width: 100%;
            padding: 12px;
            background: red;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn:hover {
            background: darkred;
        }

        .error {
            color: #b00020;
            margin-bottom: 12px;
            text-align: left;
        }
    </style>
</head>

<body>
    <div class="container">
        <img src="logo.png" alt="Meat Bazar Logo" class="logo">
        <h2>Login to Meat Bazar</h2>

        <?php if (!empty($login_error)): ?>
            <div class="error">❌ <?php echo htmlspecialchars($login_error); ?></div>
        <?php endif; ?>

        <form onsubmit="return validateLogin()" method="POST" action="login.php">
            <div class="role-group">
                <label>Role:</label>
                <div class="role-options">
                    <label><input type="radio" name="role" value="User" checked> User</label>
                    <label><input type="radio" name="role" value="Distributor"> Distributor</label>
                    <label><input type="radio" name="role" value="Admin"> Admin</label>
                </div>
            </div>

            <div class="form-group">
                <label for="username">User Name</label>
                <input name="username" type="text" id="username" placeholder="Enter username">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <div class="password-container">
                    <input name="password" type="password" id="password" placeholder="Enter password">
                    <span class="toggle-password" onclick="togglePassword('password')">👁</span>
                </div>
            </div>
            <div class="form-links">
                <a href="forgot_password.php">Forgot Password?</a>
                <a href="create_account.php">Create Account</a>
            </div>
            <button type="submit" class="btn">Login</button>
        </form>
    </div>

    <script>
        function isValidUsername(u) {
            return /^[a-z0-9]+$/.test(u);
        }

        function isValidPassword(pw) {
            return (
                pw.length >= 8 &&
                /[a-z]/.test(pw) &&
                /[A-Z]/.test(pw) &&
                /[0-9]/.test(pw)
            );
        }

        function validateLogin() {
            let username = document.getElementById("username").value.trim();
            let password = document.getElementById("password").value.trim();

            if (username === "" || password === "") {
                alert("Please fill all fields.");
                return false;
            }

            if (!isValidUsername(username)) {
                alert("Username must be all lowercase letters and numbers only.");
                return false;
            }

            if (!isValidPassword(password)) {
                alert("Password must be at least 8 characters and include:\n• lowercase\n• uppercase\n• number");
                return false;
            }

            return true;
        }

        function togglePassword(id) {
            let input = document.getElementById(id);
            input.type = (input.type === "password") ? "text" : "password";
        }
    </script>
</body>

</html>