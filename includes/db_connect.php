<?php
$host = 'localhost';
$dbname = 'judging_system';
$username = 'admin';
$password = 'strong_password';

try {
    $pdo = new PDO("mysql:host=127.0.0.1;port=3306;dbname=judging_system", $username, $password);    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}
?>