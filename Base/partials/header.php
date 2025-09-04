<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'School Management System'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { background-color: #f4f7f6; }
        .container { margin-top: 20px; margin-bottom: 20px; }
        .table-hover tbody tr:hover { background-color: #e9ecef; }
        .card-header { display: flex; justify-content: space-between; align-items: center; }

        /* Simple fade-in animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .container { animation: fadeIn 0.6s ease-in-out; }

        /* Custom styles for dashboard cards */
        .card.border-left-primary { border-left: .25rem solid #4e73df !important; }
        .card.border-left-success { border-left: .25rem solid #1cc88a !important; }
        .card.border-left-info { border-left: .25rem solid #36b9cc !important; }
        .card.border-left-warning { border-left: .25rem solid #f6c23e !important; }
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
            <form class="d-flex me-3" role="search">
                <input class="form-control form-control-sm me-2" type="search" placeholder="Search..." aria-label="Search">
                <button class="btn btn-sm btn-outline-light" type="submit"><i class="fas fa-search"></i></button>
            </form>
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user-circle me-1"></i> Admin
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="#">Profile</a></li>
                        <li><a class="dropdown-item" href="#">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>