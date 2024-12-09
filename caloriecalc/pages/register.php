<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CalorieCalc - Register</title>
    <link rel="stylesheet" href="../css/registerstyle.css">
    <link rel="icon" type="image/x-icon" href="../images/icon1.png">
    
</head>
<body>
   
    
    </button>

    <div class="container">
        
        
        <!-- Display success message -->
        <?php
        session_start();
        if (isset($_SESSION['register_success'])) {
            echo '<p class="success-message">' . $_SESSION['register_success'] . '</p>';
            unset($_SESSION['register_success']);  // Clear message after displaying it
        }

        // Retrieve error messages from the session if they exist
        $errors = isset($_SESSION['register_errors']) ? $_SESSION['register_errors'] : [];
        unset($_SESSION['register_errors']);  // Clear errors after displaying
        ?>

        <form id="registerForm" action="register_process.php" method="post">
             <img src="../images/icon1.png" alt="Icon">
            <h1>Registration</h1>    

            <label for="eMail">Email:</label>
            <input type="email" id="eMail" name="eMail" required>
            <?php if (isset($errors['email'])): ?>
                <p class="error-message"><?= $errors['email']; ?></p>
            <?php endif; ?>
            
            <label for="birthday">Birth Date:</label>
            <input type="date" id="birthday" name="birthday" required>
            
            <label for="newUsername">Username:</label>
            <input type="text" id="newUsername" name="newUsername" required>
            <?php if (isset($errors['username'])): ?>
                <p class="error-message"><?= $errors['username']; ?></p>
            <?php endif; ?>
            
            <label for="newPassword">Password:</label>
            <input type="password" id="newPassword" name="newPassword" required>
            
            <div class="terms">
                <input type="checkbox" id="terms" name="terms" required>
                <label for="terms">I agree to the <a href="terms.html" target="_blank" class="terms-button">Terms and Conditions</a></label>
            </div>
            
            <button class='regbut' type="submit">Register</button>
            <a href="login.php" class="login-button">Already have an account? Login here.</a> 
        </form>
        
    </div>
</body>
</html>
