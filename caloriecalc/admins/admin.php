<?php
include '../includes/db_connect.php';  // Adjust this to your actual path

// Define the admin details
$email = 'calorieadmin@gmail.com';
$username = 'calorieadmin';
$password = 'admin123';  // The password you want to hash

// Hash the password using password_hash()
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

// Prepare the SQL query to insert the admin
$stmt = $conn->prepare("INSERT INTO admins (email, username, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $email, $username, $hashedPassword);

if ($stmt->execute()) {
    echo "Admin account created successfully!";
} else {
    echo "Error: " . $stmt->error;
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
