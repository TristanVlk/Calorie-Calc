<?php
session_start();
require '../includes/db_connect.php';
date_default_timezone_set('Asia/Manila');

$message = $error = "";

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['email'])) {
        // Forgot Password
        $email = $conn->real_escape_string($_POST['email']);
        $result = $conn->query("SELECT * FROM users WHERE email = '$email'");

        if ($result->num_rows > 0) {
            $pin = rand(100000, 999999);
            $expiration = date("Y-m-d H:i:s", strtotime('+15 minutes'));

            $conn->query("UPDATE users SET reset_pin = '$pin', pin_expiration = '$expiration' WHERE email = '$email'");

            mail($email, "Password Reset PIN", "Your PIN is: $pin", "From: no-reply@caloriecalc.com");

            $_SESSION['email'] = $email;
            $message = "A PIN has been sent to your email.";
        } else {
            $error = "Email not found.";
        }
    } elseif (isset($_POST['pin'])) {
        // Verify PIN
        $pin = $conn->real_escape_string($_POST['pin']);
        $result = $conn->query("SELECT * FROM users WHERE reset_pin = '$pin' AND pin_expiration > NOW()");

        if ($result->num_rows > 0) {
            $_SESSION['email_verified'] = true;
            $message = "PIN verified. You can now reset your password.";
        } else {
            $error = "Invalid or expired PIN.";
        }
    } elseif (isset($_POST['password'])) {
        // Reset Password
        if (isset($_SESSION['email_verified']) && $_SESSION['email_verified'] === true) {
            $email = $_SESSION['email'];
            $new_password = password_hash($_POST['password'], PASSWORD_BCRYPT);

            $conn->query("UPDATE users SET password = '$new_password', reset_pin = NULL, pin_expiration = NULL WHERE email = '$email'");

            $_SESSION['message'] = "Password reset successful.";
            session_destroy();
            header("Location: login.php");
            exit();
        } else {
            $error = "Unauthorized password reset attempt.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/forgotPass.css">
</head>
<body>
    <div class="container mt-5">
        <!-- Display messages -->
        <?php if ($message): ?>
            <div class="alert alert-success mb-5"><?= $message ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-danger mb-5"><?= $error ?></div>
        <?php endif; ?>

        <!-- Forgot Password Form -->
        <form id="forgotPasswordForm" method="POST">
            <label for="email" class="label-enter text-white ">Enter your email:</label>
            <input type="email" name="email" id="email" class="form-control mb-4" required>
            <button type="submit" class="btn btn-primary">Send PIN</button>
        </form>
    </div>

    <!-- Verify PIN Modal -->
    <div class="modal fade" id="verifyPinModal" tabindex="-1" aria-labelledby="verifyPinModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header text-white">
                    <h5 class="modal-title" id="verifyPinModalLabel">Verify PIN</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" id="verifyPinForm">
                    <div class="modal-body text-white">
                        <label for="pin">Enter PIN:</label>
                        <input type="text" name="pin" id="pin" class="form-control" required>
                    </div>
                    <div class="modal-footer text-white">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Verify PIN</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Reset Password Modal -->
    <div class="modal fade" id="resetPasswordModal" tabindex="-1" aria-labelledby="resetPasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header text-white">
                    <h5 class="modal-title" id="resetPasswordModalLabel">Reset Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" id="resetPasswordForm">
                    <div class="modal-body text-white">
                        <label for="password">New Password:</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Reset Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Handle modal transitions
        <?php if ($message === "A PIN has been sent to your email."): ?>
            var verifyPinModal = new bootstrap.Modal(document.getElementById('verifyPinModal'));
            verifyPinModal.show();
        <?php elseif ($message === "PIN verified. You can now reset your password."): ?>
            var resetPasswordModal = new bootstrap.Modal(document.getElementById('resetPasswordModal'));
            resetPasswordModal.show();
        <?php endif; ?>
    </script>
</body>
</html>
