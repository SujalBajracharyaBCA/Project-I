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

    // Escape user input for security (basic, more advanced techniques recommended)
    $email = mysqli_real_escape_string($con, trim($_POST['email']));
    $username = mysqli_real_escape_string($con, trim($_POST['username']));
    $password = mysqli_real_escape_string($con, trim($_POST['password']));

    // Enhanced validation
    $errors = []; // Array to store validation errors

    // Validate email
    if (empty($email)) {
        $errors[] = "Email is required.";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // Validate username
    if (empty($username)) {
        $errors[] = "Username is required.";
    } else if (strlen($username) < 4) {
        $errors[] = "Username must be at least 4 characters long.";
    } else if (!preg_match('/^[a-zA-Z0-9_.-]+$/', $username)) { // Allow letters, numbers, underscore, hyphen, and dot
        $errors[] = "Username can only contain letters, numbers, underscore, hyphen, and dot.";
    }

    // Validate password
    if (empty($password)) {
        $errors[] = "Password is required.";
    } else if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long.";
    } else if (!preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^\w\s])/', $password)) { // Require at least one digit, lowercase letter, uppercase letter, and special character
        $errors[] = "Password must contain at least one digit, lowercase letter, uppercase letter, and special character.";
    }

    // Continue processing only if there are no errors
    if (empty($errors)) {
        // Hash the password with a cost factor of 12 for better security
        $hashed_password = password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);

        // Prepare SQL query using prepared statements (placeholder binding for added security)
        $stmt = mysqli_prepare($con, "INSERT INTO users (email, username, password) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sss", $email, $username, $hashed_password);

        // Execute query and handle result
        if (mysqli_stmt_execute($stmt)) {
            $success = "Account created successfully!";
            mysqli_stmt_close($stmt);
            header("Location: OLMS_owner_homepage_v1.php");
            exit; // Exit to prevent further execution
        } else {
            $errors[] = "Error creating account.";
            header("Location: OLMS_create_account_v3.php");
            exit; 
        }
       
        
        
    }

    // Store errors in a session variable for easy access (consider using a more robust method for storing errors and user input in real-world applications)
    $_SESSION['errors'] = $errors;
    mysqli_close($con);
}

// Retrieve errors from session and unset afterwards
$errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : [];
unset($_SESSION['errors']);

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

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <form method="post" id="createAccountForm">
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
            <button type="submit" id="submitButton">Create Account</button>
        </form>

        <p class="error-message"></p>
        <p class="additional-links">
            <p>Already have an account? <a href="OLMS_sign_in_v3.php">Sign in</a></p>
        </div>
    </footer>
    <script>
        // Add event listener to the submit button
        document.getElementById("submitButton").addEventListener("click", function(event) {
            // Remove any previous error messages
            document.querySelector(".error-message").innerHTML = "";

            // Basic validation (can be extended further)
            if (document.getElementById("email").value === "") {
                event.preventDefault(); // Prevent form submission
                document.querySelector(".error-message").innerHTML = "Email is required.";
            }
        });
    </script>
    <script src="bootstrap-5.2.3-dist\js\bootstrap.bundle.js"></script>
</body>
</html>
