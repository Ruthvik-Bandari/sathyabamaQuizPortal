<?php
// Database Configuration
$servername = "localhost"; // Hostname of the server where the database is hosted
$username = "root"; // Default XAMPP username
$password = ""; // Default XAMPP password is empty
$dbname = "student_portal"; // Name of the database

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Database connected successfully";
}
?>