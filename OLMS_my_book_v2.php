<?php

session_start();

// Connect to the database using prepared statements
$con = mysqli_connect('localhost', 'root', '', 'olms');
if (!$con) {
  die("Database connection failed: " . mysqli_connect_error());
}

// Check if the user is logged in
if (isset($_SESSION['user_email'])) {
  $user_email = $_SESSION['user_email'];

  // Fetch user information (optional, adjust based on your needs)
  $sql = "SELECT username FROM users WHERE email = ?";
  $stmt = mysqli_prepare($con, $sql);
  mysqli_stmt_bind_param($stmt, "s", $user_email);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_bind_result($stmt, $username);
  mysqli_stmt_fetch($stmt);
  mysqli_stmt_close($stmt);

  // Query to fetch books associated with the user
  $bookQuery = "SELECT DISTINCT b.book_id, b.bname, GROUP_CONCAT(DISTINCT a.aname SEPARATOR ', ') AS authors,
                   GROUP_CONCAT(DISTINCT g.gname SEPARATOR ', ') AS genres,
                   GROUP_CONCAT(DISTINCT t.tname SEPARATOR ', ') AS tags
            FROM books AS b
            JOIN books_authors AS ba ON b.book_id = ba.book_id
            JOIN authors AS a ON ba.author_id = a.author_id
            JOIN books_genres AS bg ON b.book_id = bg.book_id
            JOIN genres AS g ON bg.genre_id = g.genre_id
            JOIN books_tags AS bt ON b.book_id = bt.book_id
            JOIN tags AS t ON bt.tag_id = t.tag_id
            WHERE b.owner_email = ?
            GROUP BY b.book_id";
  $bookStmt = mysqli_prepare($con, $bookQuery);
  if (!$bookStmt) {
    die("Error preparing query: " . mysqli_error($con));
  }
  mysqli_stmt_bind_param($bookStmt, "s", $user_email);
  if (!mysqli_stmt_execute($bookStmt)) {
    die("Error executing query: " . mysqli_stmt_error($bookStmt));
  }
  $bookResult = mysqli_stmt_get_result($bookStmt);

  // Create an empty array to store book data
  $books = [];

  // Fetch each book and add its details to the array using a loop
  while ($bookRow = mysqli_fetch_assoc($bookResult)) {
    $books[] = $bookRow; // Append current book data to the array
  }
   
  mysqli_stmt_close($bookStmt);
  
} else {
  header("Location: OLMS_sign_in_v3.php"); // Redirect to login if not logged in
  exit;
}

mysqli_close($con);

?> 
<!DOCTYPE html>
<html>
<head>
    <title>My Books | Online Literary Management System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="style_v1.css">
</head>
<body class="bg-dark text-light">
    <header>
        <h1>My Books | Online Literary Management System</h1>
        <div class="topnav">
            <a href="OLMS_owner_homepage_v1.php"><i class="fa fa-fw fa-home fa-2x"></i>Home</a>
            <a href="OLMS_my_library_v1.php"><i class="fa fa-align-justify fa-2x"></i>My Libraries</a>
            <a class="active" href="OLMS_my_book_v2.php"><i class="fa fa-book fa-2x"></i>My Books</a>
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
    </header><br>

    <button type="button" class="btn btn-primary" onclick="window.location.href='create_book_v1.php'">Create New Book Profile</button><br>

    <?php if (!empty($books)): ?>
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
                <?php $sno=1; ?>
                <?php foreach ($books as $book): ?>
                    <tr>
                        <td><p><?php echo $sno++; ?></p></td>
                        <td><p><?php echo $book["bname"]; ?></p></td>
                        <td><p><?php echo $book["authors"]; ?></p></td>
                        <td><p><?php echo $book["genres"]; ?></p></td>
                        <td><p><?php echo $book["tags"]; ?></p></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>You haven't created any books yet!</p>
    <?php endif; ?>

    <footer>
        <div class="container">
            <p>2024 Online Literary Management System Website</p>
        </div>
    </footer>
</body>
</html>
