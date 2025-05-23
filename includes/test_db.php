<?php
$host = 'localhost';
$dbname = 'judging_system';
$username = 'admin';
$password = 'strong_password';

try {
    $pdo = new PDO("mysql:host=127.0.0.1;port=3306;dbname=judging-system", $username, $password);    echo "Successfully connected to MySQL!";
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
