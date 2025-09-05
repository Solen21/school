<?php
require_once 'data/auth_check.php';
require_once 'data/db_connect.php';

// Fetch all teachers, concatenating their names for a full name display.
$sql = "SELECT 
            teacher_id, 
            CONCAT(first_name, ' ', middle_name, ' ', last_name) AS full_name, 
            phone, 
            email,
            hire_date
        FROM teachers 
        ORDER BY teacher_id ASC";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Error fetching teachers: " . mysqli_error($conn));
}

$page_title = "Manage Teachers";
include 'partials/header.php';
?>

<div class="container">
    <div class="card shadow-sm">
        <div class="card-header bg-info text-white">
            <h1 class="h4 mb-0"><i class="fas fa-chalkboard-teacher me-2"></i>Teacher Management</h1>
            <a href="create_teacher.php" class="btn btn-light"><i class="fas fa-plus me-1"></i> Add Teacher</a>
        </div>
        <div class="card-body">
            <?php if (isset($_GET['status'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php 
                        if ($_GET['status'] == 'created') echo 'Teacher added successfully!'; 
                        if ($_GET['status'] == 'updated') echo 'Teacher updated successfully!'; 
                        if ($_GET['status'] == 'deleted') echo 'Teacher deleted successfully!'; 
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
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Hire Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($result) > 0): ?>
                            <?php while($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['teacher_id']); ?></td>
                                <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['phone']); ?></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td><?php echo date('F j, Y', strtotime($row['hire_date'])); ?></td>
                                <td>
                                    <a href="view_teacher.php?id=<?php echo $row['teacher_id']; ?>" class="btn btn-sm btn-info" title="View"><i class="fas fa-eye"></i></a>
                                    <a href="edit_teacher.php?id=<?php echo $row['teacher_id']; ?>" class="btn btn-sm btn-warning" title="Edit"><i class="fas fa-edit"></i></a>
                                    <a href="delete_teacher.php?id=<?php echo $row['teacher_id']; ?>" class="btn btn-sm btn-danger" title="Delete"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">No teachers found.</td>
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