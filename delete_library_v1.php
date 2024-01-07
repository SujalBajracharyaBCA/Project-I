<?php
// Connect to the database
// (Replace with your actual database connection logic)

// Retrieve library ID from the query string
/*$libraryId = $_GET['id'];

// Prepare SQL query to delete library from the database
$query = "DELETE FROM libraries WHERE id = :id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':id', $libraryId);

// Execute query and handle result
if ($stmt->execute()) {
  $success = 'Library deleted successfully!';
  // Redirect to the libraries page
  header('Location: my_libraries.php');
  exit;
} else {
  $error = 'Error deleting library: ' . $stmt->errorInfo()[2];
}*/
?>

<!DOCTYPE html>
<html>
<head>
<title>Delete Library | Online Literary Management System</title>
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
  <h1>Delete Library</h1>

  <?php/* if (isset($error)): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
  <?php endif; ?>

  <?php if (isset($success)): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
  <?php endif; */?>

  <p>Are you sure you want to delete this library? This action cannot be undone.</p>

  <a href="OLMS_my_library_v1.php" class="btn btn-secondary">Cancel</a>
  <a href="delete_library_v1.php?id=<?php echo $libraryId; ?>&confirm=1" class="btn btn-danger">Delete Library</a><br><br>
</main>
<a href="OLMS_my_library_v1.php" class="btn btn-secondary">Back to My Libraries</a><br><br>
<footer>
        <div class="container">
              <p>2024 Online Literary Management System Website</p>
            </div>
    </footer>


</body>
</html>
