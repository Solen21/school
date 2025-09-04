<?php
$message = '';
$message_type = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once 'data/db_connect.php';

    // --- Begin Transaction ---
    mysqli_begin_transaction($conn);

    try {
        // --- 1. Create User Record ---
        $first_name = trim($_POST['first_name']);
        $last_name = trim($_POST['last_name']);
        
        // Generate a simple username and a default password
        $username = strtolower($first_name . '.' . $last_name) . rand(10, 99);
        $password = "password123"; // A default password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $role = 'student';

        $sql_user = "INSERT INTO users (username, password, role) VALUES (?, ?, ?)";
        $stmt_user = mysqli_prepare($conn, $sql_user);
        mysqli_stmt_bind_param($stmt_user, "sss", $username, $hashed_password, $role);
        
        if (!mysqli_stmt_execute($stmt_user)) {
            // Check for duplicate username
            if (mysqli_errno($conn) == 1062) {
                 throw new Exception("A user with a similar name already exists. Please adjust the name or contact an admin.");
            }
            throw new Exception("Failed to create user account: " . mysqli_stmt_error($stmt_user));
        }

        $user_id = mysqli_insert_id($conn); // Get the ID of the new user
        mysqli_stmt_close($stmt_user);

        // --- 2. Create Student Record ---
        $sql_student = "INSERT INTO students (user_id, first_name, middle_name, last_name, date_of_birth, gender, nationality, religion, city, wereda, kebele, phone, email, emergency_contact, grade_level, stream, last_school, last_score, last_grade, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt_student = mysqli_prepare($conn, $sql_student);
        
        // Bind all the student form data
        mysqli_stmt_bind_param($stmt_student, "issssssssssssssssdss", 
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
            $_POST['emergency_contact'],
            $_POST['grade_level'],
            $_POST['stream'],
            $_POST['last_school'],
            $_POST['last_score'],
            $_POST['last_grade'],
            'active' // Explicitly set status to 'active'
        );

        if (!mysqli_stmt_execute($stmt_student)) {
            throw new Exception("Failed to create student record: " . mysqli_stmt_error($stmt_student));
        }
        mysqli_stmt_close($stmt_student);

        // --- If all is well, commit the transaction ---
        mysqli_commit($conn);
        
        header("Location: students.php?status=created");
        exit();

    } catch (Exception $e) {
        // --- If anything fails, roll back the transaction ---
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
    <title>Add New Student</title>
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
        <div class="card-header bg-success text-white">
            <h1 class="h4 mb-0"><i class="fas fa-user-plus me-2"></i>Add New Student</h1>
        </div>
        <div class="card-body">
            <?php if ($message): ?>
            <div class="alert alert-<?php echo $message_type; ?>"><?php echo $message; ?></div>
            <?php endif; ?>

            <form action="create_student.php" method="post" class="row g-3">
                <!-- Personal Information -->
                <h5 class="mt-4">Personal Information</h5>
                <div class="col-md-4">
                    <label for="first_name" class="form-label">First Name</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" required>
                </div>
                <div class="col-md-4">
                    <label for="middle_name" class="form-label">Middle Name</label>
                    <input type="text" class="form-control" id="middle_name" name="middle_name" required>
                </div>
                <div class="col-md-4">
                    <label for="last_name" class="form-label">Last Name</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" required>
                </div>
                <div class="col-md-4">
                    <label for="date_of_birth" class="form-label">Date of Birth</label>
                    <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" required>
                </div>
                <div class="col-md-4">
                    <label for="gender" class="form-label">Gender</label>
                    <select id="gender" name="gender" class="form-select" required>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="nationality" class="form-label">Nationality</label>
                    <input type="text" class="form-control" id="nationality" name="nationality" value="Ethiopian" required>
                </div>
                <div class="col-md-4">
                    <label for="religion" class="form-label">Religion</label>
                    <input type="text" class="form-control" id="religion" name="religion" required>
                </div>

                <!-- Contact & Address -->
                <h5 class="mt-4">Contact & Address</h5>
                <div class="col-md-4">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="tel" class="form-control" id="phone" name="phone" required>
                </div>
                <div class="col-md-4">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email">
                </div>
                 <div class="col-md-4">
                    <label for="emergency_contact" class="form-label">Emergency Contact</label>
                    <input type="tel" class="form-control" id="emergency_contact" name="emergency_contact" required>
                </div>
                <div class="col-md-4">
                    <label for="city" class="form-label">City</label>
                    <input type="text" class="form-control" id="city" name="city" required>
                </div>
                <div class="col-md-4">
                    <label for="wereda" class="form-label">Wereda</label>
                    <input type="text" class="form-control" id="wereda" name="wereda" required>
                </div>
                <div class="col-md-4">
                    <label for="kebele" class="form-label">Kebele</label>
                    <input type="text" class="form-control" id="kebele" name="kebele" required>
                </div>

                <!-- Academic Information -->
                <h5 class="mt-4">Academic Information</h5>
                <div class="col-md-4">
                    <label for="grade_level" class="form-label">Grade Level</label>
                    <select id="grade_level" name="grade_level" class="form-select" required>
                        <option value="9">9</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="stream" class="form-label">Stream</label>
                    <select id="stream" name="stream" class="form-select" required>
                        <option value="Natural">Natural</option>
                        <option value="Social">Social</option>
                        <option value="Both">Both</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="last_school" class="form-label">Last School Attended</label>
                    <input type="text" class="form-control" id="last_school" name="last_school" required>
                </div>
                <div class="col-md-4">
                    <label for="last_grade" class="form-label">Last Grade Completed</label>
                    <input type="number" class="form-control" id="last_grade" name="last_grade" required>
                </div>
                <div class="col-md-4">
                    <label for="last_score" class="form-label">Last Score/Average</label>
                    <input type="number" step="0.01" class="form-control" id="last_score" name="last_score" required>
                </div>

                <!-- Submission -->
                <div class="col-12 mt-4">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-check me-1"></i> Save Student</button>
                    <a href="students.php" class="btn btn-secondary"><i class="fas fa-times me-1"></i> Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>