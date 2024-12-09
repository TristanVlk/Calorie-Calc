<?php
session_start();
include '../includes/db_connect.php';

// Check if the user is logged in as an admin
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// Get the user ID to unblock
if (isset($_GET['id'])) {
    $userId = intval($_GET['id']);
    
    // Update the status to 'active'
    $stmt = $conn->prepare("UPDATE users SET status = 'active' WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    
    // Redirect back to admin dashboard
    header('Location: admin_dashboard.php');
    exit();
} else {
    // Redirect to dashboard if no user ID is provided
    header('Location: admin_dashboard.php');
    exit();
}
