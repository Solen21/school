<?php
require_once 'data/db_connect.php';

// Check if classroom_id is provided and is a number
if (!isset($_GET['classroom_id']) || !is_numeric($_GET['classroom_id'])) {
    die("Error: Invalid Classroom ID.");
}

$classroom_id = $_GET['classroom_id'];

// Fetch classroom details
$sql_classroom = "SELECT name FROM classrooms WHERE classroom_id = ?";
$stmt_classroom = mysqli_prepare($conn, $sql_classroom);
mysqli_stmt_bind_param($stmt_classroom, "i", $classroom_id);
mysqli_stmt_execute($stmt_classroom);
$result_classroom = mysqli_stmt_get_result($stmt_classroom);
if (mysqli_num_rows($result_classroom) == 0) {
    die("Error: Classroom not found.");
}
$classroom = mysqli_fetch_assoc($result_classroom);
mysqli_stmt_close($stmt_classroom);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['students']) && is_array($_POST['students'])) {
        // Clear existing assignments for this classroom (optional)
        $sql_clear = "DELETE FROM class_assignments WHERE section_id = ?";
        $stmt_clear = mysqli_prepare($conn, $sql_clear);
        mysqli_stmt_bind_param($stmt_clear, "i", $classroom_id);
        mysqli_stmt_execute($stmt_clear);
        mysqli_stmt_close($stmt_clear);

        // Assign selected students
        $sql_assign = "INSERT INTO class_assignments (student_id, section_id) VALUES (?, ?)";
        $stmt_assign = mysqli_prepare($conn, $sql_assign);
        foreach ($_POST['students'] as $student_id) {
            mysqli_stmt_bind_param($stmt_assign, "ii", $student_id, $classroom_id);
            mysqli_stmt_execute($stmt_assign);
        }
        mysqli_stmt_close($stmt_assign);

        header("Location: classrooms.php?status=students_assigned");
        exit();
    }
}

// Fetch students ordered by grade and last_score
$sql_students = "SELECT student_id, CONCAT(first_name, ' ', middle_name, ' ', last_name) AS full_name, grade_level FROM students ORDER BY grade_level ASC, last_score DESC";
$result_students = mysqli_query($conn, $sql_students);
if (!$result_students) {
    die("Error fetching students: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Students to <?php echo htmlspecialchars($classroom['name']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .container { margin-top: 30px; }
    </style>
</head>
<body>

<div class="container">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h1 class="h4 mb-0">Assign Students to <?php echo htmlspecialchars($classroom['name']); ?></h1>
        </div>
        <div class="card-body">
            <form method="post" action="assign_students.php?classroom_id=<?php echo $classroom_id; ?>">
                <div class="mb-3">
                    <p>Select students to assign to this classroom:</p>
                    <?php while ($student = mysqli_fetch_assoc($result_students)): ?>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="students[]" value="<?php echo htmlspecialchars($student['student_id']); ?>" id="student_<?php echo htmlspecialchars($student['student_id']); ?>">
                            <label class="form-check-label" for="student_<?php echo htmlspecialchars($student['student_id']); ?>">
                                <?php echo htmlspecialchars($student['full_name'] . ' (Grade ' . $student['grade_level'] . ')'); ?>
                            </label>
                        </div>
                    <?php endwhile; ?>
                </div>
                <button type="submit" class="btn btn-success"><i class="fas fa-check me-1"></i> Assign Students</button>
                <a href="classrooms.php" class="btn btn-secondary"><i class="fas fa-times me-1"></i> Cancel</a>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php mysqli_close($conn); ?>