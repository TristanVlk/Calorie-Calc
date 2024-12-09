<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../css/profilestyle.css">
    <link rel="icon" type="image/x-icon" href="../images/icon1.png">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            padding-top: 60px; 
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-dark bg-transparent">
    <div class="container">
        <!-- logo -->
        <a class="navbar-brand display-1 fw-normal" href="#">Calorie<span class="rounded-pill" style="color: #f1683a; background-color: #2c3336;">Calc</span></a>
        <!-- toggle -->
        <button class="navbar-toggler shadow-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasDarkNavbar" aria-controls="offcanvasDarkNavbar" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="sidebar offcanvas offcanvas-start" tabindex="-1" id="offcanvasDarkNavbar" aria-labelledby="offcanvasDarkNavbarLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title text-white fs-2" id="offcanvasDarkNavbarLabel">Calorie<span class="rounded-pill" style="color: #f1683a; background-color: #2c3336;">Calc</span></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <?php
                // Fetch user info from the session
                session_start();
                include '../includes/db_connect.php';
                
                $user_id = $_SESSION['user_id'];
                $query = "SELECT username, profile_picture FROM users WHERE id = '$user_id'";
                $result = mysqli_query($conn, $query);
                $user = mysqli_fetch_assoc($result);
                ?>

                <!-- Display Profile Picture -->
                <div class="text-center mb-3">
                    <?php if ($user['profile_picture']): ?>
                        <img src="<?php echo $user['profile_picture']; ?>" alt="Profile Picture" class="rounded-circle" width="100" height="100">
                    <?php else: ?>
                        <img src="../images/icon1.png" alt="Default Profile Picture" class="rounded-circle" width="100" height="100">
                    <?php endif; ?>
                </div>

                <!-- Display Username -->
                <h5 class="text-center text-white mb-3"><?php echo htmlspecialchars($user['username']); ?></h5>
        
  
                <!-- Menu Items -->
                <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                    <li class="nav-item">
                        <a class="nav-link active" href="profile.php">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="tracker.php">Tracker</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Calculator
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark">
                            <li><a class="dropdown-item" href="calculator.php">Calculator</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="history.php">Calculation History</a></li>
                        </ul>
                    </li>
                </ul>
                <br>
                    <div class="hi mt-auto">
            <a class="nav-link text-danger " href="logout.php">Logout</a>
        </div>
            </div>
        </div>
    </div>
</nav>

<!-- Centered Container -->
<div class="container d-flex justify-content-center align-items-center vh-80">
    <!-- Profile Card -->
    <div class="card shadow-sm p-4 w-50">

        <?php
        // Display success message if set
        if (isset($_SESSION['success']) && $_SESSION['success'] !== '') {
            echo '<div class="alert alert-success" role="alert">' . $_SESSION['success'] . '</div>';
            // Clear the message after displaying it
            unset($_SESSION['success']);
        }

        // Display error message if set
        if (isset($_SESSION['error']) && $_SESSION['error'] !== '') {
            echo '<div class="alert alert-danger" role="alert">' . $_SESSION['error'] . '</div>';
            // Clear the message after displaying it
            unset($_SESSION['error']);
        }
        ?>

        <div class="profile-picture-container text-center mb-3">
            <!-- Profile Preview Image (Initially hidden) -->
            <img id="profilePreview" class="rounded-circle" src="" alt="Profile Preview" style="display: none; width: 150px; height: 150px; border: #f1683a 2px solid;" />
        </div>
        
        <h4 id="greeting" class="text-center" style="display: none;">Hello, <span id="usernameDisplay"></span></h4>
        
        <!-- Profile Form -->
        <form id="profileForm" action="profile_update.php" method="POST" enctype="multipart/form-data" class="mt-3">
            <div class="mb-3">
                <label for="profilePicture" class="form-label">Profile Picture</label>
                <input type="file" class="form-control" id="profilePicture" name="profilePicture" accept="image/*" onchange="previewProfilePicture()" />
                <button type="submit" id="uploadButton" class="btn btn-secondary btn-sm mt-2">Upload</button>
                <img id="profilePreview" src="" alt="Profile Preview" class="img-fluid mt-2 rounded" style="display: none;" />
            </div>
            
            <div class="mb-2 text-white">
                <h3 style='font-size: 1.4em'>Edit Your Information Here:</h3>
            </div>

            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" name="username" id="username" placeholder="Enter your New Username" required />
            </div>

            <div class="mb-3">
                <label for="currentPassword" class="form-label">Current Password</label>
                <input type="password" class="form-control" name="currentPassword" id="currentPassword" placeholder="Enter your Current Password" required />
            </div>

            <div class="mb-3">
                <label for="newPassword" class="form-label">New Password</label>
                <input type="password" class="form-control" name="newPassword" id="newPassword" placeholder="Enter your New Password" />
            </div>

            <div class="mb-3">
                <label for="confirmPassword" class="form-label">Confirm New Password</label>
                <input type="password" class="form-control" name="confirmPassword" id="confirmPassword" placeholder="Confirm your New Password" />
            </div>
        
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>

   
    </div>
</div>

<script>
    // Function to preview the profile picture
    function previewProfilePicture() {
        var file = document.getElementById('profilePicture').files[0];
        var reader = new FileReader();
        
        reader.onload = function(e) {
            var preview = document.getElementById('profilePreview');
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        
        if (file) {
            reader.readAsDataURL(file);
        }
    }


   // Validation for new password fields
document.getElementById("profileForm").addEventListener("submit", function(event) {
    var newPassword = document.getElementById("newPassword").value;
    var confirmPassword = document.getElementById("confirmPassword").value;

    if (newPassword !== confirmPassword) {
        event.preventDefault(); // Prevent form submission

        // Show the error message in the form itself
        var errorDiv = document.createElement('div');
        errorDiv.className = 'alert alert-danger'; // Bootstrap class for a red alert
        errorDiv.textContent = 'Passwords do not match!';

        // Insert the error message at the top of the form
        var form = document.getElementById("profileForm");
        form.parentNode.insertBefore(errorDiv, form);

        // Optionally scroll to the top of the form to ensure visibility
        window.scrollTo({ top: form.offsetTop, behavior: 'smooth' });
    }
});
</script>
<script></script>
</body>
</html>