<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Code Hub - Welcome</title>
  <style>
   /* ================= GLOBAL RESET ================= */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: 'Poppins', sans-serif;
}

body {
  background: linear-gradient(135deg, #f9f9ff, #eef1ff);
  color: #333;
  display: flex;
  flex-direction: column;
  min-height: 100vh;
  overflow-x: hidden;
}

/* ================= HEADER ================= */
header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 18px 8%;
  background: rgba(255, 255, 255, 0.7);
  backdrop-filter: blur(12px);
  box-shadow: 0 4px 25px rgba(0,0,0,0.08);
  border-radius: 0 0 18px 18px;
  position: sticky;
  top: 0;
  z-index: 1000;
  transition: all 0.3s ease-in-out;
}

/* Logo */
header .logo {
  font-weight: 700;
  font-size: 1.8rem;
  color: #0d1b2a;
  letter-spacing: 1px;
}

header .logo span {
  background: linear-gradient(90deg, #6c63ff, #5a54d9);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

/* Navbar */
header nav ul {
  list-style: none;
  display: flex;
  gap: 35px;
}

header nav ul li a {
  text-decoration: none;
  color: #444;
  font-size: 1rem;
  font-weight: 500;
  position: relative;
  transition: 0.3s;
}

header nav ul li a::after {
  content: "";
  position: absolute;
  left: 0;
  bottom: -4px;
  width: 0%;
  height: 2px;
  background: #6c63ff;
  transition: width 0.3s;
  border-radius: 2px;
}

header nav ul li a:hover {
  color: #6c63ff;
}

header nav ul li a:hover::after {
  width: 100%;
}

/* Header Button */
header .btn {
  padding: 10px 22px;
  background: linear-gradient(90deg, #6c63ff, #5a54d9);
  color: #fff;
  border: none;
  border-radius: 25px;
  cursor: pointer;
  transition: 0.3s ease-in-out;
  font-weight: 600;
  box-shadow: 0 4px 15px rgba(108, 99, 255, 0.4);
}

header .btn:hover {
  background: linear-gradient(90deg, #5146d9, #4035b5);
  transform: translateY(-3px);
  box-shadow: 0 8px 20px rgba(108, 99, 255, 0.5);
}

/* ================= HERO ================= */
.hero {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 90px 8%;
  flex: 1;
  gap: 40px;
  animation: fadeIn 1s ease-in-out;
}

.hero-text {
  max-width: 48%;
}

.hero-text h1 {
  font-size: 3.4rem;
  font-weight: 700;
  color: #0d1b2a;
  margin-bottom: 20px;
  line-height: 1.2;
  background: linear-gradient(90deg, #6c63ff, #4035b5);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

.hero-text p {
  font-size: 1.05rem;
  color: #555;
  margin-bottom: 28px;
  line-height: 1.7;
  animation: slideIn 0.9s ease;
}

.hero-text .cta {
  padding: 13px 32px;
  background: linear-gradient(90deg, #6c63ff, #5a54d9);
  color: #fff;
  border: none;
  border-radius: 30px;
  cursor: pointer;
  font-size: 1rem;
  font-weight: 600;
  text-decoration: none;
  display: inline-block;
  transition: all 0.3s ease-in-out;
  box-shadow: 0 4px 15px rgba(108, 99, 255, 0.4);
}

.hero-text .cta:hover {
  background: linear-gradient(90deg, #5146d9, #4035b5);
  transform: translateY(-3px) scale(1.05);
  box-shadow: 0 8px 20px rgba(108, 99, 255, 0.5);
}

/* Hero Illustration */
.hero-img {
  width: 45%;
  animation: float 3s ease-in-out infinite;
}

.hero-img img {
  width: 100%;
  border-radius: 18px;
  box-shadow: 0 8px 25px rgba(0,0,0,0.12);
  transition: transform 0.4s ease;
}

.hero-img img:hover {
  transform: scale(1.05);
}

/* Animations */
@keyframes float {
  0% { transform: translateY(0px); }
  50% { transform: translateY(-18px); }
  100% { transform: translateY(0px); }
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(25px); }
  to { opacity: 1; transform: translateY(0); }
}

@keyframes slideIn {
  from { opacity: 0; transform: translateX(-30px); }
  to { opacity: 1; transform: translateX(0); }
}

/* ================= FOOTER ================= */
footer {
  background: #0d1b2a;
  color: #ddd;
  padding: 55px 8% 25px;
}

.footer-container {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 40px;
  margin-bottom: 30px;
}

.footer-container h4 {
  margin-bottom: 15px;
  font-size: 1.1rem;
  color: #fff;
  position: relative;
}

.footer-container h4::after {
  content: "";
  position: absolute;
  bottom: -6px;
  left: 0;
  width: 35px;
  height: 2px;
  background: #6c63ff;
  border-radius: 2px;
}

.footer-container ul {
  list-style: none;
}

.footer-container ul li {
  margin-bottom: 12px;
}

.footer-container ul li a {
  text-decoration: none;
  color: #bbb;
  font-size: 0.95rem;
  transition: 0.3s;
  position: relative;
}

.footer-container ul li a::before {
  content: "›";
  position: absolute;
  left: -15px;
  color: #6c63ff;
  opacity: 0;
  transition: 0.3s;
}

.footer-container ul li a:hover {
  color: #6c63ff;
  padding-left: 8px;
}

.footer-container ul li a:hover::before {
  opacity: 1;
  left: 0;
}

.footer-bottom {
  border-top: 1px solid rgba(255,255,255,0.08);
  padding-top: 20px;
  text-align: center;
  font-size: 0.9rem;
  color: #aaa;
}

.footer-social {
  margin-top: 15px;
}

.footer-social a {
  margin: 0 10px;
  color: #bbb;
  font-size: 1.3rem;
  transition: 0.3s;
  text-decoration: none;
}

.footer-social a:hover {
  color: #6c63ff;
  transform: scale(1.2);
}

/* ================= RESPONSIVE ================= */
@media (max-width: 900px) {
  .hero {
    flex-direction: column;
    text-align: center;
    padding: 60px 6%;
  }
  .hero-text {
    max-width: 100%;
  }
  .hero-img {
    width: 80%;
    margin-top: 40px;
  }
  header nav ul {
    gap: 18px;
  }
}

  </style>
</head>
<body>
  
  <!-- Header -->
  <header>
    <div class="logo">Code <span>Hub</span></div>
    <nav>
      <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="about.php">About us</a></li>
        
        <li><a href="info.php">Info</a></li>
      </ul>
    </nav>

    <?php if(isset($_SESSION['user_id'])): ?>
      <a href="auth/register.php"><button class="btn">Register Now!</button></a>
    <?php else: ?>
      <a href="auth/login.php"><button class="btn">Get Started</button></a>
    <?php endif; ?>
  </header>

  <!-- Hero Section -->
  <section class="hero">
    <div class="hero-text">
      <h1>Collaborative Code Sharing</h1>
      <p>Welcome to <b>Code Hub</b> — a place where developers share, discover, and 
      collaborate on code snippets. Explore multiple languages, save favorites, and 
      contribute to the community.</p>

      <?php if(isset($_SESSION['user_id'])): ?>
        <a href="snippets/dashboard.php" class="cta">Go to Dashboard</a>
      <?php else: ?>
        <a href="auth/register.php" class="cta">Join Now</a>
      <?php endif; ?>
    </div>

    <div class="hero-img">
      <!-- Replace with your illustration -->
      <img src="assets/diseno-ilustracion-editor-codigo-programacion_92086-840.avif" alt="Code Collaboration">
      <!--<img src="assets/illustration-isometrique-technologie-informatique-plates-formes-informatiques-bureau-concept-codage-programmation-logicielle-code-ecran-ordinateur-illustration-vectorielle_561158-1860.avif" alt="Code Collaboration">-->
       <img src="assets/coding-background-9izlympnd0ovmpli.jpg" alt="Code Collaboration">
    </div>
  </section>

  <!-- Footer -->
  <footer>
    <div class="footer-container">
      <div>
        <h4>Our Network</h4>
        <ul>
          <li><a href="#">Developers</a></li>
          <li><a href="#">Contributors</a></li>
          <li><a href="#">Open Source</a></li>
          <li><a href="#">Community</a></li>
        </ul>
      </div>

      <div>
        <h4>Site Links</h4>
        <ul>
          <li><a href="#">Licensing</a></li>
          <li><a href="#">DMCA</a></li>
          <li><a href="#">Affiliate Program</a></li>
          <li><a href="#">Popular Snippets</a></li>
        </ul>
      </div>

      <div>
        <h4>Learn More</h4>
        <ul>
          <li><a href="faq.php">FAQs</a></li>
          <li><a href="contact.php">Contact Us</a></li>
          <li><a href="about.php">About Us</a></li>
          <li><a href="contribute.php">Become a Contributor</a></li>
        </ul>
      </div>

      <div>
        <h4>Languages</h4>
        <ul>
          <li><a href="#">English</a></li>
          <li><a href="#">Español</a></li>
          <li><a href="#">Deutsch</a></li>
          <li><a href="#">More...</a></li>
        </ul>
      </div>
    </div>

    <div class="footer-bottom">
      <p>© <?php echo date("Y"); ?> Code Hub. All rights reserved.</p>
      <div class="footer-social">
        <a href="#">F</a>
        <a href="#">T</a>
        <a href="#">G</a>
        <a href="#">in</a>
      </div>
    </div>
  </footer>

</body>
</html>
