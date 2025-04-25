<?php
session_start();

// Check if the user is logged in as admin
if (!isset($_SESSION['admin'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

// Database connection settings
$host = "localhost";
$dbUser = "root";        // Default username for XAMPP
$dbPass = "";            // Default password for XAMPP (empty)
$dbName = "charity_jet"; // Your database name

// Create connection
$conn = new mysqli($host, $dbUser, $dbPass, $dbName);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all donations from the table
$sql = "SELECT * FROM donations ORDER BY donated_at ASC";  // Ensure table name is correct
$result = $conn->query($sql);

// Check for query execution error
if (!$result) {
    die("Query failed: " . $conn->error); // Output the error message
}

// Fetch total donation amount
$totalSql = "SELECT SUM(amount) AS total_amount FROM donations";
$totalResult = $conn->query($totalSql);
$totalRow = $totalResult->fetch_assoc();
$totalAmount = $totalRow['total_amount'] ?? 0;  // If no donations, default to 0

// Update the balance_info table with the total donation amount
$updateBalanceSql = "UPDATE balance_info SET total_donations = ?, current_balance = ? WHERE id = 1";

// Get the current record in the balance_info table
$balanceResult = $conn->query("SELECT total_donations FROM balance_info WHERE id = 1");
if ($balanceResult->num_rows > 0) {
    // Update total_donations and current_balance (assuming balance equals total donations for now)
    $stmt = $conn->prepare($updateBalanceSql);
    $stmt->bind_param("dd", $totalAmount, $totalAmount); // Set both total_donations and current_balance to totalAmount
    $stmt->execute();
}

// Close the total query result
$totalResult->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Donation List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color:rgb(210, 202, 202);
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            background-color:rgb(205, 242, 192);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: center;
        }

        th {
            background-color: #5c6bc0;
            color: white;
        }

        td {
            background-color: #f9f9f9;
        }

        a {
            color: #5c6bc0;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .total-amount {
            font-size: 18px;
            font-weight: bold;
            margin-top: 20px;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Donation List</h2>
        <table>
            <thead>
                <tr>
                    
                    <th>Donor Name</th>
                    <th>Amount Donated</th>
                    <th>Donation Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Display each donation
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                            
                            <td>" . htmlspecialchars($row['name']) . "</td>
                            <td>₹" . number_format($row['amount'], 2) . "</td>
                            <td>" . htmlspecialchars($row['donated_at']) . "</td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No donations found</td></tr>";
                }

                // Close the database connection
                $conn->close();
                ?>
            </tbody>
        </table>

        <div class="total-amount">
            <p>Total Donations: ₹<?php echo number_format($totalAmount, 2); ?></p>
        </div>

        <br>
        <a href="adminhome.html">Back to Dashboard</a>
    </div>

</body>
</html>
