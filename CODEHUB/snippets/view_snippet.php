<?php
session_start();
require_once '../includes/db.php';

if (!isset($_GET['id'])) {
    die('Snippet ID not provided.');
}

$snippet_id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM snippets WHERE id = ?");
$stmt->bind_param("i", $snippet_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die('Snippet not found.');
}

$snippet = $result->fetch_assoc();

// Likes count
$like_stmt = $conn->prepare("SELECT COUNT(*) as likes FROM likes WHERE snippet_id = ?");
$like_stmt->bind_param("i", $snippet_id);
$like_stmt->execute();
$like_result = $like_stmt->get_result();
$like_data = $like_result->fetch_assoc();
$like_count = $like_data['likes'];

// Check if user liked
$liked = false;
if (isset($_SESSION['user_id'])) {
    $uid = $_SESSION['user_id'];
    $checkLike = $conn->prepare("SELECT * FROM likes WHERE user_id = ? AND snippet_id = ?");
    $checkLike->bind_param("ii", $uid, $snippet['id']);
    $checkLike->execute();
    $liked = $checkLike->get_result()->num_rows > 0;
}

// Check if bookmarked
$is_bookmarked = false;
if (isset($_SESSION['user_id'])) {
    $stmt = $conn->prepare("SELECT id FROM bookmarks WHERE user_id = ? AND snippet_id = ?");
    $stmt->bind_param("ii", $_SESSION['user_id'], $snippet_id);
    $stmt->execute();
    $stmt->store_result();
    $is_bookmarked = $stmt->num_rows > 0;
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>View Snippet - <?= htmlspecialchars($snippet['title']); ?></title>

<!-- Prism Core -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism-tomorrow.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/prism.min.js"></script>

<!-- Prism Language Support -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-php.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-javascript.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-css.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-html.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-c.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-cpp.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-java.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-python.min.js"></script>
<style>
body {
    margin: 0;
    font-family: 'Poppins', sans-serif;
    background: #0b1220;
    color: #e5e7eb;
    padding: 40px 20px;
}

.container {
    max-width: 1100px;
    margin: auto;
}

h2 {
    text-align: center;
    font-size: 32px;
    background: linear-gradient(135deg, #ffd700, #ff4b2b);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    margin-bottom: 30px;
}

/* Card styling */
.card {
    background: rgba(255,255,255,0.05);
    border-radius: 16px;
    padding: 20px;
    margin-bottom: 25px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.4);
}

/* Code block */
pre {
    background: #7c7cc3ff;
    color: #fff;
    border-radius: 12px;
    padding: 15px;
    overflow-x: auto;
    max-height: 400px;
}


/* Buttons */
button, a {
    padding: 10px 20px;
    border-radius: 12px;
    margin: 10px 5px 0 0;
    cursor: pointer;
    border: none;
    font-weight: 600;
    text-decoration: none;
    display: inline-block;
}

.like-btn { background: linear-gradient(135deg, #ff416c, #ff4b2b); color: #fff; }
.like-btn:hover { transform: translateY(-2px); }

.bookmark-btn { background: transparent; border: 2px solid #fff; color: #fff; }
.bookmark-btn.active, .bookmark-btn:hover { background: #ffd700; border-color: #ffd700; color: #000; }

.version-btn { background: linear-gradient(135deg, #8e2de2, #4a00e0); color: #fff; }
.version-btn:hover { transform: translateY(-2px); }

.back-btn { background: #6c757d; color: #fff; }
.back-btn:hover { background: #5a6268; }

/* Comments */
.comment-box {
    background: rgba(255,255,255,0.08);
    border-radius: 12px;
    padding: 14px 16px;
    margin: 12px 0;
}
.comment-box strong { color: #ffd700; }

form input, form textarea {
    width: 100%;
    padding: 12px;
    margin: 8px 0;
    border-radius: 10px;
    border: none;
    background: rgba(255,255,255,0.1);
    color: #fff;
}
form input:focus, form textarea:focus {
    border: 1px solid #00eaff;
    outline: none;
    box-shadow: 0 0 10px #00eaff;
}
form button {
    background: linear-gradient(135deg, #00c6ff, #0072ff);
    color: #fff;
}
form button:hover {
    transform: scale(1.05);
}
</style>
</head>
<body>
<div class="container">
    <h2><?= htmlspecialchars($snippet['title']); ?> (<?= htmlspecialchars($snippet['language']); ?>)</h2>

    <div class="card">
    <h3>💻 Output</h3>

    <?php if (in_array(strtolower($snippet['language']), ['html','css','javascript'])): ?>
        <iframe class="output-frame" srcdoc="
        <html>
        <head>
        <style>
            body {
                background-color: #1e1e2f; /* dark background */
                color: #ffffff;            /* bright white text */
                font-family: monospace;
                padding: 10px;
                
            }
        </style>
        </head>
        <body>
            <?= htmlspecialchars($snippet['code']); ?>
        </body>
        </html>
        "></iframe>
    <?php else: ?>
        <pre style="background:#1e1e2f; color:#ffffff; padding:12px; border-radius:8px; overflow:auto;">
            <code class="language-<?= strtolower($snippet['language']); ?>">
                <?= htmlspecialchars($snippet['output'] ?? $snippet['code']); ?>
            </code>
        </pre>
    <?php endif; ?>
</div>


    <div class="card">
        <h3>📝 Source Code</h3>
        <pre><code class="language-<?= strtolower($snippet['language']); ?>"><?= htmlspecialchars($snippet['code']); ?></code></pre>
    </div>

    <a href="snippet_versions.php?id=<?= $snippet_id ?>" class="version-btn">📜 Version History</a>

    <button class="like-btn" data-snippet-id="<?= $snippet['id']; ?>">
        <?= $liked ? "💔 Unlike" : "❤️ Like" ?>
    </button>
    <span id="like-count-<?= $snippet['id']; ?>"><?= $like_count ?></span> likes

    <form action="bookmark_handler.php" method="post" style="display:inline;">
        <input type="hidden" name="snippet_id" value="<?= $snippet_id ?>">
        <button id="bookmarkBtn" class="bookmark-btn <?= $is_bookmarked ? 'active' : '' ?>" data-snippet-id="<?= $snippet_id ?>" data-bookmarked="<?= $is_bookmarked ? '1' : '0' ?>">
            <?= $is_bookmarked ? 'Unbookmark' : 'Bookmark' ?>
        </button>
        <span id="bookmarkStatus"></span>
    </form>

    <h3>Leave a Comment</h3>
    <form action="comment_handler.php" method="POST">
        <input type="hidden" name="snippet_id" value="<?= $snippet['id']; ?>">
        <input type="text" name="user_name" placeholder="Your Name" required>
        <textarea name="comment" placeholder="Your Comment" required></textarea>
        <button type="submit">Post Comment</button>
    </form>

    <h3>Comments</h3>
    <?php
    $comments = $conn->query("SELECT * FROM comments WHERE snippet_id = $snippet_id ORDER BY created_at DESC");
    while ($row = $comments->fetch_assoc()) {
        echo "<div class='comment-box'><strong>".htmlspecialchars($row['user_name'])."</strong> at ".$row['created_at']."<br>".nl2br(htmlspecialchars($row['comment']))."</div>";
    }
    ?>

    <a href="../dashboard.php" class="back-btn">← Back to Dashboard</a>
</div>

<script>
// Like Button
document.querySelectorAll('.like-btn').forEach(btn => {
  btn.addEventListener('click', function () {
    const id = this.dataset.snippetId;
    fetch('like_handler.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: `snippet_id=${id}`
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        this.textContent = data.action === 'liked' ? '💔 Unlike' : '❤️ Like';
        document.getElementById(`like-count-${id}`).textContent = data.likes;
      }
    });
  });
});

// Bookmark Button
document.getElementById("bookmarkBtn").addEventListener("click", function(e){
    e.preventDefault();
    const btn = this;
    const id = btn.dataset.snippetId;
    const isBookmarked = btn.dataset.bookmarked === "1";

    fetch("bookmark_handler.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `snippet_id=${id}&${isBookmarked ? "unbookmark" : "bookmark"}=1`
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            btn.textContent = data.bookmarked ? "Unbookmark" : "Bookmark";
            btn.dataset.bookmarked = data.bookmarked ? "1" : "0";
            document.getElementById("bookmarkStatus").textContent = data.bookmarked ? "✅ Bookmarked!" : "❌ Removed.";
            btn.classList.toggle("active", data.bookmarked);
        }
    });
});
</script>
</body>
</html>
