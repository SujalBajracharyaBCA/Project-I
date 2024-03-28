<?php
// Start session
session_start();

// Reset error and success messages
$error = '';
$success = '';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Connect to the database
    $con = mysqli_connect('localhost', 'root', '', 'olms');
    if (!$con) {
        die("Database connection failed: " . mysqli_connect_error());
    }

    // Extract user input
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Basic validation
    if (empty($email) || empty($username) || empty($password)) {
        $_SESSION['error'] = "Please fill in all fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email format.";
    } else {
        
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare SQL query using prepared statements
        $stmt = mysqli_prepare($con, "INSERT INTO users (email, username, password) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sss", $email, $username, $hashed_password);

        // Execute query and handle result
        if (mysqli_stmt_execute($stmt)) {
            $success = "Account created successfully!";
            header("Location: OLMS_owner_homepage_v1.php");
            exit; // Add exit to prevent further execution
        } else {
            $_SESSION['error'] = "Error creating account.";
        }
        header("Location: OLMS_create_account_v2.php");
        exit;

    }

    mysqli_stmt_close($stmt);
    mysqli_close($con);
}
// Retrieve error message from session
$error = isset($_SESSION['error']) ? $_SESSION['error'] : '';
unset($_SESSION['error']); // Clear the session error variable
// Check for session error

?>


<!DOCTYPE html>
<html>
<head>
    <title>Create Account - Online Literary Management System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="style_v1.css">
</head>
<body class="bg-dark text-light">
    <div class="container">
        <header>
            <h1>Online Literary Management System</h1>
            <nav>
                <a href="OLMS_owner_homepage_v1.php">Home</a>
            </nav>
        </header>
        <h2>Create Account</h2>
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        <form method="post">
            <div class="form-group">
                <h2><label for="email">Email:</label></h2>
                <input type="email" id="email" name="email" placeholder="Enter email" required>
            </div>
            <div class="form-group">
                <h2><label for="username">Username:</label></h2>
                <input type="text" class="form-control" id="username" name="username" placeholder="Enter username" required>
            </div>
            <div class="form-group">
                <h2><label for="password">Password:</label></h2>
                <input type="password" id="password" name="password" placeholder="Enter password" required>
            </div>
            <button type="submit">Create Account</button>
        </form>
        
        <p class="error-message"></p>
        <p class="additional-links">
        <p>Already have an account? <a href="OLMS_sign_in_v3.php">Sign in</a></p>
    </div>
    <footer>
        <p>2024 Online Literary Management System Website</p>
    </footer>
    <script src="bootstrap-5.2.3-dist\js\bootstrap.bundle.js"></script>
</body>
</html>
