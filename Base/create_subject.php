<?php
require_once 'data/db_connect.php';

$message = '';
$message_type = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $code = trim($_POST['code']);
    $grade_level = $_POST['grade_level'];
    $stream = $_POST['stream'];
    $description = trim($_POST['description']);

    if (empty($name) || empty($code) || empty($grade_level)) {
        $message = "Name, Code, and Grade Level are required.";
        $message_type = "danger";
    } else {
        $sql = "INSERT INTO subjects (name, code, grade_level, stream, description) VALUES (?, ?, ?, ?, ?)";
        
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "ssiss", $name, $code, $grade_level, $stream, $description);

            if (mysqli_stmt_execute($stmt)) {
                header("Location: subjects.php?status=created");
                exit();
            } else {
                if (mysqli_errno($conn) == 1062) {
                    $message = "Error: A subject with this code already exists.";
                } else {
                    $message = "Error: Could not save subject. " . mysqli_stmt_error($stmt);
                }
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
    <title>Add New Subject</title>
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
        <div class="card-header bg-secondary text-white">
            <h1 class="h4 mb-0"><i class="fas fa-plus me-2"></i>Add New Subject</h1>
        </div>
        <div class="card-body">
            <?php if ($message): ?>
            <div class="alert alert-<?php echo $message_type; ?>"><?php echo $message; ?></div>
            <?php endif; ?>

            <form action="create_subject.php" method="post">
                <div class="mb-3"><label for="name" class="form-label">Subject Name</label><input type="text" class="form-control" id="name" name="name" required></div>
                <div class="mb-3"><label for="code" class="form-label">Subject Code</label><input type="text" class="form-control" id="code" name="code" required></div>
                <div class="mb-3"><label for="grade_level" class="form-label">Grade Level</label><input type="number" class="form-control" id="grade_level" name="grade_level" min="1" max="12" required></div>
                <div class="mb-3">
                    <label for="stream" class="form-label">Stream</label>
                    <select class="form-select" id="stream" name="stream">
                        <option value="Both" selected>Both</option>
                        <option value="Natural">Natural</option>
                        <option value="Social">Social</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                </div>
                <button type="submit" class="btn btn-primary"><i class="fas fa-check me-1"></i> Save Subject</button>
                <a href="subjects.php" class="btn btn-secondary"><i class="fas fa-times me-1"></i> Cancel</a>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>