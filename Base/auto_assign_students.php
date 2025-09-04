<?php
require_once 'data/db_connect.php';

$message = '';
$message_type = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $grade_level = $_POST['grade_level'];

    if (empty($grade_level)) {
        $message = "Please select a grade level to assign.";
        $message_type = "danger";
    } else {
        mysqli_begin_transaction($conn);
        try {
            // 1. Get all sections for the selected grade
            $sql_sections = "SELECT section_id, capacity FROM sections WHERE grade_level = ?";
            $stmt_sections = mysqli_prepare($conn, $sql_sections);
            mysqli_stmt_bind_param($stmt_sections, "i", $grade_level);
            mysqli_stmt_execute($stmt_sections);
            $sections_result = mysqli_stmt_get_result($stmt_sections);
            
            $sections = [];
            while ($row = mysqli_fetch_assoc($sections_result)) {
                $sections[] = $row;
            }
            mysqli_stmt_close($stmt_sections);

            if (empty($sections)) {
                throw new Exception("No sections found for Grade $grade_level. Please create sections first.");
            }

            // 2. Get all unassigned students for that grade, ordered by score
            $sql_students = "SELECT s.student_id FROM students s LEFT JOIN class_assignments ca ON s.student_id = ca.student_id WHERE s.grade_level = ? AND ca.schedule_id IS NULL ORDER BY s.last_score DESC";
            $stmt_students = mysqli_prepare($conn, $sql_students);
            mysqli_stmt_bind_param($stmt_students, "i", $grade_level);
            mysqli_stmt_execute($stmt_students);
            $students_result = mysqli_stmt_get_result($stmt_students);

            $students_to_assign = [];
            while ($row = mysqli_fetch_assoc($students_result)) {
                $students_to_assign[] = $row['student_id'];
            }
            mysqli_stmt_close($stmt_students);

            if (empty($students_to_assign)) {
                throw new Exception("No unassigned students found for Grade $grade_level.");
            }

            // 3. Distribute students (Round-Robin)
            $section_counts = array_fill_keys(array_column($sections, 'section_id'), 0);
            $section_index = 0;
            $assigned_count = 0;

            $sql_insert = "INSERT INTO class_assignments (student_id, section_id) VALUES (?, ?)";
            $stmt_insert = mysqli_prepare($conn, $sql_insert);

            foreach ($students_to_assign as $student_id) {
                $assigned = false;
                // Loop through sections to find one with capacity
                for ($i = 0; $i < count($sections); $i++) {
                    $current_section_id = $sections[$section_index]['section_id'];
                    $current_capacity = $sections[$section_index]['capacity'];

                    if ($section_counts[$current_section_id] < $current_capacity) {
                        mysqli_stmt_bind_param($stmt_insert, "ii", $student_id, $current_section_id);
                        mysqli_stmt_execute($stmt_insert);
                        $section_counts[$current_section_id]++;
                        $assigned_count++;
                        $assigned = true;
                        // Move to the next section for the next student
                        $section_index = ($section_index + 1) % count($sections);
                        break; // Student assigned, move to the next student
                    }
                    // If current section is full, try the next one
                    $section_index = ($section_index + 1) % count($sections);
                }
            }
            mysqli_stmt_close($stmt_insert);

            mysqli_commit($conn);
            $message = "Successfully assigned $assigned_count students to sections for Grade $grade_level.";
            $message_type = "success";

        } catch (Exception $e) {
            mysqli_rollback($conn);
            $message = "An error occurred: " . $e->getMessage();
            $message_type = "danger";
        }
    }
}

$page_title = "Automatic Student Assignment";
include 'partials/header.php';
?>

<div class="container">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h1 class="h4 mb-0"><i class="fas fa-sitemap me-2"></i>Automatic Student-to-Section Assignment</h1>
        </div>
        <div class="card-body">
            <p class="card-text">This tool automatically assigns unassigned students to available sections for a specific grade level. The system distributes students based on their last score to create balanced sections.</p>
            <hr>
            <?php if ($message): ?>
            <div class="alert alert-<?php echo $message_type; ?>"><?php echo $message; ?></div>
            <?php endif; ?>

            <form action="auto_assign_students.php" method="post">
                <div class="mb-3">
                    <label for="grade_level" class="form-label"><strong>Select Grade Level to Assign</strong></label>
                    <select class="form-select" id="grade_level" name="grade_level" required>
                        <option value="" selected disabled>Select a grade...</option>
                        <option value="9">Grade 9</option>
                        <option value="10">Grade 10</option>
                        <option value="11">Grade 11</option>
                        <option value="12">Grade 12</option>
                    </select>
                </div>
                <div class="alert alert-warning">
                    <strong>Warning:</strong> Running this process may re-assign students. It is recommended to run this only once per grade level at the beginning of a term.
                </div>
                <button type="submit" class="btn btn-primary"><i class="fas fa-cogs me-1"></i> Start Automatic Assignment</button>
            </form>
        </div>
    </div>
</div>

<?php 
mysqli_close($conn);
include 'partials/footer.php'; 
?>