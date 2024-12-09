<?php 
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CalorieCalc - Login</title>
    <link rel="stylesheet" href="../css/loginstyle.css">
    <link rel="icon" type="image/x-icon" href="../images/icon1.png">
</head>
<body>
    <!-- Home button with arrow icon -->
    <button class="button3" onclick="window.location.href='home.html'">
    <i class="fas fa-arrow-left"></i> ←🏛️
    </button>

    <div class="form">
        <img src="../images/icon1.png" alt="Icon">
        <h1>Log in</h1>

        <!-- Display success message if set -->
        <?php
        if (isset($_SESSION['register_success'])) {
            echo '<p class="success-message">' . $_SESSION['register_success'] . '</p>';
            unset($_SESSION['register_success']); // Clear message after displaying it
        }
        ?>

        <!-- Display login error message if set -->
        <?php
        if (isset($_SESSION['login_error'])) {
            echo '<p class="error-message">' . $_SESSION['login_error'] . '</p>';
            unset($_SESSION['login_error']); // Clear message after displaying it
        }
        ?>

        <!-- Login form -->
        <form id="loginForm" action="login_process.php" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <a class="forgot" href="forgotPass.php">Forgot Password?</a><br>
            <button class="button1" type="submit">Login</button>
        </form>
       
        <!-- Register button -->
        <div class="button2">
            <a href="register.php" class="register-button">Don't have an account? Register here.</a>
        </div>
        
    </div>
    
</body>
</html>
