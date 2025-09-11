<?php
require_once __DIR__ . '/db.php'; // database connection (MySQLi)
session_start();

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Prepare query
    $stmt = $conn->prepare("SELECT id, password FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        // ✅ Login success
        $_SESSION["user_id"] = $user['id'];
        header("Location: dashboard.php"); // redirect to dashboard
        exit;
    } else {
        $message = "❌ Invalid email or password";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login • NextInTech</title>

  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
    body.bg {
      background: #0b2656;
      color: #fff;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
    }
    .shell { position: relative; width: 100%; max-width: 420px; }
    .topbar .brand { font-size: 22px; font-weight: 700; margin-bottom: 20px; }
    .top-right { position: absolute; top: 10px; right: 10px; }
    .top-right a {
      font-size: 13px; font-weight: 500; color: #fff;
      text-decoration: none; background: #e6bb91;
      padding: 6px 12px; border-radius: 8px; transition: 0.3s;
    }
    .top-right a:hover { background: #d4a97f; color: #000; }
    .card {
      background: rgba(255, 255, 255, 0.05);
      border-radius: 14px;
      padding: 30px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
    }
    .title { font-size: 26px; font-weight: 700; margin-bottom: 6px; }
    .subtitle { font-size: 14px; font-weight: 400; color: #ccc; margin-bottom: 20px; }
    label { display: block; font-size: 14px; font-weight: 500; margin: 12px 0 6px; }
    input[type="email"], input[type="password"] {
      width: 100%; padding: 12px; border: none; outline: none;
      border-radius: 10px; font-size: 14px; background: #fff; color: #000;
    }
    .form-options {
      display: flex; justify-content: space-between; align-items: center;
      margin: 10px 0 18px; font-size: 13px;
    }
    .form-options a { color: #2196f3; text-decoration: none; }
    .btn {
      display: inline-block; text-align: center; padding: 12px;
      border-radius: 12px; font-weight: 600; cursor: pointer;
      transition: 0.3s; border: none; text-decoration: none;
    }
    .btn-primary {
      background: #e6bb91; color: #000; width: 48%;
    }
    .btn-primary:hover { background: #d4a97f; }
    .btn-outline {
      border: 2px solid #e6bb91; color: #fff; background: transparent; width: 48%;
    }
    .btn-outline:hover { background: #e6bb91; color: #000; }
    .btn-row { display: flex; gap: 10px; justify-content: space-between; }
    .alert { margin-bottom: 15px; padding: 10px; border-radius: 8px; text-align: center; }
    .alert.error { background: #ff4d4d; color: #fff; }
    .alert.success { background: #4caf50; color: #fff; }
  </style>
</head>
<body class="bg">
  <div class="shell">
    <header class="topbar">
      <div class="brand">NextInTech</div>
      <div class="top-right">
        <a href="index.php">Back to Home</a>
      </div>
    </header>

    <main class="card">
      <h1 class="title">Welcome Back</h1>
      <p class="subtitle">Login to your account</p>

      <?php if (!empty($message)): ?>
        <div class="alert error"><?= htmlspecialchars($message) ?></div>
      <?php endif; ?>

      <form method="POST" action="login.php">
        <label>Email</label>
        <input type="email" name="email" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <div class="form-options">
          <label><input type="checkbox" name="remember"> Remember me</label>
          <a href="forgot-password.php">Forgot password?</a>
        </div>

        <div class="btn-row">
          <button type="submit" class="btn btn-primary">Login</button>
          <a href="signup.php" class="btn btn-outline">Sign Up</a>
        </div>
      </form>
    </main>
  </div>
</body>
</html>
