<?php
// db.php

$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'user_db';

try {
    // Connect without selecting DB first to create it
    $pdo = new PDO("mysql:host=$host", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create database if not exists
    $pdo->exec("CREATE DATABASE IF NOT EXISTS $dbname");

    // Now connect to the new database
    $pdo->exec("USE $dbname");

    // Create users table if it doesn't exist
    $createTable = "
        CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL UNIQUE,
            email VARCHAR(100) NOT NULL
        )";
    $pdo->exec($createTable);

    // Insert sample data if table is empty
    $stmt = $pdo->query("SELECT COUNT(*) FROM users");
    if ($stmt->fetchColumn() == 0) {
        $pdo->exec("
            INSERT INTO users (username, email) VALUES
            ('kim_sadia', 'sadia@example.com'),
            ('Lee_seoyeon', 'seoyeon@example.com')
            ('doreamon', 'doreamon@example.com')
            ('ayesh', 'ayesh@example.com')
        ");
    }

} catch (PDOException $e) {
    die("DB Error: " . $e->getMessage());
}
?>
