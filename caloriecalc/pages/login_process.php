<?php
session_start();
include '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if the username exists in the admins table first
    $adminStmt = $conn->prepare("SELECT id, username, password FROM admins WHERE username = ?");
    $adminStmt->bind_param("s", $username);
    $adminStmt->execute();
    $adminResult = $adminStmt->get_result();

    if ($adminResult->num_rows > 0) {
        $admin = $adminResult->fetch_assoc();
        
        // If the password matches, the user is an admin
        if (password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['username'] = $admin['username'];
            $_SESSION['logged_in'] = true;
            header('Location: ../admins/admin_dashboard.php');  // Admin dashboard
            exit();
        } else {
            // Incorrect password for admin
            $_SESSION['login_error'] = "Incorrect password.";
            header('Location: login.php');
            exit();
        }
    } else {
        // If not found in admins, check the users table
        $userStmt = $conn->prepare("SELECT id, username, password, status FROM users WHERE username = ?");
        $userStmt->bind_param("s", $username);
        $userStmt->execute();
        $userResult = $userStmt->get_result();

        if ($userResult->num_rows > 0) {
            $user = $userResult->fetch_assoc();
            
            // Check if the user is blocked
            if ($user['status'] == 'blocked') {
                $_SESSION['login_error'] = "Your account has been blocked.";
                header('Location: login.php');
                exit();
            }

            // Check if the user's account is inactive
            if ($user['status'] == 'inactive') {
                $_SESSION['login_error'] = "Your account is inactive. Please contact support.";
                header('Location: login.php');
                exit();
            }
            
            // If the password matches, the user is a regular user
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['logged_in'] = true;
                header('Location: calculator.php');  // User profile
                exit();
            } else {
                // Incorrect password for user
                $_SESSION['login_error'] = "Incorrect password.";
                header('Location: login.php');
                exit();
            }
        } else {
            // No account found with the username
            $_SESSION['login_error'] = "No account found with that username.";
            header('Location: login.php');
            exit();
        }
    }
}
?>
