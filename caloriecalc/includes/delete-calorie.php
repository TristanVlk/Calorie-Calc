<?php
include ('../includes/conn.php');

if (isset($_GET['calorie'])) {
    $calorie = $_GET['calorie'];

    try {

        $query = "DELETE FROM tbl_calorie WHERE tbl_calorie_id = '$calorie'";

        $stmt = $conn->prepare($query);

        $query_execute = $stmt->execute();

        if ($query_execute) {
            header("Location: http://localhost/calorie_calculator/pages/tracker.php");
        } else {
            header("Location: http://localhost/calorie_calculator/pages/tracker.php");
        }

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

?>