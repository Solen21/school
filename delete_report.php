<?php
require_once 'data/db_connect.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Error: Invalid Report ID.");
}
$report_id = $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['report_id']) && $_POST['report_id'] == $report_id) {
        $sql = "DELETE FROM reports WHERE report_id = ?";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "i", $report_id);
            if (mysqli_stmt_execute($stmt)) {
                header("Location: reports.php?status=deleted");
                exit();
            } else {
                die("Error deleting record: " . mysqli_error($conn));
            }
            mysqli_stmt_close($stmt);
        }
    }
}
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .container { max-width: 600px; margin-top: 50px; }
    </style>
</head>
<body>

<div class="container">
    <div class="card border-danger shadow-sm">
        <div class="card-header bg-danger text-white">
            <h1 class="h4 mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Confirm Deletion</h1>
        </div>
        <div class="card-body">
            <p>Are you sure you want to permanently delete this report (ID: <?php echo htmlspecialchars($report_id); ?>)?</p>
            <p class="text-danger">This action cannot be undone.</p>
            
            <form action="delete_report.php?id=<?php echo $report_id; ?>" method="post">
                <input type="hidden" name="report_id" value="<?php echo $report_id; ?>">
                <button type="submit" class="btn btn-danger"><i class="fas fa-trash me-1"></i> Yes, Delete Report</button>
                <a href="reports.php" class="btn btn-secondary"><i class="fas fa-times me-1"></i> No, Cancel</a>
            </form>
        </div>
    </div>
</div>

</body>
</html>