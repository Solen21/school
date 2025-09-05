<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'School Management System'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- AOS (Animate on Scroll) CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

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
        /* We use AOS now, so this can be removed or kept as a fallback */
        /* .container { animation: fadeIn 0.6s ease-in-out; } */

        /* Custom styles for dashboard cards */
        .card.border-left-primary { border-left: .25rem solid #4e73df !important; }
        .card.border-left-success { border-left: .25rem solid #1cc88a !important; }
        .card.border-left-info { border-left: .25rem solid #36b9cc !important; }
        .card.border-left-warning { border-left: .25rem solid #f6c23e !important; }

        /* Dark Mode Toggle Switch */
        .theme-switch-wrapper { display: flex; align-items: center; }
        .theme-switch { display: inline-block; height: 24px; position: relative; width: 50px; margin-left: 1rem; }
        .theme-switch input { display:none; }
        .slider { background-color: #ccc; bottom: 0; cursor: pointer; left: 0; position: absolute; right: 0; top: 0; transition: .4s; }
        .slider:before { background-color: #fff; bottom: 4px; content: ""; height: 16px; left: 4px; position: absolute; transition: .4s; width: 16px; }
        input:checked + .slider { background-color: #66bb6a; }
        input:checked + .slider:before { transform: translateX(26px); }
        .slider.round { border-radius: 34px; }
        .slider.round:before { border-radius: 50%; }

        /* --- Dark Mode Styles for Admin Panel --- */
        body.dark-mode { background-color: #111827; color: #d1d5db; }
        body.dark-mode .navbar.bg-dark { background-color: #1f2937 !important; }
        body.dark-mode .card { background-color: #1f2937; border-color: #374151; }
        body.dark-mode .card-header { background-color: #374151 !important; border-bottom: 1px solid #4b5563; }
        body.dark-mode .card-header.bg-primary,
        body.dark-mode .card-header.bg-success,
        body.dark-body .card-header.bg-info { background-color: #1f2937 !important; }
        body.dark-mode .text-primary { color: #60a5fa !important; }
        body.dark-mode .text-success { color: #4ade80 !important; }
        body.dark-mode .text-info { color: #22d3ee !important; }
        body.dark-mode .text-warning { color: #facc15 !important; }
        body.dark-mode .text-muted { color: #6b7280 !important; }
        body.dark-mode .table { color: #d1d5db; }
        body.dark-mode .table-striped > tbody > tr:nth-of-type(odd) > * { background-color: rgba(255, 255, 255, 0.05); }
        body.dark-mode .table-hover > tbody > tr:hover > * { background-color: rgba(255, 255, 255, 0.075); }
        body.dark-mode .table-bordered { border-color: #374151; }
        body.dark-mode .table-dark { background-color: #374151; border-color: #4b5563; }
        body.dark-mode .dropdown-menu { background-color: #374151; border-color: #4b5563; }
        body.dark-mode .dropdown-item { color: #d1d5db; }
        body.dark-mode .dropdown-item:hover { background-color: #4b5563; }
        body.dark-mode .list-group-item { background-color: #1f2937; border-color: #374151; color: #d1d5db; }
        body.dark-mode .list-group-item-action:hover { background-color: #374151; }
        body.dark-mode .btn-light { background-color: #4b5563; color: #e5e7eb; border-color: #6b7280; }
        body.dark-mode .form-control, body.dark-mode .form-select { background-color: #374151; color: #d1d5db; border-color: #4b5563; }
        body.dark-mode .form-control:focus, body.dark-mode .form-select:focus { background-color: #374151; color: #d1d5db; border-color: #60a5fa; box-shadow: 0 0 0 0.25rem rgba(96, 165, 250, 0.25); }
        body.dark-mode .alert-success { background-color: #166534; color: #a7f3d0; border-color: #15803d; }
    </style>
    <script>
        // Apply theme instantly to prevent flash of light mode
        (function() {
            const theme = localStorage.getItem('theme');
            if (theme === 'dark') {
                document.documentElement.classList.add('dark-mode');
                document.body.classList.add('dark-mode');
            }
        })();
    </script>
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
            <div class="theme-switch-wrapper">
                <label class="theme-switch" for="checkbox">
                    <input type="checkbox" id="checkbox" />
                    <div class="slider round"></div>
                </label>
            </div>
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user-circle me-1"></i> <?php echo htmlspecialchars($_SESSION['username']); ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="profile.php"><i class="fas fa-user-cog fa-fw me-2"></i>Profile</a></li>
                        <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt fa-fw me-2"></i>Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>