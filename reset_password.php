<?php
require_once __DIR__ . '/db.php';

$message = "";
$token = $_GET['token'] ?? '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'];
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];

    if ($password !== $confirm) {
        $message = "<div class='error'>❌ Passwords do not match</div>";
    } else {
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("SELECT user_id FROM password_resets WHERE token=? AND expires_at > NOW() ORDER BY id DESC LIMIT 1");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();
        $reset = $result->fetch_assoc();

        if ($reset) {
            $userId = $reset['user_id'];

            // Update password
            $stmt = $conn->prepare("UPDATE users SET password=? WHERE id=?");
            $stmt->bind_param("si", $hashed, $userId);
            $stmt->execute();

            // Delete used reset request
            $stmt = $conn->prepare("DELETE FROM password_resets WHERE token=?");
            $stmt->bind_param("s", $token);
            $stmt->execute();

            $message = "<div class='success'>✅ Password updated! <a href='login.php'>Login now</a></div>";
        } else {
            $message = "<div class='error'>❌ Invalid or expired token</div>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Reset Password</title>
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
    <h2>Reset Password</h2>
    <?php echo $message; ?>
    <form method="POST">
      <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
      <label>New Password:</label>
      <input type="password" name="password" required>
      <label>Confirm Password:</label>
      <input type="password" name="confirm" required>
      <button type="submit">Update Password</button>
    </form>
    <p><a href="login.php" style="color:#e6bb91;">⬅ Back to Login</a></p>
  </div>
</body>
</html>
