<?php
session_start();
$message = '';
$message_type = 'danger';

// If the user is already logged in, redirect them to the dashboard
if (isset($_SESSION["user_id"])) {
    header("location: dashboard.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once 'data/db_connect.php';

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $message = "Username and password are required.";
    } else {
        // Use prepared statements to prevent SQL injection
        $sql = "SELECT user_id, username, password, role FROM users WHERE username = ?";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) == 1) {
                $user = mysqli_fetch_assoc($result);
                // Verify the hashed password
                if (password_verify($password, $user['password'])) {
                    // Password is correct, so start a new session
                    session_regenerate_id();
                    $_SESSION["user_id"] = $user['user_id'];
                    $_SESSION["username"] = $user['username'];
                    $_SESSION["role"] = $user['role'];

                    // Redirect user to the dashboard
                    header("location: dashboard.php");
                    exit;
                } else {
                    $message = "Invalid username or password.";
                }
            } else {
                $message = "Invalid username or password.";
            }
            mysqli_stmt_close($stmt);
        }
        mysqli_close($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | School Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { background-color: #f4f7f6; display: flex; align-items: center; justify-content: center; height: 100vh; }
        .login-card { max-width: 400px; width: 100%; }
    </style>
</head>
<body>
    <div class="card shadow-lg login-card">
        <div class="card-header text-center bg-dark text-white">
            <h1 class="h3 my-2"><i class="fas fa-school me-2"></i>Admin Portal</h1>
        </div>
        <div class="card-body p-4">
            <p class="text-center text-muted">Please sign in to continue</p>
            <?php if ($message): ?>
            <div class="alert alert-<?php echo $message_type; ?>"><?php echo $message; ?></div>
            <?php endif; ?>
            <form action="login.php" method="post">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
                    <label for="username">Username</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                    <label for="password">Password</label>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-sign-in-alt me-1"></i> Sign In</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>