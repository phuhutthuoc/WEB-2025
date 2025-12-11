<?php
session_start();
require_once __DIR__ . '/../db.php';
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}
// Lấy danh sách sản phẩm
$stmt = $pdo->query('SELECT id, name, image_url, price FROM products ORDER BY id DESC');
$rows = $stmt->fetchAll();
?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Quản lý Sản phẩm | UNIKOON</title>
  <link rel="stylesheet" href="admin.css">
</head>
<body>

  <header class="admin-header">
    <h1>Quản lý Sản phẩm</h1>
    <nav class="admin-nav">
      <a href="new_product.php" class="btn-primary">Thêm sản phẩm mới</a>
      <a href="dashboard.php" class="btn">Quay lại Dashboard</a>
      <a href="logout.php" class="btn-logout">Đăng xuất</a>
    </nav>
  </header>

  <div class="container">
    <table class="admin-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Hình ảnh</th>
          <th>Tên sản phẩm</th>
          <th>Giá</th>
          <th>Hành động</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($rows as $r): ?>
        <tr>
          <td><?php echo $r['id']; ?></td>
          <td>
            <img src="../<?php echo htmlspecialchars($r['image_url']); ?>" alt="" style="width: 60px; height: 60px; object-fit: contain; background: #333; border-radius: 4px;">
          </td>
          <td><?php echo htmlspecialchars($r['name']); ?></td>
          <td>
    <?php
        // Kiểm tra xem giá có phải là số không
        if (is_numeric($r['price'])) {
            // Nếu là số, định dạng nó
            echo number_format($r['price'], 0, ',', '.') . ' ₫';
        } else {
            // Nếu là chữ (Liên hệ) hoặc NULL, chỉ hiển thị ra
            echo htmlspecialchars($r['price']);
        }
    ?>
</td>
          <td class="actions">
            <a href="edit_product.php?id=<?php echo $r['id']; ?>" class="edit">Sửa</a> |
            <a href="delete_product.php?id=<?php echo $r['id']; ?>" class="delete" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')">Xóa</a>
          </td>
        </tr>
        <?php endforeach; ?>
        
        <?php if(empty($rows)): ?>
            <tr><td colspan="5" style="text-align: center;">Chưa có sản phẩm nào.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

</body>
</html>