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
// Retrieve library ID
$libraryId = $_GET['library_id'];


// Retrieve library details from the database (ensure query execution)
$stmt = mysqli_prepare($con, "SELECT library_id, lname, numofbooks FROM libraries WHERE library_id = ? AND owner_email = ?");
mysqli_stmt_bind_param($stmt, "is", $libraryId, $user_email); // Bind both library ID and email

if (!mysqli_stmt_execute($stmt)) {
  echo "Error preparing statement: " . mysqli_error($con);
} else {
  mysqli_stmt_bind_result($stmt, $library_id, $lname, $numofbooks);
  mysqli_stmt_fetch($stmt);
  mysqli_stmt_close($stmt);
}

// Check if library ID is actually retrieved
if (!$libraryId) {
  $error = 'Library not found.';
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Process library editing request

  // Validate user input (replace with your validation logic)
  if (empty($_POST['lname'])) {
    $error = 'Please enter a name for the library.';
  } else {
    $stmt = mysqli_prepare($con, "UPDATE libraries SET lname = ? WHERE library_id = ?");
    mysqli_stmt_bind_param($stmt, "si", $_POST['lname'], $libraryId); // Use submitted name

    // Check for connection or query errors
    if (!mysqli_stmt_execute($stmt)) {
      echo "Error updating library: " . mysqli_error($con);
    } else {
      $success = 'Library updated successfully!';
      header('Location: OLMS_my_library_v1.php');
      exit;
    }
  }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Library | Online Literary Management System</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="style_v1.css">
</head>
<body>
<main>
<header>
        <h1> Edit Library | My Libraries | Online Literary Management System</h1>
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

  <h1>Edit Library</h1>
  <?php if (isset($error)): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
  <?php endif; ?>
  <?php if (isset($success)): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
  <?php endif; ?>


  <form method="post">
  <div class="container">
    <label for="name" class="form-label"><p>Library Name</p></label>
    <input type="text" class="form-control" id="lname" name="lname" placeholder="Library Name" value="<?php echo $lname; ?>" required>
  </div>
  <br>
  <button type="submit" class="btn btn-primary">Update Library</button><br><br>
</form>
<br>
 
  <a href="OLMS_my_library_v1.php" class="btn btn-primary">Back to My Libraries</a><br><br>
</main>
<footer>
        <div class="container">
              <p>2024 Online Literary Management System Website</p>
            </div>
    </footer>
</body>
</html>