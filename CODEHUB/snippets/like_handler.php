<?php
session_start();
include '../includes/db.php'; // FIXED path

if (!isset($_SESSION['user_id']) || !isset($_POST['snippet_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized or Invalid Request']);
    exit;
}

$user_id = $_SESSION['user_id'];
$snippet_id = $_POST['snippet_id'];

// Check if the user already liked this snippet
$stmt = $conn->prepare("SELECT * FROM likes WHERE user_id = ? AND snippet_id = ?");
$stmt->bind_param("ii", $user_id, $snippet_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Already liked, so unlike
    $deleteStmt = $conn->prepare("DELETE FROM likes WHERE user_id = ? AND snippet_id = ?");
    $deleteStmt->bind_param("ii", $user_id, $snippet_id);
    $deleteStmt->execute();
    echo json_encode(['success' => true, 'action' => 'unliked']);
} else {
    // Not liked yet, so like it
    $insertStmt = $conn->prepare("INSERT INTO likes (user_id, snippet_id) VALUES (?, ?)");
    $insertStmt->bind_param("ii", $user_id, $snippet_id);
    $insertStmt->execute();
    echo json_encode(['success' => true, 'action' => 'liked']);
}
?>
