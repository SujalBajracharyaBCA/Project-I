<?php
session_start(); // Start or resume the session

// Reset error message
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['email']) || empty($_POST['password'])) {
        $error = "Please fill in all fields.";
    } else {
        $con = mysqli_connect('localhost', 'root', '', 'olms');
        if (!$con) {
            die("Database connection failed: " . mysqli_connect_error());
        }

        $email = $_POST['email'];
        $password = $_POST['password'];

        $stmt = mysqli_prepare($con, "SELECT * FROM users WHERE email = ?");
        mysqli_stmt_bind_param($stmt, "s", $email);

        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            $user = mysqli_fetch_assoc($result);

            if ($user) {
                if (password_verify($password, $user['password'])) {
                    // Successful login, store user email and username in session
                    $_SESSION['user_email'] = $email; // Store user email in session
                    $_SESSION['username'] = $user['username']; // Store username in session
                    header("Location: OLMS_owner_homepage_v1.php"); // Redirect to owner homepage
                    exit;
                } else {
                    $_SESSION['error'] = "Invalid password.";
                }
            } else {
                $_SESSION['error'] = "User not found.";
            }
        } else {
            $_SESSION['error'] = "Error executing query: " . mysqli_error($con);
        }

        mysqli_stmt_close($stmt);
        mysqli_close($con);

        // Redirect back to sign-in page after form submission
        header("Location: OLMS_sign_in_v3.php");
        exit;
    }
}

// Retrieve error message from session
$error = isset($_SESSION['error']) ? $_SESSION['error'] : '';
unset($_SESSION['error']); // Clear the session error variable
?>

<!DOCTYPE html>
<html>
<head>
    <title>Online Literary Management System Sign-In</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="style_v1.css">
</head>
<body class="bg-dark text-light">
    <div class="container">
        <header>
            <h1>Online Literary Management System</h1>
            <nav>
                <a href="OLMS_homepage_v1.html">Home</a>
            </nav>
        </header>
        <h2>Sign in</h2>
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="post">
            <div class="form-group">
                <h2><label for="email">Email:</label></h2>
                <input type="email" id="email" name="email" placeholder="Enter email" required>
            </div>
            <div class="form-group">
                <h2><label for="password">Password:</label></h2>
                <input type="password" id="password" name="password" placeholder="Enter password" required>
            </div>
            <button type="submit">Sign in</button>
        </form>
        <p class="error-message"></p>
        <p class="additional-links">
            <a href="OLMS_create_account_v2.php">Create account</a>
            <a href="#">Forgot password?</a>
        </p>
    </div>
    <footer>
        <p>2024 Online Literary Management System Website</p>
    </footer>
    <script src="bootstrap-5.2.3-dist\js\bootstrap.bundle.js"></script>
</body>
</html>
