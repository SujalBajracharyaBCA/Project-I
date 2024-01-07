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
        <th><p>Name</p></th>
        <th><p>Actions</p></th>
      </tr>
    </thead>
    <tbody>
      <?php
      // Connect to the database and retrieve library data (replace with your actual code)
      //$libraries = fetch_libraries_from_database();
      //foreach ($libraries as $library) 
      {
        ?>
        <tr>
          <td><?//php echo $library['name']; ?></td>
          <td>
            <a href="view_library_v1.php?id=<?//php echo $library['id']; ?>">View</a>
            <a href="edit_library_v1.php?id=<?//php echo $library['id']; ?>">Edit</a>
            <a href="delete_library_v1.php?id=<?//php echo $library['id']; ?>" 
            onclick="return confirm('Are you sure you want to delete this library?')">Delete</a>
          </td>
        </tr>
        <?php
      }
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
