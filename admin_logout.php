<?php
// Start session for user management
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to QureshiTrips</title>
    <style>
        /* Basic CSS Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Body and background */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            line-height: 1.6;
            padding: 20px;
        }

        /* Header Styles */
        header {
            background-color: #4CAF50;
            color: white;
            text-align: center;
            padding: 20px 0;
        }

        header h1 {
            margin: 0;
            font-size: 2.5em;
        }

        header p {
            font-size: 1.2em;
        }

        /* Navigation Styles */
        nav {
            background-color: #333;
            padding: 10px 0;
            text-align: center;
        }

        nav ul {
            list-style: none;
            padding: 0;
        }

        nav ul li {
            display: inline;
            margin-right: 20px;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
            font-size: 1.1em;
        }

        nav ul li a:hover {
            text-decoration: underline;
        }

        /* Main Content Styles */
        main {
            margin-top: 20px;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        main h2 {
            font-size: 1.8em;
            margin-bottom: 15px;
        }

        main p, main ul {
            font-size: 1.2em;
            margin-bottom: 20px;
        }

        main ul {
            list-style-type: disc;
            margin-left: 20px;
        }

        /* Footer Styles */
        footer {
            text-align: center;
            padding: 10px 0;
            background-color: #333;
            color: white;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <header>
        <h1>Welcome to QureshiTrips</h1>
        <p>Your one-stop solution for travel and service bookings!</p>
    </header>

    <!-- Navigation Links -->
    <nav>
        <ul>
            <li><a href="customer/customer_login.php">Customer Login</a></li>
            <li><a href="business_owner/business_owner_login.php">Business Owner Login</a></li>
            <li><a href="administrator/admin_login.php">Administrator Login</a></li>
            <li><a href="register.php">Register</a></li>
        </ul>
    </nav>

    <!-- Main Content Section -->
    <main>
        <section>
            <h2>About Us</h2>
            <p>QureshiTrips is a platform that connects customers with trusted business owners to provide seamless hotel and transport booking services.</p>
        </section>
        <section>
            <h2>Our Services</h2>
            <ul>
                <li>Book comfortable hotel rooms.</li>
                <li>Rent transport for your travel needs.</li>
                <li>Secure and hassle-free payments.</li>
            </ul>
        </section>
    </main>

    <!-- Footer Section -->
    <footer>
        <p>&copy; 2024 QureshiTrips. All rights reserved.</p>
    </footer>
</body>
</html>
