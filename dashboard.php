<?php
// Start the session
session_start();

// Display errors for debugging (remove this in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the user is logged in, if not, redirect to login page
if (!isset($_SESSION['register_number'])) {
    header("Location: login.php"); // Redirect to login page if user is not logged in
    exit();
}

// Database connection settings
$host = 'localhost';
$username = 'root';
$password = ''; // Your database password
$database = 'student_portal'; // Your database name

// Create a connection to the database
$conn = new mysqli($host, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the user ID from the session
$register_number = $_SESSION['register_number'];

// Fetch user details from the database
$query = "SELECT name FROM users WHERE register_number = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $register_number);
$stmt->execute();
$stmt->bind_result($username);
$stmt->fetch();
$stmt->close();

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        /* Sidebar styling */
        .sidebar {
            height: 100vh;
            background-color: #4a5953;
            color: #fff;
            padding: 20px;
            display: flex;
            flex-direction: column;
        }

        .sidebar a {
            color: #fff;
            text-decoration: none;
            display: block;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .sidebar a:hover, .sidebar a.active {
            background-color: #bfc7c4;
        }

        .sidebar .logout-btn {
            margin-top: auto;
            background-color: #ff4b5c;
            border: none;
            color: #fff;
            padding: 10px;
            border-radius: 8px;
            text-align: center;
            display: block;
            text-decoration: none;
        }

        .sidebar .logout-btn:hover {
            background-color: #d73b4a;
        }

        /* Main content styling */
        .main-content {
            background: linear-gradient(135deg, #ece9e6, #ffffff, #ffe4e1);
            padding: 30px;
            min-height: 100vh;
        }

        .card {
            border: none;
            border-radius: 20px;
            padding: 20px;
            background: linear-gradient(135deg, #ffffff, #f3e5f5);
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 15px 25px rgba(0, 0, 0, 0.2);
        }

        .dashboard-header {
            font-size: 22px;
            font-weight: bold;
            color: #4B67C2;
        }

        .notification-btn {
            background-color: #bfc7c4;
            color: #fff;
            border: none;
            padding: 10px 15px;
            border-radius: 50%;
            font-size: 18px;
            cursor: pointer;
        }

        .notification-btn:hover {
            background-color: #3751a0;
        }

        /* Quiz card content */
        .quiz-title {
            font-size: 24px;
            font-weight: bold;
            color: #4B67C2;
        }

        .quiz-description {
            font-size: 16px;
            color: #6b6b6b;
        }

        .quiz-btn {
            padding: 12px 20px;
            background: linear-gradient(135deg, #9C27B0, #4B67C2);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .quiz-btn:hover {
            background: linear-gradient(135deg, #4B67C2, #9C27B0);
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 sidebar">
                <h4 class="mb-4">Sathyabama Quiz Portal</h4>
                <a href="dashboard.php" class="active">Dashboard</a>
                <a href="settings.php">Settings</a>
                <a href="logout.php" class="logout-btn">Logout</a>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 main-content">
                <!-- Top Bar -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="dashboard-header">Welcome, <?php echo htmlspecialchars($username); ?>!</h5>
                    <button class="notification-btn">
                        <i class="bi bi-bell"></i>
                    </button>
                </div>

                <!-- Enhanced Quiz Card -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <h5 class="quiz-title">Quiz on Problem Solving</h5>
                            <p class="quiz-description">
                                Test your problem-solving skills with this C-language quiz. 
                                It lasts for 30 minutes and includes randomized multiple-choice questions.
                            </p>
                            <button class="quiz-btn">Start Quiz</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>