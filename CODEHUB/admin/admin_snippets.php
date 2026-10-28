<?php
// admin/admin_snippets.php
session_start();
require_once '../includes/db.php';
if (!isset($_SESSION['admin_id'])) 
  {
    header('Location: admin_login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_snippet'])) {
    $sid = (int)$_POST['sid'];
    $conn->query("DELETE FROM comments WHERE snippet_id=$sid");
    $conn->query("DELETE FROM likes WHERE snippet_id=$sid");
    $conn->query("DELETE FROM bookmarks WHERE snippet_id=$sid");
    $conn->query("DELETE FROM snippet_versions WHERE snippet_id=$sid");
    $conn->query("DELETE FROM snippets WHERE id=$sid");
    header('Location: admin_snippets.php'); exit();
}

$q = $conn->query("
  SELECT s.id, s.title, s.language, s.created_at, u.username,
         (SELECT COUNT(*) FROM likes l WHERE l.snippet_id = s.id) AS like_count
  FROM snippets s
  JOIN users u ON u.id = s.user_id
  ORDER BY s.created_at DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Snippets - Admin</title>
<style>
    body {font-family: Arial, sans-serif; margin:0; background:#0f1225; color:#eaeaea;}
    .topbar {display:flex; align-items:center; justify-content:space-between; padding:16px 22px; background:#171a33;}
    .nav a {color:#eaeaea; text-decoration:none; margin:0 8px; padding:8px 12px; border-radius:8px; background:#1d2140;}
    .nav a:hover {background:#232752;}
    .wrap {padding:20px;}
    table {width:100%; border-collapse:collapse; background:#171a33; border-radius:12px; overflow:hidden;}
    th, td {padding:12px; border-bottom:1px solid #262a52; text-align:left; font-size:14px;}
    .muted {color:#9aa;}
    .btn {border:none; padding:6px 10px; border-radius:8px; cursor:pointer; color:#fff; margin-right:6px; background:#e74c3c;}
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
  <h2>Manage Snippets</h2>
  <table>
    <thead><tr><th>ID</th><th>Title</th><th>Lang</th><th>Author</th><th>Likes</th><th>Created</th><th>Actions</th></tr></thead>
    <tbody>
      <?php while ($s = $q->fetch_assoc()): ?>
      <tr>
        <td><?= $s['id'] ?></td>
        <td><a class="link" href="../snippets/view_snippet.php?id=<?= $s['id'] ?>" target="_blank"><?= htmlspecialchars($s['title']) ?></a></td>
        <td><?= htmlspecialchars($s['language']) ?></td>
        <td><?= htmlspecialchars($s['username']) ?></td>
        <td><?= (int)$s['like_count'] ?></td>
        <td class="muted"><?= htmlspecialchars($s['created_at']) ?></td>
        <td>
          <form method="post" onsubmit="return confirm('Delete this snippet and its related data?')">
            <input type="hidden" name="sid" value="<?= $s['id'] ?>">
            <button class="btn" name="delete_snippet">Delete</button>
          </form>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>
</body>
</html>
