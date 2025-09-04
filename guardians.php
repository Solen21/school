<?php
// Include the database connection file
require_once 'data/db_connect.php';

// Fetch all guardians and their associated students' full names
$sql = "SELECT 
            g.guardian_id, 
            g.name, 
            g.relation, 
            g.phone, 
            CONCAT(s.first_name, ' ', s.middle_name, ' ', s.last_name) AS student_name
        FROM guardians g
        LEFT JOIN students s ON g.student_id = s.student_id
        ORDER BY g.guardian_id ASC";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Error fetching guardians: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Guardians</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
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
        <div class="card-header bg-dark text-white">
            <h1 class="h4 mb-0"><i class="fas fa-user-shield me-2"></i>Guardian Management</h1>
            <a href="create_guardian.php" class="btn btn-light"><i class="fas fa-plus me-1"></i> Add Guardian</a>
        </div>
        <div class="card-body">
            <?php if (isset($_GET['status'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php 
                        if ($_GET['status'] == 'created') echo 'Guardian added successfully!'; 
                        if ($_GET['status'] == 'updated') echo 'Guardian updated successfully!'; 
                        if ($_GET['status'] == 'deleted') echo 'Guardian deleted successfully!'; 
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Guardian Name</th>
                            <th>Relation</th>
                            <th>Phone</th>
                            <th>Student</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($result) > 0): ?>
                            <?php while($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['guardian_id']); ?></td>
                                <td><?php echo htmlspecialchars($row['name']); ?></td>
                                <td><?php echo htmlspecialchars($row['relation']); ?></td>
                                <td><?php echo htmlspecialchars($row['phone']); ?></td>
                                <td><?php echo htmlspecialchars($row['student_name'] ?? 'N/A'); ?></td>
                                <td>
                                    <a href="view_guardian.php?id=<?php echo $row['guardian_id']; ?>" class="btn btn-sm btn-info" title="View"><i class="fas fa-eye"></i></a>
                                    <a href="edit_guardian.php?id=<?php echo $row['guardian_id']; ?>" class="btn btn-sm btn-warning" title="Edit"><i class="fas fa-edit"></i></a>
                                    <a href="delete_guardian.php?id=<?php echo $row['guardian_id']; ?>" class="btn btn-sm btn-danger" title="Delete"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">No guardians found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php mysqli_close($conn); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>