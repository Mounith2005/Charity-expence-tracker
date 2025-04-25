<?php
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

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get and sanitize form inputs
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);

    // Prepare and execute the query to fetch user data from the database
    $sql = "SELECT id, username, password FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);  // Bind the username parameter
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // User exists, now check the password
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            // Password matches, login successful
            session_start();
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];

            // Redirect to a dashboard or a secure page
            header("Location: index1.html");  // Replace with your desired page
            exit();
        } else {
            echo "Incorrect password!";
        }
    } else {
        echo "No user found with that username!";
    }
}

// Close the connection
$conn->close();
?>
