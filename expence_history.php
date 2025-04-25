<?php
session_start();

// Check if the user is logged in as admin
if (!isset($_SESSION['admin'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

// Database connection
$host = "localhost";
$dbUser = "root";
$dbPass = "";
$dbName = "charity_jet";

$conn = new mysqli($host, $dbUser, $dbPass, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all donations
$sql = "SELECT * FROM donations ORDER BY donated_at ASC";
$result = $conn->query($sql);

// Fetch total donations
$totalSql = "SELECT SUM(amount) AS total_amount FROM donations";
$totalResult = $conn->query($totalSql);
$totalRow = $totalResult->fetch_assoc();
$totalAmount = $totalRow['total_amount'] ?? 0;

// Fetch all expenses
$expenseSql = "SELECT * FROM expenses ORDER BY date ASC";
$expenseResult = $conn->query($expenseSql);

// Check if the query returned a valid result
if (!$expenseResult) {
    die("Error executing query: " . $conn->error);
}

// Fetch total expenses
$expenseTotalSql = "SELECT SUM(amount) AS total_expenses FROM expenses";
$expenseTotalResult = $conn->query($expenseTotalSql);
$expenseRow = $expenseTotalResult->fetch_assoc();
$totalExpenses = $expenseRow['total_expenses'] ?? 0;

// Calculate available balance
$balance = $totalAmount - $totalExpenses;

// Close extra queries
$totalResult->close();
$expenseTotalResult->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donation and Expense History</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color:rgb(168, 225, 239);
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 30px auto;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ccc;
            text-align: center;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        .summary {
            margin-top: 30px;
            font-size: 18px;
        }

        .summary p {
            margin: 5px 0;
        }

        a {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #4CAF50;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Donation and Expense History</h2>

        
        <h3>Expense History</h3>
        <table>
            <tr>
                
                <th>Expense Category</th>
                <th>Amount (₹)</th>
                <th>Date</th>
            </tr>
            <?php
            if ($expenseResult->num_rows > 0) {
                while ($row = $expenseResult->fetch_assoc()) {
                    echo "<tr>
                        
                        <td>" . htmlspecialchars($row['category']) . "</td>
                        <td>" . number_format($row['amount'], 2) . "</td>
                        <td>" . htmlspecialchars($row['date']) . "</td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No expense records found.</td></tr>";
            }
            ?>
        </table>

        <!-- Summary -->
        <div class="summary">
            <p><strong>Total Donations:</strong> ₹<?php echo number_format($totalAmount, 2); ?></p>
            <p><strong>Total Expenses:</strong> ₹<?php echo number_format($totalExpenses, 2); ?></p>
            <p><strong>Available Balance:</strong> ₹<?php echo number_format($balance, 2); ?></p>
        </div>

        <a href="adminhome.html">← Back to Dashboard</a>
    </div>
</body>
</html>
