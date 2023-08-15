<!DOCTYPE html>
<html>
<head>
    <title>FundFlow | Log In</title>
    <!-- Include Bootstrap CSS link -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body style="background-color: #087EA4;"> 

<?php
// Check if 'error' parameter is set in the URL
if (isset($_GET['error'])) {
    // Display an error message with the value of the 'error' parameter
    echo '<div class="alert alert-danger text-center" role="alert">' . $_GET['error'] . '</div>';
    
    // Clear the 'error' parameter from the URL using JavaScript
    echo '<script>history.replaceState({}, document.title, "' . $_SERVER['PHP_SELF'] . '");</script>';
}
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-center">Log in to your FundFlow account</h4>
                </div>
                <div class="card-body">
                    <!-- Create a form to submit login credentials to 'login.php' -->
                    <form method="post" action="login.php">
                        <label for="email">Email address</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    &#128231; <!-- Envelope icon for email field -->
                                </span>
                            </div>
                            <input type="email" class="form-control" name="email" placeholder="Email address" required>
                        </div>
                        <label for="password">Password</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    &#128274; <!-- Lock icon for password field -->
                                </span>
                            </div>
                            <input type="password" class="form-control" name="password" placeholder="Password" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block" style="background-color: #087EA4">
                            Log In
                        </button>
                    </form>
                </div>
                <div class="card-footer">
                    <p class="mt-3 text-center">Don't have an account? <a href="signup.php" style="color: #087EA4; 
                    text-decoration: underline">Sign Up</a></p>
                </div>
            </div>
            <p class="mt-3 text-center">
                <a href="reset_password.php" style="color: white; text-decoration: underline">Forgot your password</a>
            </p>
        </div>
    </div>
</div>

<!-- Include jQuery, Popper.js, and Bootstrap JS scripts -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
