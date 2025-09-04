<?php
require_once 'data/db_connect.php';

// Fetch counts for the dashboard cards
$student_count_result = mysqli_query($conn, "SELECT COUNT(*) AS count FROM students WHERE status = 'active'");
$student_count = mysqli_fetch_assoc($student_count_result)['count'];

$teacher_count_result = mysqli_query($conn, "SELECT COUNT(*) AS count FROM teachers");
$teacher_count = mysqli_fetch_assoc($teacher_count_result)['count'];

$subject_count_result = mysqli_query($conn, "SELECT COUNT(*) AS count FROM subjects");
$subject_count = mysqli_fetch_assoc($subject_count_result)['count'];

$section_count_result = mysqli_query($conn, "SELECT COUNT(*) AS count FROM sections");
$section_count = mysqli_fetch_assoc($section_count_result)['count'];

$page_title = "Dashboard";
include 'partials/header.php';
?>

<div class="container">
    <h1 class="mb-4">School Dashboard</h1>

    <div class="row">
        <!-- Students Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Active Students</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $student_count; ?></div>
                        </div>
                        <div class="col-auto"><i class="fas fa-user-graduate fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Teachers Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Teachers</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $teacher_count; ?></div>
                        </div>
                        <div class="col-auto"><i class="fas fa-chalkboard-teacher fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Subjects Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Subjects</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $subject_count; ?></div>
                        </div>
                        <div class="col-auto"><i class="fas fa-book fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'partials/footer.php'; ?>