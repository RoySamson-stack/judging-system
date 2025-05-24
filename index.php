<?php 
$page_title = "Public Scoreboard";
include 'includes/header.php';
include 'includes/db_connect.php'; 
?>

<div class="page-header">
    <div class="container">
        <h1>🏆 Live Scoreboard</h1>
        <p class="subtitle">Real-time competition results</p>
    </div>
</div>

<div class="container">
    <!-- Stats Overview -->
    <div class="stats-grid">
        <?php
        // Get statistics
        $total_participants = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
        $total_scores = $pdo->query("SELECT COUNT(*) FROM scores")->fetchColumn();
        $total_judges = $pdo->query("SELECT COUNT(*) FROM judges")->fetchColumn();
        $avg_score = $pdo->query("SELECT AVG(score) FROM scores")->fetchColumn();
        ?>
        
        <div class="stat-card fade-in">
            <span class="stat-number"><?php echo $total_participants; ?></span>
            <div class="stat-label">Participants</div>
        </div>
        
        <div class="stat-card fade-in">
            <span class="stat-number"><?php echo $total_judges; ?></span>
            <div class="stat-label">Judges</div>
        </div>
        
        <div class="stat-card fade-in">
            <span class="stat-number"><?php echo $total_scores; ?></span>
            <div class="stat-label">Total Scores</div>
        </div>
        
        <div class="stat-card fade-in">
            <span class="stat-number"><?php echo number_format($avg_score, 1); ?></span>
            <div class="stat-label">Average Score</div>
        </div>
    </div>

    <!-- Main Scoreboard -->
    <div class="card slide-up">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0">🥇 Competition Rankings</h2>
                <div class="d-flex align-items-center gap-2">
                    <span class="text-sm text-gray-500">Last updated:</span>
                    <span id="last-updated" class="text-sm font-weight-bold"></span>
                    <div class="loading" id="refresh-indicator" style="display: none;"></div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 80px;">Rank</th>
                            <th>Participant</th>
                            <th style="width: 120px;" class="text-center">Total Score</th>
                            <th style="width: 100px;" class="text-center">Scores</th>
                        </tr>
                    </thead>
                    <tbody id="scoreboard">
                        <?php
                        $stmt = $pdo->query("
                            SELECT 
                                u.id, 
                                u.name, 
                                COALESCE(SUM(s.score), 0) as total_score,
                                COUNT(s.score) as score_count
                            FROM users u
                            LEFT JOIN scores s ON u.id = s.user_id
                            GROUP BY u.id, u.name
                            ORDER BY total_score DESC
                        ");
                        
                        $rank = 1;
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            $rank_class = '';
                            $rank_badge_class = 'rank-badge';
                            
                            if ($rank == 1) {
                                $rank_class = 'rank-1';
                                $rank_badge_class .= ' rank-1';
                            } elseif ($rank == 2) {
                                $rank_class = 'rank-2';
                                $rank_badge_class .= ' rank-2';
                            } elseif ($rank == 3) {
                                $rank_class = 'rank-3';
                                $rank_badge_class .= ' rank-3';
                            } elseif ($rank <= 5) {
                                $rank_class = 'top-5';
                            }
                            
                            echo "<tr class='$rank_class'>
                                <td><span class='$rank_badge_class'>$rank</span></td>
                                <td class='font-weight-bold'>" . htmlspecialchars($row['name']) . "</td>
                                <td class='text-center'><span class='score-display'>{$row['total_score']}</span></td>
                                <td class='text-center'><span class='text-sm text-gray-500'>{$row['score_count']} scores</span></td>
                            </tr>";
                            
                            $rank++;
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer text-center">
            <small class="text-gray-500">📊 Scoreboard updates automatically every 10 seconds</small>
        </div>
    </div>
</div>

<script>
// Enhanced auto-refresh
document.addEventListener('DOMContentLoaded', function() {
    updateLastRefreshTime();
    setInterval(refreshScoreboard, 10000);
    
    function updateLastRefreshTime() {
        const now = new Date();
        document.getElementById('last-updated').textContent = now.toLocaleTimeString();
    }
    
    function refreshScoreboard() {
        const indicator = document.getElementById('refresh-indicator');
        indicator.style.display = 'block';
        
        setTimeout(() => {
            fetch(window.location.href)
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newScoreboard = doc.getElementById('scoreboard').innerHTML;
                    document.getElementById('scoreboard').innerHTML = newScoreboard;
                    updateLastRefreshTime();
                })
                .catch(error => console.error('Error refreshing scoreboard:', error))
                .finally(() => {
                    indicator.style.display = 'none';
                });
        }, 500);
    }
});
</script>

<?php include 'includes/footer.php'; ?>