<?php
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Error: Invalid Report ID.");
}
$report_id = $_GET['id'];

require_once 'data/db_connect.php';

$sql = "SELECT 
            r.*,
            u.username AS rep_name,
            CONCAT('Grade ', s.grade_level, ' ', s.stream) AS section_name
        FROM reports r
        LEFT JOIN users u ON r.rep_id = u.user_id
        LEFT JOIN sections s ON r.section_id = s.section_id
        WHERE r.report_id = ?";

$report = null;
if ($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $report_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($result) == 1) {
        $report = mysqli_fetch_assoc($result);
    } else {
        die("Error: Report not found.");
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
    <title>View Report</title>
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
        <div class="card-header bg-success text-white">
            <h1 class="h4 mb-0"><i class="fas fa-file-alt me-2"></i>Report Details</h1>
        </div>
        <div class="card-body">
            <?php if ($report): ?>
            <div class="d-flex justify-content-between text-muted mb-3">
                <div>
                    <strong>Filed By:</strong> <?php echo htmlspecialchars($report['rep_name'] ?? 'N/A'); ?><br>
                    <strong>Section:</strong> <?php echo htmlspecialchars($report['section_name'] ?? 'N/A'); ?>
                </div>
                <div>
                    <strong>Type:</strong> <?php echo htmlspecialchars($report['type']); ?><br>
                    <strong>Date:</strong> <?php echo date('F j, Y, g:i a', strtotime($report['created_at'])); ?>
                </div>
            </div>
            <hr>
            <h5 class="card-title">Report Details</h5>
            <div class="report-content p-3 bg-light rounded">
                <p><?php echo nl2br(htmlspecialchars($report['details'])); ?></p>
            </div>
            <?php endif; ?>
        </div>
        <div class="card-footer text-end">
            <a href="reports.php" class="btn btn-secondary"><i class="fas fa-arrow-left me-1"></i> Back to Log</a>
        </div>
    </div>
</div>

</body>
</html>