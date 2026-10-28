<?php
session_start();
require_once __DIR__ . '/../includes/db.php'; // connect to DB

// Initialize error message
$error = "";

// Handle form submit
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_input = trim($_POST['username'] ?? '');
    $password   = trim($_POST['password'] ?? '');

    if ($user_input === '' || $password === '') {
        $error = "Please enter username/email and password.";
    } else {
        // Fetch by username OR email
        $sql = "SELECT id, username, password FROM admins WHERE username = ? OR email = ? LIMIT 1";
        $stmt = $conn->prepare($sql);
        
        if ($stmt) {
            $stmt->bind_param("ss", $user_input, $user_input);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows === 1) {
                $row = $result->fetch_assoc();

                // Verify hash
                if (password_verify($password, $row['password'])) {
                    // Store session
                    $_SESSION['admin_id'] = $row['id'];
                    $_SESSION['admin_username'] = $row['username'];

                    // ✅ Stop output before redirect
                    header("Location: admin_dashboard.php");
                    exit;
                } else {
                    $error = "Invalid username/email or password.";
                }
            } else {
                $error = "Invalid username/email or password.";
            }

            $stmt->close();
        } else {
            $error = "Database error: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login - Code Hub</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #2c5364, #203a43, #0f2027);
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background: rgba(255,255,255,0.08);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.35);
            width: 360px;
            text-align: center;
        }
        input[type="text"], input[type="password"] {
            width: 92%;
            padding: 12px;
            margin: 10px 0;
            border: none;
            border-radius: 6px;
            outline: none;
        }
        button {
            width: 95%;
            padding: 12px;
            border: none;
            border-radius: 6px;
            background: linear-gradient(to right, #0072ff, #00c6ff);
            color: white;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
        }
        .error {
            color: #ff8080;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Admin Login</h2>

        <?php if (!empty($error)): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="text" name="username" placeholder="Username or Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
