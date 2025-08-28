<?php
require_once __DIR__ . '/config.php';

$msg = '';
$ok = false;
$values = ['email' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf'] ?? '';
    $email = trim($_POST['email'] ?? '');
    $password = (string)($_POST['password'] ?? '');
    $remember = isset($_POST['remember']);

    $values['email'] = $email;

    if (!verify_csrf($token)) {
        $msg = 'Security check failed. Please refresh and try again.';
    } elseif ($email === '' || $password === '') {
        $msg = 'Email and Password are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg = 'Please enter a valid email address.';
    } else {
        $chk = $mysqli->prepare("SELECT id, fullname, password FROM users WHERE email = ? LIMIT 1");
        if ($chk) {
            $chk->bind_param('s', $email);
            $chk->execute();
            $result = $chk->get_result();
            $user = $result->fetch_assoc();

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['fullname'] = $user['fullname'];

                if ($remember) {
                    $token = bin2hex(random_bytes(16));
                    $hashedToken = password_hash($token, PASSWORD_DEFAULT);
                    $upd = $mysqli->prepare("UPDATE users SET remember_token=? WHERE id=?");
                    $upd->bind_param('si', $hashedToken, $user['id']);
                    $upd->execute();
                    $upd->close();

                    setcookie("remember_email", $email, time() + (86400 * 7), "/", "", false, true);
                    setcookie("remember_token", $token, time() + (86400 * 7), "/", "", false, true);
                }

                $ok = true;
                $msg = 'Login successful! Redirecting...';
                header("Refresh:1; url=dashboard.php");
                exit;
            } else {
                $msg = 'Invalid email or password.';
            }
            $chk->close();
        } else {
            $msg = 'Server error. Try later.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>NextInTech • Login</title>
  <link rel="stylesheet" href="assets/css/style.css" />
  <style>
    .form-options {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin: 12px 0 20px;
    }
    .form-options .remember {
      font-size: 0.9rem;
      color: #555;
      display: flex;
      align-items: center;
      gap: 6px;
    }
    .form-options input[type="checkbox"] {
      accent-color: #007bff;
      transform: scale(1.1);
      cursor: pointer;
    }
    .forgot-link {
      font-size: 0.9rem;
      color: #007bff;
      text-decoration: none;
      transition: 0.2s ease;
    }
    .forgot-link:hover {
      text-decoration: underline;
      color: #0056b3;
    }
  </style>
</head>
<body class="bg">
  <div class="shell">
    <header class="topbar">
      <div class="brand">NextInTech</div>
    </header>

    <main class="card form-card slide-up">
      <h1 class="title">Welcome Back</h1>
      <p class="subtitle">Login to your account</p>

      <?php if ($msg): ?>
        <div class="alert <?php echo $ok ? 'success' : 'error'; ?>">
          <?php echo h($msg); ?>
        </div>
      <?php endif; ?>

      <form method="post" class="form" action="">
        <?php csrf_field(); ?>

        <label class="lbl">Email</label>
        <input class="field" type="email" name="email" value="<?php echo h($values['email']); ?>" required>

        <label class="lbl">Password</label>
        <input class="field" type="password" name="password" required>

        <div class="form-options">
          <label class="remember">
            <input type="checkbox" name="remember"> Remember me
          </label>
          <a href="forgot_password.php" class="forgot-link">Forgot password?</a>
        </div>

        <div class="form-buttons">
          <button class="btn btn-primary lg" type="submit">Login</button>
          <a class="btn btn-secondary" href="signup.php">Sign Up</a>
        </div>
      </form>
    </main>
  </div>

  <script src="assets/js/app.js"></script>
</body>
</html>
