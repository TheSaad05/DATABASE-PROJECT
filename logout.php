<?php
session_start();

// Check if customer is logged in
if (!isset($_SESSION['customer_email'])) {
    header("Location: customer_login.php");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "database";
$dbname = "QureshiTrips";
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get service ID from URL
if (!isset($_GET['service_id'])) {
    die("Service not specified.");
}

$service_id = intval($_GET['service_id']);

// Fetch service details
$sql = "SELECT * FROM Service WHERE ServiceID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $service_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Service not found.");
}

$service = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_email = $_SESSION['customer_email'];
    $payment_amount = $service['Price'];
    $payment_date = date('Y-m-d H:i:s');
    $payment_status = 'Completed';
    $transaction_address = $_POST['transaction_address'];
    $payment_method = $_POST['payment_method'];
    $wallet_number = $_POST['wallet_number'];

    // Validate input
    if (empty($transaction_address) || empty($payment_method) || empty($wallet_number)) {
        echo "All fields are required.";
        exit();
    }

    // Get customer ID
    $customer_sql = "SELECT CustomerID FROM Customer WHERE Email = ?";
    $customer_stmt = $conn->prepare($customer_sql);
    $customer_stmt->bind_param("s", $customer_email);
    $customer_stmt->execute();
    $customer_result = $customer_stmt->get_result();

    if ($customer_result->num_rows == 0) {
        die("Customer not found.");
    }

    $customer = $customer_result->fetch_assoc();
    $customer_id = $customer['CustomerID'];

    // Insert booking record
    $booking_status = 'Confirmed';
    $refund_status = 'Not Applicable';
    $booking_sql = "INSERT INTO Booking (CustomerID, ServiceID, BookingDate, Status, TotalPrice, RefundStatus)
                    VALUES (?, ?, ?, ?, ?, ?)";
    $booking_stmt = $conn->prepare($booking_sql);
    $booking_stmt->bind_param("iissds", $customer_id, $service_id, $payment_date, $booking_status, $payment_amount, $refund_status);
    $booking_stmt->execute();
    $booking_id = $conn->insert_id;

    // Insert transaction record
    $transaction_sql = "INSERT INTO Transaction (BookingID, PaymentAmount, PaymentDate, PaymentStatus, TransactionAddress, PaymentMethod, WalletNumber)
                        VALUES (?, ?, ?, ?, ?, ?, ?)";
    $transaction_stmt = $conn->prepare($transaction_sql);
    $transaction_stmt->bind_param("idsssss", $booking_id, $payment_amount, $payment_date, $payment_status, $transaction_address, $payment_method, $wallet_number);
    $transaction_stmt->execute();

    echo "Transaction successful!";
    header("Location: customer_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #218838;
        }
        input, textarea, select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        label {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Transaction for <?php echo htmlspecialchars($service['ServiceType']); ?></h1>
        <p>Description: <?php echo htmlspecialchars($service['Description']); ?></p>
        <p>Price: Rs. <?php echo htmlspecialchars($service['Price']); ?></p>
        <form method="POST">
            <label for="transaction_address">Transaction Address:</label>
            <textarea id="transaction_address" name="transaction_address" placeholder="Enter your address here..." required></textarea>
            
            <label for="payment_method">Payment Method:</label>
            <select id="payment_method" name="payment_method" required>
                <option value="">Select Payment Method</option>
                <option value="JazzCash">JazzCash</option>
                <option value="EasyPaisa">EasyPaisa</option>
            </select>
            
            <label for="wallet_number">Wallet/Mobile Number:</label>
            <input type="text" id="wallet_number" name="wallet_number" placeholder="Enter your wallet/mobile number" required>

            <input type="submit" class="btn" value="Confirm Payment">
        </form>
    </div>
</body>
</html>
