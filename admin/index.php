<?php include '../includes/header.php'; ?>
<?php include '../includes/db_connect.php'; ?>

<div class="container">
    <h1>Admin Dashboard</h1>
    
    <div class="card">
        <div class="card-body">
            <h2>Judges Management</h2>
            <a href="add_judge.php" class="btn btn-primary">Add New Judge</a>
            
            <h3 class="mt-4">Current Judges</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Display Name</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt = $pdo->query("SELECT * FROM judges");
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['username']}</td>
                            <td>{$row['display_name']}</td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>