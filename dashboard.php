<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$fullname = $_SESSION['fullname'] ?? 'Guest';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard • NextInTech</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <style>
    .alert { margin-top: 15px; padding: 10px; border-radius: 8px; text-align: center; }
    .alert.success { background: #4caf50; color: #fff; }
  </style>
</head>
<body class="bg">
  <div class="shell">
    <header class="topbar">
      <div class="brand">NextInTech</div>
    </header>

    <main class="card form-card slide-up">
      <h1 class="title">Hello, <?php echo htmlspecialchars($fullname); ?> 👋</h1>
      <p class="subtitle">You are now logged in.</p>

      <?php if (isset($_GET['login']) && $_GET['login'] === 'success'): ?>
        <div class="alert success">✅ Login successful! Redirecting to homepage...</div>
      <?php else: ?>
        <div class="alert success">Redirecting to homepage...</div>
      <?php endif; ?>
    </main>
  </div>

<?php
// auto redirect after 3 sec
header("Refresh:3; url=index.php");
?>
</body>
</html>


