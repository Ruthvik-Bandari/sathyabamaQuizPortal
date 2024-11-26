<?php
// Include database connection
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'student_portal';

$conn = new mysqli($host, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Start session
session_start();

// Handle Login Form Submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $register_number = $_POST['regNumber'];
    $password = $_POST['password'];

    // Check user credentials
    $query = "SELECT id, name, password FROM users WHERE register_number = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $register_number); // "s" indicates a string parameter
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Verify password
        if (password_verify($password, $user['password'])) {
            // Store user info in session
            $_SESSION['register_number'] = $user['id'];
            $_SESSION['name'] = $user['name'];

            // Redirect to dashboard
            header('Location: dashboard.php');
            exit();
        } else {
            $error = "Invalid Password.";
        }
    } else {
        $error = "User not found. Please register.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
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

        /* Right Section - Login */
        .login-section {
            flex: 1;
            background: #fff;
            padding: 50px 30px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-section h2 {
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

        .create-account {
            margin-top: 15px;
            text-align: center;
            font-size: 14px;
            color: #9c27b0;
            cursor: pointer;
        }

        .create-account:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="welcome-section">
            <h1>Welcome To Sathyabama Quiz Portal</h1>
            <p>Sign In To Your Account</p>
        </div>
        <div class="login-section">
            <h2>Hello! <br> Good Morning</h2>
            <form method="POST" action="">
                <?php if (isset($error)) { echo "<p style='color:red;'>$error</p>"; } ?>
                <label for="regNumber">Register Number</label>
                <input type="text" id="regNumber" name="regNumber" placeholder="Register Number" required>
                
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Password" required>

                <button type="submit">Login</button>
            </form>
            <p class="create-account" onclick="window.location.href='signup.php'">Create Account</p>
        </div>
    </div>
</body>
</html>
