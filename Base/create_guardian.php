<?php
require_once 'data/db_connect.php';

$message = '';
$message_type = '';

// Fetch all students to populate the dropdown
$students_result = mysqli_query($conn, "SELECT student_id, CONCAT(first_name, ' ', middle_name, ' ', last_name) AS full_name FROM students ORDER BY full_name ASC");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // --- Validation ---
    if (empty($_POST['student_id']) || empty($_POST['name']) || empty($_POST['relation']) || empty($_POST['phone'])) {
        $message = "Student, Name, Relation, and Phone are required fields.";
        $message_type = "danger";
    } else {
        $sql = "INSERT INTO guardians (student_id, name, relation, phone, email) VALUES (?, ?, ?, ?, ?)";
        
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "issss", 
                $_POST['student_id'],
                $_POST['name'],
                $_POST['relation'],
                $_POST['phone'],
                $_POST['email']
            );

            if (mysqli_stmt_execute($stmt)) {
                header("Location: guardians.php?status=created");
                exit();
            } else {
                $message = "Error: Could not save guardian. " . mysqli_stmt_error($stmt);
                $message_type = "danger";
            }
            mysqli_stmt_close($stmt);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Guardian</title>
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
            <h1 class="h4 mb-0"><i class="fas fa-user-plus me-2"></i>Add New Guardian</h1>
        </div>
        <div class="card-body">
            <?php if ($message): ?>
            <div class="alert alert-<?php echo $message_type; ?>"><?php echo $message; ?></div>
            <?php endif; ?>

            <form action="create_guardian.php" method="post">
                <div class="mb-3">
                    <label for="student_id" class="form-label">Student</label>
                    <select class="form-select" id="student_id" name="student_id" required>
                        <option value="" selected disabled>Select a student...</option>
                        <?php while($student = mysqli_fetch_assoc($students_result)): ?>
                            <option value="<?php echo $student['student_id']; ?>">
                                <?php echo htmlspecialchars($student['full_name']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="name" class="form-label">Guardian Full Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="relation" class="form-label">Relation to Student</label>
                    <input type="text" class="form-control" id="relation" name="relation" placeholder="e.g., Father, Mother, Aunt" required>
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input type="tel" class="form-control" id="phone" name="phone" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email">
                </div>
                <button type="submit" class="btn btn-primary"><i class="fas fa-check me-1"></i> Save Guardian</button>
                <a href="guardians.php" class="btn btn-secondary"><i class="fas fa-times me-1"></i> Cancel</a>
            </form>
        </div>
    </div>
</div>

<?php mysqli_close($conn); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>