<?php 
include '../includes/header.php';
include '../includes/db_connect.php';

if (!isset($_GET['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_GET['user_id'];
$stmt = $pdo->prepare("SELECT name FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header("Location: index.php");
    exit();
}
?>

<div class="container">
    <h1>Score Participant: <?php echo htmlspecialchars($user['name']); ?></h1>
    
    <form action="process_score.php" method="post">
        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
        
        <div class="form-group">
            <label for="judge_id">Judge:</label>
            <select class="form-control" id="judge_id" name="judge_id" required>
                <?php
                $stmt = $pdo->query("SELECT id, display_name FROM judges");
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<option value='{$row['id']}'>{$row['display_name']}</option>";
                }
                ?>
            </select>
        </div>
        
        <div class="form-group">
            <label for="score">Score (1-100):</label>
            <input type="number" class="form-control" id="score" name="score" min="1" max="100" required>
        </div>
        
        <button type="submit" class="btn btn-primary">Submit Score</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>