<?php
session_start();
include '../includes/db.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_pic'])) {
    $target_dir = "uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $file_name = time() . "_" . basename($_FILES["profile_pic"]["name"]);
    $target_file = $target_dir . $file_name;

    if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file)) {
        $sql = "UPDATE users SET profile_pic = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $file_name, $user_id);
        $stmt->execute();
        $stmt->close();

        $_SESSION['profile_pic'] = $file_name;
        header("Location: profile.php?success=1");
        exit();
    } else {
        echo "Error uploading file.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Profile Picture</title>
</head>
<body>
    <h2>Update Profile Picture</h2>
    <?php if (isset($_GET['success'])) echo "<p>Profile picture updated successfully!</p>"; ?>
    <form action="profile.php" method="POST" enctype="multipart/form-data">
        <input type="file" name="profile_pic" required>
        <button type="submit">Upload</button>
    </form>
</body>
</html>
