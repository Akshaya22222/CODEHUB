<?php
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $snippet_id = $_POST['snippet_id'];
    $user_name = $_POST['user_name'];
    $comment = $_POST['comment'];

    $stmt = $conn->prepare("INSERT INTO comments (snippet_id, user_name, comment) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $snippet_id, $user_name, $comment);
    $stmt->execute();
    
    header("Location: view_snippet.php?id=" . $snippet_id);
    exit();
}
?>

