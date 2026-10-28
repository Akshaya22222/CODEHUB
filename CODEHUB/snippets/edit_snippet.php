<?php
include '../includes/db.php';
session_start();

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Get snippet ID
$snippet_id = $_GET['id'] ?? $_POST['snippet_id'] ?? null;
if (!$snippet_id) {
    die("No snippet ID provided.");
}

// Fetch snippet details
$get_snippet = $conn->prepare("SELECT * FROM snippets WHERE id = ?");
$get_snippet->bind_param("i", $snippet_id);
$get_snippet->execute();
$result = $get_snippet->get_result();
if ($result->num_rows === 0) {
    die("Snippet not found.");
}
$snippet = $result->fetch_assoc();

// Handle update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'] ?? '';
    $language = $_POST['language'] ?? '';
    $code = $_POST['code'] ?? '';
    $description = $_POST['description'] ?? '';

    if (empty($title) || empty($language) || empty($code)) {
        echo "All required fields must be filled.";
    } else {
        // Save old version
        $save_version = $conn->prepare("INSERT INTO snippet_versions (snippet_id, title, code, language, description, updated_at) VALUES (?, ?, ?, ?, ?, NOW())");
        $save_version->bind_param("issss", $snippet_id, $snippet['title'], $snippet['code'], $snippet['language'], $snippet['description']);
        $save_version->execute();

        // Update snippet
        $update = $conn->prepare("UPDATE snippets SET title = ?, code = ?, language = ?, description = ? WHERE id = ?");
        $update->bind_param("ssssi", $title, $code, $language, $description, $snippet_id);

        if ($update->execute()) {
            header("Location: ../snippets/view_snippet.php?id=$snippet_id");
            exit();
        } else {
            echo "Update failed: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Snippet</title>
    <style>
        /* --- Reset --- */
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #1e272e, #273c75, #192a56);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            color: #f5f6fa;
        }

        .container {
            width: 100%;
            max-width: 700px;
        }

        .snippet-card {
            background: rgba(255, 255, 255, 0.08);
            border-radius: 16px;
            padding: 30px;
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
            animation: fadeIn 0.8s ease-in-out;
        }

        .snippet-card h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 1.8em;
            background: linear-gradient(135deg, #00a8ff, #9c88ff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        label {
            font-weight: bold;
            font-size: 0.95em;
            margin-bottom: 6px;
            display: inline-block;
            color: #dcdde1;
        }

        input[type="text"], textarea {
            width: 100%;
            padding: 12px 14px;
            margin-bottom: 20px;
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.25);
            background: rgba(255, 255, 255, 0.1);
            color: #f5f6fa;
            font-size: 0.95em;
            transition: border 0.3s ease, box-shadow 0.3s ease;
        }

        textarea[name="code"] {
            font-family: "Fira Code", Consolas, monospace;
            font-size: 0.95em;
            background: #2f3640;
            color: #f5f6fa;
            border: 1px solid #444;
            resize: vertical;
        }

        input:focus, textarea:focus {
            border: 1px solid #00a8ff;
            outline: none;
            box-shadow: 0 0 6px rgba(0, 168, 255, 0.6);
        }

        button.save-btn, a.back-btn {
            display: inline-block;
            padding: 12px 20px;
            border-radius: 30px;
            text-decoration: none;
            font-size: 0.95em;
            font-weight: bold;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        button.save-btn {
            background: linear-gradient(135deg, #00a8ff, #9c88ff);
            color: white;
            border: none;
            margin-right: 10px;
        }

        button.save-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 14px rgba(0,0,0,0.3);
        }

        a.back-btn {
            background: rgba(255, 255, 255, 0.12);
            color: #dcdde1;
            border: 1px solid rgba(255, 255, 255, 0.25);
            padding: 12px 20px;
        }

        a.back-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: scale(1.05);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="snippet-card">
            <h2>Edit Snippet</h2>
            <form method="POST">
                <input type="hidden" name="snippet_id" value="<?php echo htmlspecialchars($snippet['id']); ?>">

                <label>Title:</label>
                <input type="text" name="title" value="<?php echo htmlspecialchars($snippet['title']); ?>" required>

                <label>Language:</label>
                <input type="text" name="language" value="<?php echo htmlspecialchars($snippet['language']); ?>" required>

                <label>Code:</label>
                <textarea name="code" rows="10" required><?php echo htmlspecialchars($snippet['code']); ?></textarea>

                <label>Description:</label>
                <textarea name="description" rows="5"><?php echo htmlspecialchars($snippet['description']); ?></textarea>

                <button type="submit" class="save-btn">💾 Save Changes</button>
                <a href="../dashboard.php" class="back-btn">← Go Back</a>
            </form>
        </div>
    </div>
</body>
</html>
