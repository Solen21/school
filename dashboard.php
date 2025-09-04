<?php
require_once 'data/db_connect.php';

// --- Fetch counts for the dashboard cards ---
$student_count_result = mysqli_query($conn, "SELECT COUNT(*) AS count FROM students WHERE status = 'active'");
$student_count = mysqli_fetch_assoc($student_count_result)['count'];

$teacher_count_result = mysqli_query($conn, "SELECT COUNT(*) AS count FROM teachers");
$teacher_count = mysqli_fetch_assoc($teacher_count_result)['count'];

$section_count_result = mysqli_query($conn, "SELECT COUNT(*) AS count FROM sections");
$section_count = mysqli_fetch_assoc($section_count_result)['count'];

$guardian_count_result = mysqli_query($conn, "SELECT COUNT(*) AS count FROM guardians");
$guardian_count = mysqli_fetch_assoc($guardian_count_result)['count'];

// --- Fetch recent additions ---
$recent_students_sql = "SELECT student_id, CONCAT(first_name, ' ', last_name) as name, registered_at FROM students ORDER BY student_id DESC LIMIT 5";
$recent_students_result = mysqli_query($conn, $recent_students_sql);

$page_title = "Dashboard";
include 'partials/header.php';
?>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2">School Dashboard</h1>
        <div>
            <a href="create_student.php" class="btn btn-primary"><i class="fas fa-user-plus me-1"></i> Add Student</a>
            <a href="create_teacher.php" class="btn btn-info text-white"><i class="fas fa-chalkboard-teacher me-1"></i> Add Teacher</a>
        </div>
    </div>

    <div class="row">
        <!-- Students Card -->
        <div class="col-xl-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="100">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-primary text-uppercase mb-1">Active Students</div>
                            <div class="h5 mb-0 fw-bold text-gray-800"><?php echo $student_count; ?></div>
                        </div>
                        <div class="col-auto"><i class="fas fa-user-graduate fa-2x text-muted"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Teachers Card -->
        <div class="col-xl-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="200">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-success text-uppercase mb-1">Teachers</div>
                            <div class="h5 mb-0 fw-bold text-gray-800"><?php echo $teacher_count; ?></div>
                        </div>
                        <div class="col-auto"><i class="fas fa-chalkboard-teacher fa-2x text-muted"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sections Card -->
        <div class="col-xl-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="300">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-info text-uppercase mb-1">Sections</div>
                            <div class="h5 mb-0 fw-bold text-gray-800"><?php echo $section_count; ?></div>
                        </div>
                        <div class="col-auto"><i class="fas fa-door-open fa-2x text-muted"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Guardians Card -->
        <div class="col-xl-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="400">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-warning text-uppercase mb-1">Guardians</div>
                            <div class="h5 mb-0 fw-bold text-gray-800"><?php echo $guardian_count; ?></div>
                        </div>
                        <div class="col-auto"><i class="fas fa-user-shield fa-2x text-muted"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row">
        <div class="col-lg-7 mb-4" data-aos="fade-right">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 fw-bold text-primary">Recently Registered Students</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-borderless">
                            <tbody>
                                <?php while($student = mysqli_fetch_assoc($recent_students_result)): ?>
                                <tr>
                                    <td><a href="view_student.php?id=<?php echo $student['student_id']; ?>"><?php echo htmlspecialchars($student['name']); ?></a></td>
                                    <td class="text-end text-muted"><?php echo date('M j, Y', strtotime($student['registered_at'])); ?></td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-5 mb-4" data-aos="fade-left">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 fw-bold text-primary">Quick Links</h6>
                </div>
                <div class="list-group list-group-flush">
                    <a href="attendance.php" class="list-group-item list-group-item-action"><i class="fas fa-user-check fa-fw me-2"></i>Take Attendance</a>
                    <a href="subject_assignments.php" class="list-group-item list-group-item-action"><i class="fas fa-link fa-fw me-2"></i>Assign Subjects</a>
                    <a href="auto_assign_students.php" class="list-group-item list-group-item-action"><i class="fas fa-sitemap fa-fw me-2"></i>Assign Students to Sections</a>
                    <a href="reports.php" class="list-group-item list-group-item-action"><i class="fas fa-file-alt fa-fw me-2"></i>View Reports</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
mysqli_close($conn);
include 'partials/footer.php'; 
?>