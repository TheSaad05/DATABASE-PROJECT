<?php
// Database connection
$servername = "localhost";
$username = "root"; // Replace with your MySQL root username
$password = "database"; // Replace with your MySQL root password
$dbname = "QureshiTrips";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $contact_info = $_POST['contact_info'];
    $service_type = $_POST['service_type'];
    $availability = $_POST['availability'];
    $commission_details = $_POST['commission_details'];

    // Insert the business owner into the database
    $sql = "INSERT INTO BusinessOwner (Name, ContactInfo, ServiceType, RealTimeAvailability, CommissionDetails) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $name, $contact_info, $service_type, $availability, $commission_details);

    if ($stmt->execute()) {
        // Redirect to login page on success
        header("Location: business_owner_login.php?success=1");
        exit();
    } else {
        $registration_error = $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Business Owner Registration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 500px;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin: 10px 0 5px;
            color: #555;
        }

        input[type="text"],
        textarea,
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            border: none;
            color: white;
            font-size: 16px;
            cursor: pointer;
            border-radius: 4px;
        }

        button:hover {
            background-color: #45a049;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #4CAF50;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .error {
            color: red;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Business Owner Registration</h2>

        <?php if (isset($registration_error)): ?>
            <p class="error">Error: <?php echo htmlspecialchars($registration_error); ?></p>
        <?php endif; ?>

        <form action="business_owner_register.php" method="POST">
            <label for="name">Full Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="contact_info">Contact Info:</label>
            <textarea id="contact_info" name="contact_info" rows="3" required></textarea>

            <label for="service_type">Service Type:</label>
            <select id="service_type" name="service_type" required>
                <option value="Hotel">Hotel</option>
                <option value="Transport">Transport</option>
            </select>

            <label for="availability">Real-Time Availability:</label>
            <input type="text" id="availability" name="availability" placeholder="e.g., Available, Unavailable" required>

            <label for="commission_details">Commission Details:</label>
            <textarea id="commission_details" name="commission_details" rows="3" required></textarea>

            <button type="submit">Register</button>
        </form>

        <a href="business_owner_login.php" class="back-link">Back to Login</a>
    </div>
</body>
</html>
