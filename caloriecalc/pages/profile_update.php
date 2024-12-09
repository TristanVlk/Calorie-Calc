<?php
// Include the database connection file
include '../includes/db_connect.php';

// Start a session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Clear any previous messages
$_SESSION['success'] = '';
$_SESSION['error'] = ''; 

// Assume the user is logged in and we have their ID stored in the session
$user_id = $_SESSION['user_id'];

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form values
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $currentPassword = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];
    $bio = mysqli_real_escape_string($conn, $_POST['bio']);
    
    // Check if the current password is correct
    $query = "SELECT password FROM users WHERE id = '$user_id'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);
    
    if (password_verify($currentPassword, $user['password'])) {
        // Check if the new username already exists (excluding current user)
        $checkUsernameQuery = "SELECT * FROM users WHERE username = '$username' AND id != '$user_id'";
        $checkUsernameResult = mysqli_query($conn, $checkUsernameQuery);

        if (mysqli_num_rows($checkUsernameResult) > 0) {
            $_SESSION['error'] = "Username already exists! Please choose a different username.";
            header("Location: profile.php");
            exit;
        }

        // Update the username and bio
        $updateQuery = "UPDATE users SET username = '$username', bio = '$bio' WHERE id = '$user_id'";

        // Check if a new password was entered and it matches the confirmation
        if (!empty($newPassword) && $newPassword === $confirmPassword) {
            $hashedNewPassword = password_hash($newPassword, PASSWORD_BCRYPT);
            $updateQuery = "UPDATE users SET username = '$username', password = '$hashedNewPassword', bio = '$bio' WHERE id = '$user_id'";
        }

        // Execute the update query
        if (mysqli_query($conn, $updateQuery)) {
            // Handle the profile picture upload if a file was selected
            if (isset($_FILES['profilePicture']) && $_FILES['profilePicture']['error'] == 0) {
                $targetDir = "../images";
                $fileName = basename($_FILES['profilePicture']['name']);
                $targetFilePath = $targetDir . $fileName;

                // Move the uploaded file to the uploads directory
                if (move_uploaded_file($_FILES['profilePicture']['tmp_name'], $targetFilePath)) {
                    // Update the profile picture path in the database
                    $updatePictureQuery = "UPDATE users SET profile_picture = '$targetFilePath' WHERE id = '$user_id'";
                    mysqli_query($conn, $updatePictureQuery);
                }
            }

            $_SESSION['success'] = "Profile updated successfully!";
            header("Location: profile.php");
            exit;
        } else {
            echo "Error updating profile: " . mysqli_error($conn);
        }
    } else {
        // If the current password is incorrect
        $_SESSION['error'] = "Incorrect current password!";
        header("Location: profile.php");
        exit;
    }
}
?>