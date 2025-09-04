<?php
require_once 'data/db_connect.php';

$message = '';
$message_type = '';
$section = null;

if (!isset($_GET['id']) && !isset($_POST['section_id'])) {
    die("Error: Section ID not specified.");
}

$section_id = isset($_POST['section_id']) ? $_POST['section_id'] : $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $grade_level = $_POST['grade_level'];
    $stream = $_POST['stream'];
    $capacity = $_POST['capacity'];
    $shift = $_POST['shift'];

    $sql = "UPDATE sections SET grade_level = ?, stream = ?, capacity = ?, shift = ? WHERE section_id = ?";

    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "isisi", $grade_level, $stream, $capacity, $shift, $section_id);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: sections.php?status=updated");
            exit();
        } else {
            $message = "Error updating record: " . mysqli_stmt_error($stmt);
            $message_type = "danger";
        }
        mysqli_stmt_close($stmt);
    }
}

$sql_fetch = "SELECT * FROM sections WHERE section_id = ?";
if ($stmt_fetch = mysqli_prepare($conn, $sql_fetch)) {
    mysqli_stmt_bind_param($stmt_fetch, "i", $section_id);
    mysqli_stmt_execute($stmt_fetch);
    $result = mysqli_stmt_get_result($stmt_fetch);
    if (mysqli_num_rows($result) == 1) {
        $section = mysqli_fetch_assoc($result);
    } else {
        die("Section not found.");
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
    <title>Edit Section</title>
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
            <h1 class="h4 mb-0"><i class="fas fa-edit me-2"></i>Edit Section</h1>
        </div>
        <div class="card-body">
            <?php if ($message): ?>
            <div class="alert alert-<?php echo $message_type; ?>"><?php echo $message; ?></div>
            <?php endif; ?>

            <?php if ($section): ?>
            <form action="edit_section.php" method="post">
                <input type="hidden" name="section_id" value="<?php echo htmlspecialchars($section_id); ?>">
                
                <div class="mb-3">
                    <label class="form-label">Grade Level</label>
                    <select name="grade_level" class="form-select" required>
                        <?php foreach (['9', '10', '11', '12'] as $grade): ?>
                        <option value="<?php echo $grade; ?>" <?php if($section['grade_level'] == $grade) echo 'selected'; ?>><?php echo $grade; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Stream</label>
                    <select name="stream" class="form-select" required>
                        <?php foreach (['Natural', 'Social'] as $stream): ?>
                        <option value="<?php echo $stream; ?>" <?php if($section['stream'] == $stream) echo 'selected'; ?>><?php echo $stream; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3"><label class="form-label">Capacity</label><input type="number" class="form-control" name="capacity" value="<?php echo htmlspecialchars($section['capacity']); ?>" required></div>
                <div class="mb-3">
                    <label class="form-label">Shift</label>
                    <select name="shift" class="form-select" required>
                        <?php foreach (['Morning', 'Afternoon'] as $shift): ?>
                        <option value="<?php echo $shift; ?>" <?php if($section['shift'] == $shift) echo 'selected'; ?>><?php echo $shift; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Update Section</button>
                <a href="sections.php" class="btn btn-secondary"><i class="fas fa-times me-1"></i> Cancel</a>
            </form>
            <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>