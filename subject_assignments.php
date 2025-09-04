<?php
require_once 'data/db_connect.php';

$sql = "SELECT 
            sa.assignment_id,
            sub.name AS subject_name,
            CONCAT(t.first_name, ' ', t.last_name) AS teacher_name,
            CONCAT('Grade ', sec.grade_level, ' ', sec.stream) AS section_name
        FROM subject_assignments sa
        JOIN subjects sub ON sa.subject_id = sub.subject_id
        JOIN teachers t ON sa.teacher_id = t.teacher_id
        JOIN sections sec ON sa.section_id = sec.section_id
        ORDER BY section_name, subject_name";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Error fetching assignments: " . mysqli_error($conn));
}

$page_title = "Subject Assignments";
include 'partials/header.php';
?>

<div class="container">
    <div class="card shadow-sm">
        <div class="card-header bg-secondary text-white">
            <h1 class="h4 mb-0"><i class="fas fa-link me-2"></i>Subject Assignments</h1>
            <a href="create_subject_assignment.php" class="btn btn-light"><i class="fas fa-plus me-1"></i> New Assignment</a>
        </div>
        <div class="card-body">
            <?php if (isset($_GET['status'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php 
                        if ($_GET['status'] == 'created') echo 'Assignment created successfully!'; 
                        if ($_GET['status'] == 'deleted') echo 'Assignment deleted successfully!'; 
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Section</th>
                            <th>Subject</th>
                            <th>Assigned Teacher</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($result) > 0): ?>
                            <?php while($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['section_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['subject_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['teacher_name']); ?></td>
                                <td>
                                    <a href="delete_subject_assignment.php?id=<?php echo $row['assignment_id']; ?>" class="btn btn-sm btn-danger" title="Delete"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center">No subject assignments found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php 
mysqli_close($conn);
include 'partials/footer.php'; 
?>