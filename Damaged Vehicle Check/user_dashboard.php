<?php
session_start(); // بدء الجلسة

// تضمين ملف الاتصال بقاعدة البيانات
include 'db_connections.php';

// تضمين الهيدر
include 'header.php';
?>

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
    }
    main {
        width: 80%;
        margin: 20px auto;
        background: #fff;
        padding: 20px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
    }
    h1, h2 {
        color: #333;
        text-align: center;
    }
    .container {
        text-align: center;
    }
    .image-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 20px;
        margin-top: 20px;
    }
    .image-box {
        background: #fff;
        padding: 10px;
        border-radius: 8px;
        box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.1);
        text-align: center;
    }
    img {
        max-width: 300px;
        max-height: 300px;
        border-radius: 5px;
    }
    hr {
        margin: 20px 0;
    }
</style>

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
