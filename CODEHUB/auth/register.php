<?php
require_once '../includes/db.php';

$msg = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    // Check if user already exists
    $check = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        $msg = "⚠️ Email already registered!";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $password);
        if ($stmt->execute()) {
            header("Location: login.php");
            exit();
        } else {
            $msg = "❌ Registration failed! Try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Register - Code Hub</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      margin: 0;
      font-family: "Inter", sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      background: linear-gradient(135deg, #1f1c2c, #928DAB);
    }

    .register-wrapper {
      display: flex;
      justify-content: center;
      align-items: center;
      width: 100%;
      padding: 20px;
    }

    .register-card {
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(12px);
      padding: 40px 30px;
      border-radius: 16px;
      width: 100%;
      max-width: 400px;
      color: #fff;
      box-shadow: 0 8px 25px rgba(0,0,0,0.25);
      animation: slideUp 0.8s ease forwards;
      transform: translateY(50px);
      opacity: 0;
    }

    @keyframes slideUp {
      to {
        transform: translateY(0);
        opacity: 1;
      }
    }

    .title {
      text-align: center;
      font-size: 1.8rem;
      margin-bottom: 10px;
    }

    .subtitle {
      text-align: center;
      font-size: 0.95rem;
      margin-bottom: 25px;
      color: #ddd;
    }

    .subtitle span {
      font-weight: 600;
      color: #ffcc70;
    }

    .alert {
      background: rgba(255,0,0,0.15);
      color: #ff6b6b;
      padding: 10px 15px;
      border-radius: 8px;
      margin-bottom: 20px;
      text-align: center;
    }

    .form-group {
      margin-bottom: 18px;
    }

    .input-card {
      background: rgba(255, 255, 255, 0.12);
      border-radius: 12px;
      padding: 12px;
      display: flex;
      align-items: center;
      transition: all 0.3s ease;
      border: 1px solid transparent;
    }

    .input-card:hover {
      border-color: #ffcc70;
      box-shadow: 0 4px 15px rgba(255,204,112,0.3);
    }

    .input-card input {
      background: transparent;
      border: none;
      outline: none;
      flex: 1;
      font-size: 0.95rem;
      color: #fff;
    }

    .input-card input::placeholder {
      color: #ccc;
    }

    .toggle-pass {
      cursor: pointer;
      margin-left: 8px;
      font-size: 1rem;
      transition: color 0.3s ease;
    }

    .toggle-pass:hover {
      color: #ffcc70;
    }

    .btn-register {
      width: 100%;
      padding: 12px;
      border: none;
      border-radius: 12px;
      font-size: 1rem;
      font-weight: 600;
      background: linear-gradient(135deg, #ffcc70, #ff7e5f);
      color: #1f1c2c;
      cursor: pointer;
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .btn-register:hover {
      transform: translateY(-3px);
      box-shadow: 0 6px 18px rgba(255,126,95,0.4);
    }

    .redirect {
      margin-top: 18px;
      text-align: center;
      font-size: 0.9rem;
      color: #ddd;
    }

    .redirect a {
      color: #ffcc70;
      font-weight: 600;
      text-decoration: none;
      transition: color 0.3s ease;
    }

    .redirect a:hover {
      color: #ff7e5f;
    }
  </style>
</head>
<body>
  <div class="register-wrapper">
    <div class="register-card">
      <h2 class="title">🚀 Create Your Account</h2>
      <p class="subtitle">Join <span>Code Hub</span> and start sharing your snippets!</p>

      <?php if ($msg): ?>
        <div class="alert"><?php echo $msg; ?></div>
      <?php endif; ?>

      <form method="POST" action="">
        <div class="form-group">
          <div class="input-card">
            <input type="text" name="username" placeholder="👤 Username" required>
          </div>
        </div>

        <div class="form-group">
          <div class="input-card">
            <input type="email" name="email" placeholder="📧 Email" required>
          </div>
        </div>

        <div class="form-group password-group">
          <div class="input-card">
            <input type="password" name="password" id="password" placeholder="🔒 Password" required>
            <span class="toggle-pass" id="togglePass">👁</span>
          </div>
        </div>

        <button type="submit" class="btn-register">Register</button>

        <p class="redirect">
          Already have an account? <a href="login.php">Login here</a>
        </p>
      </form>
    </div>
  </div>

  <script>
    const passwordInput = document.getElementById("password");
    const togglePass = document.getElementById("togglePass");

    togglePass.addEventListener("click", () => {
      const type = passwordInput.type === "password" ? "text" : "password";
      passwordInput.type = type;
      togglePass.textContent = type === "password" ? "👁" : "🙈";
    });
  </script>
</body>
</html>
