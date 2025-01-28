<?php
session_start(); // بدء الجلسة

// تضمين ملف الاتصال بقاعدة البيانات
include 'db_connections.php';

// معالجة التسجيل
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // تشفير كلمة المرور

    // إدخال المستخدم الجديد في قاعدة البيانات
    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
    $stmt->execute(['name' => $name, 'email' => $email, 'password' => $password]);

    // توجيه المستخدم إلى صفحة تسجيل الدخول
    header("Location: login.php");
    exit();
}
?>

<?php include 'header.php'; ?>
   
 <div>
    
     <h1>Register</h1>
    <form method="POST">
        <label for="name">Name:</label>
        <input type="text" name="name" required>
        <br>
        <label for="email">Email:</label>
        <input type="email" name="email" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" name="password" required>
        <br>
        <br>
        <br>
        <button type="submit" id="Registerbutton">Register</button>
        <br> <br> 
        <p>Already have an account? <a href="login.php">Login here</a>.</p>
       </form>


 </div>



    <?php include 'footer.php'; ?>