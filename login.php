<?php
include 'db_connection.php';
session_start();

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];
    $passwords = $_POST["password"];
    
    // Establish database connection (replace with your connection details)
    $conn = new mysqli("localhost", "root", "", "commit_minder");
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Fetch user data from the database
    $sql = "SELECT user_id, user_email, user_password FROM user WHERE user_email = '$email'";
    $result = $conn->query($sql);
    
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($passwords, $row["user_password"])) {
            $_SESSION["id"] = $row["user_id"];
            $_SESSION["email"] = $row["user_email"];
            header("Location: dashboard.php");
            exit;
        } else {
            header("Location: index.php?error=Invalid password");
        }
    } else {
        header("Location: index.php?error=User not found");
    }
    
    $conn->close();
}
?>
