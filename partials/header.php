<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'School Management System'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .container { margin-top: 20px; margin-bottom: 20px; }
        .table-hover tbody tr:hover { background-color: #e9ecef; }
        .card-header { display: flex; justify-content: space-between; align-items: center; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="dashboard.php"><i class="fas fa-school me-2"></i>SMS</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php"><i class="fas fa-tachometer-alt me-1"></i>Dashboard</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="academicsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-university me-1"></i> Academics
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="academicsDropdown">
                        <li><a class="dropdown-item" href="subjects.php">Subjects</a></li>
                        <li><a class="dropdown-item" href="sections.php">Sections</a></li>
                        <li><a class="dropdown-item" href="classrooms.php">Classrooms</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="subject_assignments.php">Subject Assignments</a></li>
                        <li><a class="dropdown-item" href="attendance.php">Take Attendance</a></li>
                        <li><a class="dropdown-item" href="auto_assign_students.php">Student Section Assignment</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="peopleDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-users me-1"></i> People
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="peopleDropdown">
                        <li><a class="dropdown-item" href="students.php">Students</a></li>
                        <li><a class="dropdown-item" href="teachers.php">Teachers</a></li>
                        <li><a class="dropdown-item" href="guardians.php">Guardians</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="users.php">System Users</a></li>
                    </ul>
                </li>
                 <li class="nav-item">
                    <a class="nav-link" href="reports.php"><i class="fas fa-file-alt me-1"></i>Reports</a>
                </li>
                 <li class="nav-item">
                    <a class="nav-link" href="messages.php"><i class="fas fa-envelope me-1"></i>Messages</a>
                </li>
            </ul>
        </div>
    </div>
</nav>