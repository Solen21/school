<?php
require_once 'data/db_connect.php';

$message = '';
$message_type = '';
$subject = null;

if (!isset($_GET['id']) && !isset($_POST['subject_id'])) {
    die("Error: Subject ID not specified.");
}

$subject_id = isset($_POST['subject_id']) ? $_POST['subject_id'] : $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $code = trim($_POST['code']);
    $grade_level = $_POST['grade_level'];
    $stream = $_POST['stream'];
    $description = trim($_POST['description']);

    $sql = "UPDATE subjects SET name = ?, code = ?, grade_level = ?, stream = ?, description = ? WHERE subject_id = ?";

    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "ssissi", $name, $code, $grade_level, $stream, $description, $subject_id);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: subjects.php?status=updated");
            exit();
        } else {
            if (mysqli_errno($conn) == 1062) {
                $message = "Error: A subject with this code already exists.";
            } else {
                $message = "Error updating record: " . mysqli_stmt_error($stmt);
            }
            $message_type = "danger";
        }
        mysqli_stmt_close($stmt);
    }
}

$sql_fetch = "SELECT * FROM subjects WHERE subject_id = ?";
if ($stmt_fetch = mysqli_prepare($conn, $sql_fetch)) {
    mysqli_stmt_bind_param($stmt_fetch, "i", $subject_id);
    mysqli_stmt_execute($stmt_fetch);
    $result = mysqli_stmt_get_result($stmt_fetch);
    if (mysqli_num_rows($result) == 1) {
        $subject = mysqli_fetch_assoc($result);
    } else {
        die("Subject not found.");
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
    <title>Edit Subject</title>
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
        <div class="card-header bg-warning text-dark">
            <h1 class="h4 mb-0"><i class="fas fa-edit me-2"></i>Edit Subject</h1>
        </div>
        <div class="card-body">
            <?php if ($message): ?>
            <div class="alert alert-<?php echo $message_type; ?>"><?php echo $message; ?></div>
            <?php endif; ?>

            <?php if ($subject): ?>
            <form action="edit_subject.php" method="post">
                <input type="hidden" name="subject_id" value="<?php echo htmlspecialchars($subject_id); ?>">
                
                <div class="mb-3"><label class="form-label">Subject Name</label><input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($subject['name']); ?>" required></div>
                <div class="mb-3"><label class="form-label">Subject Code</label><input type="text" class="form-control" name="code" value="<?php echo htmlspecialchars($subject['code']); ?>" required></div>
                <div class="mb-3"><label class="form-label">Grade Level</label><input type="number" class="form-control" name="grade_level" value="<?php echo htmlspecialchars($subject['grade_level']); ?>" required></div>
                <div class="mb-3">
                    <label class="form-label">Stream</label>
                    <select name="stream" class="form-select">
                        <?php foreach (['Both', 'Natural', 'Social'] as $option): ?>
                        <option value="<?php echo $option; ?>" <?php if($subject['stream'] == $option) echo 'selected'; ?>><?php echo $option; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea class="form-control" name="description" rows="3"><?php echo htmlspecialchars($subject['description']); ?></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Update Subject</button>
                <a href="subjects.php" class="btn btn-secondary"><i class="fas fa-times me-1"></i> Cancel</a>
            </form>
            <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>