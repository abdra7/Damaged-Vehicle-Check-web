<?php
session_start(); // بدء الجلسة

// تضمين ملف الاتصال بقاعدة البيانات
include 'db_connections.php';

// معالجة تسجيل الدخول
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // التحقق من بيانات المستخدم
    $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // تخزين معرف المستخدم في الجلسة
        $_SESSION['user_id'] = $user['id'];
        header("Location: index.php"); // توجيه المستخدم إلى لوحة التحكم
        exit();
    } else {
        echo "<p class='error-message'>Invalid email or password.</p>";
    }
}
?>
<?php include 'header.php'; ?>
   
    <main><link rel="stylesheet" href="style.css">
    <div class="login-container">
        <h1>Login</h1>
        <form method="POST">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required placeholder="Enter your email">

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required placeholder="Enter your password">

            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="register.php">Register here</a>.</p>
    </div></main>


<?php include 'footer.php'; ?>