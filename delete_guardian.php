<?php
require_once 'data/db_connect.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Error: Invalid Guardian ID.");
}
$guardian_id = $_GET['id'];

// --- Handle Deletion (if form is submitted) ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['guardian_id']) && $_POST['guardian_id'] == $guardian_id) {
        $sql = "DELETE FROM guardians WHERE guardian_id = ?";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "i", $guardian_id);
            if (mysqli_stmt_execute($stmt)) {
                header("Location: guardians.php?status=deleted");
                exit();
            } else {
                die("Error deleting record: " . mysqli_error($conn));
            }
            mysqli_stmt_close($stmt);
        }
    }
}

// --- Fetch guardian data for confirmation message ---
$sql_fetch = "SELECT name FROM guardians WHERE guardian_id = ?";
$guardian_name = '';
if ($stmt_fetch = mysqli_prepare($conn, $sql_fetch)) {
    mysqli_stmt_bind_param($stmt_fetch, "i", $guardian_id);
    mysqli_stmt_execute($stmt_fetch);
    $result = mysqli_stmt_get_result($stmt_fetch);
    if (mysqli_num_rows($result) == 1) {
        $guardian = mysqli_fetch_assoc($result);
        $guardian_name = $guardian['name'];
    } else {
        die("Guardian not found.");
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
    <title>Delete Guardian</title>
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
            <p>Are you sure you want to delete the guardian <strong><?php echo htmlspecialchars($guardian_name); ?></strong>?</p>
            <p class="text-danger">This action cannot be undone.</p>
            
            <form action="delete_guardian.php?id=<?php echo $guardian_id; ?>" method="post">
                <input type="hidden" name="guardian_id" value="<?php echo $guardian_id; ?>">
                <button type="submit" class="btn btn-danger"><i class="fas fa-trash me-1"></i> Yes, Delete Guardian</button>
                <a href="guardians.php" class="btn btn-secondary"><i class="fas fa-times me-1"></i> No, Cancel</a>
            </form>
        </div>
    </div>
</div>

</body>
</html>