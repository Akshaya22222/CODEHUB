<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];
$snippet_id = isset($_POST['snippet_id']) ? intval($_POST['snippet_id']) : 0;

if ($snippet_id === 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid snippet ID']);
    exit;
}

// Check if already bookmarked
$sql = "SELECT * FROM bookmarks WHERE user_id = ? AND snippet_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $user_id, $snippet_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Remove bookmark (unbookmark)
    $delete = "DELETE FROM bookmarks WHERE user_id = ? AND snippet_id = ?";
    $delStmt = $conn->prepare($delete);
    $delStmt->bind_param("ii", $user_id, $snippet_id);
    $delStmt->execute();

    echo json_encode(['success' => true, 'bookmarked' => false]);
} else {
    // Add bookmark
    $insert = "INSERT INTO bookmarks (user_id, snippet_id) VALUES (?, ?)";
    $insStmt = $conn->prepare($insert);
    $insStmt->bind_param("ii", $user_id, $snippet_id);
    $insStmt->execute();

    echo json_encode(['success' => true, 'bookmarked' => true]);
}
?>
