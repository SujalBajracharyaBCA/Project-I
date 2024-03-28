<?php
if (!empty($_POST['aname'])) {
$sql = "INSERT INTO authors (author_id,aname, owner_email) VALUES (NULL,?, ?)";
$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, "ss",$authorName, $user_email);
    if (mysqli_stmt_execute($stmt)) {
     $authorId = mysqli_insert_id($con);
     mysqli_stmt_close($stmt);
    $sql = "INSERT INTO books_authors (author_id, book_id) VALUES (?,?)";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "ii",$authorId,  $bookId);
    mysqli_stmt_execute($stmt);
    }
    else
    {
        echo "author error.";
    }
    mysqli_stmt_close($stmt);
}
     ?>