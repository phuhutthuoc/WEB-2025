<?php
session_start();
require_once __DIR__ . '/functions.php'; // Đã bao gồm db.php

// ✅ Khởi tạo giỏ hàng nếu nó chưa tồn tại
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$cart_items = [];
$total_price = 0;
$cart_product_ids = [];

if (!empty($_SESSION['cart'])) {
    // Lấy tất cả ID sản phẩm từ giỏ hàng
    $cart_product_ids = array_keys($_SESSION['cart']);
    
    // Tạo chuỗi placeholder cho câu lệnh IN (...)
    $placeholders = implode(',', array_fill(0, count($cart_product_ids), '?'));
    
    // Lấy thông tin chi tiết của các sản phẩm này từ CSDL
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
    $stmt->execute($cart_product_ids);
    $products_in_db = $stmt->fetchAll();

    // Sắp xếp lại CSDL để dễ truy cập bằng ID
    $products_by_id = [];
    foreach ($products_in_db as $product) {
        $products_by_id[$product['id']] = $product;
    }
    
    // Xây dựng mảng $cart_items để hiển thị
    foreach ($_SESSION['cart'] as $id => $quantity) {
        if (isset($products_by_id[$id])) {
            $product = $products_by_id[$id];
            
            // =========================================================
            // ✅ LOGIC TÍNH TOÁN GIÁ (KHÔNG ĐỔI SO VỚI LẦN TRƯỚC)
            // =========================================================
            
            // 1. Tìm chuỗi số đầu tiên trong giá (ví dụ: "từ 3.200.000đ..." -> "3.200.000")
            preg_match('/[0-9\.,]+/', $product['price'], $matches);
            $first_number_string = $matches[0] ?? '0'; 

            // 2. Xóa các dấu chấm/phẩy: "3.200.000" -> "3200000"
            $price_only_digits = str_replace(['.', ','], '', $first_number_string);

            // 3. Chuyển thành số
            $price_numeric = floatval($price_only_digits);

            $item_total = 0;
            $is_price_valid = false; 

            // 4. Kiểm tra $price_numeric (giá đã làm sạch)
            if ($price_numeric > 0) {
                $is_price_valid = true;
                $item_total = $price_numeric * $quantity;
                $total_price += $item_total;
            }

            $cart_items[] = [
                'id' => $id,
                'name' => $product['name'],
                'image' => $product['image_url'],
                'price_text' => $product['price'], // Vẫn lưu giá gốc
                'quantity' => $quantity,
                'item_total' => $item_total, 
                'is_numeric_price' => $is_price_valid 
            ];
            
            // =========================================================
            // ✅ KẾT THÚC KHỐI LOGIC TÍNH TOÁN
            // =========================================================

        } else {
            // Nếu sản phẩm không còn trong CSDL, xóa nó khỏi giỏ hàng
            unset($_SESSION['cart'][$id]);
        }
    }
}

$cart_count = count($_SESSION['cart']);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Giỏ hàng | UNIKOON</title>
  <link rel="stylesheet" href="style.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
  <style>
    /* CSS cho trang giỏ hàng (không đổi) */
    .cart-container {
      width: 90%;
      max-width: 1200px;
      margin: 120px auto 40px auto; 
      min-height: 60vh;
      color: #fff;
    }
    .cart-container h1 {
      color: var(--primary-color);
      text-align: center;
      margin-bottom: 30px;
    }
    .cart-table {
      width: 100%;
      border-collapse: collapse;
    }
    .cart-table thead th {
      background: #1a1a1a;
      padding: 15px;
      text-align: left;
    }
    .cart-table tbody td {
      padding: 15px;
      border-bottom: 1px solid #333;
      vertical-align: middle;
    }
    .cart-product {
      display: flex;
      align-items: center;
    }
    .cart-product img {
      width: 80px;
      height: 80px;
      object-fit: contain;
      margin-right: 15px;
      background: #222;
      border-radius: 5px;
    }
    .cart-product-info a {
      color: #fff;
      text-decoration: none;
      font-weight: bold;
      font-size: 1.1rem;
    }
    .cart-product-info a:hover {
      color: var(--primary-color);
    }
    .quantity-input {
      width: 60px;
      padding: 5px;
      text-align: center;
      background: #333;
      color: #fff;
      border: 1px solid #555;
      border-radius: 4px;
    }
    .remove-btn {
      color: #ff4d4d;
      text-decoration: none;
      font-weight: bold;
    }
    .remove-btn:hover {
      color: #ff0000;
    }
    .cart-summary {
      margin-top: 30px;
      float: right;
      width: 100%;
      max-width: 400px;
      background: #1a1a1a;
      padding: 20px;
      border-radius: 8px;
    }
    .cart-summary h3 {
      margin-top: 0;
    }
    .cart-summary .total {
      font-size: 1.2rem;
      font-weight: bold;
      color: var(--primary-color);
    }
    .empty-cart {
      text-align: center;
      font-size: 1.2rem;
      color: #888;
    }
  </style>
</head>
<body>

  <header class="header">
    <div class="container">
      <a href="index.php" class="logo"><img src="logo.png" alt="Unikoon Logo" /></a>
      <nav class="main-nav">
        <ul>
          <li><a href="index.php">Trang chủ</a></li>
          <li class="dropdown">
            <a href="#">Sản phẩm <i class="fas fa-chevron-down"></i></a>
            <ul class="dropdown-menu">
              <li><a href="index.php#mainboard-section">Bo Mạch Chủ</a></li>
              <li><a href="index.php#vga-section">Card Đồ Họa</a></li>
              <li><a href="index.php#keyboard-section">Bàn Phím</a></li>
              <li><a href="index.php#mouse-section">Chuột</a></li>
            </ul>
          </li>
          <li><a href="news.php">Tin tức</a></li>
          <li><a href="https://www.facebook.com/profile.php?id=61574853930792">Cộng đồng</a></li>
          <li><a href="#">Về chúng tôi</a></li>
        </ul>
      </nav>
      
      <div class="header-actions">
        <a href="cart.php" class="cart-icon" title="Giỏ hàng">
            <i class="fas fa-shopping-cart"></i>
            <span id="cart-item-count"><?php echo $cart_count; ?></span>
        </a>

        <?php if (isset($_SESSION['user_id'])): ?>
            <div class="user-greeting" style="display: flex; align-items: center; gap: 15px;">
                <span style="color: var(--text-color);">Chào, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
                <a href="user/user_logout.php" class="btn btn-secondary" style="padding: 8px 15px; font-size: 0.9rem;">Đăng xuất</a>
            </div>
        <?php else: ?>
            <div class="user-auth-links" style="display: flex; gap: 10px;">
                <a href="user/user_login.php" class="btn btn-secondary" style="padding: 8px 15px; font-size: 0.9rem;">Đăng nhập</a>
                <a href="user/user_register.php" class="btn btn-primary" style="padding: 8px 15px; font-size: 0.9rem;">Đăng ký</a>
            </div>
        <?php endif; ?>
      </div>
    </div>
  </header>
  <main class="cart-container">
    <h1>Giỏ hàng của bạn</h1>

    <?php if (empty($cart_items)): ?>
        <p class="empty-cart">Giỏ hàng của bạn đang trống. <a href="index.php" class="btn">Quay lại mua sắm</a></p>
    <?php else: ?>
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th>Giá</th>
                    <th>Số lượng</th>
                    <th>Tạm tính</th>
                    <th>Xóa</th>
                </tr>
            </thead>
            <tbody id="cart-body">
                <?php foreach ($cart_items as $item): ?>
                <tr data-id="<?php echo $item['id']; ?>">
                    <td>
                        <div class="cart-product">
                            <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="">
                            <div class="cart-product-info">
                                <a href="#"><?php echo htmlspecialchars($item['name']); ?></a>
                            </div>
                        </div>
                    </td>
                    
                    <td>
                        <?php 
                        $display_price = str_replace('khoảng ', '', $item['price_text']);
                        echo htmlspecialchars($display_price); 
                        ?>
                    </td>
                    
                    <td>
                        <input type="number" 
                               class="quantity-input" 
                               value="<?php echo $item['quantity']; ?>" 
                               min="1" 
                               data-id="<?php echo $item['id']; ?>">
                    </td>
                    <td class="item-total">
                        <?php 
                        if ($item['is_numeric_price']) {
                            echo number_format($item['item_total'], 0, ',', '.') . ' ₫';
                        } else {
                            echo 'Liên hệ';
                        }
                        ?>
                    </td>
                    <td>
                        <a href="#" class="remove-btn" data-id="<?php echo $item['id']; ?>">&times; Xóa</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="cart-summary">
            <h3>Tổng cộng</h3>
            <p class="total" id="cart-total">
                <?php 
                echo number_format($total_price, 0, ',', '.') . ' ₫'; 
                ?>
            </p>
            <p style="color: #999; font-size: 0.9rem;">(Chưa bao gồm phí vận chuyển)</p>
            <a href="#" class="btn btn-primary" style="width: 100%; text-align: center;">Tiến hành thanh toán</a>
        </div>
    <?php endif; ?>
  </main>

  <footer class="footer">
      </footer>


  <script>
  document.addEventListener('DOMContentLoaded', function() {
      // Header scroll effect
      const header = document.querySelector('.header');
      window.addEventListener('scroll', function() {
          if (window.scrollY > 50) {
              header.classList.add('scrolled');
          } else {
              header.classList.remove('scrolled');
          }
      });

      // Hàm cập nhật giỏ hàng (sửa số lượng, xóa)
      function updateCart(productId, quantity, action) {
          fetch('cart_manage.php', {
              method: 'POST',
              headers: { 'Content-Type': 'application/json' },
              body: JSON.stringify({
                  action: action,
                  product_id: productId,
                  quantity: quantity
              })
          })
          .then(response => response.json())
          .then(data => {
              if (data.success) {
                  // Tải lại trang để cập nhật giot hàng và tổng tiền
                  window.location.reload(); 
              } else {
                  alert('Lỗi: ' + data.message);
              }
          });
      }

      // Bắt sự kiện thay đổi số lượng
      document.querySelectorAll('.quantity-input').forEach(input => {
          input.addEventListener('change', function() {
              const id = this.dataset.id;
              const qty = parseInt(this.value, 10);
              if (qty > 0) {
                  updateCart(id, qty, 'update');
              } else {
                  // Nếu gõ số 0, coi như xóa
                  updateCart(id, 0, 'remove');
              }
          });
      });

      // =========================================================
      // ✅ BẮT ĐẦU SỬA LỖI XÁC NHẬN XÓA
      // =========================================================
      document.querySelectorAll('.remove-btn').forEach(button => {
          button.addEventListener('click', function(e) {
              e.preventDefault();
              
              // ✅ ĐÃ XÓA CÂU LỆNH IF (CONFIRM(...))
              // Giờ nó sẽ xóa ngay lập tức
              const id = this.dataset.id;
              updateCart(id, 0, 'remove');
          });
      });
      // =========================================================
      // ✅ KẾT THÚC SỬA LỖI
      // =========================================================

  });
  </script>

</body>
</html>