<?php
// Basic validation for demonstration purposes
if (empty($_POST['email']) || empty($_POST['password'])) {
    echo "Please fill in all fields.";
    exit;
}

// Simulate a login attempt (replace with actual authentication logic)
if ($_POST['email'] === "sujalbajracharya124@gmail.com" && $_POST['password'] === "password123") {
    echo "Login successful!";
} else {
    echo "Invalid email or password.";
}
?>
