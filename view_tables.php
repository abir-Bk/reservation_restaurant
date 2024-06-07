<?php
// Check if the user is not logged in, then redirect to login page
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit(); // Ensure no further code execution after redirect
} ?>
<?php require "header_admin.php" ?> 


<div class="container">
    <h3 class="text-center"><br>View Tables<br></h3>     
        
    <?php // Display success or error messages from delete.php
    if(isset($_SESSION['success'])) {
        echo '<div class="alert alert-success" role="alert">
            ' . $_SESSION['success'] . '</div>';
        unset($_SESSION['success']);
    }

    if(isset($_SESSION['error'])) {
        echo '<div class="alert alert-warning" role="alert">
            ' . $_SESSION['error'] . '</div>';
        unset($_SESSION['error']);
    }
    require 'includes/view.tables.inc.php'
    ?>  

</div>
<!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>