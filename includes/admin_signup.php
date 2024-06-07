<?php
// Check if the form is submitted
if (isset($_POST['submit_register'])) {
    // Include the database connection file
    require "dbh.inc.php";
    
    // Retrieve form data
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $repeat_password = $_POST['repeat_password'];
    
    // Perform validation (You can add more validation checks here)
    if (empty($first_name) || empty($last_name) || empty($email) || empty($password) || empty($repeat_password)) {
        // Handle empty fields error
        echo "All fields are required!";
    } elseif ($password != $repeat_password) {
        // Handle password mismatch error
        echo "Passwords do not match!";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Prepare and execute the SQL statement to insert data into the admin table
        $sql = "INSERT INTO admin (first_name, last_name, email, password) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $first_name, $last_name, $email, $hashed_password);
        if ($stmt->execute()) {
            // Signup successful
            echo "<script> alert('signup seccessful!');
            window.location.href ='../login.html'; </script>
            ";
            

        } else {
            // Handle database error
            echo "<script> alert('error!') </script>" . $conn->error;
        }
        
        // Close the database connection
        $stmt->close();
        $conn->close();
    }
}
?>
