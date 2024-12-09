<?php
session_start();
include('../includes/conn.php');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $calorieId = $_POST['calorie_id'];
    $newDate = $_POST['calorie_date'];
    $newAmount = $_POST['calorie_amount'];

    $stmt = $conn->prepare("UPDATE tbl_calorie SET calorie_date = ?, calorie_amount = ? WHERE tbl_calorie_id = ?");
    $query_execute = $stmt->execute([$newDate, $newAmount, $calorieId]);

    echo json_encode(['success' => $query_execute]);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>
