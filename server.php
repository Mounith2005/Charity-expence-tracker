<?php
// Start the session to access session variables
session_start();

// Database configuration
$servername = "localhost";  // Your database server (e.g., 'localhost')
$username = "root";         // Your MySQL username
$password = "";             // Your MySQL password
$dbname = "charity_jet";    // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user is logged in
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query to check if user exists
    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $_SESSION['username'] = $username;
    } else {
        echo "Invalid credentials!";
    }
}
?>
