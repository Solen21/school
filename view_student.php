<?php
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Error: Invalid Student ID.");
}
$student_id = $_GET['id'];

require_once 'data/db_connect.php';

// Fetch student data along with their username using a JOIN
$sql = "SELECT s.*, u.username 
        FROM students s 
        JOIN users u ON s.user_id = u.user_id 
        WHERE s.student_id = ?";

$student = null;
if ($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $student_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($result) == 1) {
        $student = mysqli_fetch_assoc($result);
    } else {
        die("Error: Student not found.");
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
    <title>View Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .container { max-width: 900px; margin-top: 30px; }
        .list-group-item strong { min-width: 180px; display: inline-block; }
    </style>
</head>
<body>

<div class="container">
    <div class="card shadow-sm">
        <div class="card-header bg-info text-white">
            <h1 class="h4 mb-0"><i class="fas fa-user-graduate me-2"></i>Student Details</h1>
        </div>
        <div class="card-body">
            <?php if ($student): ?>
            <div class="row">
                <div class="col-md-6">
                    <h5>Personal Information</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><strong>Student ID:</strong> <?php echo htmlspecialchars($student['student_id']); ?></li>
                        <li class="list-group-item"><strong>Full Name:</strong> <?php echo htmlspecialchars($student['first_name'] . ' ' . $student['middle_name'] . ' ' . $student['last_name']); ?></li>
                        <li class="list-group-item"><strong>Date of Birth:</strong> <?php echo date('F j, Y', strtotime($student['date_of_birth'])); ?></li>
                        <li class="list-group-item"><strong>Gender:</strong> <?php echo htmlspecialchars(ucfirst($student['gender'])); ?></li>
                        <li class="list-group-item"><strong>Nationality:</strong> <?php echo htmlspecialchars($student['nationality']); ?></li>
                        <li class="list-group-item"><strong>Religion:</strong> <?php echo htmlspecialchars($student['religion']); ?></li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h5>Contact & Address</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><strong>Phone:</strong> <?php echo htmlspecialchars($student['phone']); ?></li>
                        <li class="list-group-item"><strong>Email:</strong> <?php echo htmlspecialchars($student['email']); ?></li>
                        <li class="list-group-item"><strong>Emergency Contact:</strong> <?php echo htmlspecialchars($student['emergency_contact']); ?></li>
                        <li class="list-group-item"><strong>City:</strong> <?php echo htmlspecialchars($student['city']); ?></li>
                        <li class="list-group-item"><strong>Wereda/Kebele:</strong> <?php echo htmlspecialchars($student['wereda'] . ' / ' . $student['kebele']); ?></li>
                    </ul>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-md-6">
                    <h5>Academic Information</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><strong>Grade Level:</strong> <?php echo htmlspecialchars($student['grade_level']); ?></li>
                        <li class="list-group-item"><strong>Stream:</strong> <?php echo htmlspecialchars($student['stream']); ?></li>
                        <li class="list-group-item"><strong>Last School:</strong> <?php echo htmlspecialchars($student['last_school']); ?></li>
                        <li class="list-group-item"><strong>Last Score:</strong> <?php echo htmlspecialchars($student['last_score']); ?></li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h5>System Information</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><strong>Username:</strong> <?php echo htmlspecialchars($student['username']); ?></li>
                        <li class="list-group-item"><strong>Status:</strong> <span class="badge bg-<?php echo $student['status'] == 'active' ? 'primary' : 'secondary'; ?>"><?php echo htmlspecialchars(ucfirst($student['status'])); ?></span></li>
                        <li class="list-group-item"><strong>Registered On:</strong> <?php echo date('F j, Y', strtotime($student['registered_at'])); ?></li>
                    </ul>
                </div>
            </div>
            <?php endif; ?>
        </div>
        <div class="card-footer text-end">
            <a href="students.php" class="btn btn-secondary"><i class="fas fa-arrow-left me-1"></i> Back to List</a>
        </div>
    </div>
</div>

</body>
</html>