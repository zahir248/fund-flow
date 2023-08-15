<?php
include 'db_connection.php'; // Include your database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $newPassword = $_POST["new_password"];

    // Check if email already exists in the database
    $emailCheckQuery = "SELECT * FROM user WHERE user_email = '$email'";
    $emailCheckResult = $conn->query($emailCheckQuery);

    // Hash the new password before storing it in the database
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    if ($emailCheckResult->num_rows > 0) {
        // Update the password in the database
        $sql = "UPDATE user SET user_password = '$hashedPassword' WHERE user_email = '$email'";
    if ($conn->query($sql) === TRUE) {
        $message = "Password updated successfully";
        echo '<script>history.replaceState({}, document.title, "' . $_SERVER['PHP_SELF'] . '");</script>';
    } else {
        $error = "Error updating password: " . $conn->error;
    }
    } else {
        echo '<div class="alert alert-danger text-center">User not found</div>';
        // Clear the error parameter from the URL
        echo '<script>history.replaceState({}, document.title, "' . $_SERVER['PHP_SELF'] . '");</script>';
    }
}
?>

<?php if(isset($message)) { ?>
    <div class="alert alert-success text-center"><?php echo $message; ?></div>
    <?php } ?>
    <?php if(isset($error)) { ?>
    <div class="alert alert-danger text-center"><?php echo $error; ?></div>
<?php } ?>

<!DOCTYPE html>
<html>
<head>
    <title>FundFlow | Change Password</title>
    <!-- Include Bootstrap CSS link -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body style="background-color: #087EA4;"> 
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                <div class="card-header">
                    <h4 class="text-center">Change password</h4>
                </div>
                    <div class="card-body">
                        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <label for="email">Email address</label>
                            <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                &#128231;<!-- Add envelope icon for email field -->
                                </span>
                            </div>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Email address" required>
                            </div>
                            <label for="new_password">New Password</label>
                            <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                &#128274; <!-- Add lock icon for password field -->
                                </span>
                            </div>
                            <input type="password" class="form-control" id="new_password" name="new_password" placeholder="New password" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block" style="background-color: #087EA4">Change Password</button>
                        </form>
                    </div>
                    <div class="card-footer text-center">
                         <p class="mt-3">
                            <a href="index.php" style="color: #087EA4 ; text-decoration: underline">Log In</a>
                            <span class="mx-2">or</span>
                            <a href="signup.php" style="color: #087EA4 ; text-decoration: underline">Sign Up</a>
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
