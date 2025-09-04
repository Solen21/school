<?php
require_once 'data/db_connect.php';

$sql = "SELECT classroom_id, name, capacity, resources FROM classrooms ORDER BY name ASC";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Error fetching classrooms: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Classrooms</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .container { margin-top: 30px; }
        .table-hover tbody tr:hover { background-color: #e9ecef; }
        .card-header { display: flex; justify-content: space-between; align-items: center; }
    </style>
</head>
<body>

<div class="container">
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            <h1 class="h4 mb-0"><i class="fas fa-school me-2"></i>Classroom Management</h1>
            <a href="create_classroom.php" class="btn btn-light"><i class="fas fa-plus me-1"></i> Add Classroom</a>
        </div>
        <div class="card-body">
            <?php if (isset($_GET['status'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php 
                        if ($_GET['status'] == 'created') echo 'Classroom added successfully!'; 
                        if ($_GET['status'] == 'updated') echo 'Classroom updated successfully!'; 
                        if ($_GET['status'] == 'deleted') echo 'Classroom deleted successfully!'; 
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Classroom Name</th>
                            <th>Capacity</th>
                            <th>Resources</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($result) > 0): ?>
                            <?php while($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['classroom_id']); ?></td>
                                <td><?php echo htmlspecialchars($row['name']); ?></td>
                                <td><?php echo htmlspecialchars($row['capacity']); ?></td>

                                <td><?php echo nl2br(htmlspecialchars($row['resources'])); ?></td>
                                <td>
                                    <a href="edit_classroom.php?id=<?php echo $row['classroom_id']; ?>" class="btn btn-sm btn-warning" title="Edit"><i class="fas fa-edit"></i></a>
                                    <a href="delete_classroom.php?id=<?php echo $row['classroom_id']; ?>" class="btn btn-sm btn-danger" title="Delete"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                                 <a href="assign_students.php?classroom_id=<?php echo $row['classroom_id']; ?>" class="btn btn-sm btn-primary" title="Assign Students">
                                  <i class="fas fa-user-plus"></i> Assign Students
                                  </a>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center">No classrooms found.</td>
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