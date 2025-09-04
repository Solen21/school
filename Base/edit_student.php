<?php
require_once 'data/db_connect.php';

$message = '';
$message_type = '';
$student = null;

if (!isset($_GET['id']) && !isset($_POST['student_id'])) {
    die("Error: Student ID not specified.");
}

$student_id = isset($_POST['student_id']) ? $_POST['student_id'] : $_GET['id'];

// --- Handle Form Submission (POST request) ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sql = "UPDATE students SET 
                first_name = ?, middle_name = ?, last_name = ?, date_of_birth = ?, 
                gender = ?, nationality = ?, religion = ?, city = ?, wereda = ?, 
                kebele = ?, phone = ?, email = ?, emergency_contact = ?, 
                grade_level = ?, stream = ?, last_school = ?, last_score = ?, 
                last_grade = ?, status = ?
            WHERE student_id = ?";

    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "ssssssssssssssssdisi",
            $_POST['first_name'], $_POST['middle_name'], $_POST['last_name'], $_POST['date_of_birth'],
            $_POST['gender'], $_POST['nationality'], $_POST['religion'], $_POST['city'], $_POST['wereda'],
            $_POST['kebele'], $_POST['phone'], $_POST['email'], $_POST['emergency_contact'],
            $_POST['grade_level'], $_POST['stream'], $_POST['last_school'], $_POST['last_score'],
            $_POST['last_grade'], $_POST['status'],
            $student_id
        );

        if (mysqli_stmt_execute($stmt)) {
            header("Location: students.php?status=updated");
            exit();
        } else {
            $message = "Error updating record: " . mysqli_stmt_error($stmt);
            $message_type = "danger";
        }
        mysqli_stmt_close($stmt);
    }
}

// --- Fetch Student Data for Form (GET request) ---
$sql_fetch = "SELECT * FROM students WHERE student_id = ?";
if ($stmt_fetch = mysqli_prepare($conn, $sql_fetch)) {
    mysqli_stmt_bind_param($stmt_fetch, "i", $student_id);
    mysqli_stmt_execute($stmt_fetch);
    $result = mysqli_stmt_get_result($stmt_fetch);
    if (mysqli_num_rows($result) == 1) {
        $student = mysqli_fetch_assoc($result);
    } else {
        die("Student not found.");
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
    <title>Edit Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .container { max-width: 900px; margin-top: 30px; }
    </style>
</head>
<body>

<div class="container">
    <div class="card shadow-sm">
        <div class="card-header bg-warning text-dark">
            <h1 class="h4 mb-0"><i class="fas fa-user-edit me-2"></i>Edit Student</h1>
        </div>
        <div class="card-body">
            <?php if ($message): ?>
            <div class="alert alert-<?php echo $message_type; ?>"><?php echo $message; ?></div>
            <?php endif; ?>

            <?php if ($student): ?>
            <form action="edit_student.php" method="post" class="row g-3">
                <input type="hidden" name="student_id" value="<?php echo htmlspecialchars($student_id); ?>">
                
                <h5 class="mt-4">Personal Information</h5>
                <div class="col-md-4"><label class="form-label">First Name</label><input type="text" class="form-control" name="first_name" value="<?php echo htmlspecialchars($student['first_name']); ?>" required></div>
                <div class="col-md-4"><label class="form-label">Middle Name</label><input type="text" class="form-control" name="middle_name" value="<?php echo htmlspecialchars($student['middle_name']); ?>" required></div>
                <div class="col-md-4"><label class="form-label">Last Name</label><input type="text" class="form-control" name="last_name" value="<?php echo htmlspecialchars($student['last_name']); ?>" required></div>
                <div class="col-md-4"><label class="form-label">Date of Birth</label><input type="date" class="form-control" name="date_of_birth" value="<?php echo htmlspecialchars($student['date_of_birth']); ?>" required></div>
                <div class="col-md-4">
                    <label class="form-label">Gender</label>
                    <select name="gender" class="form-select" required>
                        <option value="male" <?php if($student['gender'] == 'male') echo 'selected'; ?>>Male</option>
                        <option value="female" <?php if($student['gender'] == 'female') echo 'selected'; ?>>Female</option>
                    </select>
                </div>
                <div class="col-md-4"><label class="form-label">Nationality</label><input type="text" class="form-control" name="nationality" value="<?php echo htmlspecialchars($student['nationality']); ?>" required></div>
                <div class="col-md-4"><label class="form-label">Religion</label><input type="text" class="form-control" name="religion" value="<?php echo htmlspecialchars($student['religion']); ?>" required></div>

                <h5 class="mt-4">Contact & Address</h5>
                <div class="col-md-4"><label class="form-label">Phone</label><input type="tel" class="form-control" name="phone" value="<?php echo htmlspecialchars($student['phone']); ?>" required></div>
                <div class="col-md-4"><label class="form-label">Email</label><input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($student['email']); ?>"></div>
                <div class="col-md-4"><label class="form-label">Emergency Contact</label><input type="tel" class="form-control" name="emergency_contact" value="<?php echo htmlspecialchars($student['emergency_contact']); ?>" required></div>
                <div class="col-md-4"><label class="form-label">City</label><input type="text" class="form-control" name="city" value="<?php echo htmlspecialchars($student['city']); ?>" required></div>
                <div class="col-md-4"><label class="form-label">Wereda</label><input type="text" class="form-control" name="wereda" value="<?php echo htmlspecialchars($student['wereda']); ?>" required></div>
                <div class="col-md-4"><label class="form-label">Kebele</label><input type="text" class="form-control" name="kebele" value="<?php echo htmlspecialchars($student['kebele']); ?>" required></div>

                <h5 class="mt-4">Academic & Status</h5>
                <div class="col-md-3">
                    <label class="form-label">Grade Level</label>
                    <select name="grade_level" class="form-select" required>
                        <?php foreach (['9', '10', '11', '12'] as $grade): ?>
                        <option value="<?php echo $grade; ?>" <?php if($student['grade_level'] == $grade) echo 'selected'; ?>><?php echo $grade; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Stream</label>
                    <select name="stream" class="form-select" required>
                        <?php foreach (['Natural', 'Social', 'Both'] as $stream): ?>
                        <option value="<?php echo $stream; ?>" <?php if($student['stream'] == $stream) echo 'selected'; ?>><?php echo $stream; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3"><label class="form-label">Last School</label><input type="text" class="form-control" name="last_school" value="<?php echo htmlspecialchars($student['last_school']); ?>" required></div>
                <div class="col-md-3"><label class="form-label">Last Grade</label><input type="number" class="form-control" name="last_grade" value="<?php echo htmlspecialchars($student['last_grade']); ?>" required></div>
                <div class="col-md-3"><label class="form-label">Last Score</label><input type="number" step="0.01" class="form-control" name="last_score" value="<?php echo htmlspecialchars($student['last_score']); ?>" required></div>
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select" required>
                        <option value="active" <?php if($student['status'] == 'active') echo 'selected'; ?>>Active</option>
                        <option value="inactive" <?php if($student['status'] == 'inactive') echo 'selected'; ?>>Inactive</option>
                    </select>
                </div>

                <div class="col-12 mt-4">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Update Student</button>
                    <a href="students.php" class="btn btn-secondary"><i class="fas fa-times me-1"></i> Cancel</a>
                </div>
            </form>
            <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>