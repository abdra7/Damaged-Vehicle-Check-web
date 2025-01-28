<?php
session_start(); // Start the session to access user data

// Database Configuration
$host = 'localhost'; // Database host
$dbname = 'damage_vehicle_check'; // Database name
$username = 'root'; // Database username
$password = ''; // Database password

// Initialize the database connection
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Check if the user is logged in (assuming user_id is stored in the session)
if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to upload images.");
}

$user_id = $_SESSION['user_id']; // Get the logged-in user's ID

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the description from the form
    $description = $_POST['description'];

    // Check if the file was uploaded without errors
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image = $_FILES['image'];

        // Validate file type (only allow JPG, PNG, and GIF)
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($image['type'], $allowed_types)) {
            // Define the target directory for uploads
            $target_dir = "uploads/";

            // Create the uploads directory if it doesn't exist
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0755, true);
            }

            // Generate a unique file name to avoid overwriting
            $file_extension = pathinfo($image['name'], PATHINFO_EXTENSION);
            $unique_file_name = uniqid() . "." . $file_extension;
            $target_file = $target_dir . $unique_file_name;

            // Move the uploaded file to the target directory
            if (move_uploaded_file($image['tmp_name'], $target_file)) {
                // Insert image information into the database
                try {
                    $stmt = $conn->prepare("INSERT INTO uploads (user_id, image_path, description) VALUES (:user_id, :image_path, :description)");
                    $stmt->bindParam(':user_id', $user_id);
                    $stmt->bindParam(':image_path', $target_file);
                    $stmt->bindParam(':description', $description);

                    if ($stmt->execute()) {
                        $success_message = "Image uploaded and record inserted successfully.";
                    } else {
                        $error_message = "Error inserting record into database.";
                    }
                } catch (PDOException $e) {
                    $error_message = "Database error: " . $e->getMessage();
                }
            } else {
                $error_message = "Error moving uploaded file.";
            }
        } else {
            $error_message = "Invalid file type. Only JPG, PNG, and GIF are allowed.";
        }
    } else {
        $error_message = "Error uploading file. Please try again.";
    }
}
?>

<?php include 'header.php'; ?>
<main>
<link rel="stylesheet" href="style.css">
    <h1>Upload Vehicle Image</h1>

    <!-- Display Success or Error Messages -->
    <?php if (isset($success_message)): ?>
        <div class="message success"><?php echo $success_message; ?></div>
    <?php endif; ?>
    <?php if (isset($error_message)): ?>
        <div class="message error"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <!-- Upload Form -->
    <form action="upload.php" method="post" enctype="multipart/form-data">
        <label for="image">Upload Image:</label>
        <input type="file" name="image" id="image" required>
        <br>
        <label for="description">Description:</label>
        <textarea name="description" id="description" rows="4" required></textarea>
        <br>
        <button type="submit" class="btn">Upload</button>
    </form>
</main>
<?php
// تضمين الفوتر
include 'footer.php';
?>
