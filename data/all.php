<?php
$servername = "localhost";
$username   = "root";   // your DB username
$password   = "";       // your DB password
$dbname     = "school_management_system";

// Create connection
$conn = mysqli_connect($servername, $username, $password);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Create database if it doesn't exist, and suppress errors if it does
$sql_create_db = "CREATE DATABASE IF NOT EXISTS `$dbname`";
if (!mysqli_query($conn, $sql_create_db)) {
    die("Error creating database: " . mysqli_error($conn));
}

// Select the database
mysqli_select_db($conn, $dbname);

// ================= USERS =================
$sql_users = "CREATE TABLE IF NOT EXISTS users (
    user_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'teacher', 'student', 'director', 'rep') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

// ================= STUDENTS =================
$sql_students = "CREATE TABLE IF NOT EXISTS students (
    student_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    middle_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    date_of_birth DATE NOT NULL,
    gender ENUM('male','female') NOT NULL,
    nationality VARCHAR(50) NOT NULL,
    religion VARCHAR(50) NOT NULL,
    city VARCHAR(50) NOT NULL,
    wereda VARCHAR(100) NOT NULL,
    kebele VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(100),
    emergency_contact VARCHAR(20) NOT NULL,
    grade_level ENUM('9','10','11','12') NOT NULL,
    stream ENUM('Natural','Social','Both') DEFAULT 'Both' NOT NULL, 
    last_school VARCHAR(100) NOT NULL,
    last_score FLOAT NOT NULL,
    last_grade INT NOT NULL,
    registered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    photo_path VARCHAR(255) DEFAULT NULL,
    status ENUM('active','inactive') NOT NULL DEFAULT 'active',
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
)";


// ================= GUARDIANS =================
$sql_guardians = "CREATE TABLE IF NOT EXISTS guardians (
    guardian_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    student_id INT(11) NOT NULL,
    name VARCHAR(100) NOT NULL,
    relation VARCHAR(50) NOT NULL,
    phone VARCHAR(20)NOT NULL,
    email VARCHAR(100),
    FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE
)";


// ================= TEACHERS =================
$sql_teachers = "CREATE TABLE IF NOT EXISTS teachers (
    teacher_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    middle_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    date_of_birth DATE NOT NULL,
    gender ENUM('male','female') NOT NULL,
    nationality VARCHAR(50) NOT NULL,
    religion VARCHAR(50) NOT NULL,
    city VARCHAR(50) NOT NULL,
    wereda VARCHAR(100) NOT NULL,
    kebele VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(100),
    hire_date DATE NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
)";

// ================= SUBJECTS =================
$sql_subjects = "CREATE TABLE IF NOT EXISTS subjects (
    subject_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    code VARCHAR(20) UNIQUE,
    grade_level INT,
    stream ENUM('Natural','Social','Both') DEFAULT 'Both',
    description TEXT
)";


// ================= SECTIONS =================
$sql_sections = "CREATE TABLE IF NOT EXISTS sections (
    section_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    grade_level INT NOT NULL,
    stream ENUM('Natural','Social') NOT NULL,
    capacity INT NOT NULL,
    shift ENUM('Morning','Afternoon') NOT NULL
)";

// ================= CLASS ASSIGNMENTS =================
$sql_class_assignments = "CREATE TABLE IF NOT EXISTS class_assignments (
    schedule_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    student_id INT(11) NOT NULL,
    section_id INT(11) NOT NULL,
    assigned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(student_id),
    FOREIGN KEY (section_id) REFERENCES sections(section_id)
)";

// ================= SUBJECT ASSIGNMENTS =================
$sql_subject_assignments = "CREATE TABLE IF NOT EXISTS subject_assignments (
    assignment_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    subject_id INT(11) NOT NULL,
    section_id INT(11) NOT NULL,
    teacher_id INT(11) NOT NULL,
    assigned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (teacher_id) REFERENCES teachers(teacher_id),
    FOREIGN KEY (subject_id) REFERENCES subjects(subject_id),
    FOREIGN KEY (section_id) REFERENCES sections(section_id)
)";

// ================= CLASSROOMS =================
$sql_classrooms = "CREATE TABLE IF NOT EXISTS classrooms (
    classroom_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    capacity INT NOT NULL,
    resources TEXT
)";

// ================= ATTENDANCE =================
$sql_attendance = "CREATE TABLE IF NOT EXISTS attendance (
    attendance_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    student_id INT(11) NOT NULL,
    section_id INT(11) NOT NULL,
    subject_id INT(11) NOT NULL,
    teacher_id INT(11) NOT NULL,
    date DATE,
    status ENUM('Present','Absent','Late') NOT NULL,
    locked BOOLEAN DEFAULT 0,
    marked_by VARCHAR(100) NOT NULL,
    marked_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(student_id),
    FOREIGN KEY (section_id) REFERENCES sections(section_id),
    FOREIGN KEY (subject_id) REFERENCES subjects(subject_id),
    FOREIGN KEY (teacher_id) REFERENCES teachers(teacher_id),
    UNIQUE KEY `unique_attendance` (`student_id`, `subject_id`, `date`)
)";

// ================= GRADES =================
$sql_grades = "CREATE TABLE IF NOT EXISTS grades (
    grade_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    student_id INT(11) NOT NULL,
    subject_id INT(11) NOT NULL,
    teacher_id INT(11) NOT NULL,
    test DECIMAL(5,2) NOT NULL,
    assignment DECIMAL(5,2) NOT NULL,
    activity DECIMAL(5,2) NOT NULL,
    exercise DECIMAL(5,2) NOT NULL,
    midterm DECIMAL(5,2) NOT NULL,
    final DECIMAL(5,2) NOT NULL,
    total DECIMAL(5,2) NOT NULL,
    updated_by VARCHAR(100) NOT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(student_id),
    FOREIGN KEY (subject_id) REFERENCES subjects(subject_id),
    FOREIGN KEY (teacher_id) REFERENCES teachers(teacher_id)
)";

// ================= MESSAGES =================
$sql_messages = "CREATE TABLE IF NOT EXISTS messages (
    message_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    sender_id INT(11) NOT NULL,
    receiver_id INT(11) NOT NULL,
    content TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES users(user_id),
    FOREIGN KEY (receiver_id) REFERENCES users(user_id)
)";

// ================= REPORTS =================
$sql_reports = "CREATE TABLE IF NOT EXISTS reports (
    report_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    rep_id INT(11) NOT NULL,
    section_id INT(11) NOT NULL,
    type ENUM('Attendance','Behavior','Academic') NOT NULL,
    details TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (rep_id) REFERENCES users(user_id),
    FOREIGN KEY (section_id) REFERENCES sections(section_id)
)";

// ================= NEWS =================
$sql_news = "CREATE TABLE IF NOT EXISTS news (
    news_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    image_path VARCHAR(255),
    author_id INT,
    status ENUM('published', 'draft') NOT NULL DEFAULT 'draft',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (author_id) REFERENCES users(user_id) ON DELETE SET NULL
)";

// ================= GALLERY =================
$sql_gallery = "CREATE TABLE IF NOT EXISTS gallery (
    gallery_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255),
    description TEXT,
    image_path VARCHAR(255) NOT NULL,
    category VARCHAR(100),
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

// Array of all table creation queries
$queries = [
    $sql_users,
    $sql_students,
    $sql_guardians,
    $sql_teachers,
    $sql_subjects,
    $sql_sections,
    $sql_class_assignments,
    $sql_subject_assignments,
    $sql_classrooms,
    $sql_attendance,
    $sql_grades,
    $sql_messages,
    $sql_reports,
    $sql_news,
    $sql_gallery
];

// Execute each query
foreach ($queries as $query) {
    if (!mysqli_query($conn, $query)) {
        die("Error creating table: " . mysqli_error($conn));
    }
}

echo "Database and all tables checked/created successfully!";
mysqli_close($conn);
?>
