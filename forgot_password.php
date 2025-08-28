<?php
require_once __DIR__ . '/config.php';

$msg = '';
$ok = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf'] ?? '';
    $email = trim($_POST['email'] ?? '');

    if (!verify_csrf($token)) {
        $msg = 'Security check failed. Refresh and try again.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg = 'Please enter a valid email.';
    } else {
        $stmt = $mysqli->prepare("SELECT id FROM users WHERE email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows > 0) {
            // here you would generate a reset token and send an email
            $msg = 'Password reset link sent to your email (demo only).';
            $ok = true;
        } else {
            $msg = 'No account found with that email.';
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Forgot Password - NextInTech</title>
  <link rel="stylesheet" href="assets/css/style.css" />
</head>
<body class="bg">
  <div class="shell">
    <header class="topbar">
      <div class="brand">NextInTech</div>
    </header>

    <main class="card form-card slide-up">
      <h1 class="title">Reset Password</h1>
      <p class="subtitle">Enter your email to receive a reset link</p>

      <?php if ($msg): ?>
        <div class="alert <?php echo $ok ? 'success' : 'error'; ?>"><?php echo h($msg); ?></div>
      <?php endif; ?>

      <form method="post" class="form" action="">
        <?php csrf_field(); ?>

        <label class="lbl">Email</label>
        <input class="field" type="email" name="email" required>

        <div class="form-buttons">
          <button class="btn btn-primary lg" type="submit">Send Reset Link</button>
          <a class="btn btn-secondary" href="login.php">Back to Login</a>
        </div>
      </form>
    </main>
  </div>

  <script src="assets/js/app.js"></script>
</body>
</html>
