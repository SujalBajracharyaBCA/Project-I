<?php
session_start();
$error = '';
$success = '';

// Connect to the database
$con = mysqli_connect('localhost', 'root', '', 'olms');
if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Check if the user is logged in (assuming a tag belongs to a user)
if (isset($_SESSION['user_email'])) {
    $user_email = $_SESSION['user_email'];
    // Fetch user information from the database
    $sql = "SELECT username FROM users WHERE email = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "s", $user_email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $username);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
} else {
    header("Location: OLMS_sign_in_v3.php"); // Redirect to login if not logged in
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tname = $_POST['tname'];

    // Validate tag name (optional)
    // You can add checks for length, allowed characters, or uniqueness here

    // Prepare SQL query using prepared statements
    $stmt = mysqli_prepare($con, "INSERT INTO tags (tag_id, tname, owner_email) VALUES (NULL, ?, ?)");
    mysqli_stmt_bind_param($stmt, "ss", $tname, $user_email);

    // Execute query and handle result
    if (mysqli_stmt_execute($stmt)) {
        $success = '<p>tag created successfully!</p>';
    } else {
        $error = '<p>Error creating tag: ' . mysqli_error($con).'</p>';
    }

    mysqli_stmt_close($stmt);
    header("Location: create_tag_v1.php"); // Refresh the page
}

mysqli_close($con);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create tag | Online Literary Management System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="style_v1.css">
</head>
<body>

<main>
<header>
<h1>Create tag | tags | Online Literary Management System</h1>
    <nav>
        <a href="OLMS_owner_homepage_v1.php">Home</a>
        <a href="OLMS_my_library_v1.php">My Libraries</a>
        <a href="OLMS_my_book_v1.php">My Books</a>
        <a href="OLMS_my_genre_v1.php">My Genres</a>
        <a href="OLMS_my_tag_v1.php">My Tags</a>
        <?php if ($username) : ?>
            <div class="dropdown">
                <button class="dropbtn">User: <?php echo $username; ?>
                    <i class="fa fa-caret-down"></i>
                </button>
                <div class="dropdown-content">
                    <a href="#"><?php echo $username; ?></a>
                    <a href="#"><?php echo $user_email; ?></a>
                    <a href="log_out_v1.php">Log Out</a>
                </div>
            </div>
        <?php else : ?>
            <a href="OLMS_sign_in_v3.php">Sign In</a>
            <a href="OLMS_create_account_v2.php">Create Account</a>
        <?php endif; ?>
    </nav>
    </header><br>

<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<?php if (isset($success)): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
<?php endif; ?>

<form method="post">
    <div class="container">
        <label for="name" class="form-label"><p>Tag Name:</p></label>
        <input type="text" class="form-control" placeholder="Enter tag name" id="tname" name="tname" required><br>
    </div><br>
    <button type="submit" class="btn btn-primary">Create Tag</button><br>
</form><br>
<a href="OLMS_my_tag_v1.php" class="btn btn-primary">Back to My Tags</a><br><br>
<footer>
    <div class="container">
        <p>2024 Online Literary Management System Website</p>
    </div>
</footer>
</main>
</body>
</html>