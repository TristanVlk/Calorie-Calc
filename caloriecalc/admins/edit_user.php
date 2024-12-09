<?php
session_start();
include '../includes/db_connect.php';

// Check if the user is logged in as an admin
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');  // Redirect if not logged in as admin
    exit();
}

// Check if an ID and new username are set
if (isset($_POST['id']) && isset($_POST['username'])) {
    $userId = intval($_POST['id']); // Ensure it's an integer
    $newUsername = trim($_POST['username']); // Get the new username

    // Update the user's username in the users table
    $stmt = $conn->prepare("UPDATE users SET username = ? WHERE id = ?");
    $stmt->bind_param("si", $newUsername, $userId);
    if ($stmt->execute()) {
        // Successfully updated username
        header('Location: admin_dashboard.php?message=User updated successfully');
        exit();
    } else {
        echo "Error updating user: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "No user ID or username specified.";
}

$conn->close(); // Close the database connection
?>
