<?php
include 'db_connection.php';

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usernames = $_POST["usernames"];
    $email = $_POST["emails"];
    $passwords = password_hash($_POST["passwords"], PASSWORD_DEFAULT); // Hash the password

    // Establish database connection (connection details are in db_connection.php)
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    // Check if email already exists in the database
    $emailCheckQuery = "SELECT * FROM user WHERE user_email = '$email'";
    $emailCheckResult = $conn->query($emailCheckQuery);

    if ($emailCheckResult->num_rows > 0) {
        echo '<div class="alert alert-danger text-center">Email already registered</div>';
        // Clear the error parameter from the URL
        echo '<script>history.replaceState({}, document.title, "' . $_SERVER['PHP_SELF'] . '");</script>';
    } else {
        // Insert user data into the database
        $sql = "INSERT INTO user (user_name, user_email, user_password) VALUES ('$usernames', '$email', '$passwords')";

        if ($conn->query($sql) === TRUE) {
            echo '<div class="alert alert-success text-center">User registered successfully!</div>';
        } else {
            echo '<div class="alert alert-danger text-center">Error: ' . $sql . '<br>' . $conn->error . '</div>';
        }
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>FundFlow | Sign Up</title>
    <!-- Include Bootstrap CSS link -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body style="background-color: #087EA4;"> 

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-center">Get started on FundFlow today</h4>
                    </div>
                    <div class="card-body">
                        <form method="post">
                        <label for="username">Username</label>
                        <div class="input-group mb-3">
                        <div class="input-group-prepend">
                                <span class="input-group-text">
                                &#128100;<!-- Add envelope icon for email field -->
                                </span>
                            </div>
                            <input type="text" class="form-control" name="usernames" placeholder="Username" required>
                            </div>
                            <label for="email">Email address</label>
                            <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                &#128231;<!-- Add envelope icon for email field -->
                                </span>
                            </div>
                            <input type="email" class="form-control" name="emails" placeholder="Email address" required>
                            </div>
                            <label for="password">Password</label>
                            <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                &#128274; <!-- Add lock icon for password field -->
                                </span>
                            </div>
                            <input type="password" class="form-control" name="passwords" placeholder="Password" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block" style="background-color: #087EA4">
                                CREATE AN ACCOUNT
                            </button>
                        </form>
                    </div>
                    <div class="card-footer">
                         <p class="mt-2 text-center">Already have an account?  <a href="index.php" 
                            style="color: #087EA4 ; text-decoration: underline"> Log In</a>
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
