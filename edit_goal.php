<?php
session_start();

// Check if user is logged in, redirect to login page if not
if (!isset($_SESSION["id"])) {
    header("Location: index.php");
    exit;
}

include 'db_connection.php'; // Include your database connection

if (isset($_GET["id"])) {
    $goal_id = $_GET["id"];

    // Fetch the goal data based on the provided ID
    $user_id = $_SESSION["id"];
    $sql = "SELECT * FROM goal WHERE user_id = '$user_id' AND goal_id = '$goal_id'";
    $result = $conn->query($sql);

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
    } else {
        // No goal found with the provided ID
        header("Location: goal.php");
        exit;
    }
}

// Update goal data
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Update the goal in the database
    $new_goal_name = $_POST["goal_name"];
    $new_goal_amount = $_POST["goal_amount"];
    $sql = "UPDATE goal SET goal_name = '$new_goal_name', goal_amount = '$new_goal_amount' WHERE goal_id = '$goal_id'";

    if ($conn->query($sql) === TRUE) {
        header("Location: goal.php");
        exit;
    } else {
        echo "Error updating goal: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FundFlow | Edit Goal</title>
    <!-- Include Bootstrap CSS link -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

</head>
<body style="background-color: #343A40;"> 

<nav class="navbar navbar-expand-md navbar-dark " style="background-color: #087EA4;">
    <a class="navbar-brand" href="#">FundFlow</a>
</nav>

<body>
    <div class="container mt-3">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-center">Edit goal</h4>
                    </div>
                    <div class="card-body">
                        <form method="post">
                            <div class="form-group">
                                <label for="goal_name">Name</label>
                                <input type="text" class="form-control" name="goal_name" value="<?php 
                                echo $row["goal_name"]; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="goal_amount">Goal amount</label>
                                <input type="text" class="form-control" name="goal_amount" value="<?php 
                                echo $row["goal_amount"]; ?>" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block" style="background-color: #087EA4">
                                Update
                            </button>
                        </form>
                    </div>
                    <div class="card-footer">
                        <p class="mt-2 text-center">Cancel edit? click here  <a href="goal.php" 
                            style="color: #087EA4 ; text-decoration: underline"> Cancel</a>
                        </p>                    
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Include Bootstrap JS scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
</body>
</html>
