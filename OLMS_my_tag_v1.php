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

// Prepare and execute the query to fetch tags associated with the user
$stmt = mysqli_prepare($con, "SELECT tag_id, tname FROM tags WHERE owner_email = ?");
mysqli_stmt_bind_param($stmt, "s", $user_email);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $tag_id, $tname);

// Initialize empty array to store tags
$tags = [];

// Fetch and store each tag in the array
while (mysqli_stmt_fetch($stmt)) {
    $tags[] = array("tag_id" => $tag_id, "tname" => $tname);
}

mysqli_stmt_close($stmt);
mysqli_close($con);

?>

<!DOCTYPE html>
<html>
<head>
    <title>My tags | Online Literary Management System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="style_v1.css">
</head>
<body class="bg-dark text-light">
    <header>
    <h1>My tag | Online Literary Management System</h1>
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
            </div>
    </nav>
        </header><br>

    <button type="button" class="btn btn-primary" onclick="window.location.href='create_tag_v1.php'">Create New tag</button>

    <?php if (!empty($tags)): ?>
        <table class="table table-striped" width="100%">
            <thead>
                <tr>
                    <th><p>Tag serial no.</p></th>
                    <th><p>Tag Name</p></th>
                    <th><p>Actions</p></th>
                </tr>
            </thead>
            <tbody>
                <?php $sno=1; ?>
                <?php foreach ($tags as $tag): ?>
                    <tr>
                        <td><p><?php echo $sno++; ?></p></td>
                        <td><p><?php echo $tag["tname"]; ?></p></td>
                        <td>
                            <a href="edit_tag_v1.php?tag_id=<?php echo $tag["tag_id"]; ?>">Edit</a>
                            <a href="delete_tag_v1.php?tag_id=<?php echo $tag["tag_id"]; ?>" onclick="return confirm('Are you sure you want to delete this tag?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>You haven't created any tags yet!</p>
    <?php endif; ?>

    <footer>
    
        <div class="container">
              <p>2024 Online Literary Management System Website</p>
            </div>
    </footer>
       
</body>
</html>
