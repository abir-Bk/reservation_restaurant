<?php require "header.php"; ?>

<br><br>
<div class="container">
    <h3 class="text-center"><br>View Reservations<br></h3>     

    <?php
    // Start session

    if(isset($_SESSION['user_id'])){
        echo '<p class="text-center" style="color:rgba(78, 115, 223, 1);">Welcome '. $_SESSION['username'] .', Here you can check your reservation history</p><br>';

      

        // Display success or error messages from delete.php
        if(isset($_SESSION['delete_message'])) {
            echo '<div class="alert alert-success" role="alert">
          ' . $_SESSION['delete_message'] . '</div>';
          unset($_SESSION['delete_message']);


        }

        if(isset($_SESSION['delete_error'])) {
            echo '<div class="alert alert-warning" role="alert">
            ' . $_SESSION['delete_error'] . '</div>';
            unset($_SESSION['delete_error']);
        }

        require 'includes/view.reservation.inc.php';

    } else {
        echo '<p class="text-center text-danger"><br>You are currently not logged in!<br></p>
        <p class="text-center">In order to make a reservation you have to create an account!<br><br><p>';
    }    
    ?>

</div>
<br><br>

<?php require "footer.php"; ?>
