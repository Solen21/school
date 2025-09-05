<?php
/**
 * Database Seeder
 * 
 * This script seeds the database with a default admin user.
 * It is safe to run this script multiple times.
 */

header('Content-Type: text/plain');
require_once 'db_connect.php';

echo "--- Database Seeder ---\n\n";

// --- Admin User Configuration ---
$admin_username = 'admin';
$admin_password = 'admin'; // The plain-text password
$admin_role = 'admin';

// 1. Check if the admin user already exists to prevent duplicates
$sql_check = "SELECT user_id FROM users WHERE username = ?";
if ($stmt_check = mysqli_prepare($conn, $sql_check)) {
    mysqli_stmt_bind_param($stmt_check, "s", $admin_username);
    mysqli_stmt_execute($stmt_check);
    mysqli_stmt_store_result($stmt_check);

    if (mysqli_stmt_num_rows($stmt_check) > 0) {
        echo "Admin user ('{$admin_username}') already exists. No action taken.\n";
    } else {
        // 2. User does not exist, so create it
        echo "Admin user not found. Creating...\n";
        
        // Hash the password for security
        $hashed_password = password_hash($admin_password, PASSWORD_DEFAULT);

        // 3. Insert the new user using a prepared statement
        $sql_insert = "INSERT INTO users (username, password, role) VALUES (?, ?, ?)";
        if ($stmt_insert = mysqli_prepare($conn, $sql_insert)) {
            mysqli_stmt_bind_param($stmt_insert, "sss", $admin_username, $hashed_password, $admin_role);
            
            if (mysqli_stmt_execute($stmt_insert)) {
                echo "Successfully created admin user:\n  - Username: " . $admin_username . "\n  - Password: " . $admin_password . "\n";
            }
            mysqli_stmt_close($stmt_insert);
        }
    }
    mysqli_stmt_close($stmt_check);
}

echo "\n--- Seeding Complete ---\n";
mysqli_close($conn);
?>