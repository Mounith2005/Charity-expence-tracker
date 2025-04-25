<?php
session_start();

// Database connection settings
$host = "localhost";
$dbUser = "root";
$dbPass = "";
$dbName = "charity_jet";

// Create connection
$conn = new mysqli($host, $dbUser, $dbPass, $dbName);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data safely
$username = trim($_POST['username']);
$password = $_POST['password'];

// Prepare and execute query
$sql = "SELECT * FROM admin_login WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();

    // Verify password
    if (password_verify($password, $row['password'])) {
        $_SESSION['admin'] = $username;
        echo "Login successful! Redirecting...";
        header("Location: adminhome.html"); // Change to your desired page
        exit();
    } else {
        echo "❌ Invalid password.";
    }
} else {
    echo "❌ Username not found.";
}

// Close everything
$stmt->close();
$conn->close();
?>
