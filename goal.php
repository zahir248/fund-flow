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
$sql = "SELECT goal_id, goal_name, goal_amount, goal_saved FROM goal WHERE user_id = '$user_id'";
$result = $conn->query($sql);

// Fetch user's name from the database
$user_id = $_SESSION["id"];
$sql_user = "SELECT user_name FROM user WHERE user_id = '$user_id'";
$result_user = $conn->query($sql_user);

if ($result_user->num_rows > 0) {
    $user_row = $result_user->fetch_assoc();
    $user_name = $user_row["user_name"];
}

?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FundFlow | Manage Goal</title>
    <!-- Include Bootstrap CSS link -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Include Font Awesome CSS link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

</head>
<body style="background-color: #343A40;"> 

    <nav class="navbar navbar-expand-md navbar-dark " style="background-color: #087EA4;">
        <a class="navbar-brand" href="#">FundFlow</a>
    </nav>

    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
            <h1 class="text-center mb-5" style="color: #F6F7F9; font-weight: bold; font-family: 'Open Sans', 
                sans-serif;"><?php echo $user_name; ?>'s Goal Management</h1>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <a href="dashboard.php" class="btn btn-dark" style="background-color: #087EA4">
                   <i class="fas fa-arrow-left"></i> Dashboard
                </a>
                <button class="btn btn-success float-right" data-toggle="modal" data-target="#addGoalModal">
                    <i class="fas fa-plus"></i> Add goal
                </button>
            </div>
            <div class="table-responsive">
                <table class="table text-center table-bordered table-dark">
                    <thead class="table-secondary" style="background-color: #087EA4;">
                        <tr>
                            <th style="color:white">Name</th>
                            <th style="color:white">Goal amount</th>
                            <th style="color:white">Saved amount</th>
                            <th style="color:white">Action</th> 
                        </tr>
                    </thead>
                    <tbody style="background-color: #F6F7F9; color:black;"> 
                        <?php
                        // Display goals, amounts, and action buttons in the table
                        $result->data_seek(0); // Reset the result pointer
                        while ($row = $result->fetch_assoc()) {
                            echo '<tr>
                                <td>' . $row["goal_name"] . '</td>
                                <td>' . $row["goal_amount"] . '</td>
                                <td>' . $row["goal_saved"] . '</td>
                                <td>
                                    <div class="d-flex justify-content-center">
                                        <button class="btn btn-primary btn-sm add-saved-amount-btn" 
                                        style="margin-right: 5px;" data-toggle="modal" 
                                        data-target="#addSavedAmountModal' . $row["goal_id"] . '">
                                            <i class="fas fa-plus"></i> Deposit
                                        </button>
                                        <a href="edit_goal.php?id=' . $row["goal_id"] . '" class="btn btn-info btn-sm" 
                                        style="margin-right: 5px;">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <a href="delete_goal.php?id=' . $row["goal_id"] . '" 
                                        class="btn btn-danger btn-sm">
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

    <!-- Add Goal Modal -->
    <div class="modal fade" id="addGoalModal" tabindex="-1" aria-labelledby="addGoalModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addGoalModalLabel">Add new goal</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="process_add_goal.php">
                    <div class="form-group">
                        <label for="goal_name">Name</label>
                        <input type="text" class="form-control" id="goal_name" name="goal_name" required>
                    </div>
                    <div class="form-group">
                        <label for="goal_amount">Goal amount</label>
                        <input type="text" class="form-control" id="goal_amount" name="goal_amount" required>
                    </div>
                    <div class="alert alert-info" role="alert">
                        The saved amount will be automatically set to RM 0.
                    </div>
                    <button type="submit" class="btn btn-primary btn-block" style="background-color: #087EA4">
                        Add
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
$result->data_seek(0); // Reset the result pointer
while ($row = $result->fetch_assoc()) {
    echo '<div class="modal fade" id="addSavedAmountModal' . $row["goal_id"] . '" tabindex="-1" 
        aria-labelledby="addSavedAmountModalLabel' . $row["goal_id"] . '" aria-hidden="true">';
    echo '<div class="modal-dialog modal-dialog-centered">';
    echo '<div class="modal-content">';
    echo '<div class="modal-header">';
    echo '<h5 class="modal-title" id="addSavedAmountModalLabel' . $row["goal_id"] . '">Add deposit for ' . 
        $row["goal_name"] . '</h5>';
    echo '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
    echo '<span aria-hidden="true">&times;</span>';
    echo '</button>';
    echo '</div>';
    echo '<div class="modal-body">';
    echo '<form method="post" action="process_add_saved_amount.php">';
    echo '<input type="hidden" name="goal_id" value="' . $row["goal_id"] . '">';
    echo '<div class="form-group">';
    echo '<label for="goal_saved">Amount to add</label>';
    echo '<input type="text" class="form-control" id="goal_saved" name="goal_saved" required>';
    echo '</div>';
    echo '<button type="submit" class="btn btn-primary btn-block" style="background-color: #087EA4">Add</button>';
    echo '</form>';
    echo '</div>';
    echo '<div class="modal-footer text-center">';
    echo '<p class="mb-0 mx-auto"> Want to start over? The saved amount will be set to 0 back. 
        <a href="reset_saved_amount.php?goal_id=' . $row["goal_id"] . '" 
        style="text-decoration: underline">Reset saved amount</a></p>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
}
?>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    $(document).ready(function () {
        $(".add-saved-amount-btn").click(function () {
            var goalId = $(this).data("goal-id");
            $("#addSavedAmountModal").modal("show");
            $("#goal_id").val(goalId);
        });
    });
</script>

</body>
</html>
