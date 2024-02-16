<?php
session_start();

// Check if the user is logged in
if (isset($_SESSION['user_email'])) {
    // Connect to the database using prepared statements
    $con = mysqli_connect('localhost', 'root', '', 'olms');
    if (!$con) {
        die("Database connection failed: " . mysqli_connect_error());
    }

    $user_email = $_SESSION['user_email'];
} else {
    // Redirect user to sign-in page if not logged in
    header("Location: OLMS_sign_in_v3.php");
    exit;
}

// Prepare and execute the query to fetch genres associated with the user
$stmt = mysqli_prepare($con, "SELECT genre_id, gname FROM genres WHERE owner_email = ?");
mysqli_stmt_bind_param($stmt, "s", $user_email);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $genre_id, $gname);

// Initialize empty array to store genres
$genres = [];

// Fetch and store each genre in the array
while (mysqli_stmt_fetch($stmt)) {
    $genres[] = array("genre_id" => $genre_id, "gname" => $gname);
}

mysqli_stmt_close($stmt);
mysqli_close($con);

?>

<!DOCTYPE html>
<html>
<head>
    <title>My Genres | Online Literary Management System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="style_v1.css">
</head>
<body class="bg-dark text-light">
    <header>
    <h1>My genre | Online Literary Management System</h1>
        <nav>
            <a href="OLMS_owner_homepage_v1.php">Home</a>
            <a href="OLMS_my_library_v1.php">My Libraries</a>
            <a href="#">My Books</a>
            <a href="OLMS_my_genre_v1.php">My Genres</a>
            <a href="#">Tags</a>
            <div class="dropdown">
                <button class="dropbtn">User: <?php echo $_SESSION['username'];
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
        </header><br>

    <button type="button" class="btn btn-primary" onclick="window.location.href='create_genre_v1.php'">Create New Genre</button>

    <?php if (!empty($genres)): ?>
        <table class="table table-striped" width="100%">
            <thead>
                <tr>
                    <th><p>Genre serial no.</p></th>
                    <th><p>Genre Name</p></th>
                    <th><p>Actions</p></th>
                </tr>
            </thead>
            <tbody>
                <?php $sno=1; ?>
                <?php foreach ($genres as $genre): ?>
                    <tr>
                        <td><p><?php echo $sno++; ?></p></td>
                        <td><p><?php echo $genre["gname"]; ?></p></td>
                        <td>
                            <a href="edit_genre_v1.php?genre_id=<?php echo $genre["genre_id"]; ?>">Edit</a>
                            <a href="delete_genre_v1.php?genre_id=<?php echo $genre["genre_id"]; ?>" onclick="return confirm('Are you sure you want to delete this genre?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>You haven't created any genres yet!</p>
    <?php endif; ?>

    <footer>
    
        <div class="container">
              <p>2024 Online Literary Management System Website</p>
            </div>
    </footer>
       
</body>
</html>
