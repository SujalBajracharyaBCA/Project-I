<?php
// Connect to the database
// (Replace with your actual database connection logic)

// Retrieve library ID from the query string
/*$libraryId = $_GET['id'];

// Retrieve library details from the database
$query = "SELECT * FROM libraries WHERE id = :id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':id', $libraryId);
$stmt->execute();
$library = $stmt->fetch();

// If library not found, display error message
if (!$library) {
  $error = 'Library not found.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Process library editing request

  // Validate user input (replace with your validation logic)
  if (empty($_POST['name'])) {
    $error = 'Please enter a name for the library.';
  } else {
    // Prepare SQL query to update library information in the database
    $query = "UPDATE libraries SET name = :name WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':name', $_POST['name']);
    $stmt->bindParam(':id', $libraryId);

    // Execute query and handle result
    if ($stmt->execute()) {
      $success = 'Library updated successfully!';
      // Redirect to the library's view page
      header('Location: view_library.php?id=' . $libraryId);
      exit;
    } else {
      $error = 'Error updating library: ' . $stmt->errorInfo()[2];
    }
  }
}*/
?>
<!DOCTYPE html>
<html>
<head>
<title>Edit Library | Online Literary Management System</title>
<link rel="stylesheet" href="bootstrap-5.2.3-dist\css\bootstrap.css">
<link rel="stylesheet" href="style_v1.css">
</head>
<body>
<header>
        <h1>Online Literary Management System</h1>
        <nav>
            <a href="OLMS_homepage_v1.html">Home</a>
            <a href="OLMS_my_library_v1.php">My Libraries</a>
            <a href="#">My Books</a>
            <a href="#">Genres</a>
            <a href="#">Tags</a>
    </nav>
</header><br>
<main>
  <h1>Edit Library</h1>
  <?php// if (isset($error)): ?>
    <div class="alert alert-danger"><?php// echo $error; ?></div>
  <?php// endif; ?>
  <?php// if (isset($success)): ?>
    <div class="alert alert-success"><?php// echo $success; ?></div>
  <?php// endif; ?>

  <?php// if ($library): ?>
    <form method="post">
      <div class="mb-3">
        <label for="name" class="form-label"><p>Library Name</p></label>
        <input type="text" class="form-control" id="name" name="name" placeholder="Library Name" value="<?php// echo $library['name']; ?>" required>
      </div>
      <button type="submit" class="btn btn-primary">Update Library</button><br><br>
    </form>
  <?php// endif; ?>
  <a href="OLMS_my_library_v1.php" class="btn btn-secondary">Back to My Libraries</a><br><br>
</main>
<footer>
        <div class="container">
              <p>2024 Online Literary Management System Website</p>
            </div>
    </footer>
</body>
</html>
