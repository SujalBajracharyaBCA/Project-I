<?php
session_start();

// Initialize error and success messages
$error = '';
$success = '';

// Connect to the database using prepared statements for security
$con = mysqli_connect('localhost', 'root', '', 'olms');
if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Check if the user is logged in
if (!isset($_SESSION['user_email'])) {
    header("Location: OLMS_sign_in_v3.php"); // Redirect to login if not logged in
    exit;
}
$user_email = $_SESSION['user_email'];
$username = $_SESSION['username'];
// ... existing code (including error/success handling and database connection)

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ... existing validation and sanitization

    $bname = filter_input(INPUT_POST, 'bname');
    $author = filter_input(INPUT_POST, 'author');
    $genres = filter_input(INPUT_POST, 'genres', FILTER_REQUIRE_ARRAY); // Enforce genre selection
    $tags = filter_input(INPUT_POST, 'tags', FILTER_REQUIRE_ARRAY); // Enforce tag selection
    $numofchaprd = filter_input(INPUT_POST, 'numofchaprd', FILTER_SANITIZE_NUMBER_INT);
    $numofchaptl = filter_input(INPUT_POST, 'numofchaptl', FILTER_SANITIZE_NUMBER_INT);
    $synopsis = filter_input(INPUT_POST, 'synopsis');
    $external_url = filter_input(INPUT_POST, 'external_url', FILTER_SANITIZE_URL); // Validate and sanitize URL

   
    // Check if author exists, insert if not
    $sql = "SELECT author_id FROM authors WHERE aname = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "s", $author);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $author_id);
    if (mysqli_stmt_fetch($stmt)) {
        // Author exists, use existing ID
    } else {
        // Author doesn't exist, insert new record
        $sql = "INSERT INTO authors (aname) VALUES (?)";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "s", $author);
        mysqli_stmt_execute($stmt);
        $author_id = mysqli_insert_id($con);
    }
    mysqli_stmt_close($stmt);
    if (empty($bname) || empty($author) || empty($genres) || empty($tags)) {
        $error = '<p>Please fill in all required fields.</p>';
    } else {
    // Prepare and execute SQL query for book creation
    $stmt = mysqli_prepare($con, "INSERT INTO books (book_id, bname, owner_email, author_id, numofchaprd, numofchaptl, synopsis) VALUES (NULL, ?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "ssssssi", $bname, $_SESSION['user_email'], $author_id, $numofchaprd, $numofchaptl, $synopsis);
    if (mysqli_stmt_execute($stmt)) {
        $bookId = mysqli_insert_id($con); // Get the newly inserted book ID

    
        // Insert external URL into separate table
        $stmt = mysqli_prepare($con, "INSERT INTO books_url (url, book_id, owner_email) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sss", $external_url, $bookId, $_SESSION['user_email']);
        if (mysqli_stmt_execute($stmt)) {
            // ... (handle success)
            $success = '<p>Book created successfully! View your book <a href="OLMS_book_details_v1.php?book_id=' . $bookId . '">here</a>.</p>';
       
        } else {
            // ... (handle error)
            $error = '<p>Error creating book: ' . mysqli_error($con).'</p>';
       
        }
        mysqli_stmt_close($stmt);

        // Connect genres and tags using separate prepared statements
        foreach ($genres as $genreId) {
            mysqli_stmt_bind_param($genreStmt, "ii", $bookId, $genreId);
            mysqli_stmt_execute($genreStmt);
        }
        foreach ($tags as $tagId) {
            mysqli_stmt_bind_param($tagStmt, "ii", $bookId, $tagId);
            mysqli_stmt_execute($tagStmt);
        }
        // ... (same logic as before)
 
        mysqli_stmt_close($genreStmt);
        mysqli_stmt_close($tagStmt);
        $success = '<p>Book created successfully! View your book <a href="OLMS_book_details_v1.php?book_id=' . $bookId . '">here</a>.</p>';
    } else {
        $error = '<p>Error creating book: ' . mysqli_error($con).'</p>';
    }
    mysqli_stmt_close($stmt);
}

mysqli_close($con);}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Create book | Online Literary Management System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="style_v1.css">
</head>
<body>

<main>
<header>
<h1>Create Book | My Books | Online Literary Management System</h1>
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
        <label for="name" class="form-label"><p>Book Name:</p></label>
        <input type="text" class="form-control" placeholder="Enter book name" id="bname" name="bname" required><br>
    </div><br>
    <div class="container">
        <label for="name" class="form-label"><p>Author Name:</p></label>
        <input type="text" class="form-control" placeholder="Enter author name" id="author" name="author" required><br>
    </div><br>
    <div class="container">
        <label for="name" class="form-label"><p>Genre:</p></label>
        <input type="checkbox" class="form-control" id="genres" name="genres" value="<?php foreach ($genres as $genre): ?>" required>
            <?php echo $genre;
            endforeach; ?>  <br>
    </div><br>
    <div class="container">
        <label for="name" class="form-label"><p>Tag:</p></label>
        <input type="checkbox" class="form-control" id="tags" name="tags" value="<?php foreach ($tags as $tag): ?>" required>
            <?php echo $tag;
            endforeach; ?> <br>
    </div><br>
    <div class="container">
        <label for="name" class="form-label"><p>Number of chapters read:</p></label>
        <input type="textbox" class="form-control" placeholder="Enter number of chapters read" id="numofchaprd" name="numofchpard" value="" required>
        <label for="name" class="form-label"><p>Number of total chapters:</p></label>
        <input type="textbox" class="form-control" placeholder="Enter total number of chapters" id="numofchaptl" name="numofchaptl" value="" required>
           <br>
    </div><br>
    <div class="container">
        <label for="name" class="form-label"><p>Synopsis:</p></label>
        <textarea class="form-control" placeholder="Enter synopsis" id="synopsis" name="synopsis" value="" cols="80" rows="20"></textarea>
           <br>
    </div><br>
    <button type="submit" class="btn btn-primary">Create book</button><br>
</form><br>
<a href="OLMS_my_book_v1.php" class="btn btn-primary">Back to My book</a><br><br>
<footer>
    <div class="container">
        <p>2024 Online Literary Management System Website</p>
    </div>
</footer>
</main>
</body>
</html>
