<?php
session_start(); // بدء الجلسة

// تضمين ملف الاتصال بقاعدة البيانات
include 'db_connections.php';

// تضمين الهيدر
include 'header.php';
?>

<main>
    <h1>reports and bookings</h1>
    <p>Welcome to your dashboard. Here you can view your reports and bookings.</p>

    <?php
    // التحقق من وجود المستخدم في الجلسة
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        echo "<p> user ID: $user_id</p>"; // عرض معرف المستخدم للتصحيح

        // استرداد الحجوزات
        try {
            $stmt = $conn->prepare("SELECT * FROM bookings WHERE user_id = :user_id");
            $stmt->execute(['user_id' => $user_id]);
            $bookings = $stmt->fetchAll();

            if ($bookings) {
                echo "<h2>Your Bookings</h2>";
                foreach ($bookings as $booking) {
                    echo "<p>Date: " . htmlspecialchars($booking['booking_date']) . "</p>";
                    echo "<hr>";
                }
            } else {
                echo "<p>No bookings found for user ID: $user_id</p>"; // عرض رسالة تصحيح
            }
        } catch (PDOException $e) {
            echo "<p>Error retrieving bookings: " . $e->getMessage() . "</p>";
        }

        // استرداد الصور المرفوعة
        try {
            $stmt = $conn->prepare("SELECT * FROM uploads WHERE user_id = :user_id");
            $stmt->execute(['user_id' => $user_id]);
            $uploads = $stmt->fetchAll();

            if ($uploads) {
                echo "<h2>Your Uploaded Images</h2>";
                foreach ($uploads as $upload) {
                    echo "<p>Description: " . htmlspecialchars($upload['description']) . "</p>";
                    echo "<img src='" . htmlspecialchars($upload['image_path']) . "' alt='Uploaded Image' style='max-width: 300px; max-height: 300px;'/>";
                    echo "<hr>";
                }
            } else {
                echo "<p>No images found for user ID: $user_id</p>"; // عرض رسالة تصحيح
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
// تضمين الفوتر
include 'footer.php';
?>