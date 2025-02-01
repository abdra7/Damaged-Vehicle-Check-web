<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Damage Vehicle Check</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/png" href="black_and_white_image.png">
    <script src="script.js" defer></script>
</head>
<body>
    <header>
        <nav>
            <ul>

                    <li><a href="login.php">Login</a></li>

                    <li><a href="index.php">Home</a></li>
                    <li><a href="workshops.php">Workshops</a></li>
                    <li><a href="upload.php">Upload</a></li>
                    <li><a href="ratings.php">Ratings</a></li>
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'expert'): ?>
                            <li><a href="report.php">report user</a></li>
                    <?php endif; ?>
                    <li><a href="user_dashboard.php">Reports</a></li>
                    <?php else: ?>
                    <li><a href="logout.php">Logout</a></li>
                    <?php endif; ?>

            </ul>
        </nav>
    </header>

    </body>
    </html>
