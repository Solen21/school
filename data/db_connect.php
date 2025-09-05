<?php
$servername = "localhost";
$username   = "root";   // your DB username
$password   = "";       // your DB password
$dbname     = "school_management_system";

// Enable mysqli to throw exceptions for errors, which is a more modern approach.
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    // Set the character set to utf8mb4 for full Unicode support.
    // This is a critical step for preventing encoding issues.
    mysqli_set_charset($conn, 'utf8mb4');

} catch (mysqli_sql_exception $e) {
    // In a production environment, you should log this error and show a generic
    // friendly message to the user instead of the raw error.
    die("Database connection failed: " . $e->getMessage());
}
?>