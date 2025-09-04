<?php
require_once 'data/db_connect.php';

$message = '';
$message_type = '';
$teacher = null;

if (!isset($_GET['id']) && !isset($_POST['teacher_id'])) {
    die("Error: Teacher ID not specified.");
}

$teacher_id = isset($_POST['teacher_id']) ? $_POST['teacher_id'] : $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sql = "UPDATE teachers SET 
                first_name = ?, middle_name = ?, last_name = ?, date_of_birth = ?, 
                gender = ?, nationality = ?, religion = ?, city = ?, wereda = ?, 
                kebele = ?, phone = ?, email = ?, hire_date = ?
            WHERE teacher_id = ?";

    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "sssssssssssssi",
            $_POST['first_name'], $_POST['middle_name'], $_POST['last_name'], $_POST['date_of_birth'],
            $_POST['gender'], $_POST['nationality'], $_POST['religion'], $_POST['city'], $_POST['wereda'],
            $_POST['kebele'], $_POST['phone'], $_POST['email'], $_POST['hire_date'],
            $teacher_id
        );

        if (mysqli_stmt_execute($stmt)) {
            header("Location: teachers.php?status=updated");
            exit();
        } else {
            $message = "Error updating record: " . mysqli_stmt_error($stmt);
            $message_type = "danger";
        }
        mysqli_stmt_close($stmt);
    }
}

$sql_fetch = "SELECT * FROM teachers WHERE teacher_id = ?";
if ($stmt_fetch = mysqli_prepare($conn, $sql_fetch)) {
    mysqli_stmt_bind_param($stmt_fetch, "i", $teacher_id);
    mysqli_stmt_execute($stmt_fetch);
    $result = mysqli_stmt_get_result($stmt_fetch);
    if (mysqli_num_rows($result) == 1) {
        $teacher = mysqli_fetch_assoc($result);
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
    <title>Edit Teacher</title>
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
            <h1 class="h4 mb-0"><i class="fas fa-user-edit me-2"></i>Edit Teacher</h1>
        </div>
        <div class="card-body">
            <?php if ($message): ?>
            <div class="alert alert-<?php echo $message_type; ?>"><?php echo $message; ?></div>
            <?php endif; ?>

            <?php if ($teacher): ?>
            <form action="edit_teacher.php" method="post" class="row g-3">
                <input type="hidden" name="teacher_id" value="<?php echo htmlspecialchars($teacher_id); ?>">
                
                <h5 class="mt-4">Personal Information</h5>
                <div class="col-md-4"><label class="form-label">First Name</label><input type="text" class="form-control" name="first_name" value="<?php echo htmlspecialchars($teacher['first_name']); ?>" required></div>
                <div class="col-md-4"><label class="form-label">Middle Name</label><input type="text" class="form-control" name="middle_name" value="<?php echo htmlspecialchars($teacher['middle_name']); ?>" required></div>
                <div class="col-md-4"><label class="form-label">Last Name</label><input type="text" class="form-control" name="last_name" value="<?php echo htmlspecialchars($teacher['last_name']); ?>" required></div>
                <div class="col-md-4"><label class="form-label">Date of Birth</label><input type="date" class="form-control" name="date_of_birth" value="<?php echo htmlspecialchars($teacher['date_of_birth']); ?>" required></div>
                <div class="col-md-4">
                    <label class="form-label">Gender</label>
                    <select name="gender" class="form-select" required>
                        <option value="male" <?php if($teacher['gender'] == 'male') echo 'selected'; ?>>Male</option>
                        <option value="female" <?php if($teacher['gender'] == 'female') echo 'selected'; ?>>Female</option>
                    </select>
                </div>
                <div class="col-md-4"><label class="form-label">Nationality</label><input type="text" class="form-control" name="nationality" value="<?php echo htmlspecialchars($teacher['nationality']); ?>" required></div>
                <div class="col-md-4"><label class="form-label">Religion</label><input type="text" class="form-control" name="religion" value="<?php echo htmlspecialchars($teacher['religion']); ?>" required></div>

                <h5 class="mt-4">Contact & Address</h5>
                <div class="col-md-4"><label class="form-label">Phone</label><input type="tel" class="form-control" name="phone" value="<?php echo htmlspecialchars($teacher['phone']); ?>" required></div>
                <div class="col-md-4"><label class="form-label">Email</label><input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($teacher['email']); ?>" required></div>
                <div class="col-md-4"><label class="form-label">City</label><input type="text" class="form-control" name="city" value="<?php echo htmlspecialchars($teacher['city']); ?>" required></div>
                <div class="col-md-4"><label class="form-label">Wereda</label><input type="text" class="form-control" name="wereda" value="<?php echo htmlspecialchars($teacher['wereda']); ?>" required></div>
                <div class="col-md-4"><label class="form-label">Kebele</label><input type="text" class="form-control" name="kebele" value="<?php echo htmlspecialchars($teacher['kebele']); ?>" required></div>

                <h5 class="mt-4">Professional Information</h5>
                <div class="col-md-4"><label class="form-label">Hire Date</label><input type="date" class="form-control" name="hire_date" value="<?php echo htmlspecialchars($teacher['hire_date']); ?>" required></div>

                <div class="col-12 mt-4">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Update Teacher</button>
                    <a href="teachers.php" class="btn btn-secondary"><i class="fas fa-times me-1"></i> Cancel</a>
                </div>
            </form>
            <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>