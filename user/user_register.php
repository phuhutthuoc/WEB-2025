<?php
// File: user/user_register.php
session_start();
// Lên 1 cấp (ra khỏi thư mục 'user') để tìm db.php
require_once __DIR__ . '/../db.php'; 

$err = '';
$success = '';
$user = ''; 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['username'] ?? '';
    $pass = $_POST['password'] ?? '';
    $pass_confirm = $_POST['password_confirm'] ?? '';

    // --- Kiểm tra đầu vào ---
    if (empty($user) || empty($pass)) {
        $err = 'Tên đăng nhập và mật khẩu là bắt buộc.';
    } elseif ($pass !== $pass_confirm) {
        $err = 'Mật khẩu nhập lại không khớp.';
    } elseif (strlen($pass) < 6) {
        $err = 'Mật khẩu phải có ít nhất 6 ký tự.';
    } else {
        // --- Kiểm tra xem user đã tồn tại chưa ---
        $stmt_check = $pdo->prepare('SELECT id FROM users WHERE username = ?');
        $stmt_check->execute([$user]);
        if ($stmt_check->fetch()) {
            $err = 'Tên đăng nhập này đã tồn tại.';
        } else {
            // --- Hợp lệ -> Thêm vào CSDL ---
            $hash = password_hash($pass, PASSWORD_DEFAULT);
            $stmt_insert = $pdo->prepare('INSERT INTO users (username, password_hash) VALUES (?, ?)');
            if ($stmt_insert->execute([$user, $hash])) {
                $success = 'Đăng ký thành công! Bạn có thể đăng nhập ngay bây giờ.';
            } else {
                $err = 'Đã xảy ra lỗi, vui lòng thử lại.';
            }
        }
    }
}
?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Đăng ký | UNIKOON</title>
  <link rel="stylesheet" href="../admin/admin.css">
</head>
<body>
  <div class="login-wrapper">
    <div class="login-container">
      <h2>Đăng ký tài khoản</h2>
      
      <?php if($err): ?>
        <p class="error-message"><?php echo htmlspecialchars($err); ?></p>
      <?php endif; ?>
      
      <?php if($success): ?>
        <p class"error-message" style="background:rgba(77, 255, 77, 0.1); border-color:#4dff4d; color:#4dff4d;">
          <?php echo htmlspecialchars($success); ?>
          <br><br>
          <a href="user_login.php" class="btn" style="width: auto; padding: 10px 20px; color: #fff;">Đi tới Đăng nhập</a>
        </p>
      <?php endif; ?>

      <?php if(!$success): ?>
      <form method="post">
        <div class="form-group">
          <label for="username">Tên đăng nhập:</label>
          <input id="username" name="username" value="<?php echo htmlspecialchars($user); ?>" required>
        </div>
        <div class="form-group">
          <label for="password">Mật khẩu (ít nhất 6 ký tự):</label>
          <input id="password" name="password" type="password" required>
        </div>
        <div class="form-group">
          <label for="password_confirm">Nhập lại mật khẩu:</label>
          <input id="password_confirm" name="password_confirm" type="password" required>
        </div>
        <button type="submit" class="btn">Đăng ký</button>
        <p style="text-align: center; margin-top: 15px; color: var(--text-muted);">
          Đã có tài khoản? <a href="user_login.php">Đăng nhập</a>
        </p>
      </form>
      <?php endif; ?>

    </div>
  </div>
</body>
</html>