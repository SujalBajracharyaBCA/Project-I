<?php
session_start();

// Connect to the database
$con = mysqli_connect('localhost', 'root', '', 'olms');
if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Check if the user is logged in
if (isset($_SESSION['user_email'])) {
    $user_email = $_SESSION['user_email'];
} else {
    // Redirect to login page if not logged in
    header("Location: OLMS_sign_in_v3.php");
    exit;
}

// Retrieve tag ID
$tagId = $_GET['tag_id'];

// Retrieve tag details from the database
$stmt = mysqli_prepare($con, "SELECT tag_id, tname FROM tags WHERE tag_id = ? AND owner_email = ?");
mysqli_stmt_bind_param($stmt, "is", $tagId, $user_email);

if (!mysqli_stmt_execute($stmt)) {
    echo "Error preparing statement: " . mysqli_error($con);
} else {
    mysqli_stmt_bind_result($stmt, $tag_id, $tname);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
}

if (!$tagId) {
    $error = 'tag not found.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process tag editing request

    // Validate user input (replace with your validation logic)
    if (empty($_POST['tname'])) {
        $error = 'Please enter a name for the tag.';
    } else {
        $stmt = mysqli_prepare($con, "UPDATE tags SET tname = ? WHERE tag_id = ?");
        mysqli_stmt_bind_param($stmt, "si", $_POST['tname'], $tagId);

        if (!mysqli_stmt_execute($stmt)) {
            echo "Error updating tag: " . mysqli_error($con);
        } else {
            $success = 'tag updated successfully!';
            header('Location: OLMS_my_tag_v1.php');
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit tag | My tags | Online Literary Management System</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="style_v1.css">
</head>
<body>
    <main>
    <header>
        <h1> Edit tag | My tag | Online Literary Management System</h1>
           <nav>
            <a href="OLMS_owner_homepage_v1.php">Home</a>
            <a href="OLMS_my_library_v1.php">My Libraries</a>
            <a href="OLMS_my_book_v1.php">My Books</a>
            <a href="OLMS_my_genre_v1.php">My Genres</a>
            <a href="OLMS_my_tag_v1.php">My Tags</a>
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
    </nav>
    </header><br>
    <h1>Edit tag</h1>
<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>
<?php if (isset($success)): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
<?php endif; ?>

<form method="post">
<div class="container">
    <label for="tname" class="form-label"><p>Tag Name</p></label>
    <input type="text" class="form-control" id="tname" name="tname" placeholder="tag Name" value="<?php echo $tname; ?>" required>
</div>
<br>
    <button type="submit" class="btn btn-primary">Update Tag</button><br><br>
</form>
<br>
<a href="OLMS_my_tag_v1.php" class="btn btn-primary">Back to My Tags</a><br><br>
</main>
<footer>
        <div class="container">
              <p>2024 Online Literary Management System Website</p>
            </div>
    </footer>
</body>
</html>
