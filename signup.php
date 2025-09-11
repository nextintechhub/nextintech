<?php
require_once __DIR__ . '/db.php'; // contains $conn (MySQLi)
session_start();

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname  = trim($_POST['fullname'] ?? '');
    $email     = trim($_POST['email'] ?? '');
    $password  = trim($_POST['password'] ?? '');
    $phone     = trim($_POST['phone'] ?? '');

    if ($fullname === '' || $email === '' || $password === '') {
        $message = "<div class='alert error'>❌ All required fields must be filled</div>";
    } elseif (strlen($password) < 6) {
        $message = "<div class='alert error'>❌ Password must be at least 6 characters</div>";
    } else {
        // Check if email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $message = "<div class='alert error'>❌ Email already registered</div>";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("INSERT INTO users (fullname, email, password, phone) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $fullname, $email, $hash, $phone);

            if ($stmt->execute()) {
                // ✅ Show success message and auto-redirect
                $message = "<div class='alert success'>✅ Signup successful! Redirecting to login...</div>";
                header("Refresh:3; url=login.php");
            } else {
                $message = "<div class='alert error'>❌ Error: " . htmlspecialchars($stmt->error) . "</div>";
            }
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sign Up • NextInTech</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    * {margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif;}
    body.bg {
      background: #0b2656; color: #fff;
      display: flex; justify-content: center; align-items: center;
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
      background: rgba(255,255,255,0.05); border-radius: 14px;
      padding: 30px; box-shadow: 0 8px 20px rgba(0,0,0,0.25);
    }
    .title { font-size: 26px; font-weight: 700; margin-bottom: 6px; }
    .subtitle { font-size: 14px; color: #ccc; margin-bottom: 20px; }
    label { display: block; font-size: 14px; font-weight: 500; margin: 12px 0 6px; }
    input {
      width: 100%; padding: 12px; border: none; outline: none;
      border-radius: 10px; font-size: 14px; background: #fff; color: #000;
    }
    .btn-row { display: flex; justify-content: space-between; gap: 10px; margin-top: 18px; }
    .btn {
      flex: 1; text-align: center; padding: 12px; border-radius: 12px;
      font-weight: 600; cursor: pointer; transition: 0.3s; border: none; text-decoration: none;
    }
    .btn-primary { background: #e6bb91; color: #000; }
    .btn-primary:hover { background: #d4a97f; }
    .btn-outline { border: 2px solid #e6bb91; color: #fff; background: transparent; }
    .btn-outline:hover { background: #e6bb91; color: #000; }
    .alert { margin-bottom: 15px; padding: 10px; border-radius: 8px; text-align: center; font-size: 14px; }
    .alert.error { background: #ff4d4d; color: #fff; }
    .alert.success { background: #4caf50; color: #fff; }
  </style>
</head>
<body class="bg">
  <div class="shell">
    <header class="topbar">
      <div class="brand">NextInTech</div>
      <div class="top-right"><a href="index.php">Back to Home</a></div>
    </header>

    <main class="card">
      <h1 class="title">Create Account</h1>
      <p class="subtitle">Sign up to get access</p>

      <?php if ($message) echo $message; ?>

      <form method="POST" action="signup.php">
        <label>Full Name</label>
        <input type="text" name="fullname" required>

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Password</label>
        <input type="password" name="password" required minlength="6">

        <label>Phone No</label>
        <input type="text" name="phone">

        <div class="btn-row">
          <button type="submit" class="btn btn-primary">Sign Up</button>
          <a href="login.php" class="btn btn-outline">Back to Login</a>
        </div>
      </form>
    </main>
  </div>
</body>
</html>
