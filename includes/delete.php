<?php
// Start session
session_start();

// Include database connection
require 'dbh.inc.php';

// Delete reservation
if (isset($_POST['delete-submit'])) {
    $reservation_id = $_POST['reserv_id'];
    $sql = "DELETE FROM reservation WHERE reserv_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $reservation_id);
    if ($stmt->execute()) {
        // Check if the user is an admin
            $_SESSION['delete_message'] = 'Reservation deleted successfully.';
        }            header("Location: ../view_reservations.php");

        exit();
    } else {
        $_SESSION['delete_error'] = 'Error deleting reservation.';
        header("Location: ../admin_reservation.php?delete=error"); // Redirect back to admin_reservation.php with an error message
        exit();
    }





// Close database connection
mysqli_close($conn);

