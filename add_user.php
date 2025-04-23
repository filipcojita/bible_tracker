<?php
include 'db.php';  // Include your database connection file

// Define user details
$username = 'Oca';
$password = 'Oca123';
$email = 'oca@example.com';  // Replace with actual email
$role = 'user';  // Default role for new users

// Hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Prepare the SQL query
$sql = "INSERT INTO users (username, password, email, role, created_at)
        VALUES (?, ?, ?, ?, NOW())";

// Prepare and execute the query
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $username, $hashed_password, $email, $role);
$stmt->execute();

// Check if the query was successful
if ($stmt->affected_rows > 0) {
    echo "User Oca added successfully!";
} else {
    echo "Failed to add user Oca.";
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
