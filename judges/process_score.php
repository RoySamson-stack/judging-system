<?php
include '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judge_id = $_POST['judge_id'];
    $user_id = $_POST['user_id'];
    $score = $_POST['score'];
    
    try {
        // Check if judge already scored this user
        $stmt = $pdo->prepare("SELECT id FROM scores WHERE judge_id = ? AND user_id = ?");
        $stmt->execute([$judge_id, $user_id]);
        
        if ($stmt->fetch()) {
            // Update existing score
            $stmt = $pdo->prepare("UPDATE scores SET score = ? WHERE judge_id = ? AND user_id = ?");
            $stmt->execute([$score, $judge_id, $user_id]);
        } else {
            // Insert new score
            $stmt = $pdo->prepare("INSERT INTO scores (judge_id, user_id, score) VALUES (?, ?, ?)");
            $stmt->execute([$judge_id, $user_id, $score]);
        }
        
        header("Location: index.php?success=1");
        exit();
    } catch (PDOException $e) {
        header("Location: score_user.php?user_id=$user_id&error=" . urlencode($e->getMessage()));
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
?>