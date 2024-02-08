<?php
// Check if library ID is provided in the query string
if (isset($_GET['library_id'])) {
    // Retrieve library ID
    $libraryId = $_GET['library_id'];

    // Connect to the database
    $con = mysqli_connect('localhost', 'root', '', 'olms');
    if (!$con) {
        die("Database connection failed: " . mysqli_connect_error());
    }

    // Delete associated records from books_libraries table
    $deleteBooksQuery = "DELETE FROM books_libraries WHERE library_id = '$libraryId'";
    if (!mysqli_query($con, $deleteBooksQuery)) {
        echo "Error deleting associated books from books_libraries table: " . mysqli_error($con);
        exit;
    }

    // Prepare and execute query to delete library from the database
    $deleteLibraryQuery = "DELETE FROM libraries WHERE library_id = '$libraryId'";
    if (mysqli_query($con, $deleteLibraryQuery)) {
        // Redirect to the "My Libraries" page after deletion
        header("Location: OLMS_my_library_v1.php");
        exit;
    } else {
        // Handle database error
        echo "Error deleting library: " . mysqli_error($con);
    }

    // Close database connection
    mysqli_close($con);
}
?>
