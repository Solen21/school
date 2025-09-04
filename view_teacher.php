<?php
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Error: Invalid Teacher ID.");
}
$teacher_id = $_GET['id'];

require_once 'data/db_connect.php';

$sql = "SELECT t.*, u.username 
        FROM teachers t 
        JOIN users u ON t.user_id = u.user_id 
        WHERE t.teacher_id = ?";

$teacher = null;
if ($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $teacher_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($result) == 1) {
        $teacher = mysqli_fetch_assoc($result);
    } else {
        die("Error: Teacher not found.");
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
    <title>View Teacher</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .container { max-width: 900px; margin-top: 30px; }
        .list-group-item strong { min-width: 150px; display: inline-block; }
    </style>
</head>
<body>

<div class="container">
    <div class="card shadow-sm">
        <div class="card-header bg-info text-white">
            <h1 class="h4 mb-0"><i class="fas fa-chalkboard-teacher me-2"></i>Teacher Details</h1>
        </div>
        <div class="card-body">
            <?php if ($teacher): ?>
            <div class="row">
                <div class="col-md-6">
                    <h5>Personal Information</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><strong>Teacher ID:</strong> <?php echo htmlspecialchars($teacher['teacher_id']); ?></li>
                        <li class="list-group-item"><strong>Full Name:</strong> <?php echo htmlspecialchars($teacher['first_name'] . ' ' . $teacher['middle_name'] . ' ' . $teacher['last_name']); ?></li>
                        <li class="list-group-item"><strong>Date of Birth:</strong> <?php echo date('F j, Y', strtotime($teacher['date_of_birth'])); ?></li>
                        <li class="list-group-item"><strong>Gender:</strong> <?php echo htmlspecialchars(ucfirst($teacher['gender'])); ?></li>
                        <li class="list-group-item"><strong>Nationality:</strong> <?php echo htmlspecialchars($teacher['nationality']); ?></li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h5>Contact & Address</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><strong>Phone:</strong> <?php echo htmlspecialchars($teacher['phone']); ?></li>
                        <li class="list-group-item"><strong>Email:</strong> <?php echo htmlspecialchars($teacher['email']); ?></li>
                        <li class="list-group-item"><strong>City:</strong> <?php echo htmlspecialchars($teacher['city']); ?></li>
                        <li class="list-group-item"><strong>Wereda/Kebele:</strong> <?php echo htmlspecialchars($teacher['wereda'] . ' / ' . $teacher['kebele']); ?></li>
                    </ul>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-md-6">
                    <h5>System Information</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><strong>Username:</strong> <?php echo htmlspecialchars($teacher['username']); ?></li>
                        <li class="list-group-item"><strong>Hire Date:</strong> <?php echo date('F j, Y', strtotime($teacher['hire_date'])); ?></li>
                    </ul>
                </div>
            </div>
            <?php endif; ?>
        </div>
        <div class="card-footer text-end">
            <a href="teachers.php" class="btn btn-secondary"><i class="fas fa-arrow-left me-1"></i> Back to List</a>
        </div>
    </div>
</div>

</body>
</html>