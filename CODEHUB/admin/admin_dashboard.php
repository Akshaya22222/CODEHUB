<?php
// admin/admin_dashboard.php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['admin_id'])) 
  {
    header('Location: admin_login.php');
    exit();
}


// Stats
$users_total     = $conn->query("SELECT COUNT(*) AS c FROM users")->fetch_assoc()['c'];
$snippets_total  = $conn->query("SELECT COUNT(*) AS c FROM snippets")->fetch_assoc()['c'];
$comments_total  = $conn->query("SELECT COUNT(*) AS c FROM comments")->fetch_assoc()['c'] ?? 0;
$likes_total     = $conn->query("SELECT COUNT(*) AS c FROM likes")->fetch_assoc()['c'] ?? 0;
$bookmarks_total = $conn->query("SELECT COUNT(*) AS c FROM bookmarks")->fetch_assoc()['c'] ?? 0;

// recent 5 snippets
$recent = $conn->query("
    SELECT s.id, s.title, s.language, s.created_at, u.username 
    FROM snippets s
    JOIN users u ON u.id = s.user_id
    ORDER BY s.created_at DESC
    LIMIT 5
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard - CodeHub</title>
<style>
    body {font-family: Arial, sans-serif; margin:0; background:#0f1225; color:#eaeaea;}
    .topbar {display:flex; align-items:center; justify-content:space-between; padding:16px 22px; background:#171a33; position:sticky; top:0; z-index:10;}
    .brand {font-weight:700;}
    .nav a {color:#eaeaea; text-decoration:none; margin:0 8px; padding:8px 12px; border-radius:8px; background:#1d2140;}
    .nav a:hover {background:#232752;}
    .grid {display:grid; gap:16px; padding:20px; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr));}
    .card {background:#171a33; border-radius:14px; padding:18px; box-shadow:0 10px 30px rgba(0,0,0,.35);}
    .kpi {font-size:28px; margin:8px 0;}
    .muted {color:#9aa;}
    .list table {width:100%; border-collapse:collapse;}
    .list th, .list td {text-align:left; padding:10px; border-bottom:1px solid #262a52; font-size:14px;}
    .badge {display:inline-block; padding:4px 8px; background:#2a2f55; border-radius:999px; font-size:12px;}
    .logout {background:#9b2c2c !important;}
</style>
</head>
<body>
  <div class="topbar">
    <div class="brand">⚙️ CodeHub • Admin</div>
    <div class="nav">
      <a href="admin_dashboard.php">Dashboard</a>
      <a href="admin_users.php">Users</a>
      <a href="admin_snippets.php">Snippets</a>
      <a href="admin_comments.php">Comments</a>
      <a href="admin_likes.php">Likes</a>
      <a href="admin_bookmarks.php">Bookmarks</a>
      <a href="admin_logout.php" class="logout">Logout</a>
    </div>
  </div>

  <div class="grid">
    <div class="card">
      <div class="muted">Total Users</div>
      <div class="kpi"><?= (int)$users_total ?></div>
    </div>
    <div class="card">
      <div class="muted">Total Snippets</div>
      <div class="kpi"><?= (int)$snippets_total ?></div>
    </div>
    <div class="card">
      <div class="muted">Total Comments</div>
      <div class="kpi"><?= (int)$comments_total ?></div>
    </div>
    <div class="card">
      <div class="muted">Total Likes</div>
      <div class="kpi"><?= (int)$likes_total ?></div>
    </div>
    <div class="card">
      <div class="muted">Total Bookmarks</div>
      <div class="kpi"><?= (int)$bookmarks_total ?></div>
    </div>
  </div>

  <div class="card list" style="margin: 0 20px 20px;">
    <h3>Recent Snippets</h3>
    <table>
      <thead>
        <tr><th>Title</th><th>Lang</th><th>Author</th><th>Created</th></tr>
      </thead>
      <tbody>
      <?php while ($r = $recent->fetch_assoc()): ?>
        <tr>
          <td><a style="color:#7bd" href="../snippets/view_snippet.php?id=<?= $r['id'] ?>" target="_blank"><?= htmlspecialchars($r['title']) ?></a></td>
          <td><span class="badge"><?= htmlspecialchars($r['language']) ?></span></td>
          <td><?= htmlspecialchars($r['username']) ?></td>
          <td class="muted"><?= htmlspecialchars($r['created_at']) ?></td>
        </tr>
      <?php endwhile; ?>
      </tbody>
    </table>
  </div>
  <a href="../dashboard.php" style="margin:0 20px 20px; display:inline-block; color:#7bd;">← Back to Main Page</a>
</body>
</html>
