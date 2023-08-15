<?php
include 'db_connection.php'; // Include your database connection

if (isset($_GET['goal_id'])) {
    $goal_id = $_GET['goal_id'];
    
    // Update the saved amount to 0 for the specified goal
    $sql_reset = "UPDATE goal SET goal_saved = 0 WHERE goal_id = '$goal_id'";
    if ($conn->query($sql_reset) === TRUE) {
        // Redirect back to the page where the modal was opened
        header("Location: goal.php"); 
        exit;
    } else {
        echo "Error resetting saved amount: " . $conn->error;
    }
}
?>
