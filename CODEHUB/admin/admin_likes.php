<?php
// admin/admin_likes.php
session_start();
require_once '../includes/db.php';
if (!isset($_SESSION['admin_id'])) 
  {
    header('Location: admin_login.php');
    exit();
}


$rows = $conn->query("
  SELECT s.id, s.title, u.username AS author,
         COUNT(l.id) AS likes
  FROM snippets s
  LEFT JOIN likes l ON l.snippet_id = s.id
  LEFT JOIN users u ON u.id = s.user_id
  GROUP BY s.id
  ORDER BY likes DESC, s.created_at DESC
");
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Likes - Admin</title>
<style>
    body {font-family: Arial, sans-serif; margin:0; background:#0f1225; color:#eaeaea;}
    .topbar {display:flex; align-items:center; justify-content:space-between; padding:16px 22px; background:#171a33;}
    .nav a {color:#eaeaea; text-decoration:none; margin:0 8px; padding:8px 12px; border-radius:8px; background:#1d2140;}
    .nav a:hover {background:#232752;}
    .wrap {padding:20px;}
    table {width:100%; border-collapse:collapse; background:#171a33; border-radius:12px; overflow:hidden;}
    th, td {padding:12px; border-bottom:1px solid #262a52; text-align:left; font-size:14px;}
    a.link {color:#7bd;}
    .muted {color:#9aa;}
</style>
</head>
<body>
<div class="topbar">
  <div>⚙️ CodeHub • Admin</div>
  <div class="nav">
    <a href="admin_dashboard.php">Dashboard</a>
    <a href="admin_users.php">Users</a>
    <a href="admin_snippets.php">Snippets</a>
    <a href="admin_comments.php">Comments</a>
    <a href="admin_likes.php">Likes</a>
    <a href="admin_bookmarks.php">Bookmarks</a>
    <a href="admin_logout.php">Logout</a>
  </div>
</div>

<div class="wrap">
  <h2>Likes by Snippet</h2>
  <table>
    <thead><tr><th>Snippet</th><th>Author</th><th>Likes</th></tr></thead>
    <tbody>
      <?php while ($r = $rows->fetch_assoc()): ?>
      <tr>
        <td><a class="link" href="../snippets/view_snippet.php?id=<?= $r['id'] ?>" target="_blank"><?= htmlspecialchars($r['title']) ?></a></td>
        <td><?= htmlspecialchars($r['author']) ?></td>
        <td><?= (int)$r['likes'] ?></td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>
</body>
</html>
