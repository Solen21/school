<?php
require_once 'data/db_connect.php';

$page_title = "Welcome to Our School";
include 'partials/header.php';

// Fetch some public data to display
$student_count_result = mysqli_query($conn, "SELECT COUNT(*) AS count FROM students WHERE status = 'active'");
$student_count = mysqli_fetch_assoc($student_count_result)['count'];

$teacher_count_result = mysqli_query($conn, "SELECT COUNT(*) AS count FROM teachers");
$teacher_count = mysqli_fetch_assoc($teacher_count_result)['count'];
?>

<div class="hero">
    <div class="container">
        <h1 class="display-4">Welcome to Our School</h1>
        <p class="lead">A place of learning, community, and excellence.</p>
    </div>
</div>

<div class="container mt-5">
    <div class="row text-center">
        <div class="col-md-6">
            <h2><i class="fas fa-user-graduate text-primary"></i> <?php echo $student_count; ?>+</h2>
            <p class="lead">Happy Students</p>
        </div>
        <div class="col-md-6">
            <h2><i class="fas fa-chalkboard-teacher text-success"></i> <?php echo $teacher_count; ?>+</h2>
            <p class="lead">Dedicated Teachers</p>
        </div>
    </div>
</div>

<?php include 'partials/footer.php'; ?>
