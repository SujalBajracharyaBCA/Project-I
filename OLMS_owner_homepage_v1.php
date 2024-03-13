<?php
session_start(); // Start or resume the session
$error = '';
$success = '';

// Connect to the database
$con = mysqli_connect('localhost', 'root', '', 'olms');
if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Check if the user is logged in 
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
?>

<!DOCTYPE html>
<html>
<head>
    <title>Online Literary Management System</title>
    <link rel="stylesheet" href="style_v1.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body class="bg-dark text-light">

<header>
    <h1>Online Literary Management System</h1>
    <div class="topnav">

            <a class="active" href="OLMS_owner_homepage_v1.php"><i class="fa fa-fw fa-home fa-2x"></i>Home</a>
            <a href="OLMS_my_library_v1.php"><i class="fa fa-align-justify fa-2x"></i>My Libraries</a>
            <a href="OLMS_my_book_v2.php"><i class="fa fa-book fa-2x"></i>My Books</a>
            <a href="OLMS_my_genre_v1.php"><i class="fa fa-tags fa-2x"></i>My Genres</a>
            <a href="OLMS_my_tag_v1.php"><i class="fa fa-tag fa-2x"></i>My Tags</a>
            <?php if ($username) : ?>
            <div class="dropdown">
                <button class="dropbtn"><i class="fa fa-user-circle menu fa-2x"></i>
                    <i class="fa fa-caret-down fa-2x"></i>
                </button>
                <div class="dropdown-content">
                    <a href="#"><?php echo "Username:".$username; ?></a>
                    <a href="#"><?php echo "Email:".$user_email; ?></a>
                    <a href="log_out_v1.php"><i class="fa-solid fa-fw fa-right-from-bracket mr4"></i>Log Out</a>
                </div>    
            </div>
        <?php else : ?>
            <a href="OLMS_sign_in_v3.php">Sign In</a>
            <a href="OLMS_create_account_v2.php">Create Account</a>
        <?php endif; ?>
        <div class="search-container" >
     <form action="search.php" >
      <input type="text" placeholder="Search.." name="search"><button type="submit"><i class="fa fa-search"></i></button>
     </form>
  </div>
  
    </div>
</header>

<div class="container">
    <?php
    echo "<h1>Welcome, " . $username. "!</h1>";
    ?>
    <p>You have successfully signed in to the Online Literary Management System.</p>
    <p>Start organizing your literary world by creating libraries, managing books, and discovering new reads with ease.</p>
</div>

<footer>
    <div class="container">
        <p>2024 Online Literary Management System Website</p>
    </div>
</footer>
<script src="bootstrap-5.2.3-dist\js\bootstrap.bundle.js"></script>
</body>
</html>
