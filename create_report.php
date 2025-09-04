<?php
require_once 'data/db_connect.php';

$message = '';
$message_type = '';

// Fetch users with 'rep' role
$reps_result = mysqli_query($conn, "SELECT user_id, username FROM users WHERE role = 'rep' ORDER BY username ASC");
// Fetch all sections
$sections_result = mysqli_query($conn, "SELECT section_id, CONCAT('Grade ', grade_level, ' ', stream) AS section_name FROM sections ORDER BY grade_level, stream");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST['rep_id']) || empty($_POST['section_id']) || empty($_POST['type']) || empty(trim($_POST['details']))) {
        $message = "All fields are required.";
        $message_type = "danger";
    } else {
        $sql = "INSERT INTO reports (rep_id, section_id, type, details) VALUES (?, ?, ?, ?)";
        
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "iiss", 
                $_POST['rep_id'],
                $_POST['section_id'],
                $_POST['type'],
                $_POST['details']
            );

            if (mysqli_stmt_execute($stmt)) {
                header("Location: reports.php?status=created");
                exit();
            } else {
                $message = "Error: Could not create report. " . mysqli_stmt_error($stmt);
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
    <title>Create New Report</title>
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
        <div class="card-header bg-success text-white">
            <h1 class="h4 mb-0"><i class="fas fa-plus me-2"></i>Create New Report</h1>
        </div>
        <div class="card-body">
            <?php if ($message): ?>
            <div class="alert alert-<?php echo $message_type; ?>"><?php echo $message; ?></div>
            <?php endif; ?>

            <form action="create_report.php" method="post">
                <div class="mb-3">
                    <label for="rep_id" class="form-label">Filed By (Representative)</label>
                    <select class="form-select" id="rep_id" name="rep_id" required>
                        <option value="" selected disabled>Select a representative...</option>
                        <?php while($rep = mysqli_fetch_assoc($reps_result)): ?>
                            <option value="<?php echo $rep['user_id']; ?>"><?php echo htmlspecialchars($rep['username']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="section_id" class="form-label">For Section</label>
                    <select class="form-select" id="section_id" name="section_id" required>
                        <option value="" selected disabled>Select a section...</option>
                        <?php while($section = mysqli_fetch_assoc($sections_result)): ?>
                            <option value="<?php echo $section['section_id']; ?>"><?php echo htmlspecialchars($section['section_name']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="type" class="form-label">Report Type</label>
                    <select class="form-select" id="type" name="type" required>
                        <option value="" selected disabled>Select type...</option>
                        <option value="Attendance">Attendance</option>
                        <option value="Behavior">Behavior</option>
                        <option value="Academic">Academic</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="details" class="form-label">Details</label>
                    <textarea class="form-control" id="details" name="details" rows="5" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary"><i class="fas fa-check me-1"></i> Submit Report</button>
                <a href="reports.php" class="btn btn-secondary"><i class="fas fa-times me-1"></i> Cancel</a>
            </form>
        </div>
    </div>
</div>

<?php mysqli_close($conn); ?>
</body>
</html>