<?php
// user_dashboard.php
session_start();
require_once '../includes/db.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Function to get count safely
function getCount($conn, $table, $column, $id) {
    $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM $table WHERE $column = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    return $result['total'] ?? 0;
}

$user_snippets  = getCount($conn, 'snippets', 'user_id', $user_id);
$user_comments  = getCount($conn, 'comments', 'user_id', $user_id);
$user_likes     = getCount($conn, 'likes', 'user_id', $user_id);
$user_bookmarks = getCount($conn, 'bookmarks', 'user_id', $user_id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Dashboard - CodeHub</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Inter', sans-serif;
      margin: 0;
      padding: 0;
      background: linear-gradient(135deg, #eef2f3, #dfe9f3);
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    header {
      background: #273c75;
      color: white;
      padding: 18px 25px 60px;
      text-align: center;
      font-size: 1.6em;
      font-weight: 600;
      position: relative;
      box-shadow: 0 3px 6px rgba(0,0,0,0.15);
      animation: slideDown 0.6s ease-out;
    }

    header .avatar {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      background: linear-gradient(135deg, #00c6ff, #0072ff);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 2em;
      color: white;
      margin: 10px auto;
      box-shadow: 0 4px 10px rgba(0,0,0,0.2);
      animation: glowPulse 2s infinite alternate;
    }

    .logout {
      position: absolute;
      top: 15px;
      right: 20px;
      background: #e84118;
      color: white;
      text-decoration: none;
      padding: 8px 16px;
      border-radius: 8px;
      font-size: 0.9em;
      transition: all 0.3s ease;
    }

    .logout:hover {
      background: #c23616;
      transform: scale(1.05);
    }

    .dashboard {
      flex: 1;
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
      gap: 25px;
      padding: 30px;
      animation: fadeIn 0.8s ease-in;
    }

    .card {
      background: rgba(255, 255, 255, 0.85);
      padding: 30px;
      border-radius: 16px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      text-align: center;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
      backdrop-filter: blur(8px);
    }

    .card::before {
      content: "";
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: linear-gradient(135deg, #009ffd, #2a2a72);
      transform: rotate(45deg);
      opacity: 0;
      transition: opacity 0.4s ease;
      z-index: 0;
    }

    .card:hover::before {
      opacity: 0.12;
    }

    .card h3 {
      margin: 0;
      font-size: 2.6em;
      color: #192a56;
      z-index: 1;
      position: relative;
    }

    .card p {
      font-size: 1.1em;
      color: #555;
      margin-top: 8px;
      z-index: 1;
      position: relative;
    }

    .card:hover {
      transform: translateY(-8px) scale(1.04);
      box-shadow: 0 10px 22px rgba(0,0,0,0.18);
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(15px); }
      to { opacity: 1; transform: translateY(0); }
    }

    @keyframes slideDown {
      from { transform: translateY(-100%); }
      to { transform: translateY(0); }
    }

    @keyframes glowPulse {
      from { box-shadow: 0 0 10px rgba(0,114,255,0.6); }
      to { box-shadow: 0 0 25px rgba(0,114,255,1); }
    }
  </style>
</head>
<body>

<header>
  <div class="avatar"><?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?></div>
  Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?> 👋
  <a href="../auth/logout.php" class="logout">Logout</a>
  <a href="../dashboard.php" class="logout">Back to Dashboard</a>
</header>

<div class="dashboard">
  <div class="card">
    <h3><?php echo $user_snippets; ?></h3>
    <p>Your Snippets</p>
  </div>
  <div class="card">
    <h3><?php echo $user_comments; ?></h3>
    <p>Your Comments</p>
  </div>
  <div class="card">
    <h3><?php echo $user_likes; ?></h3>
    <p>Snippets You Liked</p>
  </div>
  <div class="card">
    <h3><?php echo $user_bookmarks; ?></h3>
    <p>Your Bookmarked Snippets</p>
  </div>
</div>

</body>
</html>

