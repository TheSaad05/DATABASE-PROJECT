<?php
session_start();
if (!isset($_SESSION['business_owner_id'])) {
    header("Location: business_owner_login.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "database";
$dbname = "QureshiTrips";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$business_owner_id = $_SESSION['business_owner_id'];
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $service_type = $_POST['service_type'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $availability_status = $_POST['availability_status'];

    $sql = "INSERT INTO Service (ServiceType, Description, Price, AvailabilityStatus, BusinessID)
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdsi", $service_type, $description, $price, $availability_status, $business_owner_id);

    if ($stmt->execute()) {
        $message = "Service added successfully!";
    } else {
        $message = "Error adding service.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Service</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label, input, select, button {
            margin-bottom: 15px;
        }
        input, select {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            background-color: #007bff;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .message {
            text-align: center;
            color: green;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Add New Service</h1>
        <?php if ($message): ?>
            <p class="message"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>
        <form method="POST">
            <label for="service_type">Service Type:</label>
            <select id="service_type" name="service_type" required>
                <option value="Hotel Room">Hotel Room</option>
                <option value="Transport">Transport</option>
            </select>
            <label for="description">Description:</label>
            <input type="text" id="description" name="description" required>
            <label for="price">Price:</label>
            <input type="number" id="price" name="price" step="0.01" required>
            <label for="availability_status">Availability:</label>
            <select id="availability_status" name="availability_status" required>
                <option value="Available">Available</option>
                <option value="Unavailable">Unavailable</option>
            </select>
            <button type="submit">Add Service</button>
        </form>
    </div>
</body>
</html>
