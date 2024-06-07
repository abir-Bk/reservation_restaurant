<?php
// Check if the user is not logged in, then redirect to login page
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit(); // Ensure no further code execution after redirect
} ?>
<?php require "header_admin.php"; ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="text-center"><h2 class="text-center" style="color: #347a90;"><br>View all the Schedules<br></h2>
            <p>The default time is from 12:00 to 00:00</p></div>
            <div class="col-md-12 text-center">

            <?php
            // Include database connection
            require 'includes/dbh.inc.php';

            // Query to fetch schedule entries
            $sql = "SELECT * FROM schedule";
            $result = mysqli_query($conn, $sql);

            if ($result) {
                if (mysqli_num_rows($result) > 0) {
                    echo '<table class="table">';
                    echo '<thead>';
                    echo '<tr>';
                    echo '<th>Date</th>';
                    echo '<th>Open Time</th>';
                    echo '<th>Close Time</th>';
                    echo '<th>Action</th>'; // Fixed typo: "action" to "Action"
                    echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';
                    
                    // Output data of each row
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<tr>';
                        echo '<td>' . $row["date"] . '</td>';
                        echo '<td>' . $row["open_time"] . '</td>';
                        echo '<td>' . $row["close_time"] . '</td>';
                        echo '<td>
                                <form action="" method="POST">
                                    <input type="hidden" name="schedule_id" value="' . $row["schedule_id"] . '">
                                    <button type="submit" name="delete-date" class="btn btn-danger btn-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
                                <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5"/>
                            </svg>
                                    Delete</button>
                                </form>
                              </td>'; // Fixed form method: "methode" to "method", and corrected input value
                        echo '</tr>';
                    }
                    echo '</tbody>';
                    echo '</table>';
                } else {
                    echo "No schedule entries found.";
                }
            } else {
                echo "Error fetching schedule entries: " . mysqli_error($conn);
            }

            // Close database connection
            mysqli_close($conn);
            ?>

            <?php
            // Check if the form is submitted to delete a schedule entry
            if(isset($_POST['delete-date'])) {
                // Include database connection
                require 'includes/dbh.inc.php';

                // Get the schedule ID from the form
                $schedule_id = $_POST['schedule_id'];

                // Prepare SQL statement to delete the schedule entry
                $sql = "DELETE FROM schedule WHERE schedule_id = ?";
                $stmt = mysqli_prepare($conn, $sql);
                
                // Bind parameters
                mysqli_stmt_bind_param($stmt, "i", $schedule_id);

                // Execute the statement
                if(mysqli_stmt_execute($stmt)) {
                    // Redirect back to the page with success message
                    $_SESSION['success'] = 'date deleted successfully.';
                } else {
                    // Redirect back to the page with error message
                    $_SESSION['error'] = 'there is an error.';
                    exit();
                }

                // Close prepared statement
                mysqli_stmt_close($stmt);

                // Close database connection
                mysqli_close($conn);
            } 
            ?>

            </div>
        </div>
    </div>
</div>

<br><br>
<!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
