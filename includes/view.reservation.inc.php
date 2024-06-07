<?php
require "includes/dbh.inc.php";

// Retrieve user information from the session
$user = $_SESSION['user_id'];

// Check if the user has the role of customer
    // Prepare and execute SQL query to select reservations for the current user
    $sql = "SELECT * FROM reservation WHERE user_fk = ? and completed = 0 order by rdate asc";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if there are reservations for the user
    if ($result->num_rows > 0) {
        // Display table headers
        echo '
            <table class="table table-hover table-responsive-sm text-center">
                <thead>
                    <tr>
                        <th scope="col">Full Name</th>
                        <th scope="col">Guests</th>
                        <th scope="col">Reservation Date</th>
                        <th scope="col">Time Zone</th>
                        <th scope="col">Telephone</th>
                        <th scope="col">Register Date</th>
                        <th scope="col">Comments</th>
                        <th scope="col"></th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>';

        // Display reservation details for each reservation
        while ($row = $result->fetch_assoc()) {
            echo "
                <tr>
                    <td>".$row["f_name"]." ".$row["l_name"]."</td>
                    <td>".$row["num_guests"]."</td>
                    <td>".$row["rdate"]."</td>
                    <td>".$row["time_zone"]."</td>
                    <td>".$row["telephone"]."</td>
                    <td>".$row["reg_date"]."</td>
                    <td><textarea readonly>".$row["comment"]."</textarea></td>
                    <td>
                        <form action='includes/delete.php' method='POST'>
                            <input name='reserv_id' type='hidden' value=".$row["reserv_id"].">
                            <button type='submit' name='delete-submit' class='btn btn-outline-danger'>
                            <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-trash3-fill' viewBox='0 0 16 16'>
                                <path d='M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5'/>
                            </svg>
                            Cancel</button>
                        </form>
                    </td>
                   
                </tr>";
        }

        echo "</tbody></table>";
    } else {
        // Display a message if the user's reservation list is empty
        echo "<p class='text-white text-center bg-danger'>Your reservation list is empty!<p>";
    }

    // Close the database connection
    $stmt->close();

?>
