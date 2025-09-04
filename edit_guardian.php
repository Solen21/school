<?php
require_once 'data/db_connect.php';

$message = '';
$message_type = '';
$guardian = null;

if (!isset($_GET['id']) && !isset($_POST['guardian_id'])) {
    die("Error: Guardian ID not specified.");
}

$guardian_id = isset($_POST['guardian_id']) ? $_POST['guardian_id'] : $_GET['id'];

// Fetch all students for the dropdown
$students_result = mysqli_query($conn, "SELECT student_id, CONCAT(first_name, ' ', middle_name, ' ', last_name) AS full_name FROM students ORDER BY full_name ASC");

// --- Handle Form Submission (POST request) ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sql = "UPDATE guardians SET student_id = ?, name = ?, relation = ?, phone = ?, email = ? WHERE guardian_id = ?";

    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "issssi",
            $_POST['student_id'],
            $_POST['name'],
            $_POST['relation'],
            $_POST['phone'],
            $_POST['email'],
            $guardian_id
        );

        if (mysqli_stmt_execute($stmt)) {
            header("Location: guardians.php?status=updated");
            exit();
        } else {
            $message = "Error updating record: " . mysqli_stmt_error($stmt);
            $message_type = "danger";
        }
        mysqli_stmt_close($stmt);
    }
}

// --- Fetch Guardian Data for Form (GET request) ---
$sql_fetch = "SELECT * FROM guardians WHERE guardian_id = ?";
if ($stmt_fetch = mysqli_prepare($conn, $sql_fetch)) {
    mysqli_stmt_bind_param($stmt_fetch, "i", $guardian_id);
    mysqli_stmt_execute($stmt_fetch);
    $result = mysqli_stmt_get_result($stmt_fetch);
    if (mysqli_num_rows($result) == 1) {
        $guardian = mysqli_fetch_assoc($result);
    } else {
        die("Guardian not found.");
    }
    mysqli_stmt_close($stmt_fetch);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Guardian</title>
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
            <h1 class="h4 mb-0"><i class="fas fa-user-edit me-2"></i>Edit Guardian</h1>
        </div>
        <div class="card-body">
            <?php if ($message): ?>
            <div class="alert alert-<?php echo $message_type; ?>"><?php echo $message; ?></div>
            <?php endif; ?>

            <?php if ($guardian): ?>
            <form action="edit_guardian.php" method="post">
                <input type="hidden" name="guardian_id" value="<?php echo htmlspecialchars($guardian_id); ?>">
                
                <div class="mb-3">
                    <label for="student_id" class="form-label">Student</label>
                    <select class="form-select" id="student_id" name="student_id" required>
                        <?php while($student = mysqli_fetch_assoc($students_result)): ?>
                            <option value="<?php echo $student['student_id']; ?>" <?php if($guardian['student_id'] == $student['student_id']) echo 'selected'; ?>>
                                <?php echo htmlspecialchars($student['full_name']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="name" class="form-label">Guardian Full Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($guardian['name']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="relation" class="form-label">Relation to Student</label>
                    <input type="text" class="form-control" id="relation" name="relation" value="<?php echo htmlspecialchars($guardian['relation']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($guardian['phone']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($guardian['email']); ?>">
                </div>
                
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Update Guardian</button>
                <a href="guardians.php" class="btn btn-secondary"><i class="fas fa-times me-1"></i> Cancel</a>
            </form>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php mysqli_close($conn); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>