<!-- 5. ENHANCED JUDGE DASHBOARD (judges/index.php) -->
<?php 
$page_title = "Judge Dashboard";
$css_path = "../";
include '../includes/header.php'; 
include '../includes/db_connect.php'; 
?>

<div class="page-header">
    <div class="container">
        <h1>⚖️ Judge Panel</h1>
        <p class="subtitle">Score participants and manage evaluations</p>
    </div>
</div>

<div class="container">
    <!-- Navigation -->
    <div class="nav-pills">
        <a href="index.php" class="active">Participants</a>
        <a href="my_scores.php">My Scores</a>
        <a href="../index.php">View Scoreboard</a>
        <a href="../admin/">Admin Panel</a>
    </div>

    <!-- Judge Selection -->
    <div class="card mb-4">
        <div class="card-header">
            <h3>👤 Judge Selection</h3>
        </div>
        <div class="card-body">
            <form id="judge-selector" class="d-flex gap-4 align-items-end">
                <div class="form-group mb-0" style="min-width: 200px;">
                    <label for="current_judge" class="form-label">Select Judge</label>
                    <select class="form-control form-select" id="current_judge" name="current_judge">
                        <option value="">Choose a judge...</option>
                        <?php
                        $stmt = $pdo->query("SELECT id, display_name FROM judges ORDER BY display_name");
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<option value='{$row['id']}'>" . htmlspecialchars($row['display_name']) . "</option>";
                        }
                        ?>
                    </select>
                </div>
            </form>
        </div>
    </div>

    <!-- Participants List -->
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h2>🏃‍♂️ Participants</h2>
                <div class="d-flex align-items-center gap-2">
                    <span class="text-sm text-gray-500">Total:</span>
                    <span class="stat-number" style="font-size: 1.2rem;">
                        <?php echo $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn(); ?>
                    </span>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-container">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Participant Name</th>
                            <th class="text-center">My Score</th>
                            <th class="text-center">Total Scores</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="participants-table">
                        <?php
                        $stmt = $pdo->query("
                            SELECT 
                                u.id, 
                                u.name,
                                COUNT(s.id) as total_scores,
                                AVG(s.score) as avg_score
                            FROM users u
                            LEFT JOIN scores s ON u.id = s.user_id
                            GROUP BY u.id, u.name
                            ORDER BY u.name
                        ");
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<tr data-user-id='{$row['id']}'>
                                <td><span class='rank-badge'>{$row['id']}</span></td>
                                <td><strong>" . htmlspecialchars($row['name']) . "</strong></td>
                                <td class='text-center'>
                                    <span class='my-score-display' data-user-id='{$row['id']}'>-</span>
                                </td>
                                <td class='text-center'>
                                    <span class='score-display'>{$row['total_scores']}</span>
                                    " . ($row['avg_score'] ? "<br><small class='text-gray-500'>Avg: " . number_format($row['avg_score'], 1) . "</small>" : "") . "
                                </td>
                                <td class='text-center'>
                                    <button class='btn btn-sm btn-primary score-btn' 
                                            data-user-id='{$row['id']}' 
                                            data-user-name='" . htmlspecialchars($row['name']) . "' 
                                            disabled>
                                        <span>📝</span> Score
                                    </button>
                                </td>
                            </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Score Modal -->
<div id="score-modal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modal-title">Score Participant</h3>
            <button class="modal-close">&times;</button>
        </div>
        <div class="modal-body">
            <form id="score-form">
                <input type="hidden" id="modal-user-id" name="user_id">
                <input type="hidden" id="modal-judge-id" name="judge_id">
                
                <div class="form-group">
                    <label for="modal-score" class="form-label">Score (1-100) *</label>
                    <input type="number" class="form-control" id="modal-score" name="score" 
                           min="1" max="100" required>
                    <div class="score-range">
                        <small class="text-gray-500">
                            <span>1-20: Poor</span> | 
                            <span>21-40: Fair</span> | 
                            <span>41-60: Good</span> | 
                            <span>61-80: Very Good</span> | 
                            <span>81-100: Excellent</span>
                        </small>
                    </div>
                </div>
                
                <div class="d-flex gap-2 justify-content-end">
                    <button type="button" class="btn btn-secondary modal-close">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <span>💾</span> Submit Score
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Modal Styles */
.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
}

.modal-content {
    background: white;
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-xl);
    max-width: 500px;
    width: 90%;
    max-height: 90vh;
    overflow-y: auto;
}

.modal-header {
    padding: var(--spacing-6);
    border-bottom: 1px solid var(--gray-200);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h3 {
    margin: 0;
}

.modal-close {
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    color: var(--gray-500);
}

.modal-close:hover {
    color: var(--gray-700);
}

.modal-body {
    padding: var(--spacing-6);
}

.score-range {
    margin-top: var(--spacing-2);
    padding: var(--spacing-2);
    background: var(--gray-50);
    border-radius: var(--radius-md);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const judgeSelector = document.getElementById('current_judge');
    const scoreButtons = document.querySelectorAll('.score-btn');
    const scoreModal = document.getElementById('score-modal');
    const scoreForm = document.getElementById('score-form');
    const modalCloses = document.querySelectorAll('.modal-close');
    
    // Judge selection handler
    judgeSelector.addEventListener('change', function() {
        const judgeId = this.value;
        
        // Enable/disable score buttons
        scoreButtons.forEach(btn => {
            btn.disabled = !judgeId;
        });
        
        // Load existing scores for this judge
        if (judgeId) {
            loadExistingScores(judgeId);
        } else {
            clearScoreDisplays();
        }
    });
    
    // Score button handlers
    scoreButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const userId = this.dataset.userId;
            const userName = this.dataset.userName;
            const judgeId = judgeSelector.value;
            
            if (!judgeId) {
                alert('Please select a judge first');
                return;
            }
            
            // Set modal data
            document.getElementById('modal-title').textContent = `Score: ${userName}`;
            document.getElementById('modal-user-id').value = userId;
            document.getElementById('modal-judge-id').value = judgeId;
            
            // Load existing score if any
            const existingScore = document.querySelector(`[data-user-id="${userId}"] .my-score-display`).textContent;
            if (existingScore !== '-') {
                document.getElementById('modal-score').value = existingScore;
            } else {
                document.getElementById('modal-score').value = '';
            }
            
            scoreModal.style.display = 'flex';
            document.getElementById('modal-score').focus();
        });
    });
    
    // Modal close handlers
    modalCloses.forEach(close => {
        close.addEventListener('click', function() {
            scoreModal.style.display = 'none';
        });
    });
    
    // Close modal on outside click
    scoreModal.addEventListener('click', function(e) {
        if (e.target === scoreModal) {
            scoreModal.style.display = 'none';
        }
    });
    
    // Score form submission
    scoreForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch('process_score.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update the score display
                const userId = formData.get('user_id');
                const score = formData.get('score');
                document.querySelector(`[data-user-id="${userId}"] .my-score-display`).textContent = score;
                
                scoreModal.style.display = 'none';
                
                // Show success message
                showAlert('Score submitted successfully!', 'success');
            } else {
                showAlert(data.message || 'Error submitting score', 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Error submitting score', 'danger');
        });
    });
    
    function loadExistingScores(judgeId) {
        fetch(`get_judge_scores.php?judge_id=${judgeId}`)
            .then(response => response.json())
            .then(data => {
                // Clear all displays first
                clearScoreDisplays();
                
                // Set existing scores
                data.forEach(score => {
                    const display = document.querySelector(`[data-user-id="${score.user_id}"] .my-score-display`);
                    if (display) {
                        display.textContent = score.score;
                    }
                });
            })
            .catch(error => {
                console.error('Error loading scores:', error);
            });
    }
    
    function clearScoreDisplays() {
        document.querySelectorAll('.my-score-display').forEach(display => {
            display.textContent = '-';
        });
    }
    
    function showAlert(message, type) {
        const alert = document.createElement('div');
        alert.className = `alert alert-${type} fade-in`;
        alert.innerHTML = message;
        alert.style.position = 'fixed';
        alert.style.top = '20px';
        alert.style.right = '20px';
        alert.style.zIndex = '9999';
        alert.style.minWidth = '300px';
        
        document.body.appendChild(alert);
        
        setTimeout(() => {
            alert.remove();
        }, 3000);
    }
});
</script>

<?php include '../includes/footer.php'; ?>