<?php
// Check for tag ID in the query string
if (isset($_GET['tag_id'])) {
    // Connect to the database
    $con = mysqli_connect('localhost', 'root', '', 'olms');
    if (!$con) {
        die("Database connection failed: " . mysqli_connect_error());
    }

    // Retrieve tag ID
    $tagId = $_GET['tag_id'];

    // Check for dependencies (e.g., associated books)
    $checkBooksQuery = "SELECT COUNT(*) AS num_books FROM books_tags WHERE tag_id = '$tagId'";
    $checkBooksResult = mysqli_query($con, $checkBooksQuery);
    $checkBooksRow = mysqli_fetch_assoc($checkBooksResult);
    if ($checkBooksRow['num_books'] > 0) {
        // tag has associated books, cannot delete directly
        echo "Error: Cannot delete tag because it has associated books. Please remove those books first.";
    } else {
        // Proceed with deletion
        $deletetagQuery = "DELETE FROM tags WHERE tag_id = '$tagId'";
        if (mysqli_query($con, $deletetagQuery)) {
            // Redirect to My tags page on success
            header("Location: OLMS_my_tag_v1.php");
            exit;
        } else {
            // Display error message on failure
            echo "Error deleting tag: " . mysqli_error($con);
        }
    }

    // Close database connection
    mysqli_close($con);
}
?>
