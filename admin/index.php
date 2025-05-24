<?php 
$page_title = "Admin Dashboard";
$css_path = "../"; // Explicitly set the path
include '../includes/header.php'; 
include '../includes/db_connect.php'; 
?>

<div class="page-header">
    <div class="container">
        <h1>⚙️ Admin Dashboard</h1>
        <p class="subtitle">Manage judges and system settings</p>
    </div>
</div>

<div class="container">
    <!-- Navigation -->
    <div class="nav-pills">
        <a href="index.php" class="active">Dashboard</a>
        <a href="add_judge.php">Add Judge</a>
        <a href="../index.php">View Scoreboard</a>
        <a href="../judges/">Judge Panel</a>
    </div>

    <!-- Quick Stats -->
    <div class="stats-grid">
        <?php
        $total_judges = $pdo->query("SELECT COUNT(*) FROM judges")->fetchColumn();
        $total_participants = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
        $total_scores = $pdo->query("SELECT COUNT(*) FROM scores")->fetchColumn();
        $completion_rate = $total_participants > 0 ? round(($total_scores / ($total_judges * $total_participants)) * 100, 1) : 0;
        ?>
        

        <div class="stat-card">
            <span class="stat-number"><?php echo $total_judges; ?></span>
            <div class="stat-label">Active Judges</div>
        </div>
        
        <div class="stat-card">
            <span class="stat-number"><?php echo $total_participants; ?></span>
            <div class="stat-label">Participants</div>
        </div>
        
        <div class="stat-card">
            <span class="stat-number"><?php echo $total_scores; ?></span>
            <div class="stat-label">Scores Submitted</div>
        </div>
        
        <div class="stat-card">
            <span class="stat-number"><?php echo $completion_rate; ?>%</span>
            <div class="stat-label">Completion Rate</div>
        </div>
    </div>

    <!-- Judges Management -->
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h2>👨‍⚖️ Judges Management</h2>
                <a href="add_judge.php" class="btn btn-primary">
                    <span>➕</span> Add New Judge
                </a>
            </div>
        </div>
        <div class="card-body">
            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success">
                    ✅ Judge added successfully!
                </div>
            <?php endif; ?>
            
            <div class="table-container">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Display Name</th>
                            <th>Scores Given</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $stmt = $pdo->query("
                            SELECT j.*, COUNT(s.id) as score_count 
                            FROM judges j 
                            LEFT JOIN scores s ON j.id = s.judge_id 
                            GROUP BY j.id 
                            ORDER BY j.id
                        ");
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<tr>
                                <td><span class='rank-badge'>{$row['id']}</span></td>
                                <td><strong>" . htmlspecialchars($row['username']) . "</strong></td>
                                <td>" . htmlspecialchars($row['display_name']) . "</td>
                                <td><span class='score-display'>{$row['score_count']}</span></td>
                                <td class='text-center'>
                                    <a href='edit_judge.php?id={$row['id']}' class='btn btn-sm btn-secondary'>Edit</a>
                                    <a href='delete_judge.php?id={$row['id']}' class='btn btn-sm btn-danger' 
                                       onclick='return confirm(\"Are you sure you want to delete this judge?\")'>Delete</a>
                                </td>
                            </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="card">
        <div class="card-header">
            <h3>📊 Recent Scoring Activity</h3>
        </div>
        <div class="card-body">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Judge</th>
                            <th>Participant</th>
                            <th>Score</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $stmt = $pdo->query("
                            SELECT j.display_name, u.name, s.score, s.created_at
                            FROM scores s
                            JOIN judges j ON s.judge_id = j.id
                            JOIN users u ON s.user_id = u.id
                            ORDER BY s.created_at DESC
                            LIMIT 10
                        ");
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<tr>
                                <td>" . htmlspecialchars($row['display_name']) . "</td>
                                <td>" . htmlspecialchars($row['name']) . "</td>
                                <td><span class='score-display'>{$row['score']}</span></td>
                                <td class='text-sm text-gray-500'>" . date('M j, Y g:i A', strtotime($row['created_at'])) . "</td>
                            </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>