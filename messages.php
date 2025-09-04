<?php
require_once 'data/db_connect.php';

$sql = "SELECT 
            m.message_id, 
            sender.username AS sender_name, 
            receiver.username AS receiver_name, 
            m.content, 
            m.created_at 
        FROM messages m
        LEFT JOIN users sender ON m.sender_id = sender.user_id
        LEFT JOIN users receiver ON m.receiver_id = receiver.user_id
        ORDER BY m.created_at DESC";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Error fetching messages: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Messages</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .container { margin-top: 30px; }
        .table-hover tbody tr:hover { background-color: #e9ecef; }
        .card-header { display: flex; justify-content: space-between; align-items: center; }
        .content-preview {
            max-width: 400px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h1 class="h4 mb-0"><i class="fas fa-envelope me-2"></i>Message Log</h1>
            <a href="create_message.php" class="btn btn-light"><i class="fas fa-paper-plane me-1"></i> Send Message</a>
        </div>
        <div class="card-body">
            <?php if (isset($_GET['status'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php 
                        if ($_GET['status'] == 'created') echo 'Message sent successfully!'; 
                        if ($_GET['status'] == 'deleted') echo 'Message deleted successfully!'; 
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>From</th>
                            <th>To</th>
                            <th>Content</th>
                            <th>Date Sent</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($result) > 0): ?>
                            <?php while($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['message_id']); ?></td>
                                <td><?php echo htmlspecialchars($row['sender_name'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($row['receiver_name'] ?? 'N/A'); ?></td>
                                <td><div class="content-preview"><?php echo htmlspecialchars($row['content']); ?></div></td>
                                <td><?php echo date('M j, Y g:i A', strtotime($row['created_at'])); ?></td>
                                <td>
                                    <a href="view_message.php?id=<?php echo $row['message_id']; ?>" class="btn btn-sm btn-info" title="View"><i class="fas fa-eye"></i></a>
                                    <a href="delete_message.php?id=<?php echo $row['message_id']; ?>" class="btn btn-sm btn-danger" title="Delete"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">No messages found.</td>
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