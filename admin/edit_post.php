<?php
session_start();
require_once __DIR__ . '/../db.php';
if (!isset($_SESSION['admin_id'])) { header('Location: login.php'); exit; }
if (!isset($_GET['id'])) { header('Location: dashboard.php'); exit; }
$id = (int)$_GET['id'];
$stmt = $pdo->prepare('SELECT * FROM news WHERE id = ?');
$stmt->execute([$id]);
$post = $stmt->fetch();
if (!$post) { echo 'Không tìm thấy'; exit; }
// Gán giá trị ban đầu từ database
$title = $post['title'];
$excerpt = $post['excerpt'];
$content = $post['content'];
$err='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $title = $_POST['title'] ?? '';
    $excerpt = $_POST['excerpt'] ?? '';
    $content = $_POST['content'] ?? '';
    if (!$title) $err='Tiêu đề bắt buộc, không để trống';
    if (!$err) {
        $u = $pdo->prepare('UPDATE news SET title=?, excerpt=?, content=? WHERE id=?');
        $u->execute([$title,$excerpt,$content,$id]);
        header('Location: dashboard.php'); exit;
    }
}
?>
<!doctype html>
<html lang="vi">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sửa tin | UNIKOON</title>
  <link rel="stylesheet" href="admin.css">
</head>
<body>

  <header class="admin-header">
    <h1>Sửa tin: <?php echo htmlspecialchars($post['title']); ?></h1>
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
        
        <button type="submit" class="btn">Lưu cập nhật</button>
      </form>
    </div>
  </div>
    </body>
</html>
