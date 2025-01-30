<?php
session_start(); // بدء الجلسة

// تضمين ملف الاتصال بقاعدة البيانات
include 'db_connections.php';

// تضمين الهيدر
include 'header.php';
?>
<main>



    <h1>Reports and Bookings</h1>
    <p>Welcome to your dashboard. Here you can view your reports and bookings.</p>

    <?php
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        echo "<p><strong>User ID:</strong> $user_id</p>";

        try {
            $stmt = $conn->prepare("SELECT * FROM bookings WHERE user_id = :user_id");
            $stmt->execute(['user_id' => $user_id]);
            $bookings = $stmt->fetchAll();

            if ($bookings) {
                echo "<h2>Your Bookings</h2><div class='container'>";
                foreach ($bookings as $booking) {
                    echo "<p><strong>Date:</strong> " . htmlspecialchars($booking['booking_date']) . "</p><hr>";
                }
                echo "</div>";
            } else {
                echo "<p>No bookings found for user ID: $user_id</p>";
            }
        } catch (PDOException $e) {
            echo "<p>Error retrieving bookings: " . $e->getMessage() . "</p>";
        }

        try {
            $stmt = $conn->prepare("SELECT * FROM uploads WHERE user_id = :user_id");
            $stmt->execute(['user_id' => $user_id]);
            $uploads = $stmt->fetchAll();

            if ($uploads) {
                echo "<h2>Your Uploaded Images</h2><div class='image-container'>";
                foreach ($uploads as $upload) {
                    echo "<div class='image-box'>";
                    echo "<p><strong>Description:</strong> " . htmlspecialchars($upload['description']) . "</p>";
                    echo "<img src='" . htmlspecialchars($upload['image_path']) . "' alt='Uploaded Image'/>";
                    echo "</div>";
                }
                echo "</div>";
            } else {
                echo "<p>No images found for user ID: $user_id</p>";
            }
        } catch (PDOException $e) {
            echo "<p>Error retrieving uploads: " . $e->getMessage() . "</p>";
        }
    } else {
        echo "<p>Please log in to view your dashboard.</p>";
    }
    ?>
</main>

<?php
include 'footer.php';
?>
