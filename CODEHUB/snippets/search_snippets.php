<?php
include '../includes/db.php';
$search = isset($_GET['q']) ? trim($_GET['q']) : '';

$sql = "
    SELECT *,
    CASE
        WHEN title LIKE ? THEN 1
        WHEN description LIKE ? THEN 2
        WHEN tags LIKE ? THEN 3
        WHEN code LIKE ? THEN 4
        ELSE 5
    END AS rank
    FROM snippets
    WHERE title LIKE ?
       OR description LIKE ?
       OR tags LIKE ?
       OR code LIKE ?
    ORDER BY rank ASC, created_at DESC
";

$stmt = $conn->prepare($sql);
$searchParam = "%$search%";
$stmt->bind_param(
    'ssssssss',
    $searchParam, $searchParam, $searchParam, $searchParam,
    $searchParam, $searchParam, $searchParam, $searchParam
);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Results - Code Hub</title>
    <style>
        /* General Page Styling */
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
            color: #f1f1f1;
            margin: 0;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #ffcc00;
            text-shadow: 0px 2px 6px rgba(0, 0, 0, 0.6);
            margin-bottom: 30px;
        }

        /* Snippet Card Styling */
        .snippet-card {
            background: rgba(255, 255, 255, 0.08);
            padding: 20px;
            border-radius: 15px;
            margin: 15px auto;
            max-width: 800px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.4);
            transition: transform 0.2s ease, box-shadow 0.3s ease;
        }

        .snippet-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.6);
        }

        .snippet-card h2 {
            margin: 0 0 10px;
            font-size: 22px;
            color: #ffcc00;
        }

        .snippet-card p {
            font-size: 15px;
            color: #dcdcdc;
            margin: 0 0 12px;
            line-height: 1.5;
        }

        .snippet-card small {
            display: inline-block;
            margin-bottom: 12px;
            font-size: 13px;
            color: #a9a9a9;
        }

        /* Links */
        .snippet-card a {
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 8px;
            background: #ffcc00;
            color: #1a1a1a;
            font-weight: bold;
            transition: background 0.3s ease, color 0.3s ease;
        }

        .snippet-card a:hover {
            background: #ffaa00;
            color: #fff;
        }

        /* Highlighted Search */
        .highlight {
            background: #ffeb3b;
            color: #000;
            padding: 2px 4px;
            border-radius: 4px;
        }

        /* No results */
        .no-results {
            text-align: center;
            font-size: 18px;
            margin-top: 40px;
            color: #ff6666;
        }
    </style>
</head>
<body>

<h1>Search Results for "<?php echo htmlspecialchars($search); ?>"</h1>

<?php if ($result->num_rows > 0): ?>
    <?php while ($row = $result->fetch_assoc()): ?>
        <div class="snippet-card">
            <h2><?php echo htmlspecialchars($row['title']); ?></h2>
            <p><?php echo htmlspecialchars(substr($row['description'], 0, 120)); ?>...</p>
            <small>Tags: <?php echo htmlspecialchars($row['tags']); ?></small><br>
            <a href="view_snippet.php?id=<?php echo $row['id']; ?>">🔍 View Snippet</a>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p class="no-results">🚫 No snippets found.</p>
<?php endif; ?>

</body>
</html>
