<?php
session_start();
require_once __DIR__ . '/../db.php';
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}
// List news
$stmt = $pdo->query('SELECT id, title, created_at FROM news ORDER BY created_at DESC');
$rows = $stmt->fetchAll();
?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard | UNIKOON</title>
  <link rel="stylesheet" href="admin.css">
</head>
<body>

  <header class="admin-header">
    <h1>UNIKOON Dashboard</h1>
    <nav class="admin-nav">
      <a href="new_post.php" class="btn-primary">Thêm tin mới</a>
      <a href="new_product.php" class="btn-primary">Thêm sản phẩm</a>
      <a href="products.php" class="btn">Quản lý sản phẩm</a>
      <a href="logout.php" class="btn-logout">Đăng xuất</a>
    </nav>
  </header>

  <div class="container">
    <table class="admin-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Tiêu đề</th>
          <th>Ngày đăng</th>
          <th>Hành động</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($rows as $r): ?>
        <tr>
          <td><?php echo $r['id']; ?></td>
          <td><?php echo htmlspecialchars($r['title']); ?></td>
          <td><?php echo date('d/m/Y H:i', strtotime($r['created_at'])); ?></td>
          <td class="actions">
            <a href="edit_post.php?id=<?php echo $r['id']; ?>" class="edit">Sửa</a> |
            <a href="delete_post.php?id=<?php echo $r['id']; ?>" class="delete" onclick="return confirm('Bạn có chắc chắn muốn xóa bài viết này?')">Xóa</a>
          </td>
        </tr>
        <?php endforeach; ?>
        
        <?php if(empty($rows)): ?>
            <tr><td colspan="4" style="text-align: center;">Chưa có bài viết nào.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

</body>
</html>
