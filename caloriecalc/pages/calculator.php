<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../css/calculator.css">
    <link rel="icon" type="image/x-icon" href="../images/icon1.png">
    <title>Calorie Calculator</title>
    <style>
        body {
            padding-top: 60px; 
        }
    </style>
</head>
<body>
    <!-- PHP code to fetch user info -->
    <?php
    session_start();
    include '../includes/db_connect.php';

    // Fetch user info from the session
    $user_id = $_SESSION['user_id'];
    $query = "SELECT username, profile_picture FROM users WHERE id = '$user_id'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);
    ?>
    
    <!-- Navbar -->
    <nav class="navbar navbar-dark bg-transparent">
        <div class="container">
            <!-- logo -->
            <a class="navbar-brand display-1 fw-normal" href="#">Calorie<span class="rounded-pill" style="color: #f1683a; background-color: #2c3336;">Calc</span></a>
            <!-- toggle -->
            <button class="navbar-toggler shadow-none " type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasDarkNavbar" aria-controls="offcanvasDarkNavbar" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="sidebar offcanvas offcanvas-start " tabindex="-1" id="offcanvasDarkNavbar" aria-labelledby="offcanvasDarkNavbarLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title text-white fs-2" id="offcanvasDarkNavbarLabel">Calorie<span class="rounded-pill" style="color: #f1683a; background-color: #2c3336;">Calc</span></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <!-- Display Profile Picture -->
                    <div class="text-center mb-3">
                        <?php if ($user['profile_picture']): ?>
                            <img src="<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Profile Picture" class="rounded-circle" width="100" height="100">
                        <?php else: ?>
                            <img src="../images/icon1.png" alt="Default Profile Picture" class="rounded-circle" width="100" height="100">
                        <?php endif; ?>
                    </div>

                    <!-- Display Username -->
                    <h5 class="text-center text-white mb-3"><?php echo htmlspecialchars($user['username']); ?></h5>
                   
                    <!-- Menu Items -->
                    <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                        <li class="nav-item">
                            <a class="nav-link" href="profile.php">Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="tracker.php">Tracker</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link active dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Calculator
                            </a>
                            <ul class="dropdown-menu dropdown-menu-dark">
                                <li><a class="dropdown-item active" href="calculator.php">Calculator</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
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
    
    <!-- HERO -->
    <section id="hero" class="min-vh-100 d-flex align-center text-center justify-content-center flex-column">
        <div class="container">
            <div class="row">
                <div class="col-6 border-light gap">
                    <h1 class="text-uppercase display-1">Calculator</h1>
                    <p style="font-size: 22px; ">
                        Calculate your daily calorie needs to achieve your fitness goals. Our calorie calculator uses the Mifflin-St Jeor Equation to estimate your basal metabolic rate (BMR), which is the number of calories your body needs to maintain basic physiological functions at rest. We then adjust this estimate based on your activity level to determine your total daily calorie needs.
                    </p>
                </div>
                <div class="col-12 col-md-6 text-center justify-content-center flex-column border-light">
                    <h1 class="text-uppercase display-1">Steps</h1>
                    <ol type="I" style="font-size: 20px;">
                        <li>Enter Your Details: Input your age, gender, weight, height, and activity level.</li>
                        <li>Calculate BMR: The equation calculates your BMR based on your body weight, height, age, and gender.</li>
                        <li>Adjust for Activity: Multiply your BMR by an activity factor to account for your physical activity level.</li>
                        <li>Set Goals: Based on your total daily calories, we provide options for maintaining weight, mild weight loss, weight loss, and extreme weight loss.</li>
                    </ol>
                </div>
            </div>
            <button id="scrollButton" class="btn btn-secondary mt-4">▼</button>
        </div>
    </section>

    <!-- Calculator Section -->
    <section id="calculator">
        <div class="container">
            <h2 class="text-center my-4 text-white ">Calorie Calculator</h2>
            <br><br><br>
            <div class="calorie-calculator">
                <div class="form-container">
                    <form id="calorie-form">
                        <div class="form-group">
                            <label for="age">Age:</label>
                            <input type="number" id="age" name="age" min="1" max="100" required class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="gender">Gender:</label>
                            <select id="gender" name="gender" required class="form-select">
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="weight">Weight (kg):</label>
                            <input type="number" id="weight" name="weight" min="1" max="800" required class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="height">Height (cm):</label>
                            <input type="number" id="height" name="height" min="1" max="300" required class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="activity">Activity Level:</label>
                            <select id="activity" name="activity" required class="form-select">
                                <option value="1.2">Sedentary: little or no exercise</option>
                                <option value="1.375">Light Activity: 1-3 exercise</option>
                                <option value="1.55">Moderate Activity: 4-5 exercise</option>
                                <option value="1.725">Active: daily exercise or intense exercise 3-4 times/week</option>
                                <option value="1.9">Very Active: intense exercise 6-7 times/week</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn border-light">Calculate</button>
                    </form>
                </div>

                <!-- Results Table -->
                <div class="result-container">
                    <div id="result" class="result" style="min-height: 200px;"></div> <!-- Set a min height -->
                </div>
            </div>
        </div>
    </section>
    
    <script src="../js/formula.js"></script>
    <script>
        const scrollButton = document.getElementById('scrollButton');
        const targetSection = document.getElementById('calculator');

        scrollButton.addEventListener('click', () => {
            targetSection.scrollIntoView({ behavior: 'smooth' });
        });
    </script>
</body>
</html>
