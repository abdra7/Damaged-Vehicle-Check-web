<?php
session_start();
include 'db_connections.php';
include 'header.php';
// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if the user has the "expert" role
if ($_SESSION['user_role'] !== 'expert') {
    header("Location: index.php"); // Redirect non-experts to the home page
    exit();
}
// Process status updates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $activity_id = $_POST['activity_id'];
    $new_status = $_POST['status'];
    $repair_notes = $_POST['repair_notes'];
    $estimated_cost = $_POST['estimated_cost'];

    try {
        $stmt = $conn->prepare("
            UPDATE user_activities 
            SET status = :status,
                repair_notes = :repair_notes,
                estimated_cost = :estimated_cost,
                assigned_expert = :expert_id,
                updated_at = CURRENT_TIMESTAMP
            WHERE id = :activity_id
        ");
        $stmt->execute([
            ':status' => $new_status,
            ':repair_notes' => $repair_notes,
            ':estimated_cost' => $estimated_cost,
            ':expert_id' => $_SESSION['user_id'],
            ':activity_id' => $activity_id
        ]);
        $success = "Repair request updated successfully!";
    } catch (PDOException $e) {
        $error = "Error updating request: " . $e->getMessage();
    }
}

// Fetch all repair requests
try {
    $stmt = $conn->prepare("
        SELECT ua.*, 
               users.name as user_name, 
               users.email as user_email,
               expert.name as expert_name
        FROM user_activities ua
        LEFT JOIN users ON ua.user_id = users.id
        LEFT JOIN users expert ON ua.assigned_expert = expert.id
        WHERE ua.type = 'upload'
        ORDER BY ua.created_at DESC
    ");
    $stmt->execute();
    $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expert Repair Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .expert-dashboard {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .request-card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin: 20px 0;
            padding: 20px;
        }

        .status-indicator {
            padding: 5px 10px;
            border-radius: 4px;
            color: white;
            font-weight: bold;
        }

        .status-pending { background: #ffc107; }
        .status-in-progress { background: #17a2b8; }
        .status-completed { background: #28a745; }
        .status-rejected { background: #dc3545; }

        .metadata-row {
            display: flex;
            gap: 15px;
            margin: 10px 0;
            flex-wrap: wrap;
        }

        .metadata-item {
            background: #f8f9fa;
            padding: 8px 12px;
            border-radius: 4px;
            font-size: 0.9em;
        }

        .expanded-image {
            max-width: 100%;
            height: auto;
            margin: 10px 0;
            cursor: zoom-in;
            border-radius: 4px;
        }

        .repair-form {
            margin-top: 20px;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group select, 
        .form-group textarea, 
        .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="expert-dashboard">
        <h1>Repair Requests Management</h1>
        
        <?php if(isset($success)): ?>
            <div class="alert success"><?= $success ?></div>
        <?php endif; ?>
        <?php if(isset($error)): ?>
            <div class="alert error"><?= $error ?></div>
        <?php endif; ?>

        <div class="requests-list">
            <?php foreach ($requests as $request): ?>
                <div class="request-card">
                    <div class="request-header">
                        <h3>Customer Name:<?= $request['id'] ?> - <?= htmlspecialchars($request['user_name']) ?></h3>
                        <span class="status-indicator status-<?= htmlspecialchars($request['status']) ?>">
                            <?= ucfirst(htmlspecialchars(str_replace('-', ' ', $request['status']))) ?>
                        </span>
                    </div>

                    <div class="metadata-row">
                        <div class="metadata-item">
                            <strong>Submitted:</strong> <?= date('M d, Y H:i', strtotime($request['created_at'])) ?>
                        </div>
                        <?php if($request['assigned_expert']): ?>
                            <div class="metadata-item">
                                <strong>Expert:</strong> <?= htmlspecialchars($request['expert_name'] ?? 'Unassigned') ?>
                            </div>
                        <?php endif; ?>
                        <?php if($request['estimated_cost']): ?>
                            <div class="metadata-item">
                                <strong>Est. Cost:</strong> $<?= number_format($request['estimated_cost'], 2) ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="request-details">
                        <p><strong>Description:</strong> <?= htmlspecialchars($request['description']) ?></p>
                        
                        <?php if($request['image_path']): ?>
                            <img src="<?= htmlspecialchars($request['image_path']) ?>" 
                                 alt="Damage photo" 
                                 class="expanded-image"
                                 data-fullsize="<?= htmlspecialchars($request['image_path']) ?>">
                        <?php endif; ?>

                        <?php if($request['repair_notes']): ?>
                            <div class="notes-section">
                                <h4>Repair Notes</h4>
                                <p><?= nl2br(htmlspecialchars($request['repair_notes'])) ?></p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <form class="repair-form" method="POST">
                        <input type="hidden" name="activity_id" value="<?= $request['id'] ?>">
                        
                        <div class="form-group">
                            <label>Status Update:</label>
                            <select name="status" required>
                                <option value="pending" <?= $request['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="in-progress" <?= $request['status'] === 'in-progress' ? 'selected' : '' ?>>In Progress</option>
                                <option value="completed" <?= $request['status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
                                <option value="rejected" <?= $request['status'] === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Repair Notes:</label>
                            <textarea name="repair_notes" rows="4" 
                                placeholder="Add detailed repair notes..."><?= htmlspecialchars($request['repair_notes'] ?? '') ?></textarea>
                        </div>

                        <div class="form-group">
                            <label>Estimated Cost ($):</label>
                            <input type="number" name="estimated_cost" 
                                   value="<?= htmlspecialchars($request['estimated_cost'] ?? '') ?>"
                                   step="0.01" min="0" 
                                   placeholder="Enter estimated cost">
                        </div>

                        <button type="submit" class="btn btn-primary">Update Request</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        // Image zoom functionality
        document.querySelectorAll('.expanded-image').forEach(img => {
            img.addEventListener('click', () => {
                window.open(img.dataset.fullsize, '_blank');
            });
        });
    </script>

    <?php include 'footer.php'; ?>
</body>
</html>
