<?php
require "../header.php";
// Check if reservation ID is provided in the URL
if (isset($_GET['reserv_id'])) {
    require "includes/dbh.inc.php";

    $reserv_id = $_GET['reserv_id'];

    // Retrieve reservation details from the database
    $sql = "SELECT * FROM reservation WHERE reserv_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $reserv_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // Reservation found, populate form fields with details
        $reservation = $result->fetch_assoc();
    } else {
        // Reservation not found, handle error
        echo "Reservation not found.";
        exit();
    }
} else {
    // Reservation ID not provided, redirect to reservation list page
    header("Location: ../view_reservations.php");
    exit();
}

// Check if the form is submitted
if (isset($_POST['edit-submit'])) {
    // Retrieve form data
    $f_name = $_POST['f_name'];
    $l_name = $_POST['l_name'];
    $num_guests = $_POST['num_guests'];
    $rdate = $_POST['rdate'];
    $time_zone = $_POST['time_zone'];
    $telephone = $_POST['telephone'];
    $comment = $_POST['comment'];

    // Update reservation details in the database
    $sql = "UPDATE reservation SET f_name = ?, l_name = ?, num_guests = ?, rdate = ?, time_zone = ?, telephone = ?, comment = ? WHERE reserv_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssissssi", $f_name, $l_name, $num_guests, $rdate, $time_zone, $telephone, $comment, $reserv_id);
    if ($stmt->execute()) {
        // Reservation updated successfully, redirect to reservation list page
        header("Location: ../view_reservations.php?edit=success");
        exit();
    } else {
        // Error occurred while updating reservation, handle error
        echo "Error updating reservation.";
        exit();
    }
}

// Close the database connection
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Reservation</title>
    <!-- Add your CSS and Bootstrap here -->
</head>
<body>
    <h1>Edit Reservation</h1>
    <form action="" method="post">
    <label for="f_name">First Name:</label>
    <input type="text" id="f_name" name="f_name" value="<?php echo $reservation['f_name']; ?>"><br>

    <label for="l_name">Last Name:</label>
    <input type="text" id="l_name" name="l_name" value="<?php echo $reservation['l_name']; ?>"><br>

    <label for="num_guests">Number of Guests:</label>
    <input type="number" id="num_guests" name="num_guests" value="<?php echo $reservation['num_guests']; ?>"><br>

    <label for="rdate">Reservation Date:</label>
    <input type="date" id="rdate" name="rdate" value="<?php echo $reservation['rdate']; ?>"><br>

    <label for="time_zone">Time Zone:</label>
    <select id="time_zone" name="time_zone">
        <option value="AM" <?php if ($reservation['time_zone'] == 'AM') echo 'selected'; ?>>AM</option>
        <option value="PM" <?php if ($reservation['time_zone'] == 'PM') echo 'selected'; ?>>PM</option>
    </select><br>

    <label for="telephone">Telephone:</label>
    <input type="tel" id="telephone" name="telephone" value="<?php echo $reservation['telephone']; ?>"><br>

    <label for="comment">Comments:</label><br>
    <textarea id="comment" name="comment"><?php echo $reservation['comment']; ?></textarea><br>

    <button type="submit" name="edit-submit">Save Changes</button>
</form>

</body>
</html>
