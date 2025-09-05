<?php
require_once 'data/auth_check.php';
$message = '';
$message_type = '';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include the database connection
    require_once 'data/db_connect.php';

    // Get form data and trim whitespace
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $role = $_POST['role'];

    // --- Validation ---
    if (empty($username) || empty($password) || empty($role)) {
        $message = "All fields are required.";
        $message_type = "danger";
    } else {
        // --- Check for existing username before attempting to insert ---
        $sql_check = "SELECT user_id FROM users WHERE username = ?";
        if ($stmt_check = mysqli_prepare($conn, $sql_check)) {
            mysqli_stmt_bind_param($stmt_check, "s", $username);
            mysqli_stmt_execute($stmt_check);
            mysqli_stmt_store_result($stmt_check);

            if (mysqli_stmt_num_rows($stmt_check) > 0) {
                $message = "Error: This username already exists. Please choose another one.";
                $message_type = "danger";
            } else {
                // --- Securely hash the password ---
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // --- Use Prepared Statements to prevent SQL injection ---
                $sql = "INSERT INTO users (username, password, role) VALUES (?, ?, ?)";
                
                if ($stmt = mysqli_prepare($conn, $sql)) {
                    // Bind variables to the prepared statement as parameters
                    mysqli_stmt_bind_param($stmt, "sss", $username, $hashed_password, $role);

                    // Attempt to execute the prepared statement
                    if (mysqli_stmt_execute($stmt)) {
                        // Redirect to the user list page on success
                        header("Location: users.php?status=created");
                        exit();
                    } else {
                        $message = "Error: Something went wrong. Please try again later.";
                        $message_type = "danger";
                    }
                    // Close statement
                    mysqli_stmt_close($stmt);
                }
            }
            mysqli_stmt_close($stmt_check);
        }
    }
    // Close connection
    mysqli_close($conn);
}

$page_title = "Create New User";
include 'partials/header.php';
?>

<div class="container">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h1 class="h4 mb-0"><i class="fas fa-user-plus me-2"></i>Create New User</h1>
        </div>
        <div class="card-body">
            <?php if ($message): ?>
            <div class="alert alert-<?php echo $message_type; ?>">
                <?php echo $message; ?>
            </div>
            <?php endif; ?>

            <form action="create_user.php" method="post">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="mb-3">
                    <label for="role" class="form-label">Role</label>
                    <select class="form-select" id="role" name="role" required>
                        <option value="" selected disabled>Select a role...</option>
                        <option value="admin">Admin</option>
                        <option value="director">Director</option>
                        <option value="teacher">Teacher</option>
                        <option value="student">Student</option>
                        <option value="rep">Representative</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-success"><i class="fas fa-check me-1"></i> Save User</button>
                <a href="users.php" class="btn btn-secondary"><i class="fas fa-times me-1"></i> Cancel</a>
            </form>
        </div>
    </div>
</div>

<?php
include 'partials/footer.php';
?>