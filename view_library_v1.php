<?php
// Start session
session_start();
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
// Get library ID from GET parameter
$library_id = isset($_GET['library_id']) ? $_GET['library_id'] : null;

// Check if library ID is provided
if ($library_id) {
  // Query to fetch books associated with the selected library
  $bookQuery = "SELECT b.book_id, b.bname, GROUP_CONCAT(DISTINCT a.aname SEPARATOR ', ') AS authors,
                GROUP_CONCAT(DISTINCT g.gname SEPARATOR ', ') AS genres,
                GROUP_CONCAT(DISTINCT t.tname SEPARATOR ', ') AS tags
              FROM books AS b
              JOIN books_libraries AS bl ON b.book_id = bl.book_id
              JOIN libraries AS l ON bl.library_id = l.library_id
              JOIN books_authors AS ba ON b.book_id = ba.book_id
              JOIN authors AS a ON ba.author_id = a.author_id
              JOIN books_genres AS bg ON b.book_id = bg.book_id
              JOIN genres AS g ON bg.genre_id = g.genre_id
              JOIN books_tags AS bt ON b.book_id = bt.book_id
              JOIN tags AS t ON bt.tag_id = t.tag_id
              WHERE bl.library_id = ? AND l.owner_email = ?
              GROUP BY b.book_id";


  $bookStmt = mysqli_prepare($con, $bookQuery);
  if (!$bookStmt) {
    die("Error preparing query: " . mysqli_error($con));
  }

  mysqli_stmt_bind_param($bookStmt, "ii", $library_id, $user_email);

  if (!mysqli_stmt_execute($bookStmt)) {
    die("Error executing query: " . mysqli_stmt_error($bookStmt));
  }

    $bookResult = mysqli_stmt_get_result($bookStmt);
}

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
        <div class="topnav">
        <a href="OLMS_owner_homepage_v1.php"><i class="fa fa-fw fa-home fa-2x"></i>Home</a>
        <a class="active" href="OLMS_my_library_v1.php">My Libraries</a>
        <a href="OLMS_my_book_v1.php">My Books</a>
        <a href="OLMS_my_genre_v1.php">My Genres</a>
        <a href="OLMS_my_tag_v1.php">My Tags</a>
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
    </header><br>
    <h2>All Books</h2>
<table class='table table-striped' width='100%'>
<thead>
<tr>
<th><p>Book serial no.</p></th>
<th><p>Book Title</p></th>
<th><p>Authors</p></th>
<th><p>Genres</p></th>
<th><p>Tags</p></th>
</tr>
</thead>
<tbody>
    <?php
    $sno=1;
    if (mysqli_num_rows($bookResult) > 0) {
    
        while ($bookRow = mysqli_fetch_assoc($bookResult)) {
            $bookId = $bookRow['book_id'];
            $bookName = $bookRow['bname'];
            $authors = $bookRow['authors'];
            $genres = $bookRow['genres'];
            $tags = $bookRow['tags'];

            $detailsLink = "OLMS_book_details_v1.php?book_id=$bookId";
            echo "<tr><td><p>$sno</p></td>";
            echo "<td><a href='$detailsLink'><p>$bookName</p></a></td>";
            echo "<td><p>$authors</p></td>";
            echo  "<td><p>$genres</p></td>";
            echo  "<td><p>$tags</p></td></tr>";
        }
        echo "</tbody></table>";
        $sno++;
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
