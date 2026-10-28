<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Security</title>
    <style>
        /* General Styling */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #e0e7ff, #f4f6f9);
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            width: 90%;
            max-width: 800px;
            margin: 50px auto;
            padding: 10px;
        }

        h1 {
            text-align: center;
            color: #1a237e;
            margin-bottom: 30px;
            font-size: 2.2rem;
        }

        /* Card Styles */
        .card {
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }

        h2 {
            margin-bottom: 20px;
            font-size: 1.4rem;
            color: #0d47a1;
            border-left: 4px solid #0d47a1;
            padding-left: 10px;
        }

        /* Form Inputs */
        label {
            font-weight: 500;
            display: block;
            margin-bottom: 6px;
            color: #444;
        }

        input[type="password"] {
            padding: 10px;
            width: 100%;
            border-radius: 8px;
            border: 1px solid #ccc;
            margin-bottom: 15px;
            transition: border 0.2s ease, box-shadow 0.2s ease;
        }

        input[type="password"]:focus {
            border-color: #3f51b5;
            box-shadow: 0 0 6px rgba(63,81,181,0.3);
            outline: none;
        }

        /* Buttons */
        button {
            padding: 12px;
            width: 100%;
            border-radius: 8px;
            border: none;
            background: #3f51b5;
            color: white;
            font-weight: bold;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.2s ease;
        }

        button:hover {
            background: #303f9f;
            transform: scale(1.02);
        }

        button:active {
            transform: scale(0.98);
        }

        /* Back Button */
        .back-btn {
            width: auto;
            padding: 10px 20px;
            margin-top: 20px;
            display: inline-block;
            background: #ff7043;
        }

        .back-btn:hover {
            background: #e64a19;
        }

        /* Responsive */
        @media (max-width: 600px) {
            .container {
                width: 95%;
            }

            h1 {
                font-size: 1.7rem;
            }

            h2 {
                font-size: 1.2rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔐 Account Security</h1>

        <div class="card">
            <h2>Change Password</h2>
            <form method="post" action="change_password.php">
                <label>Current Password:</label>
                <input type="password" name="current_password" required>

                <label>New Password:</label>
                <input type="password" name="new_password" required>

                <label>Confirm New Password:</label>
                <input type="password" name="confirm_password" required>

                <button type="submit">Update Password</button>
            </form>
        </div>

        <div class="card">
            <h2>Login Activity</h2>
            <p>📍 Feature Placeholder: Show recent login devices and IP addresses.</p>
        </div>

        <div class="card">
            <h2>Two-Factor Authentication</h2>
            <p>🔑 Feature Placeholder: Enable/Disable 2FA.</p>
        </div>

        <center>
            <a href="../dashboard.php">
                <button class="back-btn">⬅ Back to Home</button>
            </a>
        </center>
    </div>
</body>
</html>
