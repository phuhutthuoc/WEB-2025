<?php
// File: user/user_login.php
session_start();
// Lên 1 cấp (ra khỏi thư mục 'user') để tìm db.php
require_once __DIR__ . '/../db.php';
$err = '';

// Nếu đã đăng nhập (với tư cách user), đẩy về trang chủ
if (isset($_SESSION['user_id'])) {
    header('Location: ../index.php'); // Lên 1 cấp, về trang chủ
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['username'] ?? '';
    $pass = $_POST['password'] ?? '';
    
    // Tìm user trong bảng 'users'
    $stmt = $pdo->prepare('SELECT id, username, password_hash FROM users WHERE username = ?');
    $stmt->execute([$user]);
    $row = $stmt->fetch();
    
    // Kiểm tra mật khẩu
    if ($row && password_verify($pass, $row['password_hash'])) {
        // Đăng nhập thành công
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $row['username'];
        header('Location: ../index.php'); // Chuyển về trang chủ (lên 1 cấp)
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
  <title>Đăng nhập | UNIKOON</title>
  <link rel="stylesheet" href="../admin/admin.css">
</head>
<body>
  <div class="login-wrapper">
    <div class="login-container">
      <h2>Đăng nhập</h2>
      
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
        <button type="submit" class="btn">Đăng nhập</button>
        <p style="text-align: center; margin-top: 15px; color: var(--text-muted);">
          Chưa có tài khoản? <a href="user_register.php">Đăng ký</a>
        </p>
      </form>
    </div>
  </div>
</body>
</html>