<?php
// check_username.php
include 'db.php';

if (isset($_POST['username'])) {
    $username = trim($_POST['username']);

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);

    echo $stmt->rowCount() > 0 ? 'taken' : 'available';
}
?>
