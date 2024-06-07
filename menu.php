<?php
// Check if the user is not logged in, then redirect to login page
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit(); // Ensure no further code execution after redirect
} ?>
<?php
// Include database connection
require 'includes/dbh.inc.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    // Handle image upload
    $targetDir = "uploads/";
    $targetFile = $targetDir . basename($_FILES["image"]["name"]);
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    $image = $_FILES['image']['name'];

    // Allow certain file formats
    $allowedFormats = array("jpg", "jpeg", "png", "gif");
    if (in_array($imageFileType, $allowedFormats)) {
        // Upload file
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            // Insert item into database
            $sql = "INSERT INTO menu (name_dish, prix, dsc, image) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sdss", $name, $price, $description, $image);
            
            if ($stmt->execute()) {
                // Item added successfully
                echo '<script>alert("Item added to menu successfully!");</script>';
            } else {
                // Error inserting item
                echo '<script>alert("Error adding item to menu!");</script>';
            }
        } else {
            // Error uploading image
            echo '<script>alert("Error uploading image!");</script>';
        }
    } else {
        // Invalid file format
        echo '<script>alert("Invalid file format! Only JPG, JPEG, PNG, GIF files are allowed.");</script>';
    }

    // Close database connection
    $stmt->close();
}
?>
<?php require "header_admin.php" ?> 

            <!-- Content -->
            <div class="col-md-9 content">
                <div class="container">
                    <h1 class="mt-5">Add Item to Menu</h1>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="price">Price:</label>
                            <input type="number" class="form-control" id="price" name="price" step="0.01" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Description:</label>
                            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="image">Image:</label>
                            <input type="file" class="form-control-file" id="image" name="image" accept="image/*" required>
                        </div>
                        <button type="submit" class="btn btn-secondary">Add Item</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

