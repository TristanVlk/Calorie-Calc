<?php
session_start();
include '../includes/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT calculation, created_at FROM calculation_history WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Fetch user info
$user_stmt = $conn->prepare("SELECT username, profile_picture FROM users WHERE id = ?");
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user_result = $user_stmt->get_result();
$user = $user_result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calculation History</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../css/history.css">
    <link rel="icon" type="image/x-icon" href="../images/icon1.png">
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
                            <a class="nav-link dropdown-toggle active" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Calculator
                            </a>
                            <ul class="dropdown-menu dropdown-menu-dark">
                                <li><a class="dropdown-item" href="calculator.php">Calculator</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item active" href="history.php">Calculation History</a></li>
                            </ul>
                        </li>
                    </ul>
                    <br>
                    <div class="hi mt-auto">
                    <a class="nav-link text-danger " href="logout.php">Logout</a>
                    
                </div>
            </div>
        </div>
    </nav>
    
    <div class="container mt-5">
        <div class="history-container">
            <h2>Your Calculation History</h2>
            <table class="table table-striped history-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Calculation</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                   while ($row = $result->fetch_assoc()) {
                    $calculation = json_decode($row['calculation'], true);
                    echo "<tr class='history-item'>";
                    echo "<td>" . date("F j, Y, g:i a", strtotime($row['created_at'])) . "</td>"; 
                    echo "<td>";
                    
                    if (!empty($calculation)) {
                        foreach ($calculation as $goal => $calories) {
                           
                            $formattedGoal = ucwords(preg_replace('/([a-z])([A-Z])/', '$1 $2', $goal));
                            echo "<strong>{$formattedGoal}</strong>: {$calories} Calories/day<br>";
                        }
                    } else {
                        echo "Calculation data not available.";
                    }
                    echo "</td>";
                    echo "</tr>";
                }
                
                    ?>
                </tbody>
            </table>
        </div>
    </div>
   
</body>
</html>

<?php
$stmt->close();
$user_stmt->close();
?>
