<?php
include("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name    = $_POST['fullName'];
    $email        = $_POST['email'];
    $username     = $_POST['username'];
    $phone_number = $_POST['phone'];
    $address      = $_POST['address'];
    $dob          = $_POST['dob'];
    $password     = $_POST['regPassword'];

    $checkUserSql = "SELECT user_name FROM registration WHERE user_name = ?";
    $checkUserStmt = $conn->prepare($checkUserSql);

    if (!$checkUserStmt) {
        die("SQL Error (username check): " . $conn->error);
    }

    $checkUserStmt->bind_param("s", $username);
    $checkUserStmt->execute();
    $checkUserStmt->store_result();

    if ($checkUserStmt->num_rows > 0) {
        echo "<script>alert('❌ Username already exists. Please choose another one.'); window.history.back();</script>";
        $checkUserStmt->close();
        exit;
    }
    $checkUserStmt->close();

    $checkEmailSql = "SELECT email FROM registration WHERE email = ?";
    $checkEmailStmt = $conn->prepare($checkEmailSql);

    if (!$checkEmailStmt) {
        die("SQL Error (email check): " . $conn->error);
    }

    $checkEmailStmt->bind_param("s", $email);
    $checkEmailStmt->execute();
    $checkEmailStmt->store_result();

    if ($checkEmailStmt->num_rows > 0) {
        echo "<script>alert('❌ Email already exists. Please use another email.'); window.history.back();</script>";
        $checkEmailStmt->close();
        exit;
    }
    $checkEmailStmt->close();

    $sql = "INSERT INTO registration (full_name, email, user_name, phone_number, address, dob, password) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("SQL Error (insert): " . $conn->error);
    }

    $stmt->bind_param("sssssss", $full_name, $email, $username, $phone_number, $address, $dob, $password);

    if ($stmt->execute()) {
        echo "<script>alert('✅ Account created successfully! Please login.'); window.location='login.php';</script>";
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account - Meat Bazar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: white;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .header {
            text-align: center;
            margin-top: 60px;
            margin-bottom: 30px;
        }

        .logo {
            width: 100px;
            height: auto;
            margin-bottom: 10px;
        }

        .header h1 {
            color: red;
            margin: 0;
        }

        .wrapper {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .container {
            width: 400px;
            padding: 25px;
            border: 2px solid red;
            border-radius: 10px;
            background: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            align-self: center;
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
            width: 95%;
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
    <div class="header">
        <img src="logo.png" alt="Logo" class="logo">
        <h1>Let's Create Your Account</h1>
    </div>

    <div class="wrapper">
        <div class="container">
            <form onsubmit="return validateRegister()" method="POST" action="create_account.php">
                <input type="hidden" name="form_name" value="register_form">

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
                    <label for="address">Address</label>
                    <input id="address" name="address" type="text" placeholder="Enter your full address" required>
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

                <button type="submit" class="btn btn-create">Create Account</button>
            </form>
        </div>

        <button type="button" class="btn btn-back" onclick="window.location='login.php'">⬅ Back</button>
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