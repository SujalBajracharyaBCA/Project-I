<?php
// Basic validation for demonstration purposes
if (empty($_POST['email']) || empty($_POST['password'])) {
    echo "Please fill in all fields.";
    exit;
}

// Validate email format (replace with more robust validation if needed)
if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    echo "Invalid email format.";
    exit;
}

// Proceed with account creation logic (e.g., store user information in a database)
echo "Account created successfully!";
?>
