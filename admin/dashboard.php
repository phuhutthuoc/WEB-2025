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
<head><meta charset="utf-8"><title>Admin Dashboard</title></head>
<body>
<h1>Dashboard</h1>
<p><a href="logout.php">Đăng xuất</a> | <a href="new_post.php">Thêm tin mới</a></p>
<table border="1" cellpadding="6">
<tr><th>ID</th><th>Tiêu đề</th><th>Ngày</th><th>Hành động</th></tr>
<?php foreach($rows as $r): ?>
<tr>
  <td><?php echo $r['id']; ?></td>
  <td><?php echo htmlspecialchars($r['title']); ?></td>
  <td><?php echo $r['created_at']; ?></td>
  <td>
    <a href="edit_post.php?id=<?php echo $r['id']; ?>">Sửa</a> |
    <a href="delete_post.php?id=<?php echo $r['id']; ?>" onclick="return confirm('Xóa?')">Xóa</a>
  </td>
</tr>
<?php endforeach; ?>
</table>
</body>
</html>
