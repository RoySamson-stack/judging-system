<?php
include '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $display_name = $_POST['display_name'];
    
    try {
        $stmt = $pdo->prepare("INSERT INTO judges (username, display_name) VALUES (?, ?)");
        $stmt->execute([$username, $display_name]);
        
        header("Location: index.php?success=1");
        exit();
    } catch (PDOException $e) {
        header("Location: add_judge.php?error=" . urlencode($e->getMessage()));
        exit();
    }
} else {
    header("Location: add_judge.php");
    exit();
}
?>