<?php
require_once 'data/db_connect.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Error: Invalid Teacher ID.");
}
$teacher_id = $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['teacher_id']) && $_POST['teacher_id'] == $teacher_id) {
        // Because of ON DELETE CASCADE, deleting the teacher will also delete the user.
        $sql = "DELETE FROM teachers WHERE teacher_id = ?";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "i", $teacher_id);
            if (mysqli_stmt_execute($stmt)) {
                header("Location: teachers.php?status=deleted");
                exit();
            } else {
                die("Error deleting record: " . mysqli_error($conn));
            }
            mysqli_stmt_close($stmt);
        }
    }
}

$sql_fetch = "SELECT first_name, middle_name, last_name FROM teachers WHERE teacher_id = ?";
$teacher_name = '';
if ($stmt_fetch = mysqli_prepare($conn, $sql_fetch)) {
    mysqli_stmt_bind_param($stmt_fetch, "i", $teacher_id);
    mysqli_stmt_execute($stmt_fetch);
    $result = mysqli_stmt_get_result($stmt_fetch);
    if (mysqli_num_rows($result) == 1) {
        $teacher = mysqli_fetch_assoc($result);
        $teacher_name = $teacher['first_name'] . ' ' . $teacher['middle_name'] . ' ' . $teacher['last_name'];
    } else {
        die("Teacher not found.");
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
    <title>Delete Teacher</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .container { max-width: 600px; margin-top: 50px; }
    </style>
</head>
<body>

<div class="container">
    <div class="card border-danger shadow-sm">
        <div class="card-header bg-danger text-white">
            <h1 class="h4 mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Confirm Deletion</h1>
        </div>
        <div class="card-body">
            <p>Are you sure you want to delete the teacher <strong><?php echo htmlspecialchars($teacher_name); ?></strong>?</p>
            <p class="text-danger">This action cannot be undone and will also delete their associated user account.</p>
            
            <form action="delete_teacher.php?id=<?php echo $teacher_id; ?>" method="post">
                <input type="hidden" name="teacher_id" value="<?php echo $teacher_id; ?>">
                <button type="submit" class="btn btn-danger"><i class="fas fa-trash me-1"></i> Yes, Delete Teacher</button>
                <a href="teachers.php" class="btn btn-secondary"><i class="fas fa-times me-1"></i> No, Cancel</a>
            </form>
        </div>
    </div>
</div>

</body>
</html>