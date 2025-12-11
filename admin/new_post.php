<?php
session_start();
require_once __DIR__ . '/../db.php';
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php'); exit;
}
$err='';
$title = '';
$excerpt = '';
$content = '';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $title = $_POST['title'] ?? '';
    $excerpt = $_POST['excerpt'] ?? '';
    $content = $_POST['content'] ?? '';
    if (!$title) $err='Tiêu đề bắt buộc, không để trống';
    if (!$err) {
        $stmt = $pdo->prepare('INSERT INTO news (title, excerpt, content, created_at) VALUES (?, ?, ?, NOW())');
        $stmt->execute([$title, $excerpt, $content]);
        header('Location: dashboard.php');
        exit;
    }
}
?>
<!doctype html>
<html lang="vi">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale-1.0">
  <title>Thêm tin | UNIKOON</title>
  <link rel="stylesheet" href="admin.css">
</head>
<body>

  <header class="admin-header">
    <h1>Thêm tin mới</h1>
    <nav class="admin-nav">
      <a href="dashboard.php">Quay lại Dashboard</a>
    </nav>
  </header>

  <div class="container">
    <div class="login-container" style="max-width: 1200px; margin-top: 20px;">
      
      <?php if($err): ?>
        <p class="error-message"><?php echo htmlspecialchars($err); ?></p>
      <?php endif; ?>

      <form method="post">
        <div class="form-group">
          <label for="title">Tiêu đề (Bắt buộc):</label>
          <input id="title" name="title" value="<?php echo htmlspecialchars($title); ?>" required>
        </div>
        
        <div class="form-group">
          <label for="excerpt">Trích dẫn (Mô tả ngắn):</label>
          <textarea id="excerpt" name="excerpt" rows="3"><?php echo htmlspecialchars($excerpt); ?></textarea>
        </div>
        
        <div class="form-group">
          <label for="content">Nội dung chi tiết:</label>
          <textarea id="content" name="content" rows="10"><?php echo htmlspecialchars($content); ?></textarea>
        </div>
        
        <button type="submit" class="btn">Đăng tin</button>
      </form>
    </div>
  </div>
    </body>
</html>
