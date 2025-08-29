<?php
session_start();
require_once __DIR__ . '/config.php';

$message = "";

// Show success message from signup
if (isset($_SESSION['success'])) {
    $message = $_SESSION['success'];
    unset($_SESSION['success']);
}

// Handle login form
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $remember = isset($_POST['remember']);

    if ($email === "" || $password === "") {
        $message = "⚠️ Please fill in both fields.";
    } else {
        $stmt = $pdo->prepare("SELECT id, fullname, password FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Login successful
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['fullname'] = $user['fullname'];

            // Remember me (30 days cookie)
            if ($remember) {
                setcookie("remember_email", $email, time() + (86400 * 30), "/");
            } else {
                setcookie("remember_email", "", time() - 3600, "/");
            }

            header("Location: dashboard.php");
            exit;
        } else {
            $message = "❌ Invalid email or password.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login • NextInTech</title>

  <!-- Google Font (Poppins) -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

  <style>
    * {margin:0;padding:0;box-sizing:border-box;font-family:'Poppins',sans-serif;}
    body.bg {background:#0b2656;color:#fff;display:flex;justify-content:center;align-items:center;min-height:100vh;}
    .shell {position:relative;width:100%;max-width:420px;}
    .topbar .brand {font-size:22px;font-weight:700;margin-bottom:20px;}
    .top-right {position:absolute;top:10px;right:10px;}
    .top-right a {font-size:13px;font-weight:500;color:#fff;text-decoration:none;background:#e6bb91;padding:6px 12px;border-radius:8px;transition:0.3s;}
    .top-right a:hover {background:#d4a97f;color:#000;}
    .card {background:rgba(255,255,255,0.05);border-radius:14px;padding:30px;box-shadow:0 8px 20px rgba(0,0,0,0.25);}
    .title {font-size:26px;font-weight:700;margin-bottom:6px;}
    .subtitle {font-size:14px;font-weight:400;color:#ccc;margin-bottom:20px;}
    label {display:block;font-size:14px;font-weight:500;margin:12px 0 6px;}
    input[type="email"], input[type="password"] {width:100%;padding:12px;border:none;outline:none;border-radius:10px;font-size:14px;background:#fff;color:#000;}
    .form-options {display:flex;justify-content:space-between;align-items:center;margin:10px 0 18px;font-size:13px;}
    .form-options a {color:#2196f3;text-decoration:none;}
    .btn {display:inline-block;text-align:center;padding:12px;border-radius:12px;font-weight:600;cursor:pointer;transition:0.3s;border:none;text-decoration:none;}
    .btn-primary {background:#e6bb91;color:#000;width:48%;}
    .btn-primary:hover {background:#d4a97f;}
    .btn-outline {border:2px solid #e6bb91;color:#fff;background:transparent;width:48%;}
    .btn-outline:hover {background:#e6bb91;color:#000;}
    .btn-row {display:flex;gap:10px;justify-content:space-between;}
    .alert {margin-bottom:15px;padding:12px;border-radius:8px;font-size:14px;text-align:center;}
    .alert.success {background:#4CAF50;color:#fff;}
    .alert.error {background:#ff4d4d;color:#fff;}
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
        <div class="alert <?= strpos($message, '❌') !== false ? 'error' : 'success' ?>">
          <?= htmlspecialchars($message) ?>
        </div>
      <?php endif; ?>

      <form method="POST" action="login.php">
        <label>Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($_COOKIE['remember_email'] ?? '') ?>" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <div class="form-options">
          <label><input type="checkbox" name="remember" <?= isset($_COOKIE['remember_email']) ? 'checked' : '' ?>> Remember me</label>
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
