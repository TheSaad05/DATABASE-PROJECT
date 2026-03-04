<?php
// Start session and enable error reporting
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection
$host = "localhost"; // Update with your host
$username = "root";  // Update with your username
$password = "database";      // Update with your password
$database = "QureshiTrips"; // Update with your database name

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if business owner is logged in
if (!isset($_SESSION['business_owner_id'])) {
    echo "<h2>Please log in to view your dashboard.</h2>";
    exit;
}

$business_owner_id = $_SESSION['business_owner_id'];

// Fetch data from BusinessOwnerView for the logged-in business owner
$sql = "
    SELECT BusinessID, BusinessOwnerName, ContactInfo, ServiceID, ServiceType, ServiceDescription,
           ServicePrice, BookingID, BookingStatus, TotalPrice, TotalBookings, CommissionDetails
    FROM BusinessOwnerView
    WHERE BusinessID = ?
";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Query preparation failed: " . $conn->error);
}

$stmt->bind_param("i", $business_owner_id);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Business Owner Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 20px auto;
            background-color: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }

        p {
            text-align: center;
            color: #888;
        }

        .buttons {
            text-align: center;
            margin-bottom: 20px;
        }

        .buttons a {
            padding: 10px 20px;
            margin: 5px;
            text-decoration: none;
            background-color: #007bff;
            color: white;
            border-radius: 5px;
            font-size: 16px;
        }

        .buttons a:hover {
            background-color: #0056b3;
        }

        .logout-btn {
            padding: 50px 10px;
            margin: 5px;
            text-decoration: none;
            background-color: #dc3545;
            color: white;
            border-radius: 5px;
            font-size: 16px;
        }

        .logout-btn:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Business Owner Dashboard</h1>

        <!-- Only Add Services Button -->
        <div class="buttons">
            <a href="add_services.php">Add Services</a>
        </div>

        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Business ID</th>
                        <th>Owner Name</th>
                        <th>Contact Info</th>
                        <th>Service ID</th>
                        <th>Service Type</th>
                        <th>Service Description</th>
                        <th>Service Price</th>
                        <th>Booking ID</th>
                        <th>Booking Status</th>
                        <th>Total Price</th>
                        <th>Total Bookings</th>
                        <th>Commission Details</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['BusinessID'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['BusinessOwnerName'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['ContactInfo'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['ServiceID'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['ServiceType'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['ServiceDescription'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['ServicePrice'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['BookingID'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['BookingStatus'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['TotalPrice'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['TotalBookings'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['CommissionDetails'] ?? '') ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No data available for your account.</p>
        <?php endif; ?>

        <!-- Logout Button placed below the table -->
        <div class="buttons">
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
