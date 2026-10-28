<?php
include '../includes/db.php';

if (isset($_GET['snippet_id'])) {
    $snippet_id = $_GET['snippet_id'];
    $result = $conn->query("SELECT COUNT(*) AS total FROM likes WHERE snippet_id = $snippet_id");
    $data = $result->fetch_assoc();
    echo $data['total'];
}
?>
