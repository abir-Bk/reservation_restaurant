<?php
// Check if the user is not logged in, then redirect to login page
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit(); // Ensure no further code execution after redirect
} ?>
<?php require "header_admin.php" ?>

        <!-- Content -->
        <main class="col-md-9 content">

            <!-- Menu Section -->
            <section id="menu" class="py-5">
                <div class="container">
                    <h2 class="text-center mb-4">Our Menu</h2>
                    <br>
                    <?php
                    if (isset($_SESSION['delete_message'])) {
                        echo '<div class="alert alert-success" role="alert">' . $_SESSION['delete_message'] . '</div>';
                        unset($_SESSION['delete_message']);
                    }

                    if (isset($_SESSION['delete_error'])) {
                        echo '<div class="alert alert-warning" role="alert">' . $_SESSION['delete_error'] . '</div>';
                        unset($_SESSION['delete_error']);
                    }
                    ?>
                    <div class="row">
                        <?php
                        // Include database connection
                        require 'includes/dbh.inc.php';

                        // Fetch items from the database
                        $sql = "SELECT * FROM menu";
                        $result = $conn->query($sql);

                        // Display each item
                        if ($result->num_rows > 0) {
                           // Display each item with update form
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<div class="col-md-4 mb-4">';
                            echo '<div class="card menu-item">';
                            echo '<img src="uploads/' . $row["image"] . '" class="card-img-top" alt="' . $row["name_dish"] . '">';
                            echo '<div class="card-body">';
                            // Update form with pre-filled values
                            echo '<form action="includes/update.php" method="POST">';
                            echo '<input type="hidden" name="id_dish" value="' . $row["id_dish"] . '">';
                            echo '<div class="form-group">';
                            echo '<label for="dish_name">Name:</label>';
                            echo '<input type="text" class="form-control" id="dish_name" name="dish_name" value="' . $row["name_dish"] . '">';
                            echo '</div>';
                            echo '<div class="form-group">';
                            echo '<label for="dish_price">Price:</label>';
                            echo '<input type="text" class="form-control" id="dish_price" name="dish_price" value="' . $row["prix"] . '">';
                            echo '</div>';
                            echo '<div class="form-group">';
                            echo '<label for="dish_description">Description:</label>';
                            echo '<textarea class="form-control" id="dish_description" name="dish_description">' . $row["dsc"] . '</textarea>';
                            echo '</div>';
                            echo '<button type="submit" class="btn btn-secondary" name="update_menu">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                            </svg>
                            Update</button>';
                            echo '</form>';
                            echo '<div>';
                            echo '<form action="view_menu.php" method="POST">';
                            echo '<input type="hidden" name="id_dish" value="' . $row["id_dish"] . '">';
                            echo '<button type="submit" class="btn btn-danger btn-sm" name="delete_menu">';
                            echo '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
                            <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5"/>
                        </svg>';
                            echo ' Delete</button>';
                            echo '</form>';
                            echo '</div>';


                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                        }
                    } else {
                        echo '<div class="col">';
                        echo '<p class="text-center">No items available in the menu.</p>';
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
                        <?php
                        if (isset($_POST['delete_menu'])) {
                            require 'includes/dbh.inc.php';

                            $dish_id = $_POST['id_dish'];
                            $sql = "DELETE FROM menu WHERE id_dish = ?";
                            $stmt = $conn->prepare($sql);
                            if ($stmt === false) {
                                echo "Error: " . $conn->error;
                            } else {
                                $stmt->bind_param("i", $dish_id);
                                if ($stmt->execute()) {
                                    $_SESSION['delete_message'] = 'Menu item deleted successfully.';
                                } else {
                                    $_SESSION['delete_error'] = 'Error deleting menu item.';
                                }
                                $stmt->close();
                            }
                            $conn->close();
                        }
                        ?>
                    </div>
                </div>
            </section>

        </main>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<!-- Ajoutez cette ligne à la fin du body pour inclure Bootstrap JS -->


</body>
</html>
