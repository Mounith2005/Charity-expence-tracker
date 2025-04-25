<?php
// Enable errors for debugging (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$conn = new mysqli("localhost", "root", "", "charity_jet");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $date = $_POST['date'];
    $category = $_POST['category'];
    $amount = floatval($_POST['amount']);
    $description = $_POST['description'];

    // Start a transaction
    $conn->begin_transaction();

    try {
        // Get current balance from balance_info table
        $result = $conn->query("SELECT current_balance FROM balance_info WHERE id = 1 LIMIT 1");

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $currentBalance = floatval($row['current_balance']);

            // Check if balance is sufficient
            if ($amount <= $currentBalance) {
                // Deduct amount from the balance
                $newBalance = $currentBalance - $amount;
                
                // Update the balance in the balance_info table
                $stmtUpdate = $conn->prepare("UPDATE balance_info SET current_balance = ? WHERE id = 1");
                $stmtUpdate->bind_param("d", $newBalance);
                $stmtUpdate->execute();

                // Insert into expenses table
                $stmtInsert = $conn->prepare("INSERT INTO expenses (date, category, amount, description) VALUES (?, ?, ?, ?)");
                $stmtInsert->bind_param("ssds", $date, $category, $amount, $description);
                $stmtInsert->execute();

                // Commit the transaction
                $conn->commit();

                echo "<script>alert('Expense recorded successfully.'); window.location.href='adminhome.html';</script>";
            } else {
                echo "<script>alert('Insufficient balance!'); window.history.back();</script>";
            }
        } else {
            echo "<script>alert('No balance record found.'); window.history.back();</script>";
        }
    } catch (Exception $e) {
        // Rollback the transaction if something fails
        $conn->rollback();

        echo "<script>alert('An error occurred: " . $e->getMessage() . "'); window.history.back();</script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Record Expense</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 50%;
            margin: 30px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
        }

        label {
            display: block;
            margin-top: 10px;
            font-size: 16px;
        }

        input, textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .back-link {
            margin-top: 20px;
            display: block;
            text-align: center;
            color: #4CAF50;
            font-weight: bold;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Record Expense</h2>

    <form action="expense.php" method="POST">
        <label for="date">Date:</label>
        <input type="date" name="date" required><br>

        <label for="category">Category:</label>
        <input type="text" name="category" required><br>

        <label for="amount">Amount (₹):</label>
        <input type="number" name="amount" step="0.01" required><br>

        <label for="description">Description:</label>
        <textarea name="description" required></textarea><br>

        <input type="submit" value="Submit Expense">
    </form>

    <a href="adminhome.html" class="back-link">Back to Dashboard</a>
</div>

</body>
</html>
