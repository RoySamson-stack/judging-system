<?php include '../includes/header.php'; ?>
<?php include '../includes/db_connect.php'; ?>

<div class="container">
    <h1>Judge Dashboard</h1>
    
    <div class="card">
        <div class="card-body">
            <h2>Participants</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt = $pdo->query("SELECT * FROM users");
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['name']}</td>
                            <td><a href='score_user.php?user_id={$row['id']}' class='btn btn-sm btn-primary'>Score</a></td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>