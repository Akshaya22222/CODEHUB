<?php
// info.php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Info - Code Hub</title>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@600;700&display=swap" rel="stylesheet">

  <style>
    /* Reset */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', sans-serif;
      background: linear-gradient(135deg, #0f172a, #1e293b);
      color: #f8fafc;
      overflow-x: hidden;
      line-height: 1.7;
    }

    /* Header */
    header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background: rgba(15, 23, 42, 0.95);
      backdrop-filter: blur(10px);
      padding: 18px 60px;
      position: sticky;
      top: 0;
      z-index: 1000;
      border-bottom: 1px solid rgba(255,255,255,0.1);
    }

    .logo {
      font-family: 'Poppins', sans-serif;
      font-size: 26px;
      font-weight: 700;
      color: #38bdf8;
      letter-spacing: 1px;
    }
    .logo span {
      color: #facc15;
    }

    nav ul {
      list-style: none;
      display: flex;
      gap: 28px;
    }

    nav ul li a {
      color: #f8fafc;
      text-decoration: none;
      font-weight: 500;
      font-size: 15px;
      padding: 6px 12px;
      border-radius: 8px;
      transition: all 0.3s ease;
    }

    nav ul li a:hover {
      background: rgba(56, 189, 248, 0.2);
      color: #38bdf8;
    }

    nav ul li a.active {
      color: #38bdf8;
      border-bottom: 2px solid #38bdf8;
    }

    /* Hero */
    .hero {
      text-align: center;
      padding: 120px 25px;
      background: rgba(30, 41, 59, 0.8);
      border-radius: 18px;
      margin: 60px auto;
      max-width: 1100px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.4);
      backdrop-filter: blur(12px);
      animation: fadeSlideUp 1s ease-in-out;
    }

    .hero h1 {
      font-family: 'Poppins', sans-serif;
      font-size: 50px;
      font-weight: 700;
      margin-bottom: 22px;
      background: linear-gradient(to right, #38bdf8, #facc15);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    .hero p {
      font-size: 19px;
      color: #cbd5e1;
      max-width: 750px;
      margin: 0 auto;
    }

    /* Sections */
    section {
      padding: 80px 60px;
      max-width: 1100px;
      margin: auto;
    }

    h2 {
      font-family: 'Poppins', sans-serif;
      font-size: 32px;
      font-weight: 700;
      margin-bottom: 25px;
      color: #38bdf8;
      text-align: center;
    }

    /* Info Cards */
    .info-card {
      background: rgba(30, 41, 59, 0.85);
      padding: 30px;
      border-radius: 16px;
      margin-bottom: 35px;
      box-shadow: 0 8px 25px rgba(0,0,0,0.3);
      backdrop-filter: blur(10px);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      animation: fadeSlideUp 1.2s ease-in-out;
    }

    .info-card:hover {
      transform: translateY(-6px);
      box-shadow: 0 15px 35px rgba(0,0,0,0.5);
    }

    .info-card h3 {
      font-family: 'Poppins', sans-serif;
      margin-bottom: 12px;
      color: #facc15;
    }

    .info-card p {
      color: #e2e8f0;
      font-size: 16px;
    }

    /* Footer */
    footer {
      background: #0f172a;
      text-align: center;
      padding: 22px;
      font-size: 14px;
      color: #94a3b8;
      border-top: 1px solid rgba(255,255,255,0.1);
    }

    footer a {
      color: #38bdf8;
      text-decoration: none;
      margin: 0 12px;
      font-weight: 500;
      transition: color 0.3s ease;
    }

    footer a:hover {
      color: #facc15;
    }

    /* Background Shapes */
    .bg-shape {
      position: absolute;
      border-radius: 50%;
      filter: blur(120px);
      z-index: -1;
    }
    .shape1 {
      width: 300px;
      height: 300px;
      background: #38bdf8;
      top: -100px;
      left: -100px;
    }
    .shape2 {
      width: 250px;
      height: 250px;
      background: #facc15;
      bottom: -100px;
      right: -80px;
    }

    /* Animations */
    @keyframes fadeSlideUp {
      0% { opacity: 0; transform: translateY(30px); }
      100% { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>
  <!-- Background Shapes -->
  <div class="bg-shape shape1"></div>
  <div class="bg-shape shape2"></div>

  <!-- Header -->
  <header>
    <div class="logo">Code<span>Hub</span></div>
    <nav>
      <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="info.php" class="active">Info</a></li>
        <li><a href="contact.php">Contact</a></li>
      </ul>
    </nav>
  </header>

  <!-- Hero -->
  <div class="hero">
    <h1>Discover Code Hub</h1>
    <p>Your collaborative platform for sharing, managing, and learning from code snippets — built for developers, by developers.</p>
  </div>

  <!-- Info Section -->
  <section>
    <h2>Why Choose Code Hub?</h2>

    <div class="info-card">
      <h3>🚀 Fast & Simple</h3>
      <p>Quickly upload, view, and share your code snippets without distractions. Code Hub is designed to be lightweight and developer-friendly.</p>
    </div>

    <div class="info-card">
      <h3>🌐 Multi-Language Support</h3>
      <p>Whether it’s PHP, Python, Java, or C++, you can store and share code across multiple programming languages.</p>
    </div>

    <div class="info-card">
      <h3>📚 Collaborative Learning</h3>
      <p>Developers can like, bookmark, and comment on snippets — making Code Hub a place for knowledge sharing and improvement.</p>
    </div>

    <div class="info-card">
      <h3>🔒 Secure & Organized</h3>
      <p>Your code stays safe with structured versioning and user-specific access. Focus on learning, while we handle organization.</p>
    </div>
  </section>

  <!-- Footer -->
  <footer>
    <p>© <?php echo date("Y"); ?> Code Hub. All Rights Reserved.</p>
    <p>
      <a href="about.php">About</a> | 
      <a href="info.php">Info</a> | 
      <a href="contact.php">Contact</a>
    </p>
  </footer>
</body>
</html>

