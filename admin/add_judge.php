<?php 
$page_title = "Add New Judge";
$css_path = "../";
include '../includes/header.php'; 
?>

<div class="page-header">
    <div class="container">
        <h1>➕ Add New Judge</h1>
        <p class="subtitle">Create a new judge account for the system</p>
    </div>
</div>

<div class="container">
    <!-- Navigation -->
    <div class="nav-pills">
        <a href="index.php">Dashboard</a>
        <a href="add_judge.php" class="active">Add Judge</a>
        <a href="../index.php">View Scoreboard</a>
        <a href="../judges/">Judge Panel</a>
    </div>

    <div class="card">
        <div class="card-header">
            <h2>👨‍⚖️ Judge Information</h2>
        </div>
        <div class="card-body">
            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger">
                    ❌ Error: <?php echo htmlspecialchars($_GET['error']); ?>
                </div>
            <?php endif; ?>
            
            <form action="process_judge.php" method="post" class="fade-in">
                <div class="form-group">
                    <label for="username" class="form-label">Username *</label>
                    <input type="text" class="form-control" id="username" name="username" 
                           placeholder="Enter unique username" required maxlength="50">
                    <small class="text-gray-500">This will be used for judge identification</small>
                </div>
                
                <div class="form-group">
                    <label for="display_name" class="form-label">Display Name *</label>
                    <input type="text" class="form-control" id="display_name" name="display_name" 
                           placeholder="Enter full display name" required maxlength="100">
                    <small class="text-gray-500">This name will appear in the system</small>
                </div>
                
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <span>💾</span> Add Judge
                    </button>
                    <a href="index.php" class="btn btn-secondary">
                        <span>↩️</span> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>