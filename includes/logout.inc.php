<? 
session_start();
 
    // If user, redirect to index.php
    header("Location: ../index.php");

// Unset all session variables
session_unset();

// Destroy the session
session_destroy();

// Ensure no further code execution after redirect
exit();
?>