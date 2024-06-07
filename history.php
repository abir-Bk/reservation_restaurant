<?php
// Check if the user is not logged in, then redirect to login page
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit(); // Ensure no further code execution after redirect
}
?>

<?php require "header_admin.php" ?>
<!-- Reservation History Section -->
<div class="col-md-9 content">

    <div class="col-md-12 mt-5">

        <hr>
        <h3 class="text-center">Reservation History</h3>
        <br>
        <!-- Search Form -->
        <form method="GET" action="history.php">
            <div class="input-group">
                <span class="input-group-text" id="basic-addon1">@</span>
                <input type="text" class="form-control" placeholder="Search by Full Name" aria-label="Username" aria-describedby="basic-addon1" name="search">
                <button class="btn btn-primary" type="submit">Search</button>
            </div>
        </form>
        <br>
        <br>
        <?php
        // Include reservation display code
        require 'includes/dbh.inc.php';

        // Check if search query is provided
        if (isset($_GET['search'])) {
            $search = $_GET['search'];
            // Modify the SQL query to include search by full name
            $sql = "SELECT * FROM reservation WHERE completed = 1 AND CONCAT(f_name, ' ', l_name) LIKE '%$search%' ORDER BY rdate ASC";
        } else {
            // Default SQL query to fetch all checked reservations
            $sql = "SELECT * FROM reservation WHERE completed = 1 ORDER BY rdate ASC";
        }

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo '
            <table class="table table-hover table-responsive-sm text-center">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Full Name</th>
                        <th scope="col">Guests</th>
                        <th scope="col">Tables</th>
                        <th scope="col">Date</th>
                        <th scope="col">Time Zone</th>
                        <th scope="col">Telephone</th>
                        <th scope="col">Register Date</th>
                        <th scope="col">Comments</th>
                    </tr>
                </thead>
                <tbody>';

            while ($row = $result->fetch_assoc()) {
                echo "
                <tr>
                    <td>" . $row["reserv_id"] . "</td> 
                    <td>" . $row["f_name"] . " " . $row["l_name"] . "</td>
                    <td>" . $row["num_guests"] . "</td>
                    <td>" . $row["num_tables"] . "</td>
                    <td>" . $row["rdate"] . "</td>
                    <td>" . $row["time_zone"] . "</td>
                    <td>" . $row["telephone"] . "</td>
                    <td>" . $row["reg_date"] . "</td>
                    <td>
                        <textarea readonly>" . $row["comment"] . "</textarea>
                    </td>
                </tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<p class='text-black text-center '>No reservations found!<p>";
        }
        ?>
    </div>
</div>
</div>