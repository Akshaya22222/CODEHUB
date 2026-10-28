<?php
// admin/admin_comments.php
session_start();
require_once '../includes/db.php';
if (!isset($_SESSION['admin_id'])) 
  {
    header('Location: admin_login.php');
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_comment'])) {
    $cid = (int)$_POST['cid'];
    $conn->query("DELETE FROM comments WHERE id=$cid");
    header('Location: admin_comments.php'); exit();
}

$comments = $conn->query("
  SELECT c.id, c.comment, c.created_at, u.username, s.title AS snippet_title, s.id AS snippet_id
  FROM comments c
  LEFT JOIN users u ON u.id=c.user_id
  LEFT JOIN snippets s ON s.id=c.snippet_id
  ORDER BY c.created_at DESC
  LIMIT 200
");
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Manage Comments - Admin</title>
<style>
    body {font-family: Arial, sans-serif; margin:0; background:#0f1225; color:#eaeaea;}
    .topbar {display:flex; align-items:center; justify-content:space-between; padding:16px 22px; background:#171a33;}
    .nav a {color:#eaeaea; text-decoration:none; margin:0 8px; padding:8px 12px; border-radius:8px; background:#1d2140;}
    .nav a:hover {background:#232752;}
    .wrap {padding:20px;}
    table {width:100%; border-collapse:collapse; background:#171a33; border-radius:12px; overflow:hidden;}
    th, td {padding:12px; border-bottom:1px solid #262a52; text-align:left; font-size:14px; vertical-align:top;}
    .muted {color:#9aa;}
    .btn {border:none; padding:6px 10px; border-radius:8px; cursor:pointer; color:#fff; background:#e74c3c;}
    a.link {color:#7bd;}
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
  <h2>Manage Comments</h2>
  <table>
    <thead><tr><th>ID</th><th>User</th><th>Snippet</th><th>Comment</th><th>Created</th><th>Actions</th></tr></thead>
    <tbody>
      <?php while ($c = $comments->fetch_assoc()): ?>
      <tr>
        <td><?= $c['id'] ?></td>
        <td><?= htmlspecialchars($c['username'] ?? 'Guest') ?></td>
        <td><a class="link" target="_blank" href="../snippets/view_snippet.php?id=<?= $c['snippet_id'] ?>"><?= htmlspecialchars($c['snippet_title'] ?? 'Unknown') ?></a></td>
        <td><?= nl2br(htmlspecialchars($c['comment'])) ?></td>
        <td class="muted"><?= htmlspecialchars($c['created_at']) ?></td>
        <td>
          <form method="post" onsubmit="return confirm('Delete this comment?')">
            <input type="hidden" name="cid" value="<?= $c['id'] ?>">
            <button class="btn" name="delete_comment">Delete</button>
          </form>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>
</body>
</html>
