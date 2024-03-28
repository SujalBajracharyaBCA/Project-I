   <?php

                if (!empty($_POST['genres'])) {
                    foreach ($_POST['genres'] as $genreId) {
                        $sql = "INSERT INTO books_genres (book_id, genre_id) VALUES (?, ?)";
                        $stmt = mysqli_prepare($con, $sql);
                        mysqli_stmt_bind_param($stmt, "ii", $bookId, $genreId);
                        mysqli_stmt_execute($stmt);
                        mysqli_stmt_close($stmt);
                    }
                }
                else
        {
            echo "genre error.";
        }
                ?>