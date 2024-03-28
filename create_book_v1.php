<?php
session_start();

// Initialize error and success messages
$error = '';
$success = '';

// Establish a single database connection at the beginning
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

// Function to fetch genres from the database (reusing existing connection)
function fetchGenresFromDatabase($con, $user_email) {
    // Use the existing connection
    $sql = "SELECT genre_id, gname FROM genres WHERE owner_email='$user_email'";
    $result = mysqli_query($con, $sql);

    // Fetch genres as associative array
    $genres = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $genres[] = $row;
    }

    return $genres;
}

// Function to fetch tags from the database (reusing existing connection)
function fetchTagsFromDatabase($con, $user_email) {
    // Use the existing connection
    $sql = "SELECT tag_id, tname FROM tags WHERE owner_email='$user_email'";
    $result = mysqli_query($con, $sql);

    // Fetch tags as associative array
    $tags = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $tags[] = $row;
    }

    return $tags;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ... existing validation and sanitization

    $authorName = $_POST['author']; // Use clear variable name

    // ... (other validation and sanitization)

    if (empty($bname) || empty($authorName) || empty($genres) || empty($tags)) {
        $error = '<p>Please fill in all required fields.</p>';
    } else {
        // Check if author exists, insert if not
        $sql = "SELECT author_id FROM authors WHERE aname = ? AND owner_email = ?";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $authorName, $user_email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $author_id);
        if (mysqli_stmt_fetch($stmt)) {
            // Author exists, use existing ID
        } else {
            // Author doesn't exist, insert new record
            $sql = "INSERT INTO authors (aname, owner_email) VALUES (?, ?)";
            $stmt = mysqli_prepare($con, $sql);
            mysqli_stmt_bind_param($stmt, "ss", $authorName, $user_email);
            mysqli_stmt_execute($stmt);
            $author_id = mysqli_insert_id($con);
        }
        mysqli_stmt_close($stmt);

        // Prepare and execute SQL query for book creation
        $stmt = mysqli_prepare($con, "INSERT INTO books (book_id, bname, owner_email, numofchaprd, numofchaptl, synopsis) VALUES (NULL, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sssiis", $bname, $user_email, $numofchaprd, $numofchaptl, $synopsis);
        if (mysqli_stmt_execute($stmt)) {
            $bookId = mysqli_insert_id($con); // Get the newly inserted book ID

            // Insert external URL into separate table
            $stmt = mysqli_prepare($con, "INSERT INTO books_url (url, book_id, owner_email) VALUES (?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "sis", $external_url, $bookId, $_SESSION['user_email']);
            if (mysqli_stmt_execute($stmt)) {
                // Associate genres and tags
                if (!empty($_POST['genres'])) {
                    foreach ($_POST['genres'] as $genreId) {
                        $sql = "INSERT INTO books_genres (book_id, genre_id) VALUES (?, ?)";
                        $stmt = mysqli_prepare($con, $sql);
                        mysqli_stmt_bind_param($stmt, "ii", $bookId, $genreId);
                        mysqli_stmt_execute($stmt);
                        mysqli_stmt_close($stmt);
                    }
                }
                if (!empty($_POST['tags'])) {
                    foreach ($_POST['tags'] as $tagId) {
                        $sql = "INSERT INTO books_tags (book_id, tag_id) VALUES (?, ?)";
                        $stmt = mysqli_prepare($con, $sql);
                        mysqli_stmt_bind_param($stmt, "ii", $bookId, $tagId);
                        mysqli_stmt_execute($stmt);
                        mysqli_stmt_close($stmt);
                    }
                }
                $success = '<p>Book created successfully! View your book <a href="OLMS_book_details_v1.php?book_id=' . $bookId . '">here</a>.</p>';
            } else {
                // ... handle error inserting external URL
                echo "Error inserting external URL: " . mysqli_stmt_error($stmt);
            }
            mysqli_stmt_close($stmt);
        } else {
            // ... handle book creation error
            echo "Error creating book: " . mysqli_stmt_error($stmt);
        }
        mysqli_stmt_close($stmt);
    }
}
// Retrieve error message from session
$error = isset($_SESSION['error']) ? $_SESSION['error'] : '';
unset($_SESSION['error']); // Clear the session error variable
// Check for session error


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
<div class="topnav">
        
        <a href="OLMS_owner_homepage_v1.php"><i class="fa fa-fw fa-home fa-2x"></i>Home</a>
        <a href="OLMS_my_library_v1.php">My Libraries</a>
        <a class="active" href="OLMS_my_book_v1.php">My Books</a>
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

<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<?php if (isset($success)): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
<?php endif; ?>

<form method="post">
    <div class="container">
        <label for="bname" class="form-label"><p>Book Name:</p></label>
        <input type="text" class="form-control" placeholder="Enter book name" id="bname" name="bname" required><br>
    </div><br>
    <div class="container">
        <label for="author" class="form-label"><p>Author Name:</p></label>
        <input type="text" class="form-control" placeholder="Enter author name" id="author" name="author" required><br>
    </div><br>
    <div class="container">
    <label class="form-label"><p>Genre:</p></label>
    <?php
    // Fetch and populate genres dynamically
    $genres = fetchGenresFromDatabase($con, $user_email);
    foreach ($genres as $genre) {
        echo "<div style='display: inline-block; margin-right: 10px;'>";
        echo "<p><input type='checkbox' id='{$genre['genre_id']}' name='genres[]' value='{$genre['genre_id']}'><label for='{$genre['genre_id']}'>{$genre['gname']}</label></p>";
        echo "</div>";
    }
    ?>
</div><br>
<div class="container">
    <label class="form-label"><p>Tag:</p></label>
    <?php
    // Fetch and populate tags dynamically
    $tags = fetchTagsFromDatabase($con, $user_email);
    foreach ($tags as $tag) {
        echo "<div style='display: inline-block; margin-right: 10px;'>";
        echo "<p><input type='checkbox' id='{$tag['tag_id']}' name='tags[]' value='{$tag['tag_id']}'><label for='{$tag['tag_id']}'>{$tag['tname']}</label></p>";
        echo "</div>";
    }
    ?>
</div><br>

    <div class="container">
        <label for="numofchaprd" class="form-label"><p>Number of chapters read:</p></label>
        <input type="textbox" class="form-control" placeholder="Enter number of chapters read" id="numofchaprd" name="numofchaprd" value="" required>
        <label for="numofchaptl" class="form-label"><p>Number of total chapters:</p></label>
        <input type="textbox" class="form-control" placeholder="Enter total number of chapters" id="numofchaptl" name="numofchaptl" value="" required>
           <br>
    </div><br>
    <div class="container">
        <label for="synopsis" class="form-label"><p>Synopsis:</p></label>
        <textarea class="form-control" placeholder="Enter synopsis" id="synopsis" name="synopsis" value="" cols="80" rows="20"></textarea>
           <br>
    </div><br>
    <button type="submit" class="btn btn-primary">Create book</button><br>
</form><br>
<a href="OLMS_my_book_v1.php" class="btn btn-primary">Back to My books</a><br><br>
<footer>
    <div class="container">
        <p>2024 Online Literary Management System Website</p>
    </div>
</footer>
</main>
</body>
</html>
