<?php
session_start(); // Start or resume the session
?>

<!DOCTYPE html>
<html>
<head>
    <title>Online Literary Management System</title>
    

    <link rel="stylesheet" href="style_v1.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    

    </style>
</head>
<body class="bg-dark text-light">

<header>
    <h1>Online Literary Management System</h1>
    <div class="container">
        <nav>
            <a href="OLMS_owner_homepage_v1.php">Home</a>
            <a href="OLMS_my_library_v1.php">My Libraries</a>
            <a href="OLMS_my_book_v1.php">My Books</a>
            <a href="OLMS_my_genre_v1.php">My Genres</a>
            <a href="OLMS_my_tag_v1.php">My Tags</a>
            
            <div class="dropdown">
                <button class="dropbtn"><?php echo $_SESSION['username'];
                ?>
                    <i class="fa fa-caret-down"></i>
                </button>
                <div class="dropdown-content">
                    <?php
                    if (isset($_SESSION['user_email'])) {
                        // User is logged in, display username, email, and logout option
                        echo '<a href="#">' . $_SESSION['username'] . '</a>';
                        echo '<a href="#">' . $_SESSION['user_email'] . '</a>';
                        echo '<a href="log_out_v1.php">Log Out</a>';
                    } else {
                        // User is not logged in, display Sign In and Create Account links
                        echo '<a href="OLMS_sign_in_v3.php">Sign In</a>';
                        echo '<a href="OLMS_create_account_v2.php">Create Account</a>';
                    }
                    ?>
                </div>
            </div>
        </nav>
    </div>
</header>

<div class="container">
    <?php
    // Check if user is signed in
    if (isset($_SESSION['user_email'])) {
        // Create connection
        $conn = new mysqli('localhost', 'root', '', 'olms');

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Assuming user is authenticated and email is available in session
        $user_email = $_SESSION['user_email'];

        // Fetch user information from the database
        $sql = "SELECT username FROM users WHERE email = '$user_email'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Output data of each row
            while($row = $result->fetch_assoc()) {
                echo "<h1>Welcome, " . $row["username"] . "!</h1>";
            }
        } else {
            echo "0 results";
        }
        $conn->close();
    } else {
        // Redirect back to sign-in page if user is not signed in
        header("Location: OLMS_sign_in_v3.php");
        exit;
    }
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
