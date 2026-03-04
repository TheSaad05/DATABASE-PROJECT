<?php
session_start();

// Check if customer is logged in
if (!isset($_SESSION['customer_email'])) {
    header("Location: customer_login.php");
    exit();
}

// Database connection details
$servername = "localhost";
$root_username = "root"; // Root user
$root_password = "database"; // Root password
$dbname = "QureshiTrips";

// Connect to MySQL
$conn = new mysqli($servername, $root_username, $root_password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch customer details from CustomerView based on logged-in customer email
$customer_sql = "SELECT * FROM CustomerView WHERE CustomerEmail = ?";
$stmt = $conn->prepare($customer_sql);
$stmt->bind_param("s", $_SESSION['customer_email']);
$stmt->execute();
$customer_result = $stmt->get_result();

// Check if customer has bookings
$bookings = [];
if ($customer_result->num_rows > 0) {
    while ($row = $customer_result->fetch_assoc()) {
        $bookings[] = $row;
    }
} else {
    $error_message = "You have no bookings yet.";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        h2 {
            color: #444;
        }
        .booking-item {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin: 15px 0;
            background-color: #fff;
        }
        .booking-item h3 {
            color: #007bff;
        }
        .booking-description {
            color: #555;
            font-size: 1rem;
        }
        .booking-status {
            font-size: 1.2rem;
            color: #333;
            margin-top: 10px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 15px;
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
        .error-message {
            text-align: center;
            color: red;
            font-size: 1.2rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome, <?php echo $_SESSION['customer_email']; ?>!</h1>
        <a href="customer_profile.php" class="btn">View Profile</a>
        <a href="customer_logout.php" class="btn">Logout</a>

        <!-- Book Services Button -->
        <a href="book_service.php" class="btn">Book a Service</a>

        <h2>Your Bookings</h2>
        <?php if (!empty($bookings)): ?>
            <?php foreach ($bookings as $booking): ?>
                <div class="booking-item">
                    <h3>Service: <?php echo htmlspecialchars($booking['ServiceType']); ?></h3>
                    <p class="booking-description"><?php echo htmlspecialchars($booking['ServiceDescription']); ?></p>
                    <p class="booking-status">Booking Date: <?php echo htmlspecialchars($booking['BookingDate']); ?></p>
                    <p class="booking-status">Booking Status: <?php echo htmlspecialchars($booking['BookingStatus']); ?></p>
                    <p class="booking-status">Total Price: Rs. <?php echo htmlspecialchars($booking['TotalPrice']); ?></p>
                    <p class="booking-status">Payment Status: <?php echo htmlspecialchars($booking['PaymentStatus']); ?></p>
                    <p class="booking-status">Payment Amount: Rs. <?php echo htmlspecialchars($booking['PaymentAmount']); ?></p>
                    <p class="booking-status">Payment Date: <?php echo htmlspecialchars($booking['PaymentDate']); ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="error-message"><?php echo $error_message; ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
