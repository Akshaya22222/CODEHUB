<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$query = "SELECT s.* FROM snippets s
          INNER JOIN bookmarks b ON s.id = b.snippet_id
          WHERE b.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Bookmarked Snippets</title>
    <style>
        /* --- Global Reset --- */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #1e272e, #273c75, #192a56);
            color: #fff;
            min-height: 100vh;
        }

        header {
            background: linear-gradient(135deg, #00a8ff, #9c88ff);
            color: #fff;
            padding: 25px;
            text-align: center;
            font-size: 1.9em;
            font-weight: bold;
            letter-spacing: 1px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.3);
        }

        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .snippet-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 25px;
        }

        /* --- Glassmorphic Cards --- */
        .snippet-card {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 20px;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 4px 12px rgba(0,0,0,0.25);
            position: relative;
            overflow: hidden;
        }

        .snippet-card:hover {
            transform: translateY(-6px) scale(1.02);
            box-shadow: 0 6px 18px rgba(0,0,0,0.4);
        }

        .snippet-card h3 {
            font-size: 1.3em;
            color: #f1f2f6;
            margin-bottom: 12px;
        }

        pre {
            background: #2f3640;
            color: #f5f6fa;
            padding: 14px;
            border-radius: 10px;
            font-size: 0.9em;
            max-height: 200px;
            overflow-y: auto;
            line-height: 1.4em;
        }

        /* --- Button --- */
        a.view-btn {
            display: inline-block;
            text-decoration: none;
            background: linear-gradient(135deg, #00a8ff, #9c88ff);
            color: white;
            padding: 10px 18px;
            border-radius: 30px;
            font-size: 0.9em;
            margin-top: 14px;
            transition: background 0.3s ease, transform 0.2s ease;
        }

        a.view-btn:hover {
            background: linear-gradient(135deg, #9c88ff, #00a8ff);
            transform: scale(1.05);
        }

        /* --- Floating Glow Effect --- */
        .snippet-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: conic-gradient(from 180deg, #00a8ff, #9c88ff, #00a8ff);
            animation: rotate 6s linear infinite;
            z-index: 0;
            opacity: 0.1;
        }

        .snippet-card > * {
            position: relative;
            z-index: 2;
        }

        @keyframes rotate {
            100% { transform: rotate(360deg); }
        }

        /* --- Empty State --- */
        .empty-message {
            text-align: center;
            font-size: 1.3em;
            color: #dcdde1;
            margin-top: 80px;
        }
    </style>
</head>
<body>

<header>📌 My Bookmarked Snippets</header>

<div class="container">
    <?php if ($result->num_rows > 0): ?>
        <div class="snippet-list">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="snippet-card">
                    <h3><?= htmlspecialchars($row['title']) ?></h3>
                    <pre><?= htmlspecialchars($row['code']) ?></pre>
                    <a href="view_snippet.php?id=<?= $row['id'] ?>" class="view-btn">View Snippet</a>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p class="empty-message">✨ You haven’t bookmarked any snippets yet.</p>
    <?php endif; ?>
</div>

</body>
</html>
