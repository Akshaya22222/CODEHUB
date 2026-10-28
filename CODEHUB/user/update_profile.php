<?php
session_start();
include '../includes/db.php';

// Check if user logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    // Handle profile picture upload
    if (!empty($_FILES['profile_pic']['name'])) {
        $target_dir = "uploads/profile_pics/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $file_name = time() . "_" . basename($_FILES["profile_pic"]["name"]);
        $target_file = $target_dir . $file_name;

        if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file)) {
            $query = "UPDATE users SET username='$username', email='$email', profile_pic='$file_name' WHERE id='$user_id'";
        } else {
            $message = "❌ Error uploading profile picture.";
        }
    } else {
        $query = "UPDATE users SET username='$username', email='$email' WHERE id='$user_id'";
    }

    if (isset($query) && mysqli_query($conn, $query)) {
        $message = "✅ Profile updated successfully!";
    } else {
        $message = "❌ Error updating profile: " . mysqli_error($conn);
    }
}

// Fetch current user data
$result = mysqli_query($conn, "SELECT * FROM users WHERE id='$user_id'");
$user = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Profile</title>
    <style>
        /* Reset & General */
        * {
            margin: 0; padding: 0; box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        body {
            background: linear-gradient(135deg, #e3f2fd, #f4f6f9);
            display: flex; justify-content: center; align-items: center;
            min-height: 100vh;
        }
        .container {
            max-width: 500px;
            width: 100%;
            background: #fff;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            animation: fadeIn 0.6s ease;
        }
        h2 {
            margin-bottom: 20px;
            text-align: center;
            color: #2c3e50;
        }
        label {
            font-weight: 600;
            display: block;
            margin: 10px 0 5px;
            color: #34495e;
        }
        input[type=text], input[type=email], input[type=file] {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #ccc;
            margin-bottom: 15px;
            transition: border 0.2s;
        }
        input:focus {
            border-color: #4a90e2;
            outline: none;
        }
        button {
            padding: 12px 15px;
            background: linear-gradient(135deg, #4a90e2, #357ab7);
            color: white;
            font-weight: 600;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            transition: all 0.3s ease;
        }
        button:hover {
            background: linear-gradient(135deg, #357ab7, #285a8e);
            transform: translateY(-2px);
        }
        .msg {
            margin: 15px 0;
            text-align: center;
            font-weight: 600;
        }
        .msg.success { color: #28a745; }
        .msg.error { color: #e74c3c; }
        img {
            border-radius: 50%;
            margin: 10px auto;
            display: block;
            border: 3px solid #4a90e2;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .back-btn {
            margin-top: 20px;
            background: linear-gradient(135deg, #28a745, #1e7e34);
        }
        .back-btn:hover {
            background: linear-gradient(135deg, #1e7e34, #145a24);
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
<div class="container">
    <h2>👤 Update Profile</h2>
    <?php if($message != ""): ?>
        <p class="msg <?php echo strpos($message, '✅') !== false ? 'success' : 'error'; ?>">
            <?php echo $message; ?>
        </p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <label>Username:</label>
        <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>

        <label>Email:</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

        <label>Profile Picture:</label>
        <?php if (!empty($user['profile_pic'])): ?>
            <img src="uploads/profile_pics/<?php echo htmlspecialchars($user['profile_pic']); ?>" width="100" height="100">
        <?php endif; ?>
        <input type="file" name="profile_pic">

        <button type="submit">💾 Update Profile</button>
        <button type="button" class="back-btn" onclick="window.location.href='settings.php'">⬅ Back to Settings</button>
    </form>
</div>
</body>
</html>
