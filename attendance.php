<?php
require_once 'data/db_connect.php';

// Fetch data for dropdowns
$sections = mysqli_query($conn, "SELECT section_id, CONCAT('Grade ', grade_level, ' ', stream) AS section_name FROM sections ORDER BY grade_level, stream");
$subjects = mysqli_query($conn, "SELECT subject_id, name, code FROM subjects ORDER BY name");

$page_title = "Attendance";
include 'partials/header.php';
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h1 class="h4 mb-0"><i class="fas fa-user-check me-2"></i>Take Attendance</h1>
                </div>
                <div class="card-body">
                    <p>Select a section, subject, and date to take or view attendance.</p>
                    <hr>
                    <form action="mark_attendance.php" method="get">
                        <div class="mb-3">
                            <label for="section_id" class="form-label"><strong>Section</strong></label>
                            <select class="form-select" id="section_id" name="section_id" required>
                                <option value="" selected disabled>Select a section...</option>
                                <?php while($row = mysqli_fetch_assoc($sections)): ?>
                                    <option value="<?php echo $row['section_id']; ?>"><?php echo htmlspecialchars($row['section_name']); ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="subject_id" class="form-label"><strong>Subject</strong></label>
                            <select class="form-select" id="subject_id" name="subject_id" required>
                                <option value="" selected disabled>Select a subject...</option>
                                <?php while($row = mysqli_fetch_assoc($subjects)): ?>
                                    <option value="<?php echo $row['subject_id']; ?>"><?php echo htmlspecialchars($row['name'] . ' (' . $row['code'] . ')'); ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="date" class="form-label"><strong>Date</strong></label>
                            <input type="date" class="form-control" id="date" name="date" value="<?php echo date('Y-m-d'); ?>" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100"><i class="fas fa-arrow-right me-1"></i> Proceed to Attendance Sheet</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
mysqli_close($conn);
include 'partials/footer.php'; 
?>