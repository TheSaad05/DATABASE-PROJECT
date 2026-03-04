<?php
session_start();

// Redirect to login if the user is not logged in
if (!isset($_SESSION['customer_email'])) {
    header("Location: customer_login.php");
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "database", "QureshiTrips");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the logged-in user's details
$customer_email = $_SESSION['customer_email'];

$query = $conn->prepare("SELECT * FROM Customer WHERE Email = ?");
$query->bind_param("s", $customer_email);
$query->execute();
$result = $query->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc(); // Fetch the logged-in user's details
} else {
    echo "Error: User details not found.";
    exit();
}

// Close the database connection
$query->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 20px;
        }
        .container {
            width: 400px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
        }
        p {
            font-size: 16px;
            margin: 10px 0;
        }
        a {
            display: block;
            text-align: center;
            margin: 10px 0;
            text-decoration: none;
            color: #007bff;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Your Profile</h1>
        <p><strong>Name:</strong> <?php echo htmlspecialchars($user['Name']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['Email']); ?></p>
        <p><strong>Phone:</strong> <?php echo htmlspecialchars($user['Phone']); ?></p>
        <p><strong>Address:</strong> <?php echo htmlspecialchars($user['Address']); ?></p>
        <a href="customer_dashboard.php">Back to Dashboard</a>
        <a href="customer_logout.php">Logout</a>
    </div>
</body>
</html>
