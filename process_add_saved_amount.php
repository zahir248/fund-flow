<?php
session_start();

include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $goal_id = $_POST["goal_id"];
    $goal_saved = $_POST["goal_saved"];

    // Update the saved amount in the database using prepared statement
    $sql_update_saved = "UPDATE goal SET goal_saved = goal_saved + ? WHERE goal_id = ?";
    $stmt = $conn->prepare($sql_update_saved);
    $stmt->bind_param("di", $goal_saved, $goal_id); // 'd' for double, 'i' for integer

    if ($stmt->execute()) {
        $stmt->close();
        header("Location: goal.php"); // Redirect back to the goal
        exit;
    } else {
        echo "Error updating saved amount: " . $stmt->error;
    }
}
?>
