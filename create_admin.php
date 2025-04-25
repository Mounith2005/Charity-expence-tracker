<?php
// Database connection settings (using default XAMPP credentials)
$host = "localhost";
$dbUser = "root";  // Default username for XAMPP
$dbPass = "";      // Default password for XAMPP (empty)
$dbName = "charity_jet"; // Ensure this matches your actual database name

// Create connection
$conn = new mysqli($host, $dbUser, $dbPass, $dbName);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Your admin insertion code here (same as before)
$username = 'mounith';
$plainPassword = 'mounith2005';
$hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);

$sql = "INSERT INTO admin_login (username, password) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $username, $hashedPassword);
$stmt->execute();

echo "Admin added securely.";

$stmt->close();
$conn->close();
?>
