<?php require_once __DIR__ . '/config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>NextInTech • Welcome</title>
  <link rel="stylesheet" href="assets/css/style.css" />
</head>
<body class="bg">
  <div class="shell">
    <header class="topbar">
      <div class="brand">NextInTech</div>
    </header>

    <main class="card landing-card center fade-in">
      <img class="hero bob" alt="logo" src="assets/image/index.png" />
      <h1 class="title center">Hello, Welcome!</h1>
      <p class="subtitle center">
        Welcome to Next Innovation Technology — A Youth-Led Company !!
      </p>

      <div class="stack gap-12 center">
        <a class="btn btn-primary lg ripple" href="login.php">Login</a>
        <a class="btn btn-primary outline lg ripple" href="signup.php">Sign Up</a>
      </div>

      <p class="muted center mt-24">Or sign in with</p>
      <div class="social">
        <a class="ico fb" href="#" aria-label="facebook"></a>
        <a class="ico gg" href="#" aria-label="google"></a>
        <a class="ico in" href="#" aria-label="linkedin"></a>
      </div>
    </main>
  </div>
  <script src="assets/js/app.js"></script>
</body>
</html>
