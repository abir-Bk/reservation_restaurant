<?php



require 'includes/dbh.inc.php';


//view reserved tables per date and time-zone


$sql = "SELECT SUM(num_tables), rdate, time_zone FROM reservation where completed = 0 GROUP BY rdate, time_zone";
$result = $conn->query($sql);
if ($result->num_rows > 0) {

    echo
    '   <div class="container">
            <div class="row">
            <div class="col-sm text-center">
            <p class="text-white bg-dark text-center">Reserved tables per date and time-zone</p><br>
            <table class="table table-hover table-bordered table-responsive-sm text-center">
                <thead>
                    <tr>
                        <th scope="col">Date</th>
                        <th scope="col">Time-Zone</th>
                        <th scope="col">Reserved Tables</th>
                    </tr>
                </thead> ';
    while ($row = $result->fetch_assoc()) {
        echo "
                <tbody>
                    <tr>
                      <th scope='row'>" . $row["rdate"] . "</th>
                      <td>" . $row["time_zone"] . "</td>
                      <td>" . $row["SUM(num_tables)"] . "</td>
                    </tr>
              </tbody>";
    }


    echo "</table>";



    echo '</div>';



    //view total tables per date that have been submited from set tables  

    $sql = "SELECT * FROM tables ORDER BY t_date";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {

        echo '  
         <div class="col-sm text-center">
         <p class="text-white bg-dark text-center">Total tables per date</p>
         <label>Default total tables is 20</label><br>
        ';

        echo
        '
            <table class="table table-hover table-bordered table-responsive-sm text-center">
                <thead>
                    <tr>
                        <th scope="col">Date</th>
                        <th scope="col">Total Tables</th>
                        <th ></th>
                    </tr>
                </thead> ';
        while ($row = $result->fetch_assoc()) {
            echo "
                <tbody>
                    <tr>
                    <form action='' method='POST'>
                    <input name='tables_id' type='hidden' value=" . $row["tables_id"] . ">
                      <th scope='row'>" . $row["t_date"] . "</th>
                      <td>" . $row["t_tables"] . "</td>
                      <td ><button type='submit' name='delete-table' class='btn btn-danger btn-sm'>
                      <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-trash3-fill' viewBox='0 0 16 16'>
                      <path d='M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5'/>
                  </svg>
                      Delete</button></td>
                          </form>
                    </tr>
              </tbody>";
        }
        echo "</table>";
    } else {
        echo "<p class='text-center'>List is empty!<p>";
    }



    echo '</div></div></div>';
}




// Delete table
if (isset($_POST['delete-table'])) {
    $tables_id = $_POST['tables_id'];
    $sql = "DELETE FROM tables WHERE tables_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $tables_id);
    if ($stmt->execute()) {
        $_SESSION['success'] = 'Table deleted successfully.';
    } else {
        $_SESSION['error'] = 'Error deleting table.';
    }
}

mysqli_close($conn);
