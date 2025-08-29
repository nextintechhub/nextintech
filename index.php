<?php require_once __DIR__ . '/config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>NextInTech • Welcome</title>

  <!-- Google Fonts (Poppins) -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

  <style>
    /* Global reset & font */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
    }

    body.bg {
      background: #0a2a5e;
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
    }

    .shell {
      width: 100%;
      max-width: 420px;
      padding: 20px;
    }

    .topbar .brand {
      font-size: 22px;
      font-weight: 700;
      color: #fff;
      margin-bottom: 24px;
    }

    .card {
      background: #102b66;
      border-radius: 18px;
      padding: 32px 24px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.25);
      text-align: center;
      color: #fff;
    }

    .card img.hero {
      width: 80%;
      max-width: 280px;
      margin: 0 auto 20px;
      display: block;
    }

    .title {
      font-size: 28px;
      font-weight: 700;
      margin: 10px 0;
    }

    .subtitle {
      font-size: 14px;
      font-weight: 400;
      color: #cdd4e7;
      margin-bottom: 24px;
      line-height: 1.5;
    }

    .stack {
      display: flex;
      flex-direction: column;
      gap: 12px;
    }

    .btn {
      display: block;
      width: 100%;
      padding: 14px;
      border-radius: 12px;
      font-size: 16px;
      font-weight: 600;
      text-align: center;
      text-decoration: none;
      transition: all 0.3s ease;
    }

    .btn-primary {
      background: #e6bb91;
      color: #000;
    }

    .btn-primary:hover {
      background: #d4a97f;
    }

    .btn.outline {
      border: 2px solid #e6bb91;
      background: transparent;
      color: #fff;
    }

    .btn.outline:hover {
      background: #e6bb91;
      color: #000;
    }

    .muted {
      font-size: 13px;
      color: #cdd4e7;
      margin-top: 20px;
    }

    .social {
      margin-top: 12px;
      display: flex;
      justify-content: center;
      gap: 16px;
    }

    .social a {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: 0.3s;
    }

    .social a:hover {
      transform: scale(1.1);
    }

    .ico.gg { background: #db4437; }   /* Google red */
    .ico.fb { background: #1877f2; }   /* Facebook blue */
    .ico.in { background: #0077b5; }   /* LinkedIn blue */

    .ico img {
      width: 20px;
      height: 20px;
      filter: brightness(0) invert(1);
    }
  </style>
</head>
<body class="bg">
  <div class="shell">
    <header class="topbar">
      <div class="brand">NextInTech</div>
    </header>

    <main class="card landing-card">
      <img class="hero" alt="Welcome" src="assets/image/index.png" />
      <h1 class="title">Hello, Welcome!</h1>
      <p class="subtitle">
        Welcome to Next Innovation Technology — A Youth-Led Company !!
      </p>

      <div class="stack">
        <a class="btn btn-primary" href="login.php">Login</a>
        <a class="btn btn-primary outline" href="signup.php">Sign Up</a>
      </div>

      <p class="muted">Or sign in with</p>
      <div class="social">
        <a class="ico gg" href="https://accounts.google.com" target="_blank">
          <img src="https://cdn.jsdelivr.net/gh/simple-icons/simple-icons/icons/google.svg" alt="Google">
        </a>
        <a class="ico fb" href="https://facebook.com" target="_blank">
          <img src="https://cdn.jsdelivr.net/gh/simple-icons/simple-icons/icons/facebook.svg" alt="Facebook">
        </a>
        <a class="ico in" href="https://linkedin.com" target="_blank">
          <img src="https://cdn.jsdelivr.net/gh/simple-icons/simple-icons/icons/linkedin.svg" alt="LinkedIn">
        </a>
      </div>
    </main>
  </div>
</body>
</html>
