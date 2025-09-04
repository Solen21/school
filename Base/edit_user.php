<?php
require_once 'data/db_connect.php';

$message = '';
$message_type = '';
$user = null;

// Check for a valid user ID
if (!isset($_GET['id']) && !isset($_POST['user_id'])) {
    die("Error: User ID not specified.");
}

$user_id = isset($_POST['user_id']) ? $_POST['user_id'] : $_GET['id'];

// --- Handle Form Submission (POST request) ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $role = $_POST['role'];
    $password = $_POST['password'];

    if (empty($username) || empty($role)) {
        $message = "Username and Role are required.";
        $message_type = "danger";
    } else {
        // Check if a new password was provided
        if (!empty($password)) {
            // Update with new password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "UPDATE users SET username = ?, password = ?, role = ? WHERE user_id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "sssi", $username, $hashed_password, $role, $user_id);
        } else {
            // Update without changing the password
            $sql = "UPDATE users SET username = ?, role = ? WHERE user_id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ssi", $username, $role, $user_id);
        }

        if (mysqli_stmt_execute($stmt)) {
            header("Location: users.php?status=updated");
            exit();
        } else {
            if (mysqli_errno($conn) == 1062) {
                $message = "Error: This username already exists.";
            } else {
                $message = "Error updating record: " . mysqli_error($conn);
            }
            $message_type = "danger";
        }
        mysqli_stmt_close($stmt);
    }
}

// --- Fetch User Data for Form (GET request) ---
$sql_fetch = "SELECT username, role FROM users WHERE user_id = ?";
if ($stmt_fetch = mysqli_prepare($conn, $sql_fetch)) {
    mysqli_stmt_bind_param($stmt_fetch, "i", $user_id);
    mysqli_stmt_execute($stmt_fetch);
    $result = mysqli_stmt_get_result($stmt_fetch);
    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
    } else {
        die("User not found.");
    }
    mysqli_stmt_close($stmt_fetch);
}
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .container { max-width: 600px; margin-top: 50px; }
    </style>
</head>
<body>

<div class="container">
    <div class="card shadow-sm">
        <div class="card-header bg-warning text-dark">
            <h1 class="h4 mb-0"><i class="fas fa-edit me-2"></i>Edit User</h1>
        </div>
        <div class="card-body">
            <?php if ($message): ?>
            <div class="alert alert-<?php echo $message_type; ?>">
                <?php echo $message; ?>
            </div>
            <?php endif; ?>

            <?php if ($user): ?>
            <form action="edit_user.php" method="post">
                <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user_id); ?>">
                
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                </div>
                
                <div class="mb-3">
                    <label for="password" class="form-label">New Password</label>
                    <input type="password" class="form-control" id="password" name="password">
                    <div class="form-text">Leave blank to keep the current password.</div>
                </div>
                
                <div class="mb-3">
                    <label for="role" class="form-label">Role</label>
                    <select class="form-select" id="role" name="role" required>
                        <?php $roles = ['admin', 'director', 'teacher', 'student', 'rep']; ?>
                        <?php foreach ($roles as $role_option): ?>
                            <option value="<?php echo $role_option; ?>" <?php if ($user['role'] == $role_option) echo 'selected'; ?>>
                                <?php echo ucfirst($role_option); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <button type="submit" class="btn btn-success"><i class="fas fa-save me-1"></i> Update User</button>
                <a href="users.php" class="btn btn-secondary"><i class="fas fa-times me-1"></i> Cancel</a>
            </form>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>