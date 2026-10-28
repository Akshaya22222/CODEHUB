<?php
// about.php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>About - Code Hub</title>

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

    /* Content */
    .content {
      max-width: 1000px;
      margin: 80px auto;
      padding: 50px;
      background: rgba(30, 41, 59, 0.8);
      border-radius: 18px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.4);
      backdrop-filter: blur(12px);
      animation: fadeSlideUp 1s ease-in-out;
    }

    .content h1 {
      font-family: 'Poppins', sans-serif;
      font-size: 42px;
      font-weight: 700;
      margin-bottom: 25px;
      color: #facc15;
      position: relative;
    }

    .content h1::after {
      content: "";
      position: absolute;
      width: 70px;
      height: 4px;
      background: #38bdf8;
      bottom: -12px;
      left: 0;
      border-radius: 4px;
    }

    .content p {
      font-size: 17px;
      color: #e2e8f0;
      margin-bottom: 20px;
    }

    .highlight {
      color: #38bdf8;
      font-weight: 600;
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

    /* Animations */
    @keyframes fadeSlideUp {
      0% { opacity: 0; transform: translateY(30px); }
      100% { opacity: 1; transform: translateY(0); }
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
        <li><a href="about.php" class="active">About</a></li>
        <li><a href="info.php">Info</a></li>
        <li><a href="login.php">Get Started</a></li>
      </ul>
    </nav>
  </header>

  <!-- Main Content -->
  <section class="content">
    <h1>About Code Hub</h1>
    <p>
      <span class="highlight">Code Hub</span> is a collaborative platform designed for developers, students, and programmers to 
      share, manage, and explore code snippets across multiple languages including <span class="highlight">PHP, Python, Java, and C++</span>.
    </p>
    <p>
      Our mission is to build a community-driven hub where coders can learn from each other, contribute, and 
      grow together. From snippet version history to bookmarking, likes, and future integrations like live 
      execution, Code Hub empowers developers to stay creative and productive.
    </p>
  </section>

  <!-- Footer -->
  <footer>
    <p>© <?php echo date("Y"); ?> Code Hub. All rights reserved.</p>
  </footer>
</body>
</html>
