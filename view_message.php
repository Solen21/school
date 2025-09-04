<?php
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Error: Invalid Message ID.");
}
$message_id = $_GET['id'];

require_once 'data/db_connect.php';

$sql = "SELECT 
            m.content, m.created_at,
            sender.username AS sender_name, 
            receiver.username AS receiver_name
        FROM messages m
        LEFT JOIN users sender ON m.sender_id = sender.user_id
        LEFT JOIN users receiver ON m.receiver_id = receiver.user_id
        WHERE m.message_id = ?";

$message = null;
if ($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $message_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($result) == 1) {
        $message = mysqli_fetch_assoc($result);
    } else {
        die("Error: Message not found.");
    }
    mysqli_stmt_close($stmt);
}
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Message</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .container { max-width: 800px; margin-top: 50px; }
    </style>
</head>
<body>

<div class="container">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h1 class="h4 mb-0"><i class="fas fa-envelope-open-text me-2"></i>Message Details</h1>
        </div>
        <div class="card-body">
            <?php if ($message): ?>
            <div class="d-flex justify-content-between text-muted mb-3">
                <div>
                    <strong>From:</strong> <?php echo htmlspecialchars($message['sender_name'] ?? 'N/A'); ?><br>
                    <strong>To:</strong> <?php echo htmlspecialchars($message['receiver_name'] ?? 'N/A'); ?>
                </div>
                <div>
                    <strong>Date:</strong> <?php echo date('F j, Y, g:i a', strtotime($message['created_at'])); ?>
                </div>
            </div>
            <hr>
            <div class="message-content p-3 bg-light rounded">
                <p><?php echo nl2br(htmlspecialchars($message['content'])); ?></p>
            </div>
            <?php endif; ?>
        </div>
        <div class="card-footer text-end">
            <a href="messages.php" class="btn btn-secondary"><i class="fas fa-arrow-left me-1"></i> Back to Log</a>
        </div>
    </div>
</div>

</body>
</html>