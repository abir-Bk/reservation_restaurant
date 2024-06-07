<?php require "header_admin.php" ?> 
<div class="container">
    <h2 class="text-center" style="color: #347a90;"><br>Edit Tables<br></h2>
    <div class="col-md-6 offset-md-3">

                           
<?php 
   
        
        echo '<h4 class="text-center">Set the number of tables for a specific date</h4>';

        if(isset($_GET['error4'])){
        if($_GET['error4'] == "sqlerror1") {   //douleuei bazw ta errors apo ta headers.. prp na bgalw to requiered
            echo '<h5 class="alert alert-danger">Error</h5>';
        }
        if($_GET['error4'] == "emptyfields") {  
            echo '<h5 class="alert alert-danger">Error, Empty fields</h5>';
        }
        }
        if(isset($_GET['tables'])){
        if($_GET['tables'] == "success") {   
            echo '<h5 class="alert alert-success">Tables was successfully submited</h5>';
        }
        }
        echo'
                                                 
<div class="signup-form">
        <form action="includes/tables.inc.php" method="post">
            <div class="form-group">
            <label>Enter Date</label>
        	<input type="date" class="form-control" name="date_tables" placeholder="Date">
            </div>
            <div class="form-group">
            <label>Number of tables</label>
            <input type="number" class="form-control" min="1" name="num_tables" required="required">
            <small class="form-text text-muted">Default number is 20</small>
            </div>
            <div class="form-group">
            <button type="submit" name="tables" class="btn btn-dark btn-lg btn-block">Submit Tables</button>
            </div>
        </form>
        <br><br>
</div> ';
        
    
?>
    </div>
</div>
  <!-- Bootstrap JS -->
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>