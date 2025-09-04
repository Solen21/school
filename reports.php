<?php
require_once 'data/db_connect.php';

$sql = "SELECT 
            r.report_id,
            u.username AS rep_name,
            CONCAT('Grade ', s.grade_level, ' ', s.stream) AS section_name,
            r.type,
            r.details,
            r.created_at
        FROM reports r
        LEFT JOIN users u ON r.rep_id = u.user_id
        LEFT JOIN sections s ON r.section_id = s.section_id
        ORDER BY r.created_at DESC";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Error fetching reports: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Reports</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .container { margin-top: 30px; }
        .table-hover tbody tr:hover { background-color: #e9ecef; }
        .card-header { display: flex; justify-content: space-between; align-items: center; }
        .details-preview { max-width: 300px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    </style>
</head>
<body>

<div class="container">
    <div class="card shadow-sm">
        <div class="card-header bg-success text-white">
            <h1 class="h4 mb-0"><i class="fas fa-file-alt me-2"></i>Report Log</h1>
            <a href="create_report.php" class="btn btn-light"><i class="fas fa-plus me-1"></i> Create Report</a>
        </div>
        <div class="card-body">
            <?php if (isset($_GET['status'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php 
                        if ($_GET['status'] == 'created') echo 'Report created successfully!'; 
                        if ($_GET['status'] == 'deleted') echo 'Report deleted successfully!'; 
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Filed By (Rep)</th>
                            <th>Section</th>
                            <th>Type</th>
                            <th>Details</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($result) > 0): ?>
                            <?php while($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['report_id']); ?></td>
                                <td><?php echo htmlspecialchars($row['rep_name'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($row['section_name'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($row['type']); ?></td>
                                <td><div class="details-preview"><?php echo htmlspecialchars($row['details']); ?></div></td>
                                <td><?php echo date('M j, Y', strtotime($row['created_at'])); ?></td>
                                <td>
                                    <a href="view_report.php?id=<?php echo $row['report_id']; ?>" class="btn btn-sm btn-info" title="View"><i class="fas fa-eye"></i></a>
                                    <a href="delete_report.php?id=<?php echo $row['report_id']; ?>" class="btn btn-sm btn-danger" title="Delete"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center">No reports found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php mysqli_close($conn); ?>
</body>
</html>