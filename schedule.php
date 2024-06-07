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
                <div class="text-center"><h2 class="text-center" style="color: #347a90;"><br>Edit Schedule<br></h2>
                <p>The default time is from 12:00 to 00:00</p></div>
                <div class="col-md-12">
                    <?php 
                    echo '<h4 class="text-center">Set the schedule for a specific date</h4><br>';

                    if(isset($_GET['error5'])) {
                        if($_GET['error5'] == "sqlerror1") {
                            echo '<div class="alert alert-danger" role="alert">
                                    Error
                                  </div>';
                        }
                        if($_GET['error5'] == "emptyfields") {
                            echo '<div class="alert alert-danger" role="alert">
                                    Error, Empty fields
                                  </div>';
                        }
                    }

                    if(isset($_GET['schedule'])) {
                        if($_GET['schedule'] == "success") {
                            echo '<div class="alert alert-success" role="alert">
                                    Schedule submitted successfully!
                                  </div>';
                        }
                    }

                    echo '
                    <div class="signup-form">
                        <form action="includes/schedule.inc.php" method="post">
                            <div class="form-group">
                                <label>Enter Date</label>
                                <input type="date" class="form-control" name="date" placeholder="Date" required="required">
                            </div>
                            <div class="form-group">
                                <label>Open Time</label>
                                <input type="time" class="form-control" name="opentime" required="required">
                            </div>
                            <div class="form-group">
                                <label>Close Time</label>
                                <input type="time" class="form-control" name="closetime" required="required">
                            </div>
                            <div class="form-group">
                                <button type="submit" name="schedule" class="btn btn-dark btn-lg btn-block">Submit Schedule</button>
                            </div>
                        </form>
                        <br><br>
                    </div>';
                    
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
