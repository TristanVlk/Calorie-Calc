<?php
session_start();
include('../includes/conn.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header('Location: ../login.php');
    exit();
}

// Get the logged-in user's ID
$user_id = $_SESSION['user_id'];

// Fetch user info from the session
$query = "SELECT username, profile_picture FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Fetch calorie data for the logged-in user for table display
$stmt = $conn->prepare("SELECT * FROM tbl_calorie WHERE user_id = ? ORDER BY calorie_date");
$stmt->execute([$user_id]);
$result = $stmt->fetchAll();


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tracker</title>
    <link rel="icon" type="image/x-icon" href="../images/icon1.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="../css/trackerstyle.css">
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
            <a class="navbar-brand display-1 fw-normal" href="#">Calorie<span class="rounded-pill" style="color: #f1683a; background-color: #2c3336;">Calc</a></span>
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
                            <a class="nav-link" href="profile.php">Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="tracker.php">Tracker</a>
                        </li>
                       
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Calculator
                            </a>
                            <ul class="dropdown-menu dropdown-menu-dark">
                                <li><a class="dropdown-item" href="calculator.php">Calculator</a></li>
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

    <div class="main">
        <div class="calories-container">
            <div class="header">
                <h3>Daily Calories Monitoring Tool</h3>
                <button type="button" class="btn btn-sm btn-primary add-calorie-btn" data-toggle="modal" data-target="#addcalorieModal">+ Add Calorie Intake</button>

                <!-- Modal -->
                <div class="modal fade" id="addcalorieModal" tabindex="2" aria-labelledby="addcalorie" aria-hidden="true" >
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addcalorie">Add Calorie Intake</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="../includes/add-calorie.php" method="POST">
                                    <div class="form-group">
                                        <label for="calorieDate">Calorie Date:</label>
                                        <input type="date" class="form-control" id="calorieDate" name="calorie_date" required>
                                    </div>
                                    <br>
                                    <div class="form-group">
                                        <label for="calorieAmount">Calorie Amount (in grams):</label>
                                        <input type="number" class="form-control" id="calorieAmount" name="calorie_amount" required>
                                    </div>
                                    <br>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Add</button>
                                    </div>
                                </form>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>

           


<!-- delete-Modal -->
<div class="modal modal-mg fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header border-none">
        <h1 class="modal-title-delete text-white text-center fs-2 fw-light " id="exampleModalLabel">Are you sure you want to delete this?</h1>
     
      </div>
      <div class="modal-body">
      
                                    <div class="modal-footer-delete text-center">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                                      
                                        <button type="button" class="btn btn-primary" onclick="removecalorie()">Yes</button>
                                      
                                    </div>
                              
      </div>
    </div>
  </div>
</div>

<!-- Edit Calorie Modal -->
<div class="modal fade" id="editCalorieModal" tabindex="-1" aria-labelledby="editCalorieLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-white" id="editCalorieLabel">Edit Calorie Intake</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editCalorieForm" method="POST">
                    <div class="form-group">
                        <label for="editCalorieDate">Calorie Date:</label>
                        <input type="date" class="form-control" id="editCalorieDate" name="calorie_date" required>
                    </div>
                    <br>
                    <div class="form-group">
                        <label for="editCalorieAmount">Calorie Amount (in grams):</label>
                        <input type="number" class="form-control" id="editCalorieAmount" name="calorie_amount" required>
                    </div>
                    <input type="hidden" id="editCalorieId" name="calorie_id">
                    <br>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- table -->
            <div class="table-graph-container">
                <div class="table-container">
                    <table class="table text-center table-sm">
                        <thead>
                            <tr>
                                <th scope="col">Date</th>
                                <th scope="col">Calories (g)</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody id="calorieTableBody">
                            <?php
                                // display data in the table
                                foreach ($result as $row) {
                                    $calorieId = $row['tbl_calorie_id'];
                                    $calorieDate = $row['calorie_date'];
                                    $calorieAmount = $row['calorie_amount'];

                                    echo '<tr class="calorieList">';
                                    echo '<th hidden>' . $calorieId . '</th>';
                                    echo '<td>' . $calorieDate . '</td>';
                                    echo '<td>' . $calorieAmount . '</td>';
                                    
                                    echo '<td style="background-color: transparent;">';
                                    echo '<button type="button" class="btn btn-sm btn-light edit" data-bs-toggle="modal" data-bs-target="#editCalorieModal" data-calorie-id="' . $calorieId . '" data-calorie-date="' . $calorieDate . '" data-calorie-amount="' . $calorieAmount . '">Edit</button>';

                                    echo '<button type="button" data-bs-toggle="modal" data-bs-target="#deleteModal" data-bs-calorie-id="' . $calorieId . '" class="btn btn-sm btn-danger">Delete</button>';

                                    echo '</td>';
                                    echo '</tr>';
                                }
                            ?>
                        </tbody>
                    </table>
                </div>

                <div class="graph-container">
                    <canvas id="myChart"></canvas>
                </div>
            </div>
        </div>
    </div>   

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>

    <?php
    // fetching calorie data and adding it
    $stmt = $conn->prepare("SELECT calorie_date, SUM(calorie_amount) AS total_calories FROM tbl_calorie WHERE user_id = ? GROUP BY calorie_date");
    $stmt->execute([$user_id]);
    $calorieData = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // data for chart
    $dates = [];
    $calories = [];
    foreach ($calorieData as $data) {
        $dates[] = $data['calorie_date'];
        $calories[] = $data['total_calories'];
    }
    ?>

    <script>
        //chart
//declare variable to get the dates and calories
        let dates = <?php echo json_encode($dates); ?>;
let calories = <?php echo json_encode($calories); ?>;

// js method to create array 
let dateCaloriesArray = dates.map((date, index) => ({ date: new Date(date), calories: calories[index] }));

// sorting array
dateCaloriesArray.sort((a, b) => a.date - b.date);


dates = dateCaloriesArray.map(item => item.date.toISOString().split('T')[0]); 
calories = dateCaloriesArray.map(item => item.calories);

    const ctx = document.getElementById('myChart').getContext('2d');
const myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: dates,
        datasets: [{
            label: 'Calories',
            data: calories,
            borderColor: '#f1683a',
            backgroundColor: '#eee',
            borderWidth: 2,
           
            fill: false
        }]
    },
    options: {
        maintainAspectRatio: false, 
        scales: {
            x: {
             
                grid: {
                    color: '#eee' 
                }
            },
            y: {
                beginAtZero: true,
                ticks: {
                    color: '#eee'
                },
                grid: {
                    color: '#eee' 
                }
            }
        },
        plugins: {
            legend: {
                labels: {
                    color: '#eee' 
                }
            }
        }
    }
});

       

         // init
    let calorieIdToDelete = null;

// event listener
document.querySelectorAll('[data-bs-calorie-id]').forEach(button => {
    button.addEventListener('click', function () {
        calorieIdToDelete = this.getAttribute('data-bs-calorie-id');
    });
});

// call function when clicked "yes"
function removecalorie() {
    if (calorieIdToDelete) {
        window.location.href = `../includes/delete-calorie.php?calorie=${calorieIdToDelete}`;
    }
}
      
        
    </script>
    
    <script>
        //edit event
document.querySelectorAll('[data-bs-target="#editCalorieModal"]').forEach(button => {
    button.addEventListener('click', function () {
        const calorieId = this.getAttribute('data-calorie-id');
        const calorieDate = this.getAttribute('data-calorie-date');
        const calorieAmount = this.getAttribute('data-calorie-amount');

        document.getElementById('editCalorieId').value = calorieId;
        document.getElementById('editCalorieDate').value = calorieDate;
        document.getElementById('editCalorieAmount').value = calorieAmount;
    });
});

// form submission to edit calorie data
document.getElementById('editCalorieForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const formData = new FormData(this);
    
    fetch('../includes/edit-calorie.php', {
        method: 'POST',
        body: formData
    }).then(response => response.json())
      .then(data => {
          if (data.success) {
              location.reload(); 
          } else {
              alert("Failed to update calorie data.");
          }
      }).catch(error => console.error('Error:', error));
});
</script>

</body>
</html>
