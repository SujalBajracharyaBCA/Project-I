<?php
     // Prepare and execute SQL query for book creation
     $stmt = mysqli_prepare($con, "INSERT INTO books (book_id, bname, owner_email, numofchaprd, numofchaptl, synopsis) VALUES (NULL, ?, ?, ?, ?, ?)");
     mysqli_stmt_bind_param($stmt, "ssiis", $bname, $user_email, $numofchaprd, $numofchaptl, $synopsis);
     if (mysqli_stmt_execute($stmt)) {
         $bookId = mysqli_insert_id($con);
        
        }
        else
        {
            echo "book error.";
        }
        mysqli_stmt_close($stmt);
?>