<?php 
session_start();
include 'db_connections.php';
include 'header.php'; 

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$error = '';
$success = '';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Retrieve user details from database
        $stmt = $conn->prepare("SELECT name, email FROM users WHERE id = :user_id");
        $stmt->bindParam(':user_id', $_SESSION['user_id']);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            throw new Exception("User not found.");
        }

        // Validate form data
        if (empty($_POST['date'])) {
            throw new Exception("Please select a date.");
        }

        // Insert booking into database
        $stmt = $conn->prepare("INSERT INTO bookings (user_id, name, email, booking_date) 
                               VALUES (:user_id, :name, :email, :booking_date)");
        
        $stmt->bindParam(':user_id', $_SESSION['user_id']);
        $stmt->bindParam(':name', $user['name']);
        $stmt->bindParam(':email', $user['email']);
        $stmt->bindParam(':booking_date', $_POST['date']);
        
        if ($stmt->execute()) {
            $success = "Booking successfully created!";
        } else {
            throw new Exception("Error creating booking.");
        }

    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>
<main>
    <h1>Book an Appointment</h1>
    
    <?php if ($error): ?>
        <div class="error message"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <div class="success message"><?php echo $success; ?></div>
    <?php endif; ?>

    <form action="booking.php" method="post">
        <label for="date">Preferred Date:</label>
        <input type="date" name="date" id="date" required>
        <br>
        
        <div class="Workshops">
            <h3>Select Service Type:</h3>
            <div class="radio-tile-group">
                <div class="input-container">
                    <input id="basic" class="radio-button" type="radio" name="service_type" value="Basic Check" required>
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
        
        <br>
        <button type="submit" class="btn">Book Now</button>
    </form>
</main>
<?php include 'footer.php'; ?>