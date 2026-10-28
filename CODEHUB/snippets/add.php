<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $language = trim($_POST['language']);
    $description = trim($_POST['description']);
    $code = $_POST['code'];
    $tags = trim($_POST['tags']);
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO snippets (user_id, title, language, description, code, tags) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssss", $user_id, $title, $language, $description, $code, $tags);
    if ($stmt->execute()) {
        header("Location: ../dashboard.php");
        exit();
    } else {
        $msg = "Failed to save snippet!";
    }
}

// List of languages supported by runCode()/Judge0
$supported_languages = [
    'c' => 'C',
    'cpp' => 'C++',
    'java' => 'Java',
    'javascript' => 'JavaScript',
    'python' => 'Python',
    'php' => 'PHP',
    'html' => 'HTML',
    'css' => 'CSS',
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Add Snippet - Code Hub</title>
  <style>
    /* Reset & Styles (same as your existing ones) */
    * { margin: 0; padding: 0; box-sizing: border-box; font-family: "Segoe UI", Tahoma, sans-serif; }
    body { background: linear-gradient(135deg, #1f1c2c, #928dab); color: #fff; display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 20px; }
    .add-container { background: #2a2a3b; padding: 30px; border-radius: 16px; box-shadow: 0 8px 25px rgba(0,0,0,0.4); width: 100%; max-width: 650px; transition: all 0.3s ease; }
    .add-container:hover { transform: translateY(-4px); box-shadow: 0 12px 30px rgba(0,0,0,0.6); }
    h2 { text-align: center; margin-bottom: 20px; font-size: 26px; letter-spacing: 1px; }
    input, textarea, select { width: 100%; padding: 12px; margin: 10px 0; border: none; border-radius: 10px; font-size: 15px; background: #383854; color: #fff; outline: none; transition: 0.3s; }
    input::placeholder, textarea::placeholder { color: #aaa; }
    input:focus, textarea:focus, select:focus { background: #454567; border: 1px solid #6a5acd; box-shadow: 0 0 8px #6a5acd; }
    textarea[name="code"] { font-family: "Fira Code", "Courier New", monospace; font-size: 14px; min-height: 200px; background: #1e1e2f; color: #00ffcc; line-height: 1.5; border: 1px solid #444; resize: vertical; }
    textarea[name="code"]:focus { background: #252547; border: 1px solid #00ffcc; box-shadow: 0 0 10px #00ffcc88; }
    button { width: 100%; padding: 12px; background: linear-gradient(135deg, #6a5acd, #836fff); border: none; border-radius: 10px; font-size: 16px; font-weight: bold; color: #fff; cursor: pointer; margin-top: 15px; transition: 0.3s; }
    button:hover { background: linear-gradient(135deg, #836fff, #6a5acd); transform: scale(1.03); box-shadow: 0 4px 12px rgba(106,90,205,0.6); }
    .back { display: block; text-align: center; margin-top: 15px; color: #bbb; text-decoration: none; font-size: 14px; transition: 0.3s; }
    .back:hover { color: #fff; }
    .error { color: #ff6b6b; background: #3b2a2a; padding: 10px; border-radius: 8px; text-align: center; margin-bottom: 15px; font-size: 14px; }
  </style>
</head>
<body>
  <div class="add-container">
    <form method="POST">
      <h2>Add Snippet</h2>
      <?php if ($msg) echo "<p class='error'>$msg</p>"; ?>

      <input type="text" name="title" placeholder="Title" required />

      <!-- Language Dropdown -->
      <select name="language" required>
        <option value="">Select Language</option>
        <?php foreach ($supported_languages as $key => $val): ?>
          <option value="<?= $key ?>"><?= $val ?></option>
        <?php endforeach; ?>
      </select>

      <input type="text" name="tags" placeholder="Tags (comma separated)" />
      <textarea name="description" rows="3" placeholder="Short Description"></textarea>
      <textarea name="code" rows="10" placeholder="Your code..." required></textarea>

      <button type="submit">Save Snippet</button>
      <a href="../dashboard.php" class="back">← Back to Dashboard</a>
    </form>
  </div>
</body>
</html>
