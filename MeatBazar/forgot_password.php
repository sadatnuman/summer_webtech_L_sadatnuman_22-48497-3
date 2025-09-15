<?php
include("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reset_password'])) {
    $email = trim($_POST['email']);
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    if ($new_password !== $confirm_password) {
        echo "<script>alert('❌ Passwords do not match'); window.history.back();</script>";
        exit;
    }

    $sql = "UPDATE registration SET password=? WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $new_password, $email);

    if ($stmt->execute() && $stmt->affected_rows > 0) {
        echo "<script>alert('✅ Password updated successfully! Please login.'); window.location='login.php';</script>";
    } else {
        echo "<script>alert('❌ Email not found.'); window.history.back();</script>";
    }
    $stmt->close();
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['check_email'])) {
    $email = trim($_POST['email']);
    $sql = "SELECT * FROM registration WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "exists";
    } else {
        echo "not_found";
    }
    $stmt->close();
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Meat Bazar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            margin: 0;
        }

        header {
            width: 100%;
            background-color: red;
            color: white;
            display: flex;
            align-items: center;
            padding: 10px 20px;
            box-sizing: border-box;
        }

        header img {
            height: 50px;
            margin-right: 15px;
        }

        header h1 {
            flex: 1;
            text-align: center;
            margin: 0;
            font-size: 24px;
        }

        .container {
            width: 380px;
            padding: 25px;
            margin: 50px auto;
            border: 2px solid red;
            border-radius: 10px;
            background: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 15px;
            position: relative;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: black;
        }

        input {
            width: 100%;
            padding: 10px 40px 10px 10px;
            border: 1px solid red;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .btn {
            width: 100%;
            padding: 10px;
            background: red;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        .btn:hover {
            background: darkred;
        }

        .back-wrapper {
            width: 380px;
            margin: 20px auto 0 auto;
            text-align: left;
        }

        .back-btn {
            display: inline-block;
            padding: 10px 20px;
            background: black;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .back-btn:hover {
            background: #333;
        }

        .toggle-password {
            position: absolute;
            right: 10px;
            top: 65%;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 18px;
            user-select: none;
        }
    </style>
</head>

<body>

    <header>
        <img src="logo.png" alt="Meat Bazar Logo">
        <h1>🔒 Forgot Password?</h1>
    </header>

    <div class="container">
        <form id="emailForm">
            <div class="form-group">
                <label>Enter Your Email</label>
                <input type="email" id="email" required>
            </div>
            <button type="submit" class="btn">Send OTP</button>
        </form>

        <form id="otpForm" style="display:none;">
            <div class="form-group">
                <label>Enter OTP</label>
                <input type="text" id="otp" required>
            </div>
            <button type="submit" class="btn">Verify OTP</button>
        </form>

        <form id="resetForm" method="POST" style="display:none;">
            <input type="hidden" name="reset_password" value="1">
            <input type="hidden" id="resetEmail" name="email">
            <div class="form-group">
                <label>New Password</label>
                <input type="password" name="new_password" id="new_password" required>
                <span class="toggle-password" onclick="togglePassword('new_password')">👁</span>
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" id="confirm_password" required>
            </div>
            <button type="submit" class="btn">Reset Password</button>
        </form>
    </div>

    <div class="back-wrapper">
        <a href="login.php" class="back-btn">Back</a>
    </div>

    <script>
        const otpCode = "1234";

        document.getElementById("emailForm").onsubmit = async (e) => {
            e.preventDefault();
            let email = document.getElementById("email").value.trim();

            let formData = new FormData();
            formData.append("check_email", 1);
            formData.append("email", email);

            let res = await fetch("forgot_password.php", {
                method: "POST",
                body: formData
            });
            let text = await res.text();

            if (text === "exists") {
                alert("✅ OTP sent (Demo: 1234)");
                document.getElementById("resetEmail").value = email;
                document.getElementById("emailForm").style.display = "none";
                document.getElementById("otpForm").style.display = "block";
            } else {
                alert("❌ Email not found in database");
            }
        };

        document.getElementById("otpForm").onsubmit = (e) => {
            e.preventDefault();
            let otp = document.getElementById("otp").value.trim();
            if (otp === otpCode) {
                document.getElementById("otpForm").style.display = "none";
                document.getElementById("resetForm").style.display = "block";
            } else {
                alert("❌ Invalid OTP");
            }
        };

        function togglePassword(id) {
            const input = document.getElementById(id);
            input.type = input.type === "password" ? "text" : "password";
        }
    </script>
</body>

</html>