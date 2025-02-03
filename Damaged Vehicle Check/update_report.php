<?php
session_start();
include 'db_connections.php';

// Check if user is logged in and has the necessary permissions
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'expert') {
    header('Location: login.php');
    exit();
}

// Get form data
$report_id = $_POST['report_id'] ?? null;
$expert_notes = $_POST['expert_notes'] ?? '';
$estimated_cost = $_POST['estimated_cost'] ?? null;

if ($report_id && $estimated_cost !== null) {
    try {
        // Update the report with expert notes and estimated cost
        $stmt = $conn->prepare("
            UPDATE user_activities 
            SET expert_notes = :expert_notes, estimated_cost = :estimated_cost 
            WHERE id = :report_id
        ");
        $stmt->execute([
            'expert_notes' => $expert_notes,
            'estimated_cost' => $estimated_cost,
            'report_id' => $report_id
        ]);

        // Redirect back to the reports page
        header('Location: damage_reports.php');
        exit();
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
} else {
    die("Invalid input.");
}
?>
