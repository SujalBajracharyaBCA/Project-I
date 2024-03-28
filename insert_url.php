<?php
if (!empty($_POST['url'])) {
// Insert external URL into separate table
$stmt = mysqli_prepare($con, "INSERT INTO books_urls (url, book_id, owner_email) VALUES (?, ?, ?)");
mysqli_stmt_bind_param($stmt, "sis", $external_url, $bookId, $user_email);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);
}
else
        {
            echo "url error.";
        }
?>