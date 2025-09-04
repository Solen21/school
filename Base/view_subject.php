<?php
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Error: Invalid Subject ID.");
}
$subject_id = $_GET['id'];

require_once 'data/db_connect.php';

$sql = "SELECT * FROM subjects WHERE subject_id = ?";

$subject = null;
if ($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $subject_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($result) == 1) {
        $subject = mysqli_fetch_assoc($result);
    } else {
        die("Error: Subject not found.");
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
    <title>View Subject</title>
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
        <div class="card-header bg-secondary text-white">
            <h1 class="h4 mb-0"><i class="fas fa-book me-2"></i>Subject Details</h1>
        </div>
        <div class="card-body">
            <?php if ($subject): ?>
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Subject ID:</strong> <?php echo htmlspecialchars($subject['subject_id']); ?></li>
                <li class="list-group-item"><strong>Name:</strong> <?php echo htmlspecialchars($subject['name']); ?></li>
                <li class="list-group-item"><strong>Code:</strong> <?php echo htmlspecialchars($subject['code']); ?></li>
                <li class="list-group-item"><strong>Grade Level:</strong> <?php echo htmlspecialchars($subject['grade_level']); ?></li>
                <li class="list-group-item"><strong>Stream:</strong> <?php echo htmlspecialchars($subject['stream']); ?></li>
                <li class="list-group-item">
                    <strong>Description:</strong><br>
                    <?php echo nl2br(htmlspecialchars($subject['description'] ?: 'N/A')); ?>
                </li>
            </ul>
            <?php endif; ?>
        </div>
        <div class="card-footer text-end">
            <a href="subjects.php" class="btn btn-secondary"><i class="fas fa-arrow-left me-1"></i> Back to List</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>