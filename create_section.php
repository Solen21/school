<?php
require_once 'data/db_connect.php';

$message = '';
$message_type = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $grade_level = $_POST['grade_level'];
    $stream = $_POST['stream'];
    $capacity = $_POST['capacity'];
    $shift = $_POST['shift'];

    if (empty($grade_level) || empty($stream) || empty($capacity) || empty($shift)) {
        $message = "All fields are required.";
        $message_type = "danger";
    } else {
        $sql = "INSERT INTO sections (grade_level, stream, capacity, shift) VALUES (?, ?, ?, ?)";
        
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "isis", $grade_level, $stream, $capacity, $shift);

            if (mysqli_stmt_execute($stmt)) {
                header("Location: sections.php?status=created");
                exit();
            } else {
                $message = "Error: Could not save section. " . mysqli_stmt_error($stmt);
                $message_type = "danger";
            }
            mysqli_stmt_close($stmt);
        }
    }
    mysqli_close($conn);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Section</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .container { max-width: 700px; margin-top: 50px; }
    </style>
</head>
<body>

<div class="container">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h1 class="h4 mb-0"><i class="fas fa-plus me-2"></i>Add New Section</h1>
        </div>
        <div class="card-body">
            <?php if ($message): ?>
            <div class="alert alert-<?php echo $message_type; ?>"><?php echo $message; ?></div>
            <?php endif; ?>

            <form action="create_section.php" method="post">
                <div class="mb-3">
                    <label for="grade_level" class="form-label">Grade Level</label>
                    <select class="form-select" id="grade_level" name="grade_level" required>
                        <option value="" selected disabled>Select grade...</option>
                        <option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="stream" class="form-label">Stream</label>
                    <select class="form-select" id="stream" name="stream" required>
                        <option value="" selected disabled>Select stream...</option>
                        <option value="Natural">Natural</option><option value="Social">Social</option>
                    </select>
                </div>
                <div class="mb-3"><label for="capacity" class="form-label">Capacity</label><input type="number" class="form-control" id="capacity" name="capacity" required></div>
                <div class="mb-3">
                    <label for="shift" class="form-label">Shift</label>
                    <select class="form-select" id="shift" name="shift" required>
                        <option value="" selected disabled>Select shift...</option>
                        <option value="Morning">Morning</option><option value="Afternoon">Afternoon</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary"><i class="fas fa-check me-1"></i> Save Section</button>
                <a href="sections.php" class="btn btn-secondary"><i class="fas fa-times me-1"></i> Cancel</a>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>