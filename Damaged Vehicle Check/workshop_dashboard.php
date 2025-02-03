<?php
session_start();
// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
// Check if the user has the "workshop_manager" role
if ($_SESSION['user_role'] !== 'workshop_manager') {
    header("Location: index.php"); // Redirect unauthorized users
    exit();
}
// Include the database connection file
include 'db_connections.php';

// Fetch recent user activities for the dashboard
$stmt = $conn->prepare("
    SELECT ua.id, ua.type, ua.name, ua.email, ua.booking_date, ua.service_type, ua.status, ua.image_path, ua.description 
    FROM user_activities ua
    ORDER BY ua.created_at DESC
    LIMIT 10
");
$stmt->execute();
$activities = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workshop Manager Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* General Styles */
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #f9f9f9, #e0e0e0);
            color: #333;
            line-height: 1.6;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
            flex: 1;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        .header h1 {
            font-size: 2rem;
            color: #2c3e50;
        }
        .header a {
            text-decoration: none;
            color: #fff;
            background: linear-gradient(135deg, #1abc9c, #16a085);
            padding: 12px 24px;
            border-radius: 30px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header a:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.2);
        }
        .section {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }
        .section h2 {
            font-size: 1.5rem;
            color: #2c3e50;
            margin-bottom: 20px;
        }
        /* Activity Table */
        .activity-table {
            width: 100%;
            border-collapse: collapse;
        }
        .activity-table th, .activity-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .activity-table th {
            background: #f8f9fa;
            color: #2c3e50;
            font-weight: 600;
        }
        .activity-table tr:hover {
            background-color: #f1f1f1;
        }
        .status-pending {
            color: orange;
        }
        .status-accepted {
            color: green;
        }
        .status-rejected {
            color: red;
        }
       
        
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="dashboard-container">
        <!-- Header -->
        <div class="header">
            <h1>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h1>
           
        </div>

        <!-- Recent Activities Section -->
        <div class="section">
            <h2>Recent Activities</h2>
            <table class="activity-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Type</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Booking Date</th>
                        <th>Service Type</th>
                        <th>Status</th>
                       
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($activities)): ?>
                        <tr>
                            <td colspan="8" style="text-align: center;">No recent activities found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($activities as $activity): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($activity['id']); ?></td>
                                <td><?php echo htmlspecialchars($activity['type']); ?></td>
                                <td><?php echo htmlspecialchars($activity['name']); ?></td>
                                <td><?php echo htmlspecialchars($activity['email']); ?></td>
                                <td><?php echo htmlspecialchars($activity['booking_date']); ?></td>
                                <td><?php echo htmlspecialchars($activity['service_type']); ?></td>
                                <td class="status-<?php echo htmlspecialchars($activity['status']); ?>">
                                    <?php echo ucfirst($activity['status']); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>
    <?php include 'footer.php'; ?>
</body>
</html>
