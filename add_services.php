<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Login</title>
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
        input[type="email"], input[type="password"], input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        input[type="submit"] {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        p {
            text-align: center;
            margin-top: 10px;
        }
        a {
            color: #007bff;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Login</h1>
        <form method="post">
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="submit" name="login" value="Login">
        </form>
        <p>Don't have an account? <a href="customer_register.php">Register Here</a></p>
        <?php
        session_start();

        if (isset($_POST['login'])) {
            // Database connection
            $conn = new mysqli("localhost", "root", "database", "QureshiTrips");

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Get email and password from form
            $email = $_POST['email'];
            $password = $_POST['password'];

            // Query to check if user exists
            $query = "SELECT * FROM Customer WHERE Email = '$email' AND Password = '$password'";
            $result = $conn->query($query);

            if ($result && $result->num_rows > 0) {
                $_SESSION['customer_email'] = $email; // Store email in session
                header("Location: customer_dashboard.php"); // Redirect to dashboard
                exit();
            } else {
                echo "<p style='color: red; text-align: center;'>Invalid email or password.</p>";
            }

            $conn->close();
        }
        ?>
    </div>
</body>
</html>
