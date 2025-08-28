<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard • NextInTech</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg">
  <div class="shell">
    <header class="topbar">
      <div class="brand">NextInTech</div>
    </header>

    <main class="card form-card slide-up">
      <h1 class="title">Hello, <?php echo htmlspecialchars($_SESSION['fullname']); ?> 👋</h1>
      <p class="subtitle">You are now logged in.</p>
      <div class="alert success">Redirecting to homepage...</div>
    </main>
  </div>

<?php
// auto redirect after 3 sec
header("Refresh:3; url=index.php");
?>
</body>
</html>
