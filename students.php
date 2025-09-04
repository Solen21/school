<?php
// Include the database connection file
require_once 'data/db_connect.php';

// Fetch all students from the database.
// We'll concatenate the name fields for a full name display.
$sql = "SELECT 
            student_id, 
            CONCAT(first_name, ' ', middle_name, ' ', last_name) AS full_name, 
            grade_level, 
            stream, 
            status 
        FROM students 
        ORDER BY student_id ASC";

$result = mysqli_query($conn, $sql);

// Check if the query was successful
if (!$result) {
    die("Error fetching students: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Students</title>
    <!-- Bootstrap CSS for styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Optional: Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        /* Re-using styles from users.php */
        body { background-color: #f8f9fa; }
        .container { margin-top: 30px; }
        .table-hover tbody tr:hover { background-color: #e9ecef; }
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card shadow-sm">
        <div class="card-header bg-success text-white">
            <h1 class="h4 mb-0"><i class="fas fa-user-graduate me-2"></i>Student Management</h1>
            <a href="create_student.php" class="btn btn-light"><i class="fas fa-plus me-1"></i> Add Student</a>
        </div>
        <div class="card-body">
            <?php if (isset($_GET['status'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php 
                        if ($_GET['status'] == 'created') echo 'Student added successfully!'; 
                        if ($_GET['status'] == 'updated') echo 'Student updated successfully!'; 
                        if ($_GET['status'] == 'deleted') echo 'Student deleted successfully!'; 
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Full Name</th>
                            <th>Grade</th>
                            <th>Stream</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($result) > 0): ?>
                            <?php while($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['student_id']); ?></td>
                                <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['grade_level']); ?></td>
                                <td><?php echo htmlspecialchars($row['stream']); ?></td>
                                <td>
                                    <span class="badge bg-<?php echo $row['status'] == 'active' ? 'primary' : 'secondary'; ?>">
                                        <?php echo htmlspecialchars(ucfirst($row['status'])); ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="view_student.php?id=<?php echo $row['student_id']; ?>" class="btn btn-sm btn-info" title="View"><i class="fas fa-eye"></i></a>
                                    <a href="edit_student.php?id=<?php echo $row['student_id']; ?>" class="btn btn-sm btn-warning" title="Edit"><i class="fas fa-edit"></i></a>
                                    <a href="delete_student.php?id=<?php echo $row['student_id']; ?>" class="btn btn-sm btn-danger" title="Delete"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">No students found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
// Close the database connection
mysqli_close($conn);
?>

<!-- Bootstrap JS Bundle (needed for some components) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>