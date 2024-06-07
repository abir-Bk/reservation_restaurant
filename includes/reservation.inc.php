<?php
// Démarrage de la session PHP
session_start();

// Fonction pour vérifier si une valeur est entre deux autres valeurs
function between($val, $x, $y){
    $val_len = strlen($val);
    return ($val_len >= $x && $val_len <= $y) ? TRUE : FALSE;
}

// Vérification si le formulaire de réservation a été soumis
if(isset($_POST['reserv-submit'])) {

    // Inclusion du fichier de connexion à la base de données
    require 'dbh.inc.php';

    // Récupération des données du formulaire
    $user = $_SESSION['user_id'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $guests = $_POST['num_guests'];
    $tele = $_POST['tele'];
    $comments = $_POST['comments'];
    
    // Calcul du nombre de tables nécessaires en fonction du nombre d'invités
    if($guests == 1 || $guests == 2) {
        $tables = 1;
    } else {
        $tables = ceil(($guests - 2) / 2);
    }
    
    // Validation des données du formulaire
    if(empty($fname) || empty($lname) || empty($date) || empty($time) || empty($guests) || empty($tele)) {
        header("Location: ../reservation.php?error3=emptyfields");
        exit();
    } elseif(!preg_match("/^[a-zA-Z ]*$/", $fname) || !between($fname, 2, 20)) {
        header("Location: ../reservation.php?error3=invalidfname");
        exit();
    } elseif(!preg_match("/^[a-zA-Z ]*$/", $lname) || !between($lname, 2, 40)) {
        header("Location: ../reservation.php?error3=invalidlname");
        exit();
    } elseif(!preg_match("/^[0-9]*$/", $guests) || !between($guests, 1, 3)) {
        header("Location: ../reservation.php?error3=invalidguests");
        exit();
    } elseif(!preg_match("/^[a-zA-Z0-9]*$/", $tele) || !between($tele, 6, 20)) {
        header("Location: ../reservation.php?error3=invalidtele");
        exit();
    } elseif(!preg_match("/^[a-zA-Z 0-9]*$/", $comments) || !between($comment, 0, 200)) {
        header("Location: ../reservation.php?error3=invalidcomment");
        exit();
    }
    
    // Vérification de la disponibilité des tables pour la date et l'heure spécifiées
    $sql = "SELECT t_tables FROM tables WHERE t_date='$date'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $a_tables = $row["t_tables"];
        }
    } else {
        $a_tables = 20; // Valeur par défaut si aucune donnée n'est trouvée dans la base de données
    }
    
    // Vérification du nombre de tables déjà réservées pour la même date et heure
    $sql = "SELECT SUM(num_tables) FROM reservation WHERE rdate='$date' AND time_zone='$time'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $current_tables = $row["SUM(num_tables)"];
        }
    }
    
    // Vérification si le nombre total de tables requis dépasse le nombre total de tables disponibles
    if($current_tables + $tables > $a_tables) {
        header("Location: ../reservation.php?error3=full");
        exit();
    }
          
    // Insertion des données de réservation dans la base de données
    $sql = "INSERT INTO reservation(f_name, l_name, num_guests, num_tables, rdate, time_zone, telephone, comment, user_fk) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../reservation.php?error3=sqlerror1");
        exit();
    } else {       
        mysqli_stmt_bind_param($stmt, "sssssssss", $fname, $lname, $guests, $tables, $date, $time, $tele, $comments, $user);
        mysqli_stmt_execute($stmt);
        header("Location: ../reservation.php?reservation=success");
        exit();
    }
    
    // Fermeture des instructions préparées et de la connexion à la base de données
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
} else {
    // Si le formulaire n'a pas été soumis, rediriger vers la page de réservation
    header("Location: ../reservation.php");
    exit();
}
?>
