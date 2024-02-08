<?php
// Connect to the database
$con = mysqli_connect('localhost', 'root', '', 'olms');
if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Initialize variables
$error = '';
$library = null;

// Check if the library_id is provided in the query string
if (isset($_GET['library_id'])) {
    $libraryId = $_GET['library_id'];

    // Prepare and execute query to retrieve library details
    $query = "SELECT * FROM libraries WHERE library_id = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "i", $libraryId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        // Library found, fetch details
        $library = mysqli_fetch_assoc($result);
    } else {
        $error = "Library not found.";
    }

    mysqli_stmt_close($stmt);
}

// Close database connection
mysqli_close($con);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Library | Online Literary Management System</title>
    <link rel="stylesheet" href="bootstrap-5.2.3-dist\css\bootstrap.css">
    <link rel="stylesheet" href="style_v1.css">
</head>
<body>
    <header>
        <h1>Online Literary Management System</h1>
        <nav>
            <a href="OLMS_homepage_v1.html">Home</a>
            <a href="OLMS_my_library_v1.php">My Libraries</a>
            <a href="#">My Books</a>
            <a href="#">Genres</a>
            <a href="#">Tags</a>
        </nav>
    </header><br>
    <main>
        <div class="container">
            <?php if ($library): ?>
                <h1><?php echo $library['lname']; ?></h1>
                <p>Number of Books: <?php echo $library['numofbooks']; ?></p>
                <p>Owner Email: <?php echo $library['owner_email']; ?></p>
            <?php else: ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            <a href="OLMS_my_library_v1.php" class="btn btn-secondary">Back to My Libraries</a>
        </div>
    </main>
    <footer>
        <div class="container">
            <p>2024 Online Literary Management System Website</p>
        </div>
    </footer>
</body>
</html>
