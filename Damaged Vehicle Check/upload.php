<?php
session_start();
include 'db_connections.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$upload_success = $upload_error = "";
$booking_success = $booking_error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $has_upload = !empty($_FILES['image']['name']);
    $has_booking = !empty($_POST['date']) && !empty($_POST['service_type']);

    $conn->beginTransaction();

    try {
        if ($has_upload) {
            $description = trim($_POST['description']);
            if (strlen($description) > 500) {
                throw new Exception("Description cannot exceed 500 characters.");
            }

            $image = $_FILES['image'];
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            $max_size = 5 * 1024 * 1024; // 5MB

            if (!in_array($image['type'], $allowed_types)) {
                throw new Exception("Invalid file type. Only JPEG, PNG, and GIF are allowed.");
            }
            if ($image['size'] > $max_size) {
                throw new Exception("File size exceeds the maximum limit of 5MB.");
            }

            $file_info = finfo_open(FILEINFO_MIME_TYPE);
            $mime_type = finfo_file($file_info, $image['tmp_name']);
            finfo_close($file_info);

            if (!in_array($mime_type, $allowed_types)) {
                throw new Exception("Invalid file type detected.");
            }

            $target_dir = "uploads/";
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0755, true);
            }
            $unique_file_name = uniqid() . "." . pathinfo($image['name'], PATHINFO_EXTENSION);
            $target_file = $target_dir . $unique_file_name;

            if (!move_uploaded_file($image['tmp_name'], $target_file)) {
                throw new Exception("Error moving uploaded file.");
            }

            $stmt = $conn->prepare("INSERT INTO user_activities (user_id, type, image_path, description) 
                                    VALUES (:user_id, 'upload', :image_path, :description)");
            $stmt->execute([
                ':user_id' => $user_id,
                ':image_path' => $target_file,
                ':description' => $description
            ]);

            $upload_success = "Image uploaded successfully.";
        }

        if ($has_booking) {
            $stmt = $conn->prepare("SELECT name, email FROM users WHERE id = :user_id");
            $stmt->execute([':user_id' => $user_id]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$user) {
                throw new Exception("User not found.");
            }

            $date = $_POST['date'];
            $service_type = $_POST['service_type'];
            $valid_service_types = ['Basic Check', 'Premium Check', 'Full Inspection'];

            if (strtotime($date) < time()) {
                throw new Exception("The selected date must be in the future.");
            }
            if (!in_array($service_type, $valid_service_types)) {
                throw new Exception("Invalid service type selected.");
            }

            $stmt = $conn->prepare("INSERT INTO user_activities (user_id, type, name, email, booking_date, service_type) 
                                    VALUES (:user_id, 'booking', :name, :email, :booking_date, :service_type)");
            $stmt->execute([
                ':user_id' => $user_id,
                ':name' => $user['name'],
                ':email' => $user['email'],
                ':booking_date' => $date,
                ':service_type' => $service_type
            ]);

            $booking_success = "Booking created successfully!";
        }

        $conn->commit();
    } catch (Exception $e) {
        $conn->rollBack();
        if ($has_upload) {
            $upload_error = "An error occurred while uploading the image.";
            error_log("Upload Error: " . $e->getMessage());
        }
        if ($has_booking) {
            $booking_error = "An error occurred while creating the booking.";
            error_log("Booking Error: " . $e->getMessage());
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Damage Vehicle Check</title>
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="form-container">
        <form id="combinedForm" action="" method="post" enctype="multipart/form-data">
            <!-- Upload Section -->
            <div class="upload-section">
                <h1>Upload Image</h1>
                <?php if ($upload_success): ?>
                    <div class="message success"><?php echo htmlspecialchars($upload_success); ?></div>
                <?php endif; ?>
                <?php if ($upload_error): ?>
                    <div class="message error"><?php echo htmlspecialchars($upload_error); ?></div>
                <?php endif; ?>
                <label for="image">Upload Image:</label>
                <input type="file" name="image" id="image">
                <br>
                <label for="description">Description:</label>
                <textarea name="description" id="description" rows="4"></textarea>
            </div>

            <!-- Booking Section -->
            <div class="booking-section">
                <h1>Book Appointment</h1>
                <?php if ($booking_success): ?>
                    <div class="message success"><?php echo htmlspecialchars($booking_success); ?></div>
                <?php endif; ?>
                <?php if ($booking_error): ?>
                    <div class="message error"><?php echo htmlspecialchars($booking_error); ?></div>
                <?php endif; ?>
                <label for="date">Preferred Date:</label>
                <input type="date" name="date" id="date">
                <h4>Select service type:</h4>
                <div class="Workshops">
                    <div class="radio-tile-group">
                        <div class="input-container">
                            <input id="basic" class="radio-button" type="radio" name="service_type" value="Basic Check">
                            <div class="radio-tile">
                                <label for="basic" class="radio-tile-label">Basic Check</label>
                            </div>
                        </div>
                        <div class="input-container">
                            <input id="premium" class="radio-button" type="radio" name="service_type" value="Premium Check">
                            <div class="radio-tile">
                                <label for="premium" class="radio-tile-label">Premium Check</label>
                            </div>
                        </div>
                        <div class="input-container">
                            <input id="full" class="radio-button" type="radio" name="service_type" value="Full Inspection">
                            <div class="radio-tile">
                                <label for="full" class="radio-tile-label">Full Inspection</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Single Submit Button -->
            <div class="submit-section">
                <button type="submit" class="btn" id="submitBtn">Submit</button>
            </div>
        </form>
    </div>
    <?php include 'footer.php'; ?>
</body>
<script src="script.js"></script>
</html>
// تضمين الفوتر
include 'footer.php';
?>
