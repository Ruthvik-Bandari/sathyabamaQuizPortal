<?php
session_start();

// Check if the user is logged in, if not, redirect to login page
if (!isset($_SESSION['register_number'])) {
    header("Location: login.php");
    exit();
}

// Database connection (Modify as per your settings)
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'student_portal';
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user details (assuming you have a users table with register_number, name, etc.)
$register_number = $_SESSION['register_number'];
$query = "SELECT name FROM users WHERE register_number = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $register_number);
$stmt->execute();
$stmt->bind_result($username);
$stmt->fetch();
$stmt->close();

// Change password logic
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $oldPassword = $_POST['oldPassword'];
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];

    // Fetch the current password from the database
    $query = "SELECT password FROM users WHERE register_number = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $register_number);
    $stmt->execute();
    $stmt->bind_result($currentPassword);
    $stmt->fetch();
    $stmt->close();

    // Check if the old password matches
    if (password_verify($oldPassword, $currentPassword)) {
        // Check if new password and confirm password match
        if ($newPassword === $confirmPassword) {
            // Hash the new password
            $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);

            // Update password in the database
            $query = "UPDATE users SET password = ? WHERE register_number = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("si", $newPasswordHash, $register_number);
            if ($stmt->execute()) {
                $message = "Password updated successfully!";
            } else {
                $message = "Failed to update password.";
            }
            $stmt->close();
        } else {
            $message = "New password and confirm password do not match.";
        }
    } else {
        $message = "Old password is incorrect.";
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        /* General styles */
        body {
            background-color: #ece9e6;
            color: #4B67C2;
            transition: background-color 0.3s, color 0.3s;
        }

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
        }

        .sidebar .logout-btn:hover {
            background-color: #d73b4a;
        }

        /* Main content styling */
        .main-content {
            padding: 30px;
            min-height: 100vh;
        }

        .card {
            border: none;
            border-radius: 20px;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            background-color: #9C27B0;
            border: none;
        }

        .btn-primary:hover {
            background-color: #7b1fa2;
        }

        .alert {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 sidebar">
                <h4 class="mb-4">easy.jobs</h4>
                <a href="dashboard.php" id="dashboard-link">Dashboard</a>
                <a href="settings.php" id="settings-link" class="active">Settings</a>
                <a href="logout.php" class="logout-btn">Logout</a>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 main-content">
                <h5 class="mb-4">Settings</h5>
                
                <!-- Display Message -->
                <?php if (isset($message)): ?>
                    <div class="alert alert-info">
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>

                <div class="card">
                    <h6>User Name: <strong><?php echo htmlspecialchars($username); ?></strong></h6>
                    <hr>
                    <h6>Change Password</h6>
                    <form method="POST">
                        <div class="mb-3">
                            <label for="oldPassword" class="form-label">Old Password</label>
                            <input type="password" class="form-control" id="oldPassword" name="oldPassword" required>
                        </div>
                        <div class="mb-3">
                            <label for="newPassword" class="form-label">New Password</label>
                            <input type="password" class="form-control" id="newPassword" name="newPassword" required>
                        </div>
                        <div class="mb-3">
                            <label for="confirmPassword" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Change Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>