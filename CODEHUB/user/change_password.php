<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";

// Handle password change
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $current_password = mysqli_real_escape_string($conn, $_POST['current_password']);
    $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    // Fetch old password
    $result = mysqli_query($conn, "SELECT password FROM users WHERE id='$user_id'");
    $user = mysqli_fetch_assoc($result);

    if (password_verify($current_password, $user['password'])) {
        if ($new_password === $confirm_password) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            mysqli_query($conn, "UPDATE users SET password='$hashed_password' WHERE id='$user_id'");
            $message = "Password updated successfully!";
        } else {
            $message = "New passwords do not match.";
        }
    } else {
        $message = "Current password is incorrect.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Change Password</title>
    <style>
        body { font-family: Arial; background: #f4f4f9; margin: 0; padding: 20px; }
        .container { max-width: 500px; margin: auto; background: #fff; padding: 20px; border-radius: 10px; }
        input[type=password] { width: 100%; padding: 10px; margin: 8px 0; border-radius: 5px; border: 1px solid #ccc; }
        button { padding: 10px 15px; background: #4CAF50; color: white; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background: #45a049; }
        .msg { margin: 10px 0; color: red; }
    </style>
</head>
<body>
<div class="container">
    <h2>Change Password</h2>
    <?php if($message != "") echo "<p class='msg'>$message</p>"; ?>
    <form method="POST">
        <label>Current Password:</label>
        <input type="password" name="current_password" required>
        
        <label>New Password:</label>
        <input type="password" name="new_password" required>
        
        <label>Confirm New Password:</label>
        <input type="password" name="confirm_password" required>
        
        <button type="submit">Change Password</button>
    </form>
</div>
</body>
</html>
