<?php
// Check if an ID is provided in the URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Error: Invalid User ID.");
}

$user_id = $_GET['id'];

// Include the database connection
require_once 'data/db_connect.php';

// Fetch the user's data using a prepared statement
$sql = "SELECT user_id, username, role, created_at FROM users WHERE user_id = ?";

$user = null;
if ($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        if (mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);
        } else {
            die("Error: User not found.");
        }
    } else {
        die("Error executing query.");
    }
    mysqli_stmt_close($stmt);
}
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View User</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .container { max-width: 700px; margin-top: 50px; }
        .list-group-item strong { min-width: 120px; display: inline-block; }
    </style>
</head>
<body>

<div class="container">
    <div class="card shadow-sm">
        <div class="card-header bg-info text-white">
            <h1 class="h4 mb-0"><i class="fas fa-user me-2"></i>User Details</h1>
        </div>
        <div class="card-body">
            <?php if ($user): ?>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <strong>User ID:</strong> <?php echo htmlspecialchars($user['user_id']); ?>
                </li>
                <li class="list-group-item">
                    <strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?>
                </li>
                <li class="list-group-item">
                    <strong>Role:</strong> <?php echo htmlspecialchars(ucfirst($user['role'])); ?>
                </li>
                <li class="list-group-item">
                    <strong>Date Created:</strong> <?php echo date('F j, Y, g:i a', strtotime($user['created_at'])); ?>
                </li>
            </ul>
            <?php endif; ?>
        </div>
        <div class="card-footer text-end">
            <a href="users.php" class="btn btn-secondary"><i class="fas fa-arrow-left me-1"></i> Back to List</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>