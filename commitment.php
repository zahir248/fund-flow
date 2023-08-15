<?php
session_start();

// Check if user is logged in, redirect to login page if not
if (!isset($_SESSION["id"])) {
    header("Location: index.php");
    exit;
}

include 'db_connection.php'; // Include your database connection

// Fetch user commitments from the database
$user_id = $_SESSION["id"];
$sql = "SELECT commitment_id, commitment_name, commitment_amount FROM commitment WHERE user_id = '$user_id'";
$result = $conn->query($sql);

// Fetch user's name from the database
$user_id = $_SESSION["id"];
$sql_user = "SELECT user_name FROM user WHERE user_id = '$user_id'";
$result_user = $conn->query($sql_user);

if ($result_user->num_rows > 0) {
    $user_row = $result_user->fetch_assoc();
    $user_name = $user_row["user_name"];
}

// Calculate total commitment amount
$totalAmount = 0;
while ($row = $result->fetch_assoc()) {
    $totalAmount += $row["commitment_amount"];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>FundFlow | Manage Commitment</title>
    <!-- Include Bootstrap CSS link -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Include Font Awesome CSS link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
</head>
<body style="background-color: #343A40;"> 

    <nav class="navbar navbar-expand-md navbar-dark " style="background-color: #087EA4;">
        <a class="navbar-brand" href="#">FundFlow</a>
    </nav>

    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h1 class="text-center mb-5" style="color: #F6F7F9; font-weight: bold; font-family: 'Open Sans', 
                    sans-serif;"><?php echo $user_name; ?>'s Commitment Management
                </h1>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <a href="dashboard.php" class="btn btn-dark" style="background-color: #087EA4">
                        <i class="fas fa-arrow-left"></i> Dashboard
                    </a>
                    <button class="btn btn-success float-right" data-toggle="modal" data-target="#addCommitmentModal">
                            <i class="fas fa-plus"></i> Add commitment
                    </button>
                </div>
                <div class="table-responsive">
                    <table class="table text-center table-bordered table-dark">
                        <thead class="table-secondary" style="background-color: #087EA4;">
                            <tr>
                                <th style="color:white">Name</th>
                                <th style="color:white">Amount</th>
                                <th style="color:white">Action</th> 
                            </tr>
                        </thead>
                        <tbody style="background-color: #F6F7F9; color:black;"> 
                            <?php
                            // Display commitments, amounts, and action buttons in the table
                            $result->data_seek(0); // Reset the result pointer
                            while ($row = $result->fetch_assoc()) {
                                echo '<tr>
                                        <td>' . $row["commitment_name"] . '</td>
                                        <td>' . $row["commitment_amount"] . '</td>
                                        <td>
                                        <div class="d-flex justify-content-center">
                                            <a href="edit_commitment.php?id=' . $row["commitment_id"] . 
                                            '" class="btn btn-info btn-sm" style="margin-right: 5px;">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <a href="delete_commitment.php?id=' . $row["commitment_id"] . 
                                            '" class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash"></i> Delete
                                            </a>
                                        </div>
                                        </td>
                                </tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Commitment Modal -->
    <div class="modal fade" id="addCommitmentModal" tabindex="-1" aria-labelledby="addCommitmentModalLabel" 
        aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCommitmentModalLabel">Add new commitment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="process_add_commitment.php">
                    <div class="form-group">
                        <label for="commitment_name">Name</label>
                        <input type="text" class="form-control" id="commitment_name" name="commitment_name" required>
                    </div>
                    <div class="form-group">
                        <label for="commitment_amount">Amount</label>
                        <input type="text" class="form-control" id="commitment_amount" name="commitment_amount" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block" style="background-color: #087EA4">Add</button>
                </form>
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
