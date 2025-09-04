<?php
require_once 'data/db_connect.php';

$message = '';
$message_type = '';

// --- Validate GET parameters ---
if (!isset($_GET['section_id']) || !isset($_GET['subject_id']) || !isset($_GET['date'])) {
    die("Error: Missing required parameters.");
}
$section_id = (int)$_GET['section_id'];
$subject_id = (int)$_GET['subject_id'];
$attendance_date = $_GET['date'];

// --- Handle POST request to save attendance ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $attendance_data = $_POST['attendance'];
    // Assuming the user is an admin for now. In a real system, this would be the logged-in user's ID.
    $marked_by_user = 'admin'; 

    $sql = "INSERT INTO attendance (student_id, section_id, subject_id, date, status, marked_by) 
            VALUES (?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE status = VALUES(status), marked_by = VALUES(marked_by)";
    
    if ($stmt = mysqli_prepare($conn, $sql)) {
        foreach ($attendance_data as $student_id => $status) {
            if (!empty($status)) {
                mysqli_stmt_bind_param($stmt, "iiisss", $student_id, $section_id, $subject_id, $attendance_date, $status, $marked_by_user);
                mysqli_stmt_execute($stmt);
            }
        }
        mysqli_stmt_close($stmt);
        $message = "Attendance saved successfully!";
        $message_type = "success";
    } else {
        $message = "Error preparing statement: " . mysqli_error($conn);
        $message_type = "danger";
    }
}

// --- Fetch data for displaying the page ---

// Get Section and Subject names for the title
$section_info_sql = "SELECT CONCAT('Grade ', grade_level, ' ', stream) AS name FROM sections WHERE section_id = $section_id";
$subject_info_sql = "SELECT name FROM subjects WHERE subject_id = $subject_id";
$section_name = mysqli_fetch_assoc(mysqli_query($conn, $section_info_sql))['name'];
$subject_name = mysqli_fetch_assoc(mysqli_query($conn, $subject_info_sql))['name'];

// Get students assigned to this section
$sql_students = "SELECT s.student_id, CONCAT(s.first_name, ' ', s.middle_name, ' ', s.last_name) AS full_name
                 FROM students s
                 JOIN class_assignments ca ON s.student_id = ca.student_id
                 WHERE ca.section_id = ?
                 ORDER BY s.last_name, s.first_name";

$stmt_students = mysqli_prepare($conn, $sql_students);
mysqli_stmt_bind_param($stmt_students, "i", $section_id);
mysqli_stmt_execute($stmt_students);
$students_result = mysqli_stmt_get_result($stmt_students);

// Get existing attendance records for this day to pre-fill the form
$existing_attendance = [];
$sql_existing = "SELECT student_id, status FROM attendance WHERE section_id = ? AND subject_id = ? AND date = ?";
$stmt_existing = mysqli_prepare($conn, $sql_existing);
mysqli_stmt_bind_param($stmt_existing, "iis", $section_id, $subject_id, $attendance_date);
mysqli_stmt_execute($stmt_existing);
$existing_result = mysqli_stmt_get_result($stmt_existing);
while ($row = mysqli_fetch_assoc($existing_result)) {
    $existing_attendance[$row['student_id']] = $row['status'];
}

$page_title = "Mark Attendance";
include 'partials/header.php';
?>

<div class="container">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <div>
                <h1 class="h4 mb-0"><i class="fas fa-user-check me-2"></i>Attendance Sheet</h1>
                <small><?php echo htmlspecialchars($section_name . ' - ' . $subject_name . ' on ' . date('F j, Y', strtotime($attendance_date))); ?></small>
            </div>
            <a href="attendance.php" class="btn btn-light btn-sm">Change Selection</a>
        </div>
        <div class="card-body">
            <?php if ($message): ?>
            <div class="alert alert-<?php echo $message_type; ?>"><?php echo $message; ?></div>
            <?php endif; ?>

            <form action="" method="post">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Student Name</th>
                                <th class="text-center">Present</th>
                                <th class="text-center">Absent</th>
                                <th class="text-center">Late</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($students_result) > 0): ?>
                                <?php while($student = mysqli_fetch_assoc($students_result)): 
                                    $student_id = $student['student_id'];
                                    $current_status = $existing_attendance[$student_id] ?? '';
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($student['full_name']); ?></td>
                                    <td class="text-center">
                                        <input class="form-check-input" type="radio" name="attendance[<?php echo $student_id; ?>]" value="Present" <?php if($current_status == 'Present') echo 'checked'; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input class="form-check-input" type="radio" name="attendance[<?php echo $student_id; ?>]" value="Absent" <?php if($current_status == 'Absent') echo 'checked'; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input class="form-check-input" type="radio" name="attendance[<?php echo $student_id; ?>]" value="Late" <?php if($current_status == 'Late') echo 'checked'; ?>>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center">No students found in this section.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <?php if (mysqli_num_rows($students_result) > 0): ?>
                <div class="text-end mt-3">
                    <button type="submit" class="btn btn-success"><i class="fas fa-save me-1"></i> Save Attendance</button>
                </div>
                <?php endif; ?>
            </form>
        </div>
    </div>
</div>

<?php 
mysqli_stmt_close($stmt_students);
mysqli_stmt_close($stmt_existing);
mysqli_close($conn);
include 'partials/footer.php'; 
?>