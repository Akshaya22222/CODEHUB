<?php
include '../includes/db.php';
session_start();

$snippet_id = $_GET['id'];

$query = $conn->prepare("SELECT * FROM snippet_versions WHERE snippet_id = ? ORDER BY updated_at DESC");
$query->bind_param("i", $snippet_id);
$query->execute();
$result = $query->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Version History</title>
    <style>
        body {
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #eef2f7, #f9fbfd);
            margin: 0;
            padding: 0;
            color: #2c3e50;
        }

        header {
            background: linear-gradient(135deg, #273c75, #192a56);
            color: #fff;
            padding: 20px;
            text-align: center;
            font-size: 1.8rem;
            font-weight: bold;
            letter-spacing: 1px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }

        .container {
            max-width: 1100px;
            margin: 40px auto;
            padding: 0 20px;
        }

        a.back-link {
            display: inline-block;
            margin-bottom: 25px;
            text-decoration: none;
            background: linear-gradient(135deg, #718093, #596275);
            color: white;
            padding: 10px 18px;
            border-radius: 8px;
            font-size: 0.95rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        a.back-link:hover {
            background: linear-gradient(135deg, #57606f, #2f3542);
            transform: translateY(-2px);
            box-shadow: 0 6px 14px rgba(0,0,0,0.15);
        }

        .snippet-version {
            background: #fff;
            border-radius: 14px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.08);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
            animation: fadeInUp 0.6s ease;
        }

        .snippet-version:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 22px rgba(0,0,0,0.12);
        }

        .snippet-version h3 {
            margin-top: 0;
            font-size: 1.4rem;
            color: #273c75;
            margin-bottom: 15px;
        }

        pre {
            background: #1e1e2f;
            color: #f8f8f2;
            padding: 15px;
            border-radius: 10px;
            overflow-x: auto;
            font-size: 0.95rem;
            font-family: "Fira Code", monospace;
            line-height: 1.6;
            box-shadow: inset 0 0 12px rgba(0,0,0,0.3);
            margin-bottom: 15px;
            transition: background 0.3s ease;
        }

        pre:hover {
            background: #2a2a40;
        }

        p {
            margin: 8px 0;
            font-size: 0.95rem;
            color: #444;
        }

        strong {
            color: #192a56;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>

<header>
    Version History
</header>

<div class="container">
    <a href="view_snippet.php?id=<?= $snippet_id ?>" class="back-link">← Back to Snippet</a>

    <?php while ($version = $result->fetch_assoc()) { ?>
        <div class="snippet-version">
            <h3><?= htmlspecialchars($version['title']) ?> (<?= $version['updated_at'] ?>)</h3>
            <pre><code><?= htmlspecialchars($version['code']) ?></code></pre>
            <p><strong>Language:</strong> <?= htmlspecialchars($version['language']) ?></p>
            <p><strong>Description:</strong> <?= htmlspecialchars($version['description']) ?></p>
        </div>
    <?php } ?>
</div>

</body>
</html>
