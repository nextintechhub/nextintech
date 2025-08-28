<?php
require_once __DIR__ . '/config.php';

$msg = '';
$ok = false;
$values = ['fullname' => '', 'email' => '', 'phone' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf'] ?? '';
    $fullname = trim($_POST['fullname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = (string)($_POST['password'] ?? '');
    $phone = trim($_POST['phone'] ?? '');

    $values['fullname'] = $fullname;
    $values['email'] = $email;
    $values['phone'] = $phone;

    if (!verify_csrf($token)) {
        $msg = 'Security check failed. Please refresh and try again.';
    } elseif ($fullname === '' || $email === '' || $password === '' || $phone === '') {
        $msg = 'All fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg = 'Please enter a valid email address.';
    } elseif (strlen($password) < 6) {
        $msg = 'Password must be at least 6 characters.';
    } else {
        $chk = $mysqli->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
        if ($chk) {
            $chk->bind_param('s', $email);
            $chk->execute();
            $chk->store_result();
            if ($chk->num_rows > 0) {
                $msg = 'Email already registered.';
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $ins = $mysqli->prepare("INSERT INTO users (fullname, email, password, phone) VALUES (?,?,?,?)");
                if ($ins) {
                    $ins->bind_param('ssss', $fullname, $email, $hash, $phone);
                    if ($ins->execute()) {
                        $ok = true;
                        $msg = 'Account created successfully! Redirecting to login...';
                        header("Refresh:1; url=login.php");
                        exit;
                    } else {
                        $msg = 'Failed to create account. Try again.';
                    }
                    $ins->close();
                } else {
                    $msg = 'Server error. Try later.';
                }
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
  <title>NextInTech • Sign Up</title>
  <link rel="stylesheet" href="assets/css/style.css" />
</head>
<body class="bg">
  <div class="shell">
    <header class="topbar">
      <div class="brand">NextInTech</div>
    </header>

    <main class="card form-card slide-up">
      <h1 class="title">Create Account</h1>
      <p class="subtitle">Sign up to get access</p>

      <?php if ($msg): ?>
        <div class="alert <?php echo $ok ? 'success' : 'error'; ?>"><?php echo h($msg); ?></div>
      <?php endif; ?>

      <form method="post" class="form" action="">
        <?php csrf_field(); ?>

        <label class="lbl">Full Name</label>
        <input class="field" type="text" name="fullname" value="<?php echo h($values['fullname']); ?>" required>

        <label class="lbl">Email</label>
        <input class="field" type="email" name="email" value="<?php echo h($values['email']); ?>" required>

        <label class="lbl">Password</label>
        <input class="field" type="password" name="password" placeholder="At least 6 characters" required>

        <label class="lbl">Phone No</label>
        <input class="field" type="tel" name="phone" value="<?php echo h($values['phone']); ?>" required>

        <div class="form-buttons">
          <button class="btn btn-primary lg" type="submit">Sign Up</button>
          <a class="btn btn-secondary" href="login.php">Back to Login</a>
        </div>
      </form>
    </main>
  </div>

  <script src="assets/js/app.js"></script>
</body>
</html>
