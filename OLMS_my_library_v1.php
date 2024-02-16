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
            <a href="#">My Books</a>
            <a href="OLMS_my_genre_v1.php">Genres</a>
            <a href="#">Tags</a>
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
  <button type="button" class="btn btn-primary" onclick="window.location.href='create_library_v1.php'">Create New Library</button>
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
      <?php
      $sno=1;
    // Prepare and execute the query to fetch libraries associated with the user
          $stmt = mysqli_prepare($con, "SELECT library_id, lname, numofbooks FROM libraries WHERE owner_email = ?");
          mysqli_stmt_bind_param($stmt, "s", $user_email);
          mysqli_stmt_execute($stmt);
          mysqli_stmt_bind_result($stmt, $library_id, $lname, $numofbooks);
            
          // Fetch and display each library
          while (mysqli_stmt_fetch($stmt)) {
              ?>
              <tr>
                  <td><p><?php echo $sno++; ?></p></td>
                  <td><p><a href="view_library_v1.php?library_id=<?php echo $library_id; ?>"><?php echo $lname; ?></a></p></td>
                  <td><p><?php echo $numofbooks; ?></p></td>
                  <td>
                      <a href="view_library_v1.php?library_id=<?php echo $library_id; ?>">View</a>
                      <a href="edit_library_v1.php?library_id=<?php echo $library_id; ?>">Edit</a>
                      <a href="delete_library_v1.php?library_id=<?php echo $library_id; ?>" onclick="return confirm('Are you sure you want to delete this library?')">Delete</a>
                  </td>
              </tr>
              <?php
          
          }?>
    </tbody>
  </table>
  <?
  mysqli_stmt_close($stmt);
  mysqli_close($con);
  ?>
  <footer>
        <div class="container">
              <p>2024 Online Literary Management System Website</p>
            </div>
    </footer>
</body>
</html>
