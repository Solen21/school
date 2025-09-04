<?php
require_once 'data/db_connect.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Error: Invalid Assignment ID.");
}
$assignment_id = $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['assignment_id']) && $_POST['assignment_id'] == $assignment_id) {
        $sql = "DELETE FROM subject_assignments WHERE assignment_id = ?";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "i", $assignment_id);
            if (mysqli_stmt_execute($stmt)) {
                header("Location: subject_assignments.php?status=deleted");
                exit();
            } else {
                die("Error deleting record: " . mysqli_error($conn));
            }
            mysqli_stmt_close($stmt);
        }
    }
}
mysqli_close($conn);

$page_title = "Delete Assignment";
include 'partials/header.php';
?>

<div class="container">
    <div class="card border-danger shadow-sm">
        <div class="card-header bg-danger text-white">
            <h1 class="h4 mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Confirm Deletion</h1>
        </div>
        <div class="card-body">
            <p>Are you sure you want to delete this subject assignment (ID: <?php echo htmlspecialchars($assignment_id); ?>)?</p>
            <p class="text-danger">This action cannot be undone.</p>
            
            <form action="delete_subject_assignment.php?id=<?php echo $assignment_id; ?>" method="post">
                <input type="hidden" name="assignment_id" value="<?php echo $assignment_id; ?>">
                <button type="submit" class="btn btn-danger"><i class="fas fa-trash me-1"></i> Yes, Delete</button>
                <a href="subject_assignments.php" class="btn btn-secondary"><i class="fas fa-times me-1"></i> No, Cancel</a>
            </form>
        </div>
    </div>
</div>

<?php include 'partials/footer.php'; ?>