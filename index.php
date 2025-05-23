<?php include 'includes/header.php'; ?>
<?php include 'includes/db_connect.php'; ?>

<div class="container">
    <h1 class="text-center">Public Scoreboard</h1>
    
    <div class="card">
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Participant</th>
                        <th>Total Score</th>
                    </tr>
                </thead>
                <tbody id="scoreboard">
                    <?php
                    $stmt = $pdo->query("
                        SELECT u.id, u.name, COALESCE(SUM(s.score), 0) as total_score
                        FROM users u
                        LEFT JOIN scores s ON u.id = s.user_id
                        GROUP BY u.id, u.name
                        ORDER BY total_score DESC
                    ");
                    
                    $rank = 1;
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $highlight = '';
                        if ($rank == 1) {
                            $highlight = 'class="table-success"';
                        } elseif ($rank <= 3) {
                            $highlight = 'class="table-primary"';
                        }
                        
                        echo "<tr $highlight>
                            <td>$rank</td>
                            <td>{$row['name']}</td>
                            <td>{$row['total_score']}</td>
                        </tr>";
                        
                        $rank++;
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="assets/js/auto-refresh.js"></script>
<?php include 'includes/footer.php'; ?>