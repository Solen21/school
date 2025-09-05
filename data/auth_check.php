<?php
// Initialize the session
session_start();
 
// Check if the user is logged in. If not, redirect them to the login page.
if(!isset($_SESSION["user_id"]) || !isset($_SESSION["username"])){
    header("location: login.php");
    exit;
}
?>