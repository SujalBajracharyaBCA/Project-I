<?php
// Check for genre ID in the query string
if (isset($_GET['genre_id'])) {
    // Connect to the database
    $con = mysqli_connect('localhost', 'root', '', 'olms');
    if (!$con) {
        die("Database connection failed: " . mysqli_connect_error());
    }

    // Retrieve genre ID
    $genreId = $_GET['genre_id'];

    // Check for dependencies (e.g., associated books)
    $checkBooksQuery = "SELECT COUNT(*) AS num_books FROM books_genres WHERE genre_id = '$genreId'";
    $checkBooksResult = mysqli_query($con, $checkBooksQuery);
    $checkBooksRow = mysqli_fetch_assoc($checkBooksResult);
    if ($checkBooksRow['num_books'] > 0) {
        // Genre has associated books, cannot delete directly
        echo "Error: Cannot delete genre because it has associated books. Please remove those books first.";
    } else {
        // Proceed with deletion
        $deleteGenreQuery = "DELETE FROM genres WHERE genre_id = '$genreId'";
        if (mysqli_query($con, $deleteGenreQuery)) {
            // Redirect to My Genres page on success
            header("Location: OLMS_my_genre_v1.php");
            exit;
        } else {
            // Display error message on failure
            echo "Error deleting genre: " . mysqli_error($con);
        }
    }

    // Close database connection
    mysqli_close($con);
}
?>
