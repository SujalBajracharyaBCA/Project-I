<?php
session_start();

// Check if the user is logged in
if (isset($_SESSION['user_email'])) {
  // Destroy the session to log out the user
  session_destroy();

  // Redirect the user to the owner homepage after logout
  header("Location: OLMS_homepage_v1.html");
  exit;
} else {
  // User is already not logged in, redirect to login or display a message
  header("Location: sign_in_v3.php");
  exit;
}
?>
