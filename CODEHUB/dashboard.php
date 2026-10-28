<?php
session_start();
require_once 'includes/db.php';

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}

// Get logged in user info
$user_id  = $_SESSION['user_id'];
$username = $_SESSION['username'];
$role     = $_SESSION['role'] ?? 'user'; // Default role: user

// Fetch full user details from DB
$stmt = $conn->prepare("SELECT username, email, created_at FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$email = $user['email'] ?? 'Not available';
$created_at = $user['created_at'] ?? null;

// Get all snippets with author info
$snippets = $conn->query("
    SELECT snippets.*, users.username 
    FROM snippets 
    JOIN users ON snippets.user_id = users.id 
    ORDER BY snippets.created_at DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Dashboard - Code Hub</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism.min.css">

    <!-- Google Custom Search (Global Search) -->
    <script async src="https://cse.google.com/cse.js?cx=02cd23809d27041be"></script>

    <style>
         :root{
            --bg: #0b1220;
            --panel: rgba(20,26,40,0.8);
            --card: rgba(255,255,255,0.06);
            --accent: #38bdf8;
            --accent-2: #34d399;
            --danger: #ef4444;
            --warning: #f59e0b;
            --text: #e5e7eb;
            --muted: #94a3b8;
            --shadow: 0 10px 30px rgba(0,0,0,0.35);
            --radius: 16px;
            --bezier: cubic-bezier(.2,.8,.2,1);
        }

        /* Base */
        * { box-sizing: border-box }
        body{
            margin:0;
            font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, "Helvetica Neue", Arial;
            color: var(--text);
            background:
                radial-gradient(80vw 60vw at 10% -10%, #0ea5e980 0%, transparent 60%),
                radial-gradient(60vw 50vw at 110% 10%, #22c55e60 0%, transparent 60%),
                var(--bg);
        }

        /* Header */
        header{
            position: sticky; top:0; z-index: 50;
            background: linear-gradient(180deg, rgba(10,14,22,0.9), rgba(10,14,22,0.6));
            backdrop-filter: blur(10px);
            padding: 18px 24px;
            display:flex; align-items:center; justify-content:space-between; gap:16px; flex-wrap:wrap;
            border-bottom: 1px solid rgba(255,255,255,0.06);
        }
        header h1{ margin:0; font-size: 22px; letter-spacing: .3px }

        /* Buttons */
        .btn, .dashboard-link{
            display:inline-flex; align-items:center; justify-content:center;
            gap:8px;
            padding: 10px 14px;
            border-radius: 999px;
            text-decoration: none;
            color: #fff;
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            box-shadow: 0 6px 16px rgba(37,99,235,.35);
            transition: transform .25s var(--bezier), box-shadow .25s var(--bezier), filter .25s;
            will-change: transform;
        }
        .btn:hover, .dashboard-link:hover{ transform: translateY(-2px) }
        .btn.logout{ background: linear-gradient(135deg, #f87171, #ef4444); box-shadow: 0 6px 16px rgba(239,68,68,.35) }
        .btn.admin{ background: linear-gradient(135deg, #f59e0b, #d97706); box-shadow: 0 6px 16px rgba(245,158,11,.35) }

        /* Profile trigger */
        .profile-btn{
            position: fixed; top: 20px; left: 20px; z-index: 1001;
            border: 0; color:#0b1220; font-weight:700;
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            padding: 10px 14px; border-radius: 12px; cursor:pointer;
            box-shadow: 0 8px 20px rgba(251,191,36,.35);
            transition: transform .25s var(--bezier), box-shadow .25s var(--bezier);
        }
        .profile-btn:hover{ transform: translateY(-2px); box-shadow: 0 10px 24px rgba(251,191,36,.45) }

        /* Sidebar + Overlay (enhanced transitions) */
        .sidebar-overlay{
            position: fixed; inset: 0; background: rgba(3,7,18,0.5);
            backdrop-filter: blur(2px);
            opacity: 0; visibility: hidden;
            transition: opacity .35s var(--bezier), visibility .35s var(--bezier);
            z-index: 998;
        }
        .sidebar-overlay.active{ opacity:1; visibility: visible }

        .profile-sidebar{
            position: fixed; inset: 0 auto 0 0; width: 320px; max-width: 88vw;
            background: linear-gradient(180deg, rgba(17,24,39,.95), rgba(12,18,30,.95));
            border-right: 1px solid rgba(255,255,255,0.06);
            box-shadow: var(--shadow);
            transform: translateX(-110%) scale(.98);
            opacity: 0;
            transition: transform .5s var(--bezier), opacity .5s var(--bezier);
            will-change: transform, opacity;
            z-index: 999;
            padding: 20px 18px;
        }
        .profile-sidebar.active{
            transform: translateX(0) scale(1);
            opacity: 1;
        }

        .profile-header{
            display:flex; align-items:center; justify-content:space-between; gap:8px;
            margin-bottom: 10px;
        }
        .profile-header h2{ margin:0; font-size: 18px; color:#fff }
        .close-btn{
            width: 36px; height: 36px; display:grid; place-items:center;
            border-radius: 10px; cursor:pointer;
            transition: transform .35s var(--bezier), background .25s, color .25s;
        }
        .close-btn:hover{ background: rgba(255,255,255,0.06); transform: rotate(90deg) }

        .profile-content{ margin-top: 10px; text-align:center }
        .profile-pic{
            width: 96px; height: 96px; border-radius: 50%;
            border: 2px solid rgba(255,255,255,0.25);
            box-shadow: 0 8px 24px rgba(0,0,0,.4);
            display:block; margin: 6px auto 10px;
            transition: transform .4s var(--bezier), filter .4s;
        }
        .profile-sidebar.active .profile-pic{ transform: translateY(0) }
        .profile-content p{ margin: 6px 0; color: var(--muted) }

        /* Sidebar menu (staggered reveal) */
        .sidebar-menu{ list-style:none; padding:0; margin: 16px 0; text-align:left }
        .sidebar-menu li{ margin: 8px 0 }
        .sidebar-menu a{
            color: #e2e8f0; text-decoration:none; display:block;
            padding: 10px 12px; border-radius: 12px;
            background: transparent;
            transition: background .25s, color .25s, transform .25s var(--bezier);
            transform: translateX(-6px);
            opacity: 0;
        }
        /* Stagger animation using nth-child */
        .profile-sidebar.active .sidebar-menu li:nth-child(1) a{ animation: menuIn .45s var(--bezier) .05s forwards }
        .profile-sidebar.active .sidebar-menu li:nth-child(2) a{ animation: menuIn .45s var(--bezier) .10s forwards }
        .profile-sidebar.active .sidebar-menu li:nth-child(3) a{ animation: menuIn .45s var(--bezier) .15s forwards }
        .profile-sidebar.active .sidebar-menu li:nth-child(4) a{ animation: menuIn .45s var(--bezier) .20s forwards }

        @keyframes menuIn {
            from { transform: translateX(-12px); opacity: 0 }
            to   { transform: translateX(0); opacity: 1 }
        }

        .sidebar-menu a:hover{ background:#0b1220; color: var(--accent); transform: translateX(2px) }
        .logout-link{
            color:#fff; text-decoration:none; display:block;
            padding: 10px 12px; border-radius: 12px; text-align:center;
            background: linear-gradient(135deg, #ef4444, #b91c1c);
            box-shadow: 0 8px 18px rgba(239,68,68,.35);
            margin-top: 14px;
            transition: transform .25s var(--bezier), box-shadow .25s var(--bezier);
        }
        .logout-link:hover{ transform: translateY(-2px) }

        /* Search bars */
        .search-wrap{
            padding: 16px 24px;
            display:flex; align-items:center; gap:10px; flex-wrap: wrap;
        }
        .search-wrap input[type="text"]{
            background: rgba(255,255,255,0.06); color: var(--text);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 12px; padding: 10px 12px; min-width: 240px;
            outline: none;
            transition: border-color .25s, box-shadow .25s;
        }
        .search-wrap input[type="text"]:focus{
            border-color: #38bdf8;
            box-shadow: 0 0 0 4px rgba(56,189,248,.15);
        }
        .search-button{
            background: linear-gradient(135deg, #34d399, #059669);
            color:#fff; border:0; border-radius: 12px; padding: 10px 14px; cursor:pointer;
            transition: transform .2s var(--bezier), box-shadow .2s var(--bezier);
            box-shadow: 0 8px 18px rgba(16,185,129,.25);
            font-weight: 600;
        }
        .search-button:hover{ transform: translateY(-1px) }

        /* Sections */
        h2{ margin: 16px 24px 6px; font-size: 18px; font-weight:700; }
        .section-card{
            margin: 12px 24px 24px;
            background: var(--card);
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: var(--radius);
            padding: 16px;
            backdrop-filter: blur(8px);
            box-shadow: var(--shadow);
        }

        /* CSE wrapper styling */
        .cse-wrapper{
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 14px;
            padding: 10px;
        }
        /* Make Google CSE blend with dark UI */
        .gsc-control-cse, .gsc-control-cse .gsc-table-result{
            background: transparent !important;
        }
        .gsc-input, .gsc-input-box, input.gsc-input{
            background: rgba(255,255,255,0.08) !important;
            color: #e5e7eb !important;
            border-radius: 10px !important;
            border: 1px solid rgba(255,255,255,0.12) !important;
        }
        .gsc-search-button-v2{
            border-radius: 10px !important;
        }
        .gs-webResult .gs-snippet, .gs-fileFormat, .gs-visibleUrl{
            color: #cbd5e1 !important;
        }

        /* Snippet grid/cards */
        .snippet-feed{
            display:grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 20px; padding: 10px 24px 40px;
        }
        .snippet-card{
            background: var(--card); border:1px solid rgba(255,255,255,0.06);
            border-radius: var(--radius); padding: 16px;
            backdrop-filter: blur(8px);
            box-shadow: var(--shadow);
            transition: transform .25s var(--bezier), box-shadow .25s var(--bezier), border-color .25s;
        }
        .snippet-card:hover{
            transform: translateY(-6px);
            border-color: rgba(56,189,248,.45);
            box-shadow: 0 18px 40px rgba(0,0,0,.45);
        }
        .snippet-card h3{ margin:0 0 10px; font-size: 16px; color:#fff }
        .snippet-card iframe,
        .snippet-card .code-output{
            width:100%; height: 220px; border:1px solid rgba(255,255,255,0.08);
            border-radius: 10px; margin-top:8px; background:#0b1220;
        }
        pre{ margin:0 }
        code{ font-size: 13px }

        .snippet-meta{
            display:flex; justify-content:space-between; align-items:center;
            font-size: 12px; color: var(--muted); margin-top: 10px;
        }
        .snippet-card .btn{ margin-top: 10px; margin-right: 8px; padding: 8px 12px }
        .run-btn{ background: linear-gradient(135deg, #10b981, #059669) }

        /* Utility */
        hr{ border:0; border-top: 1px solid rgba(255,255,255,0.08); margin: 14px 0 }
        a.inline{ color: var(--accent) }
        .hidden{ display:none }
        @media (max-width: 560px){
            .profile-btn{ top: 14px; left: 14px }
        }
    </style>
</head>
<body>

<!-- Sidebar overlay -->
<div id="sidebarOverlay" class="sidebar-overlay" onclick="toggleProfile(false)"></div>

<!-- Profile Sidebar -->
<aside id="profileSidebar" class="profile-sidebar" aria-hidden="true">
    <div class="profile-header">
        <h2>👤 Profile</h2>
        <span class="close-btn" role="button" aria-label="Close sidebar" onclick="toggleProfile(false)">✕</span>
    </div>

    <div class="profile-content">
        <img src="default.png" alt="Profile Picture" class="profile-pic">
        <h3><?php echo htmlspecialchars($username); ?></h3>
        <p><?php echo htmlspecialchars($email); ?></p>
        <p><small>Member since: <?php echo $created_at ? date("d M Y", strtotime($created_at)) : "N/A"; ?></small></p>

        <hr>

        <ul class="sidebar-menu">
            <li><a href="snippets/view_snippet.php">📜 My Snippets</a></li>
            <li><a href="snippets/bookmarked_snippets.php">📌 Bookmarks</a></li>
            <li><a href="user/settings.php">⚙️ Settings</a></li>
            <li><a href="user/security.php">🔒 Security</a></li>
        </ul>

        <hr>

        <a class="logout-link" href="auth/logout.php">🚪 Logout</a>
    </div>
</aside>

<!-- Profile Button -->
<button id="profileBtn" class="profile-btn" aria-expanded="false" onclick="toggleProfile(true)">
    <i class="fa-solid fa-bars"></i> Profile
</button>

<header>
    <br><br><h1>Welcome, <?= htmlspecialchars($username) ?> 👋</h1>
    <nav>
        <?php if ($role === 'admin'): ?>
            <a href="../admin/admin_dashboard.php" class="btn admin">Admin Panel</a>
        <?php else: ?>
            <a href="user/user_dashboard.php" class="btn">User Dashboard</a>
        <?php endif; ?>
        <a href="snippets/add.php" class="btn">+ New Snippet</a>
        <a href="snippets/bookmarked_snippets.php" class="dashboard-link">📌 Bookmarked</a>
        <a href="auth/logout.php" class="btn logout">Logout</a>
    </nav>
</header>

<!-- Local Snippet Search -->
<div class="search-wrap">
    <form action="snippets/search_snippets.php" method="get" style="display:flex; gap:10px; align-items:center;">
        <input type="text" name="q" placeholder="Search your snippets..." required>
        <button type="submit" class="search-button">Search</button>
    </form>
</div>

<!-- Global Code Search (Google CSE) -->
<h2>🌐 Global Code Search</h2>
<div class="section-card">
    <div class="cse-wrapper">
        <!-- Google search box + results render here -->
        <div class="gcse-search"></div>
    </div>
    <p style="margin-top:10px; color:var(--muted); font-size:13px;">
        Tip: Use keywords like <em>“PHP PDO prepared statements example”</em> or <em>“JavaScript debounce function”</em>.
    </p>
</div>

<!-- Popular Snippets -->
<h2>🔥 Popular Snippets</h2>
<div class="section-card">
<?php
$popular = $conn->query("SELECT * FROM snippets ORDER BY likes DESC LIMIT 5");
while ($p = $popular->fetch_assoc()):
?>
    <div class="snippet-card" style="margin-bottom:14px;">
        <h3><?php echo htmlspecialchars($p['title']); ?></h3>
        <a href="snippets/view_snippet.php?id=<?php echo $p['id']; ?>" class="btn">View</a>
    </div>
<?php endwhile; ?>
</div>

<!-- Recent Snippets -->
<h2>🆕 Recent Snippets</h2>
<div class="section-card">
<?php
$recent = $conn->query("SELECT * FROM snippets ORDER BY created_at DESC LIMIT 5");
while ($r = $recent->fetch_assoc()):
?>
    <div class="snippet-card" style="margin-bottom:14px;">
        <h3><?php echo htmlspecialchars($r['title']); ?></h3>
        <a href="snippets/view_snippet.php?id=<?php echo $r['id']; ?>" class="btn">View</a>
    </div>
<?php endwhile; ?>
</div>

<!-- Snippets Feed -->
<main class="snippet-feed">
<?php while ($row = $snippets->fetch_assoc()): 
    $lang = strtolower($row['language']);
    $code = htmlspecialchars($row['code']);
?>
    <div class="snippet-card">
        <h3><?= htmlspecialchars($row['title']) ?></h3>

        <?php if (in_array($lang, ['html','css','javascript','c++','python'])): ?>
            <iframe sandbox="allow-scripts" srcdoc="<?= $code ?>"></iframe>
        <?php else: ?>
            <pre><code class="language-<?= $lang ?>"><?= $code ?></code></pre>

            <button class="btn run-btn" onclick="runCode(this)">Run Code</button>
<pre class="code-output" style="margin-top:8px;"></pre>
<textarea class="hidden code-content"><?= htmlspecialchars($row['code']) ?></textarea>
<select class="hidden language-select">
    <option value="<?= strtolower($row['language']) ?>" selected><?= strtolower($row['language']) ?></option>
</select>


        <?php endif; ?>

        <div class="snippet-meta">
            <span>By <?= htmlspecialchars($row['username']) ?></span>
            <span><?= date("d M Y", strtotime($row['created_at'])) ?></span>
        </div>

        <div>
            <a href="snippets/view_snippet.php?id=<?= $row['id'] ?>" class="btn">View</a>
            <?php if ($row['user_id'] == $user_id): ?>
                <a href="snippets/edit_snippet.php?id=<?= $row['id'] ?>" class="btn">Edit</a>
            <?php endif; ?>
        </div>
    </div>
<?php endwhile; ?>
</main>

<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/prism.min.js"></script>
<script>
/** Sidebar toggle **/
function toggleProfile(forceOpen){
    const sidebar = document.getElementById('profileSidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const btn = document.getElementById('profileBtn');
    let open = (typeof forceOpen === 'boolean') ? forceOpen : !sidebar.classList.contains('active');
    sidebar.classList.toggle('active', open);
    overlay.classList.toggle('active', open);
    sidebar.setAttribute('aria-hidden', String(!open));
    btn.setAttribute('aria-expanded', String(open));
}

document.addEventListener('keydown', (e)=>{
    if (e.key === 'Escape'){
        const sidebar = document.getElementById('profileSidebar');
        if (sidebar.classList.contains('active')) toggleProfile(false);
    }
});

/** Run Code Function using Piston API **/
function runCode(btn){
    const card = btn.closest('.snippet-card');
    const code = card.querySelector('.code-content').value;
    const lang = card.querySelector('.language-select').value;
    const outputEl = card.querySelector('.code-output');
    outputEl.textContent = 'Running...';

    fetch('snippets/run_code.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `program=${encodeURIComponent(code)}&language=${encodeURIComponent(lang)}`
    })
    .then(res => res.json())
    .then(data => { 
        outputEl.textContent = data.output || 'No output';
    })
    .catch(err => { outputEl.textContent = 'Error: ' + err })
}


</script>
</body>
</html>
