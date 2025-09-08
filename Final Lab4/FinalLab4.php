<?php
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'user';

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$table_creation_query = "CREATE TABLE IF NOT EXISTS numan (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(15) NOT NULL
)";

if ($conn->query($table_creation_query) === FALSE) {
    echo "Error creating table: " . $conn->error;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];

        $insert_query = "INSERT INTO numan (name, email, phone) VALUES ('$name', '$email', '$phone')";
        if ($conn->query($insert_query) === TRUE) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $conn->error;
        }
    }

    if (isset($_POST['update'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];

        $update_query = "UPDATE numan SET name='$name', email='$email', phone='$phone' WHERE id=$id";
        if ($conn->query($update_query) === TRUE) {
            echo "Record updated successfully";
        } else {
            echo "Error: " . $conn->error;
        }
    }

    if (isset($_POST['delete'])) {
        $id = $_POST['id'];

        $delete_query = "DELETE FROM numan WHERE id=$id";
        if ($conn->query($delete_query) === TRUE) {
            echo "Record deleted successfully";
        } else {
            echo "Error: " . $conn->error;
        }
    }
}

$select_query = "SELECT * FROM numan";
$result = $conn->query($select_query);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Operations - Numan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            padding: 20px;
        }

        h1 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        input[type="text"],
        input[type="email"],
        input[type="phone"] {
            padding: 8px;
            width: 100%;
            margin-bottom: 10px;
            border: 1px solid #ccc;
        }

        button {
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        .form-container {
            max-width: 400px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .form-container h2 {
            text-align: center;
        }   

        .form-container button {
            width: 100%;
        }
    </style>
</head>

<body>

    <h1>CRUD Operations on Numan Table</h1>

    
    <div class="form-container">
        <h2>Add New Record</h2>
        <form method="POST">
            <input type="text" name="name" placeholder="Enter Name" required>
            <input type="email" name="email" placeholder="Enter Email" required>
            <input type="text" name="phone" placeholder="Enter Phone Number" required>
            <button type="submit" name="add">Add Record</button>
        </form>
    </div>

    
    <h2>Existing Records</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['name']}</td>
                    <td>{$row['email']}</td>
                    <td>{$row['phone']}</td>
                    <td>
                        <form method='POST' style='display:inline-block'>
                            <input type='hidden' name='id' value='{$row['id']}'>
                            <button type='submit' name='delete'>Delete</button>
                        </form>
                        <button onclick='openUpdateForm({$row['id']}, \"{$row['name']}\", \"{$row['email']}\", \"{$row['phone']}\")'>Update</button>
                    </td>
                </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No records found</td></tr>";
            }
            ?>
        </tbody>
    </table>

    
    <div class="form-container" id="updateForm" style="display:none;">
        <h2>Update Record</h2>
        <form method="POST">
            <input type="hidden" name="id" id="updateId">
            <input type="text" name="name" id="updateName" required>
            <input type="email" name="email" id="updateEmail" required>
            <input type="text" name="phone" id="updatePhone" required>
            <button type="submit" name="update">Update Record</button>
        </form>
    </div>

    <script>
        function openUpdateForm(id, name, email, phone) {
            document.getElementById('updateId').value = id;
            document.getElementById('updateName').value = name;
            document.getElementById('updateEmail').value = email;
            document.getElementById('updatePhone').value = phone;
            document.getElementById('updateForm').style.display = 'block';
        }
    </script>

</body>

</html>

<?php
$conn->close();
?>