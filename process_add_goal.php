<?php
session_start();

// Check if user is logged in, redirect to login page if not
if (!isset($_SESSION["id"])) {
    header("Location: index.php");
    exit;
}

include 'db_connection.php'; // Include your database connection

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $goal_name = $_POST["goal_name"];
    $goal_amount = $_POST["goal_amount"];
    $user_id = $_SESSION["id"];

    // Insert the new goal into the database
    $sql = "INSERT INTO goal (user_id, goal_name, goal_amount) VALUES ('$user_id', '$goal_name', '$goal_amount')";

    if ($conn->query($sql) === TRUE) {
        header("Location: goal.php");
        exit;
    } else {
        echo "Error adding goal: " . $conn->error;
    }
}

$conn->close();
?>
