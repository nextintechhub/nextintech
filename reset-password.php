<?php
require_once __DIR__ . '/db.php';
session_start();

$message = "";

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Check if token is valid
    $stmt = $conn->prepare("SELECT * FROM password_resets WHERE token=? AND expires_at > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    $reset = $result->fetch_assoc();

    if (!$reset) {
        $message = "<div class='error'>❌ Invalid or expired reset link</div>";
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($reset['user_id'])) {
        $newPass = password_hash($_POST['password'], PASSWORD_DEFAULT);

        // Update user password
        $stmt = $conn->prepare("UPDATE users SET password=? WHERE id=?");
        $stmt->bind_param("si", $newPass, $reset['user_id']);
        $stmt->execute();

        // Delete used token
        $stmt = $conn->prepare("DELETE FROM password_resets WHERE token=?");
        $stmt->bind_param("s", $token);
        $stmt->execute();

        $message = "<div class='success'>✅ Password updated! <a href='login.php'>Login</a></div>";
    }
} else {
    $message = "<div class='error'>❌ No reset token provided</div>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Reset Password</title>
  <style>
    body { font-family: Arial, sans-serif; background:#0b2656; color:white; display:flex; justify-content:center; align-items:center; height:100vh; }
    .card { background:rgba(255,255,255,0.1); padding:20px; border-radius:8px; width:350px; }
    .success { background:#4CAF50; padding:10px; margin-bottom:10px; border-radius:5px; }
    .error { background:#f44336; padding:10px; margin-bottom:10px; border-radius:5px; }
    input,button { width:100%; padding:10px; margin-top:10px; border:none; border-radius:5px; }
    button { background:#e6bb91; cursor:pointer; font-weight:bold; }
    button:hover { background:#d4a97f; }
  </style>
</head>
<body>
  <div class="card">
    <h2>Reset Password</h2>
    <?php echo $message; ?>
    <?php if (isset($reset) && $reset): ?>
      <form method="POST">
        <label>New Password:</label>
        <input type="password" name="password" required minlength="6">
        <button type="submit">Update Password</button>
      </form>
    <?php endif; ?>
  </div>
</body>
</html>
