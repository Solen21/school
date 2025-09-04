<?php
require_once 'data/db_connect.php';

$message = '';
$message_type = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $capacity = $_POST['capacity'];
    $resources = trim($_POST['resources']);

    if (empty($name) || empty($capacity)) {
        $message = "Name and Capacity are required.";
        $message_type = "danger";
    } else {
        $sql = "INSERT INTO classrooms (name, capacity, resources) VALUES (?, ?, ?)";
        
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "sis", $name, $capacity, $resources);

            if (mysqli_stmt_execute($stmt)) {
                header("Location: classrooms.php?status=created");
                exit();
            } else {
                $message = "Error: Could not save classroom. " . mysqli_stmt_error($stmt);
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
    <title>Add New Classroom</title>
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
        <div class="card-header bg-dark text-white">
            <h1 class="h4 mb-0"><i class="fas fa-plus me-2"></i>Add New Classroom</h1>
        </div>
        <div class="card-body">
            <?php if ($message): ?>
            <div class="alert alert-<?php echo $message_type; ?>"><?php echo $message; ?></div>
            <?php endif; ?>

            <form action="create_classroom.php" method="post">
                <div class="mb-3">
                    <label for="name" class="form-label">Classroom Name / Number</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="capacity" class="form-label">Capacity</label>
                    <input type="number" class="form-control" id="capacity" name="capacity" required>
                </div>
                <div class="mb-3">
                    <label for="resources" class="form-label">Available Resources</label>
                    <textarea class="form-control" id="resources" name="resources" rows="3" placeholder="e.g., Projector, Whiteboard, 40 Chairs"></textarea>
                </div>
                <button type="submit" class="btn btn-primary"><i class="fas fa-check me-1"></i> Save Classroom</button>
                <a href="classrooms.php" class="btn btn-secondary"><i class="fas fa-times me-1"></i> Cancel</a>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>