<?php
require_once __DIR__ . '/../includes/db.php';

$username = "vignesh";
$email    = "vignesh@example.com";
$password = password_hash("vignesh123", PASSWORD_DEFAULT);

$sql = "INSERT INTO admins (username, email, password) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $username, $email, $password);

if ($stmt->execute()) {
    echo "New admin created! Username: vignesh, Password: vignesh123";
} else {
    echo "Error: " . $stmt->error;
}
$stmt->close();
$conn->close();
?>
