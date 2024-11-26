<?php
// Include database connection
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'student_portal';

// Create a database connection
$conn = new mysqli($host, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle Signup Form Submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $register_number = $_POST['regNumber'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Hash the password for secure storage
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if the register number or email already exists
    $check_query = "SELECT * FROM users WHERE register_number = ? OR email = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("is", $register_number, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $error = "User with this Register Number or Email already exists.";
    } else {
        // Insert the new user into the database
        $query = "INSERT INTO users (register_number, name, email, password) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("isss", $register_number, $name, $email, $hashed_password);

        if ($stmt->execute()) {
            $success = "Account created successfully! You can now log in.";
        } else {
            $error = "An error occurred. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup Page</title>
    <style>
        /* General Styling */
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: url('1_10.jpeg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        /* Main Container */
        .container {
            display: flex;
            max-width: 900px;
            width: 90%;
            background: linear-gradient(135deg, #512da8, #9c27b0);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        /* Left Section - Welcome */
        .welcome-section {
            flex: 1;
            position: relative;
            background: linear-gradient(135deg, #1c2151, #4b67c2, #9c27b0);
            color: #fff;
            text-align: center;
            padding: 50px 20px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            overflow: hidden;
        }

        .welcome-section h1 {
            font-size: 36px;
            margin-bottom: 10px;
        }

        .welcome-section p {
            font-size: 18px;
            margin-bottom: 30px;
        }

        /* Right Section - Signup */
        .signup-section {
            flex: 1;
            background: #fff;
            padding: 50px 30px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .signup-section h2 {
            font-size: 28px;
            margin-bottom: 10px;
            color: #4b67c2;
        }

        /* Form Styling */
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        label {
            font-size: 14px;
            color: #555;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 100%;
        }

        button {
            padding: 12px 20px;
            background: linear-gradient(135deg, #512da8, #9c27b0);
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s;
        }

        button:hover {
            background: linear-gradient(135deg, #9c27b0, #512da8);
        }

        .login-link {
            margin-top: 15px;
            text-align: center;
            font-size: 14px;
            color: #9c27b0;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="welcome-section">
            <h1>Create Your Account</h1>
            <p>Sign up to access the quiz portal</p>
        </div>
        <div class="signup-section">
            <h2>Signup</h2>
            <form method="POST" action="">
                <?php 
                if (isset($error)) { 
                    echo "<p style='color:red;'>$error</p>"; 
                } 
                if (isset($success)) { 
                    echo "<p style='color:green;'>$success</p>"; 
                } 
                ?>
                <label for="regNumber">Register Number</label>
                <input type="text" id="regNumber" name="regNumber" placeholder="Register Number" required>

                <label for="name">Name</label>
                <input type="text" id="name" name="name" placeholder="Name" required>

                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" placeholder="Email Address" required>

                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Password" required>

                <button type="submit">Sign Up</button>
            </form>
            <p class="login-link" onclick="window.location.href='login.php'">Already have an account? Login</p>
        </div>
    </div>
</body>
</html>