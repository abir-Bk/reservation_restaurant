<?php
// Check if the user is not logged in, then redirect to login page
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit(); // Ensure no further code execution after redirect
}
?>

<?php require "header_admin.php" ?>

<!-- Content -->
<div class="col-md-9 content">

    <h3 class="text-center"><br>View Reservations<br></h3>
    <nav class="navbar bg-body-tertiary">
        <form action="" method="GET" class="container-fluid">
            <div class="input-group">
                <span class="input-group-text" id="basic-addon1">@</span>
                <input type="text" class="form-control" placeholder="Search by Full Name" aria-label="Username" aria-describedby="basic-addon1" name="search">
                <button class="btn btn-primary" type="submit">Search</button>
            </div>
        </form>
    </nav>
    <br>

    <?php // Display success or error messages from delete.php
    if (isset($_SESSION['delete_message'])) {
        echo '<div class="alert alert-success" role="alert">
            ' . $_SESSION['delete_message'] . '</div>';
        unset($_SESSION['delete_message']);
    }

    if (isset($_SESSION['delete_error'])) {
        echo '<div class="alert alert-warning" role="alert">
            ' . $_SESSION['delete_error'] . '</div>';
        unset($_SESSION['delete_error']);
    } ?>

    <?php
    // Include reservation display code
    require 'includes/dbh.inc.php';

    // Check if a search query is set
    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $search = $_GET['search'];
        // Fetch reservations matching the search query
        $sql = "SELECT * FROM reservation WHERE CONCAT(f_name, ' ', l_name) LIKE '%$search%' AND completed = 0 ORDER BY rdate ASC";
    } else {
        // Fetch all unchecked reservations if no search query is provided
        $sql = "SELECT * FROM reservation WHERE completed = 0 ORDER BY rdate ASC";
    }
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo '
        <form action="" method="POST">
        <table class="table table-hover table-responsive-sm text-center">
            <thead>
                <tr>
                    <th scope="col"></th>
                    <th scope="col">ID</th>
                    <th scope="col">Full Name</th>
                    <th scope="col">Guests</th>
                    <th scope="col">Tables</th>
                    <th scope="col">Date</th>
                    <th scope="col">Time Zone</th>
                    <th scope="col">Telephone</th>
                    <th scope="col">Register Date</th>
                    <th scope="col">Comments</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>';

        while ($row = $result->fetch_assoc()) {
            echo "
            <tr>
                <td>
                    <input type='checkbox' name='reservation_done[]' value='" . $row["reserv_id"] . "' " . ($row["completed"] ? "checked" : "") . ">
                </td> <!-- Modified checkbox input -->
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
                <td> <!-- Here was the missing > -->
                    <form action='admin_reservation.php' method='POST'>
                        <input name='reserv_id' type='hidden' value=" . $row["reserv_id"] . ">
                        <button type='submit' name='delete-submit' class='btn btn-danger btn-sm'>
                            <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-trash3-fill' viewBox='0 0 16 16'>
                                <path d='M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5'/>
                            </svg>
                            Delete
                        </button>
                    </form>
                </td>
            </tr>";
        }
        echo "</tbody></table>";
        echo '<button type="submit" style=" position: absolute;
    
        right: 16px;
        font-size: 18px;" class="btn btn-secondary" name="update_completed">Update</button>'; // Add submit button
        echo '</form>';
    } else {
        echo "<p class='text-black text-center '>No unchecked reservations found!<p>";
    }
    ?>

    <?php
    // Check if the delete-submit button was clicked
    if (isset($_POST['delete-submit'])) {
        // Include the database connection
        require 'includes/dbh.inc.php';

        // Get the reservation ID to be deleted
        $reservation_id = $_POST['reserv_id'];

        // Prepare a DELETE statement
        $sql = "DELETE FROM reservation WHERE reserv_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $reservation_id);

        // Execute the statement
        if ($stmt->execute()) {
            $_SESSION['delete_message'] = 'Reservation deleted successfully.';
        } else {
            // Redirect back to the page with error message
            $_SESSION['delete_error'] = 'Error deleting reservation.';
            exit();
        }

        // Close prepared statement and database connection
        $stmt->close();
        $conn->close();
    }
    ?>

    <?php
    // Check if the update_completed button was clicked
    if (isset($_POST['update_completed'])) {
        if (isset($_POST['reservation_done'])) {
            // Include the database connection
            require 'includes/dbh.inc.php';

            // Get the list of reservation IDs marked as completed
            $completed_reservations = $_POST['reservation_done'];

            // Prepare an UPDATE statement to mark reservations as completed
            $sql = "UPDATE reservation SET completed = 1 WHERE reserv_id IN (" . implode(',', $completed_reservations) . ")";

            // Execute the statement
            if ($conn->query($sql) === TRUE) {
                $_SESSION['update_message'] = 'Reservations updated successfully.';
            } else {
                $_SESSION['update_error'] = 'Error updating reservations.';
            }

            // Close database connection
            $conn->close();
        } else {
            $_SESSION['update_error'] = 'No reservations selected.';
        }
    }
    ?>

    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    </body>

    </html>