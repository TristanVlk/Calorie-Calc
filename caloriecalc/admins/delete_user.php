<?php
session_start();
include '../includes/db_connect.php';

// Check if the user is logged in as an admin
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');  // Redirect if not logged in as admin
    exit();
}

// Check if an ID is set in the URL
if (isset($_GET['id'])) {
    $userId = intval($_GET['id']); // Ensure it's an integer

    // First, delete records from the calculation_history table
    $stmt = $conn->prepare("DELETE FROM calculation_history WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->close();

    // Now delete the user from the users table
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    if ($stmt->execute()) {
        // User deleted successfully
        header('Location: admin_dashboard.php'); // Redirect to admin dashboard
        exit();
    } else {
        echo "Error deleting user: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "No user ID specified.";
}

$conn->close(); // Close the database connection
?>
