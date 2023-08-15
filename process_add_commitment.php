<?php
session_start();

// Check if user is logged in, redirect to login page if not
if (!isset($_SESSION["id"])) {
    header("Location: index.php");
    exit;
}

include 'db_connection.php'; // Include your database connection

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $commitment_name = $_POST["commitment_name"];
    $commitment_amount = $_POST["commitment_amount"];
    $user_id = $_SESSION["id"];

    // Insert the new commitment into the database
    $sql = "INSERT INTO commitment (user_id, commitment_name, commitment_amount) 
            VALUES ('$user_id', '$commitment_name', '$commitment_amount')";

    if ($conn->query($sql) === TRUE) {
        header("Location: commitment.php");
        exit;
    } else {
        echo "Error adding commitment: " . $conn->error;
    }
}

$conn->close();
?>
