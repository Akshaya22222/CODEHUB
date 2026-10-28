<?php
// admin/admin_users.php
session_start();

require_once '../includes/db.php';
if (!isset($_SESSION['admin_id'])) 
  {
    header('Location: admin_login.php');
    exit();
}


// Handle actions: promote/demote/delete
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uid = (int)($_POST['uid'] ?? 0);
    if (isset($_POST['make_admin'])) {
        $stmt = $conn->prepare("UPDATE users SET role='admin' WHERE id=?");
        $stmt->bind_param("i", $uid);
        $stmt->execute();
    } elseif (isset($_POST['make_user'])) {
        $stmt = $conn->prepare("UPDATE users SET role='user' WHERE id=?");
        $stmt->bind_param("i", $uid);
        $stmt->execute();
    } elseif (isset($_POST['delete_user'])) {
        // delete cascade (simple): remove their comments/likes/bookmarks/snippets
        $conn->query("DELETE FROM comments WHERE user_id=$uid");
        $conn->query("DELETE FROM likes WHERE user_id=$uid");
        $conn->query("DELETE FROM bookmarks WHERE user_id=$uid");
        $conn->query("DELETE FROM snippets WHERE user_id=$uid");
        $conn->query("DELETE FROM users WHERE id=$uid");
    }
    header('Location: admin_users.php');
    exit();
}

$users = $conn->query("SELECT id, username, email, role, created_at FROM users ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Users - Admin</title>
<style>
    body {font-family: Arial, sans-serif; margin:0; background:#0f1225; color:#eaeaea;}
    .topbar {display:flex; align-items:center; justify-content:space-between; padding:16px 22px; background:#171a33;}
    .nav a {color:#eaeaea; text-decoration:none; margin:0 8px; padding:8px 12px; border-radius:8px; background:#1d2140;}
    .nav a:hover {background:#232752;}
    .wrap {padding:20px;}
    table {width:100%; border-collapse:collapse; background:#171a33; border-radius:12px; overflow:hidden;}
    th, td {padding:12px; border-bottom:1px solid #262a52; text-align:left; font-size:14px;}
    .muted {color:#9aa;}
    .btn {border:none; padding:6px 10px; border-radius:8px; cursor:pointer; color:#fff; margin-right:6px;}
    .b1 {background:#6c5ce7;} .b2 {background:#00b894;} .b3 {background:#e74c3c;}
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
  <h2>Manage Users</h2>
  <table>
    <thead><tr><th>ID</th><th>Username</th><th>Email</th><th>Role</th><th>Created</th><th>Actions</th></tr></thead>
    <tbody>
      <?php while ($u = $users->fetch_assoc()): ?>
      <tr>
        <td><?= $u['id'] ?></td>
        <td><?= htmlspecialchars($u['username']) ?></td>
        <td class="muted"><?= htmlspecialchars($u['email'] ?? '') ?></td>
        <td><strong><?= htmlspecialchars($u['role']) ?></strong></td>
        <td class="muted"><?= htmlspecialchars($u['created_at'] ?? '') ?></td>
        <td>
          <form method="post" style="display:inline">
            <input type="hidden" name="uid" value="<?= $u['id'] ?>">
            <?php if ($u['role'] !== 'admin'): ?>
              <button class="btn b1" name="make_admin">Make Admin</button>
            <?php else: ?>
              <button class="btn b2" name="make_user">Make User</button>
            <?php endif; ?>
            <button class="btn b3" name="delete_user" onclick="return confirm('Delete this user (and all their data)?')">Delete</button>
          </form>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>
</body>
</html>
