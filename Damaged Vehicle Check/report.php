<?php 
session_start();
include 'db_connections.php';
include 'header.php';     

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Get sort parameter
$sort = $_GET['sort'] ?? 'date_desc';
$order = match($sort) {
    'date_asc' => 'created_at ASC',
    'severity' => 'service_type ASC',
    'status' => 'status ASC',
    default => 'created_at DESC'
};

// Fetch damage reports (uploads) for current user
try {
    $stmt = $conn->prepare("
        SELECT * FROM user_activities 
        WHERE user_id = :user_id AND type = 'upload'
        ORDER BY $order
    ");
    $stmt->execute(['user_id' => $_SESSION['user_id']]);
    $reports = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Damage Vehicle Check - User Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <style>
/* Report Card Styling */
.report-card {
    border: 1px solid #ddd;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
    background-color: #fff;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.report-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
}
.report-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}
.report-status {
    font-weight: bold;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 0.9rem;
}
.report-status.accepted {
    background-color: #d4edda;
    color: #155724;
}
.report-status.rejected {
    background-color: #f8d7da;
    color: #721c24;
}
.report-status.pending {
    background-color: #fff3cd;
    color: #856404;
}
.report-date {
    font-size: 0.9em;
    color: #666;
}
.damage-severity {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
}
.severity-indicator {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    margin-right: 10px;
}
.severity-indicator.low {
    background-color: green;
}
.severity-indicator.medium {
    background-color: orange;
}
.severity-indicator.high {
    background-color: red;
}
.expert-notes {
    margin-top: 15px;
    padding: 10px;
    background-color: #f9f9f9;
    border-left: 4px solid #007bff;
}
.expert-notes h4 {
    margin-top: 0;
    color: #007bff;
}
.estimated-cost {
    margin-top: 15px;
    padding: 10px;
    background-color: #f9f9f9;
    border-left: 4px solid #28a745;
}
.estimated-cost h4 {
    margin-top: 0;
    color: #28a745;
}
.sort-controls {
    margin-bottom: 20px;
    text-align: right;
}
.sort-select {
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 1rem;
    cursor: pointer;
}
.no-reports {
    text-align: center;
    font-size: 1.2rem;
    color: #666;
    margin-top: 20px;
}
    </style>
</head>
<body>
<main>
    <div class="container">
        <h1>Your Damage Reports</h1>

        <!-- Sorting Controls -->
        <div class="sort-controls">
            <label for="sort">Sort By:</label>
            <select id="sort" class="sort-select" onchange="location.href='?sort=' + this.value;">
                <option value="date_desc" <?= $sort === 'date_desc' ? 'selected' : '' ?>>Newest First</option>
                <option value="date_asc" <?= $sort === 'date_asc' ? 'selected' : '' ?>>Oldest First</option>
                <option value="severity" <?= $sort === 'severity' ? 'selected' : '' ?>>Severity</option>
                <option value="status" <?= $sort === 'status' ? 'selected' : '' ?>>Status</option>
            </select>
        </div>

        <!-- Reports Grid -->
        <div class="report-grid">
            <?php if (empty($reports)): ?>
                <div class="no-reports">No damage reports found.</div>
            <?php else: ?>
                <?php foreach ($reports as $report): ?>
                    <div class="report-card">
                        <div class="report-header">
                            <span class="report-status <?= $report['status'] ?>">
                                <?= match($report['status']) {
                                    'accepted' => 'Resolved',
                                    'rejected' => 'Rejected',
                                    default => 'Pending'
                                } ?>
                            </span>
                            <span class="report-date"><?= date('M d, Y', strtotime($report['created_at'])) ?></span>
                        </div>
                        <div class="report-body">
                            <h3><?= htmlspecialchars($report['service_type'] ?? 'Damage Report') ?></h3>
                            <p class="report-description"><?= htmlspecialchars($report['description']) ?></p>
                            <?php if ($report['image_path']): ?>
                                <div class="damage-images">
                                    <img src="<?= htmlspecialchars($report['image_path']) ?>" alt="Damage photo" class="damage-thumbnail">
                                </div>
                            <?php endif; ?>
                            <!-- Expert Notes Section -->
                            <?php if ($report['repair_notes']): ?>
                                <div class="expert-notes">
                                    <h4>Expert Notes:</h4>
                                    <p><?= htmlspecialchars($report['repair_notes']) ?></p>
                                </div>
                            <?php endif; ?>
                            <!-- Estimated Cost Section -->
                            <?php if ($report['estimated_cost']): ?>
                                <div class="estimated-cost">
                                    <h4>Estimated Cost:</h4>
                                    <p>$<?= number_format((float)$report['estimated_cost'], 2) ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</main>
<?php include 'footer.php'; ?>
</body>
<script src="script.js"></script>
</html>
