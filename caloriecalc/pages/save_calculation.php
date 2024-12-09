<?php
session_start();
include '../includes/db_connect.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(["error" => "Unauthorized"]);
    exit;
}

$user_id = $_SESSION['user_id'];
$data = json_decode(file_get_contents("php://input"), true);

// Validate and save the calculation data
if ($data) {
    $calculation = json_encode($data); // Encode calculation as JSON
    $created_at = date('Y-m-d H:i:s');

    $stmt = $conn->prepare("INSERT INTO calculation_history (user_id, calculation, created_at) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $user_id, $calculation, $created_at);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Calculation saved."]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Failed to save calculation."]);
    }

    $stmt->close();
} else {
    http_response_code(400);
    echo json_encode(["error" => "Invalid input data."]);
}
$conn->close();
?>
