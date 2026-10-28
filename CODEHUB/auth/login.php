<?php
session_start();
require_once '../includes/db.php';

$msg = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 1) {
        $user = $res->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: ../dashboard.php");
            exit();
        } else {
            $msg = "Invalid password!";
        }
    } else {
        $msg = "User not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login - Code Hub</title>
  <style>
    /* Background */
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #1d1f27, #2a2e37);
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
      color: #fff;
      overflow: hidden;
    }

    /* Subtle animated background circles */
    body::before, body::after {
      content: '';
      position: absolute;
      width: 300px;
      height: 300px;
      border-radius: 50%;
      background: radial-gradient(circle, rgba(255,255,255,0.1), transparent);
      animation: float 12s ease-in-out infinite;
    }
    body::after {
      width: 400px;
      height: 400px;
      right: -100px;
      bottom: -100px;
      animation-delay: 4s;
    }

    @keyframes float {
      0%, 100% { transform: translateY(0) translateX(0); }
      50% { transform: translateY(-40px) translateX(30px); }
    }

    .login-container {
      background: rgba(255, 255, 255, 0.05);
      border: 1px solid rgba(255, 255, 255, 0.15);
      backdrop-filter: blur(15px);
      border-radius: 20px;
      padding: 40px;
      width: 360px;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
      position: relative;
      animation: fadeIn 1s ease forwards;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: scale(0.9);}
      to { opacity: 1; transform: scale(1);}
    }

    .login-form h2 {
      margin: 0 0 20px;
      font-size: 26px;
      text-align: center;
      font-weight: 600;
      color: #fff;
    }

    .login-form input {
      width: 100%;
      padding: 12px 15px;
      margin: 12px 0;
      border-radius: 10px;
      border: none;
      background: rgba(255,255,255,0.08);
      color: #fff;
      font-size: 15px;
      outline: none;
      transition: all 0.3s ease;
    }

    .login-form input:focus {
      background: rgba(255,255,255,0.15);
      transform: scale(1.02);
    }

    .show-pass {
      display: flex;
      align-items: center;
      gap: 8px;
      margin: 10px 0 15px;
      font-size: 13px;
      color: #ccc;
    }

    .show-pass input {
      width: auto;
      transform: scale(1.1);
      cursor: pointer;
    }

    .login-form button {
      width: 100%;
      padding: 12px;
      background: linear-gradient(135deg, #4e9bff, #8a6dff);
      border: none;
      border-radius: 10px;
      color: #fff;
      font-size: 16px;
      cursor: pointer;
      font-weight: 500;
      transition: all 0.3s ease;
    }

    .login-form button:hover {
      background: linear-gradient(135deg, #8a6dff, #4e9bff);
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(0,0,0,0.4);
    }

    .redirect {
      text-align: center;
      margin-top: 15px;
      font-size: 14px;
      color: #bbb;
    }

    .redirect a {
      color: #4e9bff;
      text-decoration: none;
      transition: 0.3s;
    }

    .redirect a:hover {
      text-decoration: underline;
    }

    .error {
      text-align: center;
      color: #ff6b6b;
      font-size: 14px;
      margin-bottom: 12px;
      animation: shake 0.3s ease;
    }

    @keyframes shake {
      0%, 100% { transform: translateX(0); }
      20%, 60% { transform: translateX(-6px); }
      40%, 80% { transform: translateX(6px); }
    }
  </style>
</head>
<body>
  <div class="login-container">
    <form class="login-form" method="POST">
      <h2>Welcome Back</h2>
      <?php if ($msg) echo "<p class='error'>$msg</p>"; ?>
      <input type="email" name="email" placeholder="Email" required />
      <input type="password" name="password" id="password" placeholder="Password" required />
      
      <div class="show-pass">
        <input type="checkbox" id="showPassword" />
        <label for="showPassword">Show Password</label>
      </div>

      <button type="submit">Login</button>
      <p class="redirect">Are you an admin? Then <a href="../admin/admin_login.php">Login as Admin</a></p>
      <p class="redirect">Don't have an account? <a href="register.php">Register</a></p>
    </form>
  </div>

  <script>
    const passInput = document.getElementById("password");
    const togglePass = document.getElementById("showPassword");

    togglePass.addEventListener("change", () => {
      passInput.type = togglePass.checked ? "text" : "password";
    });
  </script>
</body>
</html>
