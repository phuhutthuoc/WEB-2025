<?php
session_start();
require_once __DIR__ . '/../db.php';
if (!isset($_SESSION['admin_id'])) { header('Location: login.php'); exit; }
if (!isset($_GET['id'])) { header('Location: products.php'); exit; }
$id = (int)$_GET['id'];
$stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
$stmt->execute([$id]);
$product = $stmt->fetch();
if (!$product) { echo 'Không tìm thấy sản phẩm'; exit; }

// Gán giá trị ban đầu từ database
$name = $product['name'];
$image = $product['image_url'];
$price = $product['price'];
$description = $product['description'];
$category = trim($product['category']); // <-- Thêm hàm trim() vào đây
$specs = $product['specs']; // <-- LẤY SPECS CÓ SẴN

$err='';

if ($_SERVER['REQUEST_METHOD']==='POST') {
    // Lấy dữ liệu từ form
    $name = $_POST['name'] ?? '';
    $image = $_POST['image'] ?? '';
    $price = $_POST['price'] ?? '';
    $description = $_POST['description'] ?? '';
    $category = $_POST['category'] ?? '';
    $specs = $_POST['specs'] ?? ''; // <-- LẤY SPECS MỚI

    // Kiểm tra
    if (!$name) $err='Tên sản phẩm là bắt buộc';
    if (!$price) $err='Giá sản phẩm là bắt buộc';
    if (!$category) $err='Danh mục là bắt buộc';
    
    if (!$err) {
        // Cập nhật câu lệnh UPDATE
        $u = $pdo->prepare('UPDATE products SET name=?, image_url=?, price=?, description=?, category=?, specs=? WHERE id=?');
        $u->execute([$name, $image, $price, $description, $category, $specs, $id]); // <-- Thêm $specs
        
        header('Location: products.php'); exit;
    }
}
?>
<!doctype html>
<html lang="vi">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sửa sản phẩm | UNIKOON</title>
  <link rel="stylesheet" href="admin.css">
</head>
<body>

  <header class="admin-header">
    <h1>Sửa sản phẩm: <?php echo htmlspecialchars($product['name']); ?></h1>
    <nav class="admin-nav">
      <a href="products.php">Quay lại Quản lý sản phẩm</a>
    </nav>
  </header>

  <div class="container">
    <div class="login-container" style="max-width: 1200px; margin-top: 20px;">
      
      <?php if($err): ?>
        <p class="error-message"><?php echo htmlspecialchars($err); ?></p>
      <?php endif; ?>

      <form method="post">
        <div class="form-group">
          <label for="name">Tên sản phẩm (Bắt buộc):</label>
          <input id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
        </div>

        <div class="form-group">
          <label for="image">Đường dẫn hình ảnh:</label>
          <input id="image" name="image" value="<?php echo htmlspecialchars($image); ?>" placeholder="Ví dụ: images/vga_rtx_4060.png">
        </div>
        
        <div class="form-group">
          <label for="description">Mô tả (Hiển thị trên card sản phẩm):</label>
          <textarea id="description" name="description" rows="3"><?php echo htmlspecialchars($description); ?></textarea>
        </div>
        
        <div class="form-group">
          <label for="specs">Thông số chi tiết (Hiển thị ở pop-up):</label>
          <textarea id="specs" name="specs" rows="6"><?php echo htmlspecialchars($specs); ?></textarea>
          <small style="color: var(--text-muted); display: block; margin-top: 5px;">*Gõ thông số, mỗi thông số 1 dòng.</small>
        </div>

        <div class="form-group">
          <label for="category">Danh mục (Bắt buộc):</label>
          <select id="category" name="category" required>
            <option value="">-- Chọn danh mục --</option>
            <option value="Bo Mạch Chủ" <?php if($category == 'Bo Mạch Chủ') echo 'selected'; ?>>Bo Mạch Chủ</option>
            <option value="Card Đồ Họa" <?php if($category == 'Card Đồ Họa') echo 'selected'; ?>>Card Đồ Họa</option>
            <option value="Bàn Phím" <?php if($category == 'Bàn Phím') echo 'selected'; ?>>Bàn Phím</option>
            <option value="Chuột" <?php if($category == 'Chuột') echo 'selected'; ?>>Chuột</option>
          </select>
        </div>

        <div class="form-group">
          <label for="price">Giá (Bắt buộc):</label>
          <input id="price" name="price" type="text" value="<?php echo htmlspecialchars($price); ?>" required placeholder="Nhập số tiền hoặc chữ 'Liên hệ'">
        </div>
        
        <button type="submit" class="btn">Lưu cập nhật</button>
      </form>
    </div>
  </div>
    </body>
</html>