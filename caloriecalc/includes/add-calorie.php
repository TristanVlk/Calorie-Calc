<?php
session_start();
include('conn.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if the user is logged in and session contains user_id
    if (isset($_SESSION['user_id'])) {
        $calorie_date = $_POST['calorie_date'];
        $calorie_amount = $_POST['calorie_amount'];
        $user_id = $_SESSION['user_id']; // Get the logged-in user ID from the session

        try {
            // Insert into the database, including the user_id column
            $stmt = $conn->prepare("INSERT INTO tbl_calorie (calorie_date, calorie_amount, user_id) VALUES (?, ?, ?)");
            $stmt->execute([$calorie_date, $calorie_amount, $user_id]);

            // Redirect to the tracking page after successful insertion
            header('Location: ../pages/tracker.php');
            exit();
        } catch (PDOException $e) {
            // Display error message in case of a query failure
            echo "Error: " . $e->getMessage();
        }
    } else {
        // If user_id is not found in the session, redirect to login page
        echo "
            <script>
                alert('Please log in to add your calorie intake.');
                window.location.href = '..pages/login.php';
            </script>
        ";
    }
}
?>
