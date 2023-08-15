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
$sql = "SELECT commitment_name, commitment_amount FROM commitment WHERE user_id = '$user_id'";
$result = $conn->query($sql);

// Fetch user's name from the database
$user_id = $_SESSION["id"];
$sql_user = "SELECT user_name FROM user WHERE user_id = '$user_id'";
$result_user = $conn->query($sql_user);

if ($result_user->num_rows > 0) {
    $user_row = $result_user->fetch_assoc();
    $user_name = $user_row["user_name"];
}

$totalCommitments = $result->num_rows;

// Calculate total commitment amount
$totalAmount = 0;
while ($row = $result->fetch_assoc()) {
    $totalAmount += $row["commitment_amount"];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>FundFlow | Dashboard</title>
    <!-- Include Bootstrap CSS link -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Include Font Awesome CSS link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;700&display=swap" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        .progress-container {
            width: 150px;
            height: 150px;
            position: relative;
        }

        .progress {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            position: absolute;
            clip: rect(0, 50%, 100%, 0);
            background-color: #f3f3f3;
        }

        .progress.active {
            animation: fillProgress 2s forwards;
        }

        .progress-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 18px;
            font-weight: bold;
        }

        @keyframes fillProgress {
            to {
                transform: rotate(180deg);
            }
        }
    </style>

    <style>
        .badge-container {
            display: inline-block;
            vertical-align: middle;
        }

        .badge {
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #F6F7F9;
            color: #000;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-family: 'Open Sans', sans-serif;
            font-size: 15px; /* Adjust the font size as needed */
        }

        .text-center {
            text-align: center;
        }
    </style>

</head>
<body style="background-color: #343A40;"> 

    <nav class="navbar navbar-expand-md navbar-dark " style="background-color: #087EA4;">
        <a class="navbar-brand" href="#">FundFlow</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" 
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="commitment.php">
                        <i class="fas fa-check-square"></i> Commitment
                    </a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="goal.php">
                        <i class="fas fa-trophy"></i> Goal
                    </a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="manageprofile.php"> 
                        <i class="fas fa-user"></i> Account
                    </a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="#" data-toggle="modal" data-target="#logoutModal">
                        <i class="fas fa-sign-out-alt"></i> Log out
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
            <h1 class="text-center mb-4" style="color: #F6F7F9; font-weight: bold; font-family: 'Open Sans', 
                sans-serif;"><?php echo $user_name; ?>'s Dashboard</h1>
            <h3 class="text-center mt-5 mb-4" style="color: #F6F7F9; font-family: 'Open Sans', sans-serif; 
                font-weight: bold;">
                Commitment
                <span class="badge-container">
                     <span class="badge"><?php echo $totalCommitments; ?></span>
                </span>
            </h3>                
            <div class="table-responsive">
                    <table class="table text-center table-bordered table-dark" >
                    <thead class="table-secondary" style="background-color: #087EA4;">
                        <tr>
                            <th style="color:white" >Name</th>
                            <th style="color:white" >Amount</th>
                        </tr>
                    </thead>
                    <tbody style="background-color: #F6F7F9; color:black;">
                        <?php
                        // Display commitments and amounts in the table
                        $result->data_seek(0); // Reset the result pointer
                        while ($row = $result->fetch_assoc()) {
                            echo '<tr>
                                <td>' . $row["commitment_name"] . '</td>
                                <td>' . $row["commitment_amount"] . '</td>
                            </tr>';
                        }
                        ?>
                        </tbody>
                        <tfoot>
                            <tr class="table-secondary" style="color:black;"> 
                                <th>Total (RM):</th>
                                <th><?php echo $totalAmount; ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <?php
            // Fetch all user goals from the database
            $sql_goals = "SELECT goal_name, goal_amount, goal_saved FROM goal WHERE user_id = '$user_id'";
            $result_goals = $conn->query($sql_goals);
            $totalGoals = $result_goals->num_rows;

            $firstGoal = true; // Flag for the first goal card
            
            while ($goal_row = $result_goals->fetch_assoc()) {
                $goalName = $goal_row["goal_name"];
                $goalAmount = $goal_row["goal_amount"];
                $savedAmount = $goal_row["goal_saved"];
                $progressPercentage = ($savedAmount / $goalAmount) * 100;
                ?>

                <!-- Goal Section Header for First Goal Card -->
                <?php if ($firstGoal) { ?>
                    <h3 class="text-center mt-4" style="color: #F6F7F9; font-family: 'Open Sans', sans-serif; 
                    font-weight: bold;">
                        Goal
                        <span class="badge-container">
                            <span class="badge"><?php echo $totalGoals; ?></span>
                        </span>
                    </h3>                
                    <?php $firstGoal = false; } ?>
                    <div class="card mt-4">
                    <div class="card-body">
                        <div class="row">
                            <!-- Progress bar on the left side -->
                            <div class="col-md-6 col-12 d-flex justify-content-center align-items-center">
                                <div class="progress-container">
                                    <div class="progress" style="transform: rotate(<?php echo $progressPercentage * 1.8; 
                                    ?>deg); background-color: #087EA4;"></div>
                                    <div class="progress-text" id="progressText" style="color: #F6F7F9"><?php 
                                    echo number_format($progressPercentage, 2); ?>%</div>
                                </div>
                            </div>
                            <!-- Information on the right side -->
                        <div class="col-md-6 col-12 d-flex justify-content-center align-items-center">
                            <div class="text-center" style="margin-top: 10px;"> 
                                <h5 class="card-title" style="font-weight: bold;"><?php echo $goalName; ?></h5>
                                <p class="card-text">Goal Amount: RM <?php echo number_format($goalAmount, 2); ?></p>
                                <p class="card-text">Saved Amount: RM <?php echo number_format($savedAmount, 2); ?></p>
                                <p class="card-text">Remaining Amount: RM <?php echo number_format
                                (max(0, $goalAmount - $savedAmount), 2); ?></p>
                                <p class="card-text">Status: <?php echo ($savedAmount >= $goalAmount) ? "Accomplished" : 
                                "In Progress"; ?></p>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
                <?php
                }
                ?>
            </div>

            <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel" 
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="mx-auto text-center" id="logoutModalLabel">Confirm log out</h4>
                        </div>
                    <div class="modal-body text-center">Are you sure you want to log out?
                <div class="mt-3">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <a class="btn btn-primary" href="logout.php" style="background-color: #087EA4">Log out</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function confirmLogout() {
    var confirmLogout = confirm("Are you sure you want to log out?");
    if (confirmLogout) {
        // If the user confirms, proceed with the logout action
        window.location.href = "logout.php";
    }
}
</script>

<!-- Include Bootstrap JS scripts -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>