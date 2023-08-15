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

    // Fetch commitment data based on the provided ID
    $user_id = $_SESSION["id"];
    $sql = "SELECT commitment_name FROM commitment WHERE user_id = '$user_id' AND commitment_id = '$commitment_id'";
    $result = $conn->query($sql);

    if ($result->num_rows === 1) {
        $commitment_name = $result->fetch_assoc()["commitment_name"];
    } else {
        // No commitment found with the provided ID
        header("Location: commitment.php");
        exit;
    }
}

// Handle commitment deletion
if (isset($_POST["confirm_delete"])) {
    $sql = "DELETE FROM commitment WHERE user_id = '$user_id' AND commitment_id = '$commitment_id'";

    if ($conn->query($sql) === TRUE) {
        header("Location: commitment.php");
        exit;
    } else {
        echo "Error deleting commitment: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>FundFlow | Delete Commitment</title>
    <!-- Include Bootstrap CSS link -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

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
                        <h4 class="text-center">Delete commitment</h4>
                    </div>
                    <div class="card-body text-center">
                        <p>Are you sure you want to delete the commitment: <strong>
                            <?php echo $commitment_name; ?></strong>?</p>
                        <form method="post">
                            <div class="text-center">
                                <button type="submit" name="confirm_delete" class="btn btn-danger">
                                    Confirm delete
                                </button>
                                <a href="commitment.php" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
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
