<?php
// Connect to the database
$con = mysqli_connect('localhost', 'root', '', 'olms');
if (!$con) {
  die("Database connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $lname = $_POST['lname'];

  // Prepare SQL query using prepared statements
  $stmt = mysqli_prepare($con, "INSERT INTO libraries (library_id, lname, numofbooks) VALUES (NULL, ?, 0)");
  mysqli_stmt_bind_param($stmt, "s", $lname); // Bind the user-provided library name

  // Execute query and handle result
  if (mysqli_stmt_execute($stmt)) {
    $success = 'Library created successfully!';
  } else {
    $error = 'Error creating library: ' . mysqli_error($con);
  }

  mysqli_stmt_close($stmt);
}

mysqli_close($con);
?>



<!DOCTYPE html>
<html>
<head>
<title>Create Library | Online Literary Management System</title>
<link rel="stylesheet" href="bootstrap-5.2.3-dist\css\bootstrap.css">
<link rel="stylesheet" href="style_v1.css">
</head>
<body>

<main>
<header>
  <h1>Create Library | My Libraries | Online Literary Management System</h1>
  <nav>
    <a href="OLMS_homepage_v1.html">Home</a>
    <a href="OLMS_my_library_v1.php">My Libraries</a>
    <a href="#">My Books</a>
    <a href="#">Genres</a>
    <a href="#">Tags</a>
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
  <label for="name" class="form-label"><p>Library Name:</p></label>
    <input type="text" class="form-control" placeholder="Enter library name" id="lname" name="lname" required><br>
  </div>
  <button type="submit" class="btn btn-primary">Create Library</button><br>
</form><br>
<a href="OLMS_my_library_v1.php" class="btn btn-secondary">Back to My Libraries</a><br><br>
<footer>
  <div class="container">
    <p>2024 Online Literary Management System Website</p>
  </div>
</footer>
</main>

</body>
</html>
