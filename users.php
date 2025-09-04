<?php
// Include the database connection file
require_once 'data/db_connect.php';

// Fetch all users from the database, ordered by their ID.
// We are selecting specific columns and excluding the password for security and clarity.
$sql = "SELECT user_id, username, email, role, created_at FROM users ORDER BY user_id ASC";
$result = mysqli_query($conn, $sql);

// Check if the query was successful
if (!$result) {
    die("Error fetching users: " . mysqli_error($conn));
}

$page_title = "Manage Users";
include 'partials/header.php';
?>

<div class="container">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h1 class="h4 mb-0"><i class="fas fa-users me-2"></i>User Management</h1>
            <a href="create_user.php" class="btn btn-light"><i class="fas fa-plus me-1"></i> Add User</a>
        </div>
        <div class="card-body">
            <?php if (isset($_GET['status'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php 
                        if ($_GET['status'] == 'created') echo 'User created successfully!'; 
                        if ($_GET['status'] == 'updated') echo 'User updated successfully!'; 
                        if ($_GET['status'] == 'deleted') echo 'User deleted successfully!'; 
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Date Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($result) > 0): ?>
                            <?php while($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['user_id']); ?></td>
                                <td><?php echo htmlspecialchars($row['username']); ?></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td><?php echo htmlspecialchars(ucfirst($row['role'])); ?></td>
                                <td><?php echo date('F j, Y, g:i a', strtotime($row['created_at'])); ?></td>
                                <td>
                                    <a href="view_user.php?id=<?php echo $row['user_id']; ?>" class="btn btn-sm btn-info" title="View"><i class="fas fa-eye"></i></a>
                                    <a href="edit_user.php?id=<?php echo $row['user_id']; ?>" class="btn btn-sm btn-warning" title="Edit"><i class="fas fa-edit"></i></a>
                                    <a href="delete_user.php?id=<?php echo $row['user_id']; ?>" class="btn btn-sm btn-danger" title="Delete"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">No users found.</td>
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
include 'partials/footer.php';
?>