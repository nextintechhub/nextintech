<?php
include __DIR__ . '/db.php';

$message = "";
$token = $_GET['token'] ?? '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("SELECT id FROM users WHERE reset_token=? AND reset_expires > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        $update = $conn->prepare("UPDATE users SET password=?, reset_token=NULL, reset_expires=NULL WHERE id=?");
        $update->bind_param("si", $password, $user['id']);
        $update->execute();

        $message = "<div class='success'>✅ Password updated! <a href='login.php'>Login now</a></div>";
    } else {
        $message = "<div class='error'>❌ Invalid or expired token</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reset Password</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <h2>Reset Password</h2>
    <?php echo $message; ?>
    <form action="" method="POST">
      <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
      <label>New Password</label>
      <input type="password" name="password" required>
      <button type="submit">Update Password</button>
    </form>
  </div>
</body>
</html>
