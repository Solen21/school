<?php
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Error: Invalid Guardian ID.");
}
$guardian_id = $_GET['id'];

require_once 'data/db_connect.php';

$sql = "SELECT g.*, CONCAT(s.first_name, ' ', s.middle_name, ' ', s.last_name) AS student_name
        FROM guardians g
        LEFT JOIN students s ON g.student_id = s.student_id
        WHERE g.guardian_id = ?";

$guardian = null;
if ($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $guardian_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($result) == 1) {
        $guardian = mysqli_fetch_assoc($result);
    } else {
        die("Error: Guardian not found.");
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
    <title>View Guardian</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .container { max-width: 700px; margin-top: 50px; }
        .list-group-item strong { min-width: 150px; display: inline-block; }
    </style>
</head>
<body>

<div class="container">
    <div class="card shadow-sm">
        <div class="card-header bg-info text-white">
            <h1 class="h4 mb-0"><i class="fas fa-user-shield me-2"></i>Guardian Details</h1>
        </div>
        <div class="card-body">
            <?php if ($guardian): ?>
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Guardian ID:</strong> <?php echo htmlspecialchars($guardian['guardian_id']); ?></li>
                <li class="list-group-item"><strong>Guardian Name:</strong> <?php echo htmlspecialchars($guardian['name']); ?></li>
                <li class="list-group-item"><strong>Relation:</strong> <?php echo htmlspecialchars($guardian['relation']); ?></li>
                <li class="list-group-item"><strong>Phone:</strong> <?php echo htmlspecialchars($guardian['phone']); ?></li>
                <li class="list-group-item"><strong>Email:</strong> <?php echo htmlspecialchars($guardian['email'] ?: 'N/A'); ?></li>
                <li class="list-group-item"><strong>Associated Student:</strong> <?php echo htmlspecialchars($guardian['student_name'] ?? 'N/A'); ?></li>
            </ul>
            <?php endif; ?>
        </div>
        <div class="card-footer text-end">
            <a href="guardians.php" class="btn btn-secondary"><i class="fas fa-arrow-left me-1"></i> Back to List</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>