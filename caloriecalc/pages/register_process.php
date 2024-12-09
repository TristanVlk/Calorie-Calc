<?php
session_start();
include '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['eMail'];
    $birthdate = $_POST['birthday'];
    $username = $_POST['newUsername'];
    $password = password_hash($_POST['newPassword'], PASSWORD_BCRYPT);

    // Check if the email already exists
    $checkEmailStmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $checkEmailStmt->bind_param("s", $email);
    $checkEmailStmt->execute();
    $emailResult = $checkEmailStmt->get_result();

    // Check if the username already exists
    $checkUsernameStmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $checkUsernameStmt->bind_param("s", $username);
    $checkUsernameStmt->execute();
    $usernameResult = $checkUsernameStmt->get_result();

    // Initialize an array to hold error messages
    $errors = [];

    if ($emailResult->num_rows > 0) {
        $errors['email'] = "Email is already registered.";
    }

    if ($usernameResult->num_rows > 0) {
        $errors['username'] = "Username is already taken.";
    }

    if (count($errors) > 0) {
        // Store errors in the session to display in register.php
        $_SESSION['register_errors'] = $errors;
        header('Location: register.php');  // Redirect back to registration page
        exit();
    } else {
        // Proceed to insert the new user if no errors
        $stmt = $conn->prepare("INSERT INTO users (email, birth_date, username, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $email, $birthdate, $username, $password);
        
        if ($stmt->execute()) {
            // Set a session variable for success message
            $_SESSION['register_success'] = 'Registered successfully!';
            header('Location: login.php');  // Redirect to login page
            exit();
        } else {
            echo "Registration failed. Please try again.";
        }
    }

    // Close the statements
    $checkEmailStmt->close();
    $checkUsernameStmt->close();
    $stmt->close();
}
?>
