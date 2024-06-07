<?php

session_start();

// Check if the user is an admin
if (isset($_SESSION['admin_id'])) {
    // If admin, redirect to login.php
    header("Location: ../login.php");
} 

// Unset all session variables
session_unset();

// Destroy the session
session_destroy();

// Ensure no further code execution after redirect
exit();
?>