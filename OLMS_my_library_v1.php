<!DOCTYPE html>
<html>
<head>
<title>My Libraries | Online Literary Management System</title>
<link rel="stylesheet" href="bootstrap-5.2.3-dist\css\bootstrap.css">
<link rel="stylesheet" href="style_v1.css">
</head>
<body class="bg-dark text-light">
  <header>
        <h1>My Libraries | Online Literary Management System</h1>
        <nav>
            <a href="OLMS_homepage_v1.html">Home</a>
            <a href="OLMS_my_library_v1.php">My Libraries</a>
            <a href="#">My Books</a>
            <a href="#">Genres</a>
            <a href="#">Tags</a>
    </nav>
</header><br>
  <button type="button" class="btn btn-primary" onclick="window.location.href='create_library_v1.php'">Create New Library</button>
  <table class="table table-striped">
    <thead>
      <tr>
        <th><p>Library ID</p></th>
        <th><p>Name</p></th>
        <th><p>Number of books</p></th>
        <th><p>Actions</p></th>
      </tr>
    </thead>
    <tbody>
      <?php
      // Connect to the database using prepared statements
      $con = mysqli_connect('localhost', 'root', '', 'olms');
      if (!$con) {
          die("Database connection failed: " . mysqli_connect_error());
      }

      // Prepare and execute the query to fetch libraries
      $stmt = mysqli_prepare($con, "SELECT library_id, lname, numofbooks FROM libraries");
      mysqli_stmt_execute($stmt);
      mysqli_stmt_bind_result($stmt, $library_id, $lname, $numofbooks);

      // Fetch and display each library
      while (mysqli_stmt_fetch($stmt)) {
          ?>
          <tr>
              <td><p><?php echo $library_id; ?></p></td>
              <td><p><?php echo $lname; ?></p></td>
              <td><p><?php echo $numofbooks; ?></p></td>
              <td>
                  <a href="view_library_v1.php?library_id=<?php echo $library_id; ?>">View</a>
                  <a href="edit_library_v1.php?library_id=<?php echo $library_id; ?>">Edit</a>
                  <a href="delete_library_v1.php?library_id=<?php echo $library_id; ?>" onclick="return confirm('Are you sure you want to delete this library?')">Delete</a>
              </td>
          </tr>
          <?php
      }

      mysqli_stmt_close($stmt);
      mysqli_close($con);
      ?>
    </tbody>
  </table>
  <footer>
        <div class="container">
              <p>2024 Online Literary Management System Website</p>
            </div>
    </footer>
</body>
</html>