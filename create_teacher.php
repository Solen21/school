<?php
$message = '';
$message_type = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once 'data/db_connect.php';

    mysqli_begin_transaction($conn);

    try {
        // --- 1. Create User Record ---
        $first_name = trim($_POST['first_name']);
        $last_name = trim($_POST['last_name']);
        
        $username = strtolower($first_name . '.' . $last_name) . rand(10, 99);
        $password = "password123"; // Default password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $role = 'teacher';

        $sql_user = "INSERT INTO users (username, password, role) VALUES (?, ?, ?)";
        $stmt_user = mysqli_prepare($conn, $sql_user);
        mysqli_stmt_bind_param($stmt_user, "sss", $username, $hashed_password, $role);
        
        if (!mysqli_stmt_execute($stmt_user)) {
            if (mysqli_errno($conn) == 1062) {
                 throw new Exception("A user with a similar name already exists. Please adjust the name.");
            }
            throw new Exception("Failed to create user account: " . mysqli_stmt_error($stmt_user));
        }

        $user_id = mysqli_insert_id($conn);
        mysqli_stmt_close($stmt_user);

        // --- 2. Create Teacher Record ---
        $sql_teacher = "INSERT INTO teachers (user_id, first_name, middle_name, last_name, date_of_birth, gender, nationality, religion, city, wereda, kebele, phone, email, hire_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt_teacher = mysqli_prepare($conn, $sql_teacher);
        
        mysqli_stmt_bind_param($stmt_teacher, "isssssssssssss", 
            $user_id,
            $_POST['first_name'],
            $_POST['middle_name'],
            $_POST['last_name'],
            $_POST['date_of_birth'],
            $_POST['gender'],
            $_POST['nationality'],
            $_POST['religion'],
            $_POST['city'],
            $_POST['wereda'],
            $_POST['kebele'],
            $_POST['phone'],
            $_POST['email'],
            $_POST['hire_date']
        );

        if (!mysqli_stmt_execute($stmt_teacher)) {
            throw new Exception("Failed to create teacher record: " . mysqli_stmt_error($stmt_teacher));
        }
        mysqli_stmt_close($stmt_teacher);

        mysqli_commit($conn);
        
        header("Location: teachers.php?status=created");
        exit();

    } catch (Exception $e) {
        mysqli_rollback($conn);
        $message = $e->getMessage();
        $message_type = "danger";
    }

    mysqli_close($conn);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Teacher</title>
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
        <div class="card-header bg-info text-white">
            <h1 class="h4 mb-0"><i class="fas fa-user-plus me-2"></i>Add New Teacher</h1>
        </div>
        <div class="card-body">
            <?php if ($message): ?>
            <div class="alert alert-<?php echo $message_type; ?>"><?php echo $message; ?></div>
            <?php endif; ?>

            <form action="create_teacher.php" method="post" class="row g-3">
                <!-- Personal Information -->
                <h5 class="mt-4">Personal Information</h5>
                <div class="col-md-4"><label for="first_name" class="form-label">First Name</label><input type="text" class="form-control" id="first_name" name="first_name" required></div>
                <div class="col-md-4"><label for="middle_name" class="form-label">Middle Name</label><input type="text" class="form-control" id="middle_name" name="middle_name" required></div>
                <div class="col-md-4"><label for="last_name" class="form-label">Last Name</label><input type="text" class="form-control" id="last_name" name="last_name" required></div>
                <div class="col-md-4"><label for="date_of_birth" class="form-label">Date of Birth</label><input type="date" class="form-control" id="date_of_birth" name="date_of_birth" required></div>
                <div class="col-md-4">
                    <label for="gender" class="form-label">Gender</label>
                    <select id="gender" name="gender" class="form-select" required><option value="male">Male</option><option value="female">Female</option></select>
                </div>
                <div class="col-md-4"><label for="nationality" class="form-label">Nationality</label><input type="text" class="form-control" id="nationality" name="nationality" value="Ethiopian" required></div>
                <div class="col-md-4"><label for="religion" class="form-label">Religion</label><input type="text" class="form-control" id="religion" name="religion" required></div>

                <!-- Contact & Address -->
                <h5 class="mt-4">Contact & Address</h5>
                <div class="col-md-4"><label for="phone" class="form-label">Phone</label><input type="tel" class="form-control" id="phone" name="phone" required></div>
                <div class="col-md-4"><label for="email" class="form-label">Email</label><input type="email" class="form-control" id="email" name="email" required></div>
                <div class="col-md-4"><label for="city" class="form-label">City</label><input type="text" class="form-control" id="city" name="city" required></div>
                <div class="col-md-4"><label for="wereda" class="form-label">Wereda</label><input type="text" class="form-control" id="wereda" name="wereda" required></div>
                <div class="col-md-4"><label for="kebele" class="form-label">Kebele</label><input type="text" class="form-control" id="kebele" name="kebele" required></div>

                <!-- Professional Information -->
                <h5 class="mt-4">Professional Information</h5>
                <div class="col-md-4">
                    <label for="hire_date" class="form-label">Hire Date</label>
                    <input type="date" class="form-control" id="hire_date" name="hire_date" required>
                </div>

                <!-- Submission -->
                <div class="col-12 mt-4">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-check me-1"></i> Save Teacher</button>
                    <a href="teachers.php" class="btn btn-secondary"><i class="fas fa-times me-1"></i> Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>