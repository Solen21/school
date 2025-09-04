<?php
require_once 'data/db_connect.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Error: Invalid Subject ID.");
}
$subject_id = $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['subject_id']) && $_POST['subject_id'] == $subject_id) {
        $sql = "DELETE FROM subjects WHERE subject_id = ?";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "i", $subject_id);
            if (mysqli_stmt_execute($stmt)) {
                header("Location: subjects.php?status=deleted");
                exit();
            } else {
                die("Error deleting record: " . mysqli_error($conn));
            }
            mysqli_stmt_close($stmt);
        }
    }
}

$sql_fetch = "SELECT name FROM subjects WHERE subject_id = ?";
$subject_name = '';
if ($stmt_fetch = mysqli_prepare($conn, $sql_fetch)) {
    mysqli_stmt_bind_param($stmt_fetch, "i", $subject_id);
    mysqli_stmt_execute($stmt_fetch);
    $result = mysqli_stmt_get_result($stmt_fetch);
    if (mysqli_num_rows($result) == 1) {
        $subject = mysqli_fetch_assoc($result);
        $subject_name = $subject['name'];
    } else {
        die("Subject not found.");
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
    <title>Delete Subject</title>
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
            <p>Are you sure you want to delete the subject <strong><?php echo htmlspecialchars($subject_name); ?></strong>?</p>
            <p class="text-danger">This action cannot be undone. Any class assignments related to this subject will also be affected.</p>
            
            <form action="delete_subject.php?id=<?php echo $subject_id; ?>" method="post">
                <input type="hidden" name="subject_id" value="<?php echo $subject_id; ?>">
                <button type="submit" class="btn btn-danger"><i class="fas fa-trash me-1"></i> Yes, Delete Subject</button>
                <a href="subjects.php" class="btn btn-secondary"><i class="fas fa-times me-1"></i> No, Cancel</a>
            </form>
        </div>
    </div>
</div>

</body>
</html>