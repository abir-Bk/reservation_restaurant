<?php
// Include database connection
require 'dbh.inc.php';

if (isset($_POST['update_menu'])) {
    // Get form data
    $dish_id = $_POST['id_dish'];
    $dish_name = $_POST['dish_name'];
    $dish_price = $_POST['dish_price'];
    $dish_description = $_POST['dish_description'];

    // SQL to update the dish
    $sql = "UPDATE menu SET name_dish = ?, prix = ?, dsc = ? WHERE id_dish = ?";

    // Prepare and execute the statement
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $dish_name, $dish_price, $dish_description, $dish_id);
    if ($stmt->execute()) {
        // Redirect back to the menu page with success message
        header("Location: ../view_menu.php?update=success");
        exit();
    } else {
        // Redirect back to the menu page with error message
        header("Location: ../view_menu.php?update=error");
        exit();
    }
} else {
    // Redirect back to the menu page if accessed directly
    header("Location: ../view_menu.php");
    exit();
}

mysqli_close($conn);
?>
