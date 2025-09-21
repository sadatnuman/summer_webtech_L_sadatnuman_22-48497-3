<?php
// Database configuration
$servername = "localhost";
$username = "root"; // MySQL username
$password = "";     // MySQL password
$dbname = "formDB";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if not exists
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
$conn->query($sql);

// Select the database
$conn->select_db($dbname);

// Create table if not exists
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL,
    website VARCHAR(100),
    comment TEXT,
    gender VARCHAR(10) NOT NULL,
    reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
$conn->query($sql);

// Initialize variables
$nameErr = $emailErr = $genderErr = $websiteErr = "";
$name = $email = $gender = $comment = $website = "";
$id = 0;

// Handle form submit (Insert or Update)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

    // Validate Name
    if (empty($_POST["name"])) {
        $nameErr = "Name is required";
    } else {
        $name = test_input($_POST["name"]);
        if (!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
            $nameErr = "Only letters and white space allowed";
        }
    }

    // Validate Email
    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
    } else {
        $email = test_input($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
        }
    }

    // Website
    if (!empty($_POST["website"])) {
        $website = test_input($_POST["website"]);
        if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $website)) {
            $websiteErr = "Invalid URL";
        }
    }

    // Comment
    $comment = !empty($_POST["comment"]) ? test_input($_POST["comment"]) : "";

    // Gender
    if (empty($_POST["gender"])) {
        $genderErr = "Gender is required";
    } else {
        $gender = test_input($_POST["gender"]);
    }

    // If no errors, Insert or Update
    if (empty($nameErr) && empty($emailErr) && empty($genderErr) && empty($websiteErr)) {
        if ($id == 0) {
            // Insert
            $stmt = $conn->prepare("INSERT INTO users (name,email,website,comment,gender) VALUES (?,?,?,?,?)");
            $stmt->bind_param("sssss", $name, $email, $website, $comment, $gender);
            $stmt->execute();
            $stmt->close();
        } else {
            // Update
            $stmt = $conn->prepare("UPDATE users SET name=?, email=?, website=?, comment=?, gender=? WHERE id=?");
            $stmt->bind_param("sssssi", $name, $email, $website, $comment, $gender, $id);
            $stmt->execute();
            $stmt->close();
        }

        // Reset variables
        $name = $email = $website = $comment = $gender = "";
        $id = 0;
    }
}

// Handle Delete
if (isset($_GET['delete'])) {
    $del_id = intval($_GET['delete']);
    $conn->query("DELETE FROM users WHERE id=$del_id");
}

// Handle Edit (fetch data)
if (isset($_GET['edit'])) {
    $edit_id = intval($_GET['edit']);
    $result = $conn->query("SELECT * FROM users WHERE id=$edit_id");
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $name = $row['name'];
        $email = $row['email'];
        $website = $row['website'];
        $comment = $row['comment'];
        $gender = $row['gender'];
        $id = $row['id'];
    }
}

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>

<h2>PHP Form with Database CRUD</h2>
<p><span>* required field</span></p>

<form method="post" action="">
    <input type="hidden" name="id" value="<?php echo $id; ?>">
    Name: <input type="text" name="name" value="<?php echo $name; ?>">
    * <?php echo $nameErr; ?><br><br>

    Email: <input type="text" name="email" value="<?php echo $email; ?>">
    * <?php echo $emailErr; ?><br><br>

    Website: <input type="text" name="website" value="<?php echo $website; ?>">
    <?php echo $websiteErr; ?><br><br>

    Comment: <textarea name="comment" rows="5" cols="40"><?php echo $comment; ?></textarea><br><br>

    Gender:
    <input type="radio" name="gender" <?php if ($gender == "female") echo "checked"; ?> value="female">Female
    <input type="radio" name="gender" <?php if ($gender == "male") echo "checked"; ?> value="male">Male
    <input type="radio" name="gender" <?php if ($gender == "other") echo "checked"; ?> value="other">Other
    * <?php echo $genderErr; ?><br><br>

    <input type="submit" value="<?php echo $id == 0 ? 'Submit' : 'Update'; ?>">
</form>

<h2>Users Data</h2>
<table border="1" cellpadding="5" cellspacing="0">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Website</th>
        <th>Comment</th>
        <th>Gender</th>
        <th>Actions</th>
    </tr>
    <?php
    $result = $conn->query("SELECT * FROM users ORDER BY id DESC");
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
        <td>" . $row['id'] . "</td>
        <td>" . $row['name'] . "</td>
        <td>" . $row['email'] . "</td>
        <td>" . $row['website'] . "</td>
        <td>" . $row['comment'] . "</td>
        <td>" . $row['gender'] . "</td>
        <td>
            <a href='?edit=" . $row['id'] . "'>Edit</a> | 
            <a href='?delete=" . $row['id'] . "' onclick='return confirm(\"Are you sure?\")'>Delete</a>
        </td>
        </tr>";
        }
    } else {
        echo "<tr><td colspan='7'>No records found</td></tr>";
    }
    $conn->close();
    ?>
</table>