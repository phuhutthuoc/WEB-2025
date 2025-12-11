<?php
// Simple admin login. In production use stronger measures (rate-limit, CSRF, session hardening).
session_start();
require_once __DIR__ . '/../db.php';
$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['username'] ?? '';
    $pass = $_POST['password'] ?? '';
    // Fetch admin user
    $stmt = $pdo->prepare('SELECT id, username, password_hash FROM admins WHERE username = ?');
    $stmt->execute([$user]);
    $row = $stmt->fetch();
    if ($row && password_verify($pass, $row['password_hash'])) {
        $_SESSION['admin_id'] = $row['id'];
        header('Location: dashboard.php');
        exit;
    } else {
        $err = 'Tên đăng nhập hoặc mật khẩu không đúng';
    }
}
?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Đăng nhập Admin | UNIKOON</title>
  <link rel="stylesheet" href="admin.css">
</head>
<body>

  <div class="login-wrapper">
    <div class="login-container">
      <h2>Đăng nhập quản trị</h2>
      
      <?php if($err): ?>
        <p class="error-message"><?php echo htmlspecialchars($err); ?></p>
      <?php endif; ?>

      <form method="post">
        <div class="form-group">
          <label for="username">Username:</label>
          <input id="username" name="username" required>
        </div>
        <div class="form-group">
          <label for="password">Password:</label>
          <input id="password" name="password" type="password" required>
        </div>
        <button typeS="submit" class="btn">Đăng nhập</button>
      </form>
    </div>
  </div>

</body>
</html>
