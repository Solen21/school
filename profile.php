<?php
require_once 'data/auth_check.php'; // Assumes this file exists from login system setup
require_once 'data/db_connect.php';

$page_title = "My Profile";
$user_id = $_SESSION['user_id'];
$message = '';
$message_type = 'danger';

// Handle password change form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $message = "All password fields are required.";
    } elseif ($new_password !== $confirm_password) {
        $message = "New password and confirmation do not match.";
    } else {
        // Fetch the current hashed password from the database
        $sql = "SELECT password FROM users WHERE user_id = ?";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "i", $user_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $user = mysqli_fetch_assoc($result);

            // Verify the current password
            if ($user && password_verify($current_password, $user['password'])) {
                // Hash the new password
                $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);

                // Update the password in the database
                $update_sql = "UPDATE users SET password = ? WHERE user_id = ?";
                if ($update_stmt = mysqli_prepare($conn, $update_sql)) {
                    mysqli_stmt_bind_param($update_stmt, "si", $hashed_new_password, $user_id);
                    if (mysqli_stmt_execute($update_stmt)) {
                        $message = "Password updated successfully!";
                        $message_type = "success";
                    } else {
                        $message = "Error updating password. Please try again.";
                    }
                    mysqli_stmt_close($update_stmt);
                }
            } else {
                $message = "Incorrect current password.";
            }
            mysqli_stmt_close($stmt);
        }
    }
}

// Fetch user's general information
$user_sql = "SELECT u.username, u.role, 
            COALESCE(t.first_name, s.first_name) as first_name,
            COALESCE(t.last_name, s.last_name) as last_name,
            COALESCE(t.email, s.email) as email
            FROM users u
            LEFT JOIN teachers t ON u.user_id = t.user_id
            LEFT JOIN students s ON u.user_id = s.user_id
            WHERE u.user_id = ?";
$user_info = null;
if ($stmt = mysqli_prepare($conn, $user_sql)) {
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user_info = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
}

include 'partials/header.php';
?>

<div class="container">
    <div class="row">
        <div class="col-lg-4" data-aos="fade-right">
            <div class="card shadow-sm mb-4">
                <div class="card-body text-center">
                    <img src="https://via.placeholder.com/150/0D6EFD/FFFFFF?text=<?php echo strtoupper(substr($user_info['first_name'] ?? 'U', 0, 1)); ?>" alt="avatar" class="rounded-circle img-fluid mb-3" style="width: 150px;">
                    <h5 class="my-3"><?php echo htmlspecialchars(($user_info['first_name'] ?? '') . ' ' . ($user_info['last_name'] ?? $user_info['username'])); ?></h5>
                    <p class="text-muted mb-1"><?php echo htmlspecialchars(ucfirst($user_info['role'])); ?></p>
                    <p class="text-muted mb-4"><?php echo htmlspecialchars($user_info['email'] ?? 'No email on file'); ?></p>
                </div>
            </div>
        </div>
        <div class="col-lg-8" data-aos="fade-left">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-4"><i class="fas fa-key me-2"></i>Change Password</h5>
                    
                    <?php if ($message): ?>
                    <div class="alert alert-<?php echo $message_type; ?>"><?php echo $message; ?></div>
                    <?php endif; ?>

                    <form action="profile.php" method="post">
                        <input type="hidden" name="change_password" value="1">
                        <div class="mb-3"><label for="current_password" class="form-label">Current Password</label><input type="password" class="form-control" id="current_password" name="current_password" required></div>
                        <div class="mb-3"><label for="new_password" class="form-label">New Password</label><input type="password" class="form-control" id="new_password" name="new_password" required></div>
                        <div class="mb-3"><label for="confirm_password" class="form-label">Confirm New Password</label><input type="password" class="form-control" id="confirm_password" name="confirm_password" required></div>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Update Password</button>
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