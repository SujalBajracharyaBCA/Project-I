<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_email'])) {
    // Redirect to sign-in page if not logged in
    header("Location: OLMS_sign_in_v3.php");
    exit;
}

// Get user email from session
$user_email = $_SESSION['user_email'];

// Connect to the database
$con = mysqli_connect('localhost', 'root', '', 'olms');
if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Fetch books associated with the user
$bookQuery = "SELECT b.book_id, b.bname, GROUP_CONCAT(a.aname SEPARATOR ', ') AS authors, GROUP_CONCAT(g.gname SEPARATOR ', ') AS genres, GROUP_CONCAT(t.tname SEPARATOR ', ') AS tags
             FROM books AS b
             JOIN books_authors AS ba ON b.book_id = ba.book_id
             JOIN authors AS a ON ba.author_id = a.author_id
             JOIN books_genres AS bg ON b.book_id = bg.book_id
             JOIN genres AS g ON bg.genre_id = g.genre_id
             JOIN books_tags AS bt ON b.book_id = bt.book_id
             JOIN tags AS t ON bt.tag_id = t.tag_id
             WHERE b.owner_email = ?
             GROUP BY b.book_id";

// Fix: Prepare and execute the query correctly
$bookStmt = mysqli_prepare($con, $bookQuery);
if (!$bookStmt) {
    die("Error preparing query: " . mysqli_error($con));
}
mysqli_stmt_bind_param($bookStmt, "s", $user_email);
if (!mysqli_stmt_execute($bookStmt)) {
    die("Error executing query: " . mysqli_stmt_error($bookStmt));
}
$bookResult = mysqli_stmt_get_result($bookStmt);

?>

<!DOCTYPE html>
<html>
<head>
    <title>My Libraries | Online Literary Management System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="style_v1.css">
</head>
<body class="bg-dark text-light">
    <header>
        <h1>My Libraries | Online Literary Management System</h1>
        <nav>
            <a href="OLMS_owner_homepage_v1.php">Home</a>
            <a href="OLMS_my_library_v1.php">My Libraries</a>
            <a href="OLMS_my_book_v1.php">My Books</a>
            <a href="OLMS_my_genre_v1.php">My Genres</a>
            <a href="OLMS_my_tag_v1.php">My Tags</a>
            <div class="dropdown">
                <button class="dropbtn">User: <?php echo $_SESSION['username']; ?>
                    <i class="fa fa-caret-down"></i>
                </button>
                <div class="dropdown-content">
                    <a href="#"><?php echo $_SESSION['username']; ?></a>
                    <a href="#"><?php echo $_SESSION['user_email']; ?></a>
                    <a href="log_out_v1.php">Log Out</a>
                </div>
            </div>
        </nav>
    </header><br>

    <?php
    if (mysqli_num_rows($bookResult) > 0) {
        echo "<h2>All Books</h2>";
        echo "<div class='row row-cols-1 row-cols-md-3 g-4'>";
        while ($bookRow = mysqli_fetch_assoc($bookResult)) {
            $bookId = $bookRow['book_id'];
            $bookName = $bookRow['bname'];
            $authors = $bookRow['authors'];
            $genres = $bookRow['genres'];
            $tags = $bookRow['tags'];

            $detailsLink = "OLMS_book_details_v1.php?book_id=$bookId";


            echo "<tr><td><a href='$detailsLink'>$bookName</a></td><td>$authors</td><td>$genres</td><td>$tags</td></tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<p>No books found in your library.</p>";
    }

    mysqli_stmt_close($bookStmt);
    mysqli_close($con);
    ?>

    <footer>
        <div class="container">
            <p>2024 Online Literary Management System Website</p>
        </div>
    </footer>
</body>
</html>
