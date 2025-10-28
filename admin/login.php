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
<head><meta charset="utf-8"><title>Đăng nhập Admin</title></head>
<body>
<h2>Đăng nhập quản trị</h2>
<?php if($err) echo '<p style="color:red">'.htmlspecialchars($err).'</p>'; ?>
<form method="post">
  <label>Username: <input name="username"></label><br>
  <label>Password: <input name="password" type="password"></label><br>
  <button>Đăng nhập</button>
</form>
</body>
</html>
