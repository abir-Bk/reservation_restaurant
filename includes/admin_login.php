<?php
// Check if the form is submitted
if (isset($_POST['submit_login'])) {
    // Include the database connection file
    require "dbh.inc.php";
    
    // Retrieve form data
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // Perform validation (You can add more validation checks here)
    if (empty($email) || empty($password)) {
        // Handle empty fields error
        echo "All fields are required!";
    } else {
        // Prepare and execute the SQL statement to retrieve user data from the admin table
        $sql = "SELECT * FROM admin WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 1) {
            // User found, verify password
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                session_start();                                //create of sessions
                $_SESSION['admin_id'] = $row['admin_id'];
                // Password is correct, login successful
                echo "<script>alert('Welcome Back ADMIN :)');</script>";
                // Redirect to the dashboard or any other page after successful login
                echo "<script>window.location.href = '../admin_reservation.php';</script>";
                exit();
            } else {
                // Password is incorrect
                echo "<script>alert('Incorrect password!');</script>";
                echo "<script>window.location.href = '../login.php';</script>";

            }
        } else {
            // User not found
            echo "<script>alert('User not found!');</script>";
        }
        
        // Close the database connection
        $stmt->close();
        $conn->close();
    }
}
?>
