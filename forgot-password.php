<?php
require_once __DIR__ . '/db.php';  // database connection

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "<div class='error'>❌ Invalid email address</div>";
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

            // Normally send an email - here we just show the reset link
            $resetLink = "http://localhost/nextintech/reset-password.php?token=" . $token;
            $message = "<div class='success'>✅ Reset link (demo): <a href='$resetLink'>$resetLink</a></div>";
        } else {
            $message = "<div class='error'>❌ No account found with that email</div>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Forgot Password</title>
  <style>
    body { font-family: Arial, sans-serif; background: #0b2656; color: white; display:flex; justify-content:center; align-items:center; height:100vh; }
    .card { background: rgba(255,255,255,0.1); padding:20px; border-radius:8px; width:350px; }
    .success { background: #4CAF50; padding:10px; margin-bottom:10px; border-radius:5px; }
    .error { background: #f44336; padding:10px; margin-bottom:10px; border-radius:5px; }
    input, button { width:100%; padding:10px; margin-top:10px; border:none; border-radius:5px; }
    button { background:#e6bb91; cursor:pointer; font-weight:bold; }
    button:hover { background:#d4a97f; }
  </style>
</head>
<body>
  <div class="card">
    <h2>Forgot Password</h2>
    <?php echo $message; ?>
    <form method="POST">
      <label>Email:</label>
      <input type="email" name="email" required>
      <button type="submit">Send Reset Link</button>
    </form>
    <p><a href="login.php" style="color:#e6bb91;">⬅ Back to Login</a></p>
  </div>
</body>
</html>
