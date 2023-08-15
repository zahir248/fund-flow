<?php
session_start();

// Check if user is logged in, redirect to login page if not
if (!isset($_SESSION["id"])) {
    header("Location: index.php");
    exit;
}

include 'db_connection.php'; // Include your database connection

$user_id = $_SESSION["id"];

// Fetch user's current profile information
$sql = "SELECT user_name, user_email, user_password FROM user WHERE user_id = '$user_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user_row = $result->fetch_assoc();
    $user_name = $user_row["user_name"];
    $user_email = $user_row["user_email"];
    $user_password = $user_row["user_password"];
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $new_username = $_POST["new_username"];
    $new_email = $_POST["new_email"];
    // Check if a new password was provided
    if (!empty($_POST["new_password"])) {
         $new_password = password_hash($_POST["new_password"], PASSWORD_DEFAULT); 
         $update_sql = "UPDATE user SET user_name = '$new_username', user_email = '$new_email', 
         user_password = '$new_password'";
    } else {
         $update_sql = "UPDATE user SET user_name = '$new_username', user_email = '$new_email'";
    }
    
    $update_sql .= " WHERE user_id = '$user_id'";

    if ($conn->query($update_sql) === TRUE) {
        $success_message = "Account updated successfully!";
        echo '<script>history.replaceState({}, document.title, "' . $_SERVER['PHP_SELF'] . '");</script>';
        //header("Location: dashboard.php");
        //exit;
    } else {
        $error_message = "Error updating profile: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FundFlow | Manage Account</title>
    <!-- Include Bootstrap CSS link -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

</head>
<body style="background-color: #343A40;"> 

    <nav class="navbar navbar-expand-md navbar-dark " style="background-color: #087EA4;">
        <a class="navbar-brand" href="#">FundFlow</a>
    </nav>

    <div class="container mt-3 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-center">Manage account</h4>
                </div>
                <div class="alert alert-info">
                     <p><strong>Info:</strong> To change your account details, fill in the field(s) you want to update. 
                     You can leave other fields unchanged. You can click on Dashboard below if you want return to 
                     the dashboard page back.
                    </p>
                </div>
                <div class="card-body">
                <?php
                    if (isset($error_message)) {
                       echo '<div class="alert alert-danger">' . $error_message . '</div>';
                    }
                    if (isset($success_message)) {
                       echo '<div class="alert alert-success">' . $success_message . '</div>';
                    }
                    ?>
                    <form method="post">
                        <label for="username">New username</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    &#128100;
                                </span>
                            </div>
                            <input type="text" class="form-control" id="new_username" name="new_username" 
                            value="<?php echo $user_name; ?>">
                        </div>
                        <label for="email">New email address</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                &#128231;
                                </span>
                            </div>
                            <input type="email" class="form-control" id="new_email" name="new_email" 
                            value="<?php echo $user_email; ?>">  
                        </div>
                        <label for="password">New password</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                &#128274; 
                                </span>
                            </div>
                            <input type="password" class="form-control" id="new_password" name="new_password" 
                            value="" placeholder="********************************">
                        </div>
                        <button type="submit" class="btn btn-primary btn-block" style="background-color: #087EA4">
                            Update
                        </button>
                    </form>
                </div>
                <div class="card-footer">
                    <p class="mt-2 text-center">Back to Dashboard? click here  <a href="dashboard.php" 
                        style="color: #087EA4 ; text-decoration: underline"> Dashboard</a>
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
