<?php include '../includes/header.php'; ?>

<div class="container">
    <h1>Add New Judge</h1>
    
    <form action="process_judge.php" method="post">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        
        <div class="form-group">
            <label for="display_name">Display Name:</label>
            <input type="text" class="form-control" id="display_name" name="display_name" required>
        </div>
        
        <button type="submit" class="btn btn-primary">Add Judge</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>