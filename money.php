<?php
// Database connection
$host = "localhost";
$user = "root"; // Change if using a different DB user
$password = ""; // Add your DB password if any
$dbname = "charity_jet"; // Ensure this DB exists

$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['first_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $donation_type = $_POST['donation_type'];
    $comments = $_POST['comments'];
    $amount = $_POST['amount'];
    $payment_method = $_POST['payment_method'];

    $stmt = $conn->prepare("INSERT INTO donations (name, email, phone, donation_type, comments, amount, payment_method) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssis", $name, $email, $phone, $donation_type, $comments, $amount, $payment_method);

    if ($stmt->execute()) {
        $success = "✅ Thank you for your generous donation!";
    } else {
        $success = "❌ Something went wrong. Please try again.";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Donation Form</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #121212;
      color: #f1f1f1;
      margin: 0;
      padding: 0;
    }

    nav {
      background-color: #1e1e1e;
      padding: 15px 30px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      position: sticky;
      top: 0;
      z-index: 999;
    }

    nav .logo img {
      width: 180px;
    }

    .nav_links {
      display: flex;
      gap: 20px;
    }

    .nav_links a {
      color: #f1f1f1;
      text-decoration: none;
      font-weight: bold;
      transition: color 0.3s ease;
    }

    .nav_links a:hover {
      color: #00b894;
    }

    form {
      max-width: 700px;
      width: 100%;
      background: #1e1e1e;
      padding: 30px;
      margin: 40px auto;
      border-radius: 10px;
      box-shadow: 0 0 20px rgba(0,0,0,0.3);
    }

    h2 {
      text-align: center;
      margin-bottom: 20px;
      color: #ffffff;
    }

    .form-group {
      margin-bottom: 20px;
    }

    .form-row {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
    }

    .form-row .form-group {
      flex: 1;
      min-width: 200px;
    }

    label {
      display: block;
      font-weight: bold;
      margin-bottom: 5px;
      color: #ccc;
    }

    input, textarea, select {
      width: 100%;
      padding: 10px;
      border: 1px solid #555;
      border-radius: 5px;
      font-size: 14px;
      background-color: #2c2c2c;
      color: #f1f1f1;
    }

    input::placeholder, textarea::placeholder {
      color: #888;
    }

    textarea {
      resize: vertical;
    }

    .radio-group {
      display: flex;
      gap: 20px;
      margin-top: 5px;
    }

    .radio-group label {
      font-weight: normal;
    }

    .currency-input input {
      flex: 1;
    }

    .payment-methods {
      margin-top: 10px;
    }

    .payment-methods label {
      display: block;
      margin-bottom: 5px;
    }

    .submit-btn {
      text-align: center;
      margin-top: 30px;
    }

    .submit-btn button {
      padding: 12px 30px;
      background-color: #00b894;
      color: white;
      border: none;
      border-radius: 5px;
      font-size: 16px;
      cursor: pointer;
    }

    .submit-btn button:hover {
      background-color: #019875;
    }

    .note {
      font-size: 12px;
      color: #aaa;
    }

    .message {
      text-align: center;
      font-weight: bold;
      margin-bottom: 20px;
      color: #00b894;
    }

    img {
      vertical-align: middle;
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav>
  <div class="logo">
    <a href="index.html"><img src="images/logo.png" alt="Charity Jet Logo"></a>
  </div>
  <div class="nav_links">
    <a href="index.html">HOME</a>
    <a href="about.html">ABOUT</a>
    <a href="donate.php">DONATE</a>
    <a href="contact.html">CONTACT</a>
    <a href="logout.php">LOG OUT</a>
  </div>
</nav>

<!-- Donation Form -->
<form action="" method="post">
  <h2>Donation Form</h2>

  <?php if ($success): ?>
    <div class="message"><?php echo $success; ?></div>
  <?php endif; ?>

  <div class="form-row">
    <div class="form-group">
      <label>Full Name</label>
      <input type="text" name="first_name" placeholder="First Name" required>
    </div>
  </div>

  <div class="form-row">
    <div class="form-group">
      <label>E-mail *</label>
      <input type="email" name="email" placeholder="ex: myname@example.com" required>
    </div>
    <div class="form-group">
      <label>Phone Number *</label>
      <input type="tel" name="phone" placeholder="(000) 000-0000" required>
    </div>
  </div>

  <div class="form-group">
    <label>Type of Donation *</label>
    <div class="radio-group">
      <label><input type="radio" name="donation_type" value="Flood Donation" required> Flood Donation</label>
      <label><input type="radio" name="donation_type" value="Health Donation"> Health Donation</label>
      <label><input type="radio" name="donation_type" value="Education Donation"> Education Donation</label>
    </div>
  </div>

  <div class="form-group">
    <label>Comments *</label>
    <textarea name="comments" rows="4" placeholder="Write something here..." required></textarea>
  </div>

  <div class="form-group">
    <label>Donation Amount *</label>
    <input type="number" name="amount" placeholder="Enter your donation amount" required>
  </div>

  <div class="form-group">
    <label>Payment Methods</label>
    <div class="payment-methods">
      <label><input type="radio" name="payment_method" value="Card" required> 💳 Debit or Credit Card</label>
      <label><input type="radio" name="payment_method" value="PayPal">
        <img src="https://www.paypalobjects.com/webstatic/icon/pp258.png" alt="PayPal" height="16"> PayPal
      </label>
    </div>
  </div>

  <div class="submit-btn">
    <a href="https://pages.razorpay.com/pl_HuJnmgS6QySggl/view">pay</a><br>
    <button type="submit">Submit</button>
  </div>
</form>

</body>
</html>
