<?php
require_once __DIR__ . '/db.php';  // database connection

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "<div class='alert error'>❌ Invalid email address</div>";
    } else {
        // Check if user exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user) {
            $token = bin2hex(random_bytes(16));
            $expires = date("Y-m-d H:i:s", strtotime("+1 hour"));

            // Insert reset request
            $stmt = $conn->prepare("INSERT INTO password_resets (user_id, token, expires_at) VALUES (?, ?, ?)");
            $stmt->bind_param("iss", $user['id'], $token, $expires);
            $stmt->execute();

            // Normally send email → here we just show the reset link
            $resetLink = "http://localhost/nextintech/reset-password.php?token=" . $token;
            $message = "<div class='alert success'>✅ Reset link (demo): <a href='$resetLink' style='color:#fff;'>$resetLink</a></div>";
        } else {
            $message = "<div class='alert error'>❌ No account found with that email</div>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Forgot Password • NextInTech</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    * {margin:0; padding:0; box-sizing:border-box; font-family:'Poppins',sans-serif;}
    body.bg {
      background:#0b2656; color:#fff;
      display:flex; justify-content:center; align-items:center;
      min-height:100vh;
    }
    .shell { width:100%; max-width:420px; }
    .topbar .brand { font-size:22px; font-weight:700; margin-bottom:20px; }
    .top-right { position:absolute; top:10px; right:10px; }
    .top-right a {
      font-size:13px; font-weight:500; color:#fff;
      text-decoration:none; background:#e6bb91;
      padding:6px 12px; border-radius:8px; transition:0.3s;
    }
    .top-right a:hover { background:#d4a97f; color:#000; }
    .card {
      background:rgba(255,255,255,0.05);
      border-radius:14px;
      padding:30px;
      box-shadow:0 8px 20px rgba(0,0,0,0.25);
    }
    .title { font-size:26px; font-weight:700; margin-bottom:6px; }
    .subtitle { font-size:14px; color:#ccc; margin-bottom:20px; }
    label { display:block; font-size:14px; font-weight:500; margin:12px 0 6px; }
    input {
      width:100%; padding:12px; border:none; outline:none;
      border-radius:10px; font-size:14px; background:#fff; color:#000;
    }
    .btn-row { display:flex; justify-content:space-between; gap:10px; margin-top:18px; }
    .btn {
      flex:1; text-align:center; padding:12px; border-radius:12px;
      font-weight:600; cursor:pointer; transition:0.3s; border:none; text-decoration:none;
    }
    .btn-primary { background:#e6bb91; color:#000; }
    .btn-primary:hover { background:#d4a97f; }
    .btn-outline { border:2px solid #e6bb91; color:#fff; background:transparent; }
    .btn-outline:hover { background:#e6bb91; color:#000; }
    .alert { margin-bottom:15px; padding:10px; border-radius:8px; text-align:center; }
    .alert.error { background:#ff4d4d; color:#fff; }
    .alert.success { background:#4caf50; color:#fff; }
  </style>
</head>
<body class="bg">
  <div class="shell">
    <header class="topbar">
      <div class="brand">NextInTech</div>
      <div class="top-right"><a href="index.php">Back to Home</a></div>
    </header>

    <main class="card">
      <h1 class="title">Forgot Password</h1>
      <p class="subtitle">Enter your email to receive a reset link</p>

      <?php if ($message) echo $message; ?>

      <form method="POST" action="">
        <label>Email</label>
        <input type="email" name="email" required>

        <div class="btn-row">
          <button type="submit" class="btn btn-primary">Send Reset Link</button>
          <a href="login.php" class="btn btn-outline">Back to Login</a>
        </div>
      </form>
    </main>
  </div>
</body>
</html>
