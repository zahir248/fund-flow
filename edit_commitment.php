<?php
session_start();

// Check if user is logged in, redirect to login page if not
if (!isset($_SESSION["id"])) {
    header("Location: index.php");
    exit;
}

include 'db_connection.php'; // Include your database connection

if (isset($_GET["id"])) {
    $commitment_id = $_GET["id"];

    // Fetch the commitment data based on the provided ID
    $user_id = $_SESSION["id"];
    $sql = "SELECT * FROM commitment WHERE user_id = '$user_id' AND commitment_id = '$commitment_id'";
    $result = $conn->query($sql);

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
    } else {
        // No commitment found with the provided ID
        header("Location: commitment.php");
        exit;
    }
}

// Update commitment data
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Update the commitment in the database
    $new_commitment_name = $_POST["commitment_name"];
    $new_commitment_amount = $_POST["commitment_amount"];
    $sql = "UPDATE commitment SET commitment_name = '$new_commitment_name', commitment_amount = '$new_commitment_amount' 
        WHERE commitment_id = '$commitment_id'";

    if ($conn->query($sql) === TRUE) {
        header("Location: commitment.php");
        exit;
    } else {
        echo "Error updating commitment: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>FundFlow | Edit Commitment</title>
    <!-- Include Bootstrap CSS link -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

</head>
<body style="background-color: #343A40;"> 

<nav class="navbar navbar-expand-md navbar-dark " style="background-color: #087EA4;">
        <a class="navbar-brand" href="#">FundFlow</a>
</nav>

<body>
    <div class="container mt-3">
        <div class="row justify-content-center">
        <div class="col-md-6 col-sm-10"> <!-- Adjust col-* classes for responsiveness -->
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-center">Edit commitment</h4>
                    </div>
                    <div class="card-body">
                    <form method="post">
                        <div class="form-row"> <!-- Use form-row and col-* for responsive form layout -->
                            <div class="form-group col-md-12">
                                <label for="commitment_name">Name</label>
                                <input type="text" class="form-control" name="commitment_name" value="<?php 
                                echo $row["commitment_name"]; ?>" required>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="commitment_amount">Amount</label>
                                <input type="text" class="form-control" name="commitment_amount" value="<?php 
                                echo $row["commitment_amount"]; ?>" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block" style="background-color: #087EA4">
                        Update</button>
                    </form>
                    </div>
                    <div class="card-footer">
                        <p class="mt-2 text-center">Cancel edit? click here  <a href="commitment.php" 
                        style="color: #087EA4 ; text-decoration: underline"> Cancel</a></p>                    
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
