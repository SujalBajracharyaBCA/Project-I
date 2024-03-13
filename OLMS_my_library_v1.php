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

    // Fetch user information from the database (optional, adjust based on your needs)
    $sql = "SELECT username FROM users WHERE email = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "s", $user_email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $username);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    // Prepare and execute the query to fetch libraries associated with the user
    $stmt = mysqli_prepare($con, "SELECT library_id, lname, numofbooks FROM libraries WHERE owner_email = ?");
    mysqli_stmt_bind_param($stmt, "s", $user_email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $library_id, $lname, $numofbooks);

    // Initialize empty array to store libraries
    $libraries = [];

    // Fetch and store each library in the array
    while (mysqli_stmt_fetch($stmt)) {
        $libraries[] = array(
            "library_id" => $library_id,
            "lname" => $lname,
            "numofbooks" => $numofbooks,
        );
    }

    mysqli_stmt_close($stmt);
} else {
    header("Location: OLMS_sign_in_v3.php"); // Redirect to login if not logged in
    exit;
}


mysqli_close($con);

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
        <a class="active" href="OLMS_my_library_v1.php"><i class="fa fa-align-justify fa-2x"></i>My Libraries</a>
            <a href="OLMS_my_book_v2.php"><i class="fa fa-book fa-2x"></i>My Books</a>
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
  <button type="button" class="btn btn-primary" onclick="window.location.href='create_library_v1.php'">Create New Library</button><br>
  <?php if (!empty($libraries)): ?>
  <table class="table table-striped" width="100%">
    <thead>
      <tr>
        <th><p>Library serial no.</p></th>
        <th><p>Library Name</p></th>
        <th><p>Number of books</p></th>
        <th><p>Actions</p></th>
      </tr>
    </thead>
    <tbody>
    <?php $sno = 1; ?>
        <?php foreach ($libraries as $library): ?> 
            <tr>
                <td><p><?php echo $sno++; ?></p></td>
                <td><p><a href="view_library_v1.php?library_id=<?php echo $library["library_id"]; ?>"><?php echo $library["lname"]; ?></a></p></td>
                <td><p><?php echo $library["numofbooks"]; ?></p></td>
                <td>
                    <a href="view_library_v1.php?library_id=<?php echo $library["library_id"]; ?>"><i class="fa fa-eye fa-2x"></i></a>
                    <a href="edit_library_v1.php?library_id=<?php echo $library["library_id"]; ?>"><i class="fa fa-edit fa-2x"></i></a>
                    <a href="delete_library_v1.php?library_id=<?php echo $library["library_id"]; ?>" onclick="return confirm('Are you sure you want to delete this library?')"><i class="fa fa-trash fa-2x"></i></a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
  </table>
  <?php else: ?>
        <p>You haven't created any libraries yet!</p>
    <?php endif; ?>
  
  <footer>
        <div class="container">
              <p>2024 Online Literary Management System Website</p>
            </div>
    </footer>
</body>
</html>
