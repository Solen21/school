<?php
require_once 'data/db_connect.php';

$message = '';
$message_type = '';

// Fetch data for dropdowns
$subjects = mysqli_query($conn, "SELECT subject_id, name, code FROM subjects ORDER BY name");
$teachers = mysqli_query($conn, "SELECT teacher_id, CONCAT(first_name, ' ', last_name) AS full_name FROM teachers ORDER BY full_name");
$sections = mysqli_query($conn, "SELECT section_id, CONCAT('Grade ', grade_level, ' ', stream) AS section_name FROM sections ORDER BY grade_level, stream");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST['subject_id']) || empty($_POST['teacher_id']) || empty($_POST['section_id'])) {
        $message = "All fields are required.";
        $message_type = "danger";
    } else {
        $sql = "INSERT INTO subject_assignments (subject_id, teacher_id, section_id) VALUES (?, ?, ?)";
        
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "iii", $_POST['subject_id'], $_POST['teacher_id'], $_POST['section_id']);

            if (mysqli_stmt_execute($stmt)) {
                header("Location: subject_assignments.php?status=created");
                exit();
            } else {
                $message = "Error: Could not save assignment. " . mysqli_stmt_error($stmt);
                $message_type = "danger";
            }
            mysqli_stmt_close($stmt);
        }
    }
}

$page_title = "New Subject Assignment";
include 'partials/header.php';
?>

<div class="container">
    <div class="card shadow-sm">
        <div class="card-header bg-secondary text-white">
            <h1 class="h4 mb-0"><i class="fas fa-plus me-2"></i>New Subject Assignment</h1>
        </div>
        <div class="card-body">
            <?php if ($message): ?>
            <div class="alert alert-<?php echo $message_type; ?>"><?php echo $message; ?></div>
            <?php endif; ?>

            <form action="create_subject_assignment.php" method="post">
                <div class="mb-3">
                    <label for="section_id" class="form-label">Section</label>
                    <select class="form-select" id="section_id" name="section_id" required>
                        <option value="" selected disabled>Select a section...</option>
                        <?php while($row = mysqli_fetch_assoc($sections)): ?>
                            <option value="<?php echo $row['section_id']; ?>"><?php echo htmlspecialchars($row['section_name']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="subject_id" class="form-label">Subject</label>
                    <select class="form-select" id="subject_id" name="subject_id" required>
                        <option value="" selected disabled>Select a subject...</option>
                        <?php while($row = mysqli_fetch_assoc($subjects)): ?>
                            <option value="<?php echo $row['subject_id']; ?>"><?php echo htmlspecialchars($row['name'] . ' (' . $row['code'] . ')'); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="teacher_id" class="form-label">Teacher</label>
                    <select class="form-select" id="teacher_id" name="teacher_id" required>
                        <option value="" selected disabled>Select a teacher...</option>
                        <?php while($row = mysqli_fetch_assoc($teachers)): ?>
                            <option value="<?php echo $row['teacher_id']; ?>"><?php echo htmlspecialchars($row['full_name']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary"><i class="fas fa-check me-1"></i> Create Assignment</button>
                <a href="subject_assignments.php" class="btn btn-secondary"><i class="fas fa-times me-1"></i> Cancel</a>
            </form>
        </div>
    </div>
</div>

<?php 
mysqli_close($conn);
include 'partials/footer.php'; 
?>