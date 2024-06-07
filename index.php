<?php
require "header.php";
?>

<header class="header">
    <div class="row">
        <div class="col-md-12 text-center">
   <a class="logo"><img src="img/logo.png" ></a>
   </div>
        <div class="col-md-12 text-center">
            <button type="button" onclick="window.location.href='reservation.php'" class="btn btn-outline-light btn-lg"><em>Make a Reservation Now!</em></button>
        </div>
    </div>
</header>



<!--about us section-->

<section id="aboutus">

 <div class="container">
   <h3 class="text-center"><br><br>Restaurant</h3>
   <div class="row">
<!--carousel-->
     <div class="col-sm"><br><br>
      	<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
         <ol class="carousel-indicators">
           <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
           <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
           <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
         </ol>
        <div class="carousel-inner">
           <div class="carousel-item active">
             <img class="d-block w-100" src="img/3.jpg" alt="First slide">
           </div>
           <div class="carousel-item">
           <img class="d-block w-100" src="img/soupe2.jpg" alt="Second slide">
           </div>
           <div class="carousel-item">
           <img class="d-block w-100" src="img/5.jpg" alt="Third slide">
           </div>
        </div>
         <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
           <span class="carousel-control-prev-icon" aria-hidden="true"></span>
           <span class="sr-only">Previous</span>
         </a>
         <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
           <span class="carousel-control-next-icon" aria-hidden="true"></span>
           <span class="sr-only">Next</span>
         </a>
       </div><br><br>
     </div>

<!--end of carousel-->

     <div class="col-sm">
    	<div class="arranging"><br><hr>
	<h4 class="text-center">Our Story</h4>
	<p><br>

Nestled in the heart of the city, our restaurant is more than just a restaurant—it's a culinary sanctuary. Founded by Chef Marco, our journey began with a passion for global cuisine, blending traditional recipes with innovative flair.

Every dish at La Dolce Vita tells a story—from hand-rolled pasta to signature curries, each bite is a voyage of flavor. But it's not just about the food; it's about the experience. Our warm atmosphere and attentive staff ensure every visit feels like coming home.

We pride ourselves on sourcing local, sustainable ingredients, supporting our community and the planet. So come join us on a journey of flavor and passion, where every meal is a celebration of the sweet life. Welcome to our restaurant.</div>
     </div>
    </div><br>
  </div>
</section>
<!--end of about us section-->

<div class="header2">
</div>

<!-- Main Content -->
<main>

    <!-- Menu Section -->
    <section id="menu" class="py-5">
        <div class="container">
            <h2 class="text-center mb-4">Our Menu</h2>
            <div class="row">
                <?php
                // Include database connection
                require 'includes/dbh.inc.php';

                // Fetch items from the database
                $sql = "SELECT * FROM menu";
                $result = $conn->query($sql);

                // Display each item
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="col-md-4 mb-4">';
                        echo '<div class="card menu-item">';
                        echo '<img src="uploads/' . $row["image"] . '" class="card-img-top" alt="' . $row["name_dish"] . '">';
                        echo '<div class="card-body">';
                        echo '<h5 class="card-title">' . $row["name_dish"] . '</h5>';
                        echo '<p class="card-text">$' . $row["prix"] . '</p>';
                        echo '<p class="card-text">' . $row["dsc"] . '</p>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo '<div class="col">';
                    echo '<p class="text-center">No items available in the menu.</p>';
                    echo '</div>';
                }

                // Close database connection
                $conn->close();
                ?>
            </div>
        </div>
    </section>

</main>
<!-- End Main Content -->

<div class="container" id="reservation">
    <h3 class="text-center"><br><br>Reservation<br><br></h3>
    <img  src="img/16.jpg" class="img-fluid rounded">
    <button type="button" onclick="window.location.href='reservation.php'" class="btn btn-outline-dark btn-block btn-lg">Make a reservation Now!</button>
        
</div><br><br>

<div class="header2">
</div>

<!-- main page map section-->
<section class="map" id="footer">
    <div class="container">
    <h3 class="text-center"><br><br>Find us!</h3><br>
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3211.397093607385!2d10.617722775160642!3d36.39958679018086!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x13029e0202d061ed%3A0x4188a22ce89da121!2sTwo%20M%20Pizzeria!5e0!3m2!1sfr!2stn!4v1707323406260!5m2!1sfr!2stn" style= "width:100%;  height:250px; border:0;" allowfullscreen></iframe>
    
        <div class="row staff">
            <div class="col">
            <h4><strong>Opening Hours</strong></h4>
                       
                <div class="signup-form">
                    <form action="#footer" method="post">
                        <div class="form-group">
                            <label>Enter Date</label>
                            <input type="date" class="form-control" name="date" placeholder="Date" required="required">
                        </div>
                        <div class="form-group">
                            <button type="submit" name="check_schedule" class="btn btn-dark btn-block">Check Open Time</button>
                        </div>
                    </form>
                    
<?php

if(isset($_POST['check_schedule'])){
      
require 'includes/dbh.inc.php';
            
$date= $_POST['date'];
 
    $sql = "SELECT * FROM schedule WHERE date = '$date'";
    $result = $conn->query($sql);
    if ($result->num_rows == 1) {
        while($row = $result->fetch_assoc()) {
            echo"
                <table class='table table-sm table-striped text-center'>
                   <thead>
                    <tr>
                    <th scope='col'>Date</th>
                    <th scope='col'>Open Time</th>
                    <th scope='col'>Close Time</th>
                    </tr>
                   </thead>
                   <tbody>
                    <tr>
                    <th scope='row'><em>". $date . "</em></th>
                    <td>".$row['open_time']."</td>
                    <td>".$row['close_time']."</td>
                    </tr>
                   </tbody>
                </table>";
                }
            }
        else{
         echo"
                <table class='table table-striped text-center'>
                   <thead>
                    <tr>
                    <th scope='col'>Date</th>
                    <th scope='col'>Open Time</th>
                    <th scope='col'>Close Time</th>
                    </tr>
                   </thead>
                   <tbody>
                    <tr>
                    <th scope='row'><em>". $date . "</em></th>
                    <td>12:00</td>
                    <td>00:00</td>
                    </tr>
                   </tbody>
                </table>";
            }
         
   //close connection
   mysqli_close($conn);
}
?>
                        
                </div><br>
            </div>

            <div class="col">
            <h4 class="text-right"><strong>Visit Us</strong></h4>
            <p class="text-right">restaurant<br><i class="fa fa-map-marker"></i>&nbsp; Hammamet. Riadh city <br>8090 <br><br>email: restaurant@reservation.com<br>phone: 96199907</p>
            </div>

	</div>
    </div>
</section>
<!--end of main page map section-->


<?php
require "footer.php";
?>