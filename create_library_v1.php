<?php
// Connect to the database
// (Replace with your actual database connection logic)

/*if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Process library creation request

  // Validate user input (replace with your validation logic)
  if (empty($_POST['name'])) {
    $error = 'Please enter a name for the library.';
  } else {
    // Prepare SQL query to insert library into the database
    $query = "INSERT INTO libraries (name) VALUES (:name)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':name', $_POST['name']);

    // Execute query and handle result
    if ($stmt->execute()) {
      $success = 'Library created successfully!';
      // Redirect to the libraries page
      header('Location: my_library.php');
      exit;
    } else {
      $error = 'Error creating library: ' . $stmt->errorInfo()[2];
    }
  }
}*/
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
      <input type="text" class="form-control" placeholder="Enter library name" id="name" name="name" required><br>
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
