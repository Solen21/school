<?php
require_once 'data/db_connect.php';

$message = '';
$message_type = '';

// Fetch all users to populate the dropdowns
$users_result = mysqli_query($conn, "SELECT user_id, username FROM users ORDER BY username ASC");
// We need to clone the result object to loop through it twice for sender and receiver
$users_result_clone = mysqli_query($conn, "SELECT user_id, username FROM users ORDER BY username ASC");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST['sender_id']) || empty($_POST['receiver_id']) || empty(trim($_POST['content']))) {
        $message = "Sender, Receiver, and Content are required.";
        $message_type = "danger";
    } else {
        $sql = "INSERT INTO messages (sender_id, receiver_id, content) VALUES (?, ?, ?)";
        
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "iis", 
                $_POST['sender_id'],
                $_POST['receiver_id'],
                $_POST['content']
            );

            if (mysqli_stmt_execute($stmt)) {
                header("Location: messages.php?status=created");
                exit();
            } else {
                $message = "Error: Could not send message. " . mysqli_stmt_error($stmt);
                $message_type = "danger";
            }
            mysqli_stmt_close($stmt);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send New Message</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .container { max-width: 700px; margin-top: 50px; }
    </style>
</head>
<body>

<div class="container">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h1 class="h4 mb-0"><i class="fas fa-paper-plane me-2"></i>Send New Message</h1>
        </div>
        <div class="card-body">
            <?php if ($message): ?>
            <div class="alert alert-<?php echo $message_type; ?>"><?php echo $message; ?></div>
            <?php endif; ?>

            <form action="create_message.php" method="post">
                <div class="mb-3">
                    <label for="sender_id" class="form-label">From (Sender)</label>
                    <select class="form-select" id="sender_id" name="sender_id" required>
                        <option value="" selected disabled>Select sender...</option>
                        <?php while($user = mysqli_fetch_assoc($users_result)): ?>
                            <option value="<?php echo $user['user_id']; ?>"><?php echo htmlspecialchars($user['username']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="receiver_id" class="form-label">To (Receiver)</label>
                    <select class="form-select" id="receiver_id" name="receiver_id" required>
                        <option value="" selected disabled>Select receiver...</option>
                        <?php while($user = mysqli_fetch_assoc($users_result_clone)): ?>
                            <option value="<?php echo $user['user_id']; ?>"><?php echo htmlspecialchars($user['username']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="content" class="form-label">Message</label>
                    <textarea class="form-control" id="content" name="content" rows="5" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane me-1"></i> Send Message</button>
                <a href="messages.php" class="btn btn-secondary"><i class="fas fa-times me-1"></i> Cancel</a>
            </form>
        </div>
    </div>
</div>

<?php mysqli_close($conn); ?>
</body>
</html>