<?php
if (!empty($_POST['tags'])) {
                    foreach ($_POST['tags'] as $tagId) {
                        $sql = "INSERT INTO books_tags (book_id, tag_id) VALUES (?, ?)";
                        $stmt = mysqli_prepare($con, $sql);
                        mysqli_stmt_bind_param($stmt, "ii", $bookId, $tagId);
                        mysqli_stmt_execute($stmt);
                        mysqli_stmt_close($stmt);
                    }
                }
                else
        {
            echo "tag error.";
        }
                ?>