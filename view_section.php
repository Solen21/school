<?php
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Error: Invalid Section ID.");
}
$section_id = $_GET['id'];

require_once 'data/db_connect.php';

$sql = "SELECT * FROM sections WHERE section_id = ?";

$section = null;
if ($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $section_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($result) == 1) {
        $section = mysqli_fetch_assoc($result);
    } else {
        die("Error: Section not found.");
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
    <title>View Section</title>
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
        <div class="card-header bg-primary text-white">
            <h1 class="h4 mb-0"><i class="fas fa-door-open me-2"></i>Section Details</h1>
        </div>
        <div class="card-body">
            <?php if ($section): ?>
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Section ID:</strong> <?php echo htmlspecialchars($section['section_id']); ?></li>
                <li class="list-group-item"><strong>Grade Level:</strong> <?php echo htmlspecialchars($section['grade_level']); ?></li>
                <li class="list-group-item"><strong>Stream:</strong> <?php echo htmlspecialchars($section['stream']); ?></li>
                <li class="list-group-item"><strong>Capacity:</strong> <?php echo htmlspecialchars($section['capacity']); ?></li>
                <li class="list-group-item"><strong>Shift:</strong> <?php echo htmlspecialchars($section['shift']); ?></li>
            </ul>
            <?php endif; ?>
        </div>
        <div class="card-footer text-end">
            <a href="sections.php" class="btn btn-secondary"><i class="fas fa-arrow-left me-1"></i> Back to List</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>