<?php
require_once 'data/db_connect.php';

$message = '';
$message_type = '';
$classroom = null;

if (!isset($_GET['id']) && !isset($_POST['classroom_id'])) {
    die("Error: Classroom ID not specified.");
}

$classroom_id = isset($_POST['classroom_id']) ? $_POST['classroom_id'] : $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $capacity = $_POST['capacity'];
    $resources = trim($_POST['resources']);

    $sql = "UPDATE classrooms SET name = ?, capacity = ?, resources = ? WHERE classroom_id = ?";

    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "sisi", $name, $capacity, $resources, $classroom_id);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: classrooms.php?status=updated");
            exit();
        } else {
            $message = "Error updating record: " . mysqli_stmt_error($stmt);
            $message_type = "danger";
        }
        mysqli_stmt_close($stmt);
    }
}

$sql_fetch = "SELECT * FROM classrooms WHERE classroom_id = ?";
if ($stmt_fetch = mysqli_prepare($conn, $sql_fetch)) {
    mysqli_stmt_bind_param($stmt_fetch, "i", $classroom_id);
    mysqli_stmt_execute($stmt_fetch);
    $result = mysqli_stmt_get_result($stmt_fetch);
    if (mysqli_num_rows($result) == 1) {
        $classroom = mysqli_fetch_assoc($result);
    } else {
        die("Classroom not found.");
    }
    mysqli_stmt_close($stmt_fetch);
}
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Classroom</title>
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
        <div class="card-header bg-warning text-dark">
            <h1 class="h4 mb-0"><i class="fas fa-edit me-2"></i>Edit Classroom</h1>
        </div>
        <div class="card-body">
            <?php if ($message): ?>
            <div class="alert alert-<?php echo $message_type; ?>"><?php echo $message; ?></div>
            <?php endif; ?>

            <?php if ($classroom): ?>
            <form action="edit_classroom.php" method="post">
                <input type="hidden" name="classroom_id" value="<?php echo htmlspecialchars($classroom_id); ?>">
                
                <div class="mb-3">
                    <label class="form-label">Classroom Name / Number</label>
                    <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($classroom['name']); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Capacity</label>
                    <input type="number" class="form-control" name="capacity" value="<?php echo htmlspecialchars($classroom['capacity']); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Available Resources</label>
                    <textarea class="form-control" name="resources" rows="3"><?php echo htmlspecialchars($classroom['resources']); ?></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Update Classroom</button>
                <a href="classrooms.php" class="btn btn-secondary"><i class="fas fa-times me-1"></i> Cancel</a>
            </form>
            <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>