<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include '../includes/db.php';

// Fetch user info
$user_id = $_SESSION['user_id'];
$query = $conn->prepare("SELECT username, email, profile_pic FROM users WHERE id = ?");
$query->bind_param("i", $user_id);
$query->execute();
$result = $query->get_result();
$user = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Settings</title>
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f8f9fc, #e9efff);
            color: #333;
        }

        .container {
            width: 70%;
            margin: 50px auto;
            text-align: center;
        }

        h1 {
            font-size: 2.5rem;
            margin-bottom: 30px;
            color: #2c3e50;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.1);
        }

        .card {
            background: #fff;
            padding: 25px;
            border-radius: 16px;
            margin-bottom: 25px;
            box-shadow: 0 8px 18px rgba(0,0,0,0.1);
            text-align: left;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(0,0,0,0.15);
        }

        h2 {
            margin-bottom: 15px;
            color: #34495e;
            font-size: 1.4rem;
            border-left: 4px solid #4a90e2;
            padding-left: 10px;
        }

        p {
            margin-bottom: 8px;
            color: #555;
        }

        button {
            padding: 12px 18px;
            margin: 8px 0;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            background: linear-gradient(135deg, #4a90e2, #357ab7);
            color: #fff;
            transition: all 0.3s ease;
        }

        button:hover {
            background: linear-gradient(135deg, #357ab7, #285a8e);
            transform: scale(1.03);
        }

        .back-btn {
            margin-top: 40px;
            width: auto;
            padding: 12px 30px;
            background: linear-gradient(135deg, #28a745, #1e7e34);
        }

        .back-btn:hover {
            background: linear-gradient(135deg, #1e7e34, #145a24);
        }

        /* Profile Picture */
        .profile-pic {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 15px;
            border: 3px solid #4a90e2;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .user-info {
            text-align: center;
            margin-bottom: 30px;
        }

        .user-info h3 {
            margin: 10px 0 5px;
            font-size: 1.5rem;
            color: #2c3e50;
        }

        .user-info p {
            font-size: 1rem;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>⚙️ User Settings</h1>

        <div class="user-info">
            <?php if (!empty($user['profile_pic'])): ?>
                <img src="../uploads/<?= htmlspecialchars($user['profile_pic']) ?>" class="profile-pic" alt="Profile Picture">
            <?php else: ?>
                <img src="https://via.placeholder.com/100" class="profile-pic" alt="Default Profile">
            <?php endif; ?>
            <h3><?= htmlspecialchars($user['username']) ?></h3>
            <p><?= htmlspecialchars($user['email']) ?></p>
        </div>

        <div class="card">
            <h2>Update Profile</h2>
            <a href="update_profile.php"><button>Update Profile Information</button></a>
        </div>

        <div class="card">
            <h2>Preferences</h2>
            <p><strong>Theme Mode:</strong> Light / Dark (Coming Soon)</p>
            <p><strong>Notifications:</strong> Email / SMS / In-app</p>
        </div>

        <a href="../dashboard.php"><button class="back-btn">⬅ Back to Home</button></a>
    </div>
</body>
</html>
