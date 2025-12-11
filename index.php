<?php
session_start(); // <-- THÊM DÒNG NÀY (LUÔN LUÔN Ở ĐẦU TIÊN)
// BƯỚC 1: LẤY DỮ LIỆU
require_once __DIR__ . '/functions.php'; // Đã bao gồm db.php

// Lấy tất cả dữ liệu sản phẩm từ database
$featured_products = get_featured_products(3);
$mainboard_products = get_products_by_category('Bo Mạch Chủ');
$vga_products = get_products_by_category('Card Đồ Họa');
$keyboard_products = get_products_by_category('Bàn Phím');
$mouse_products = get_products_by_category('Chuột');
$latest_news = get_latest_news(2); // Lấy 2 tin tức mới nhất

// TÍNH TOÁN SỐ LƯỢNG GIỎ HÀNG
$cart_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>UNIKOON</title>
  <link rel="icon" type="image/png" href="logo.png">
  <link rel="stylesheet" href="style.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
  <style>
    html {
      scroll-behavior: smooth;
    }
  </style>
</head>
<body data-logged-in="<?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>">
  <header class="header">
    <div class="container">
      <a href="index.php" class="logo"><img src="logo.png" alt="Unikoon Logo" /></a>
      <nav class="main-nav">
        <ul>
          <li><a href="index.php">Trang chủ</a></li>
          <li class="dropdown">
            <a href="#">Sản phẩm <i class="fas fa-chevron-down"></i></a>
            <ul class="dropdown-menu">
              <li><a href="#mainboard-section">Bo Mạch Chủ</a></li>
              <li><a href="#vga-section">Card Đồ Họa</a></li>
              <li><a href="#keyboard-section">Bàn Phím</a></li>
              <li><a href="#mouse-section">Chuột</a></li>
            </ul>
          </li>
          <li><a href="news.php" id="nav-news">Tin tức</a></li>
          <li><a href="https://www.facebook.com/profile.php?id=61574853930792" title="Theo dõi fanpage để cập nhật thông tin sớm nhất !" target="_blank">Cộng đồng</a></li>
          <li><a href="https://unikoon.com/" target="_blank">Về chúng tôi</a></li>
        </ul>
      </nav>
      
<div class="header-actions">
        <div class="search-container">
          <form class="style-search" role="search" id="product-search-form" action="search_results.php" method="GET">
            <div class="style-search">
              <input type="search" id="search-input" name="q" placeholder="Tìm sản phẩm..." autocomplete="off">
              <i class="fas fa-search"></i>
            </div>
          </form>
          <div id="live-search-results"></div>
          
        </div>

        <a href="cart.php" class="cart-icon" title="Giỏ hàng" style="margin-left: 15px;">
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
    </div>
  </header>

  <main>
    <section class="hero">
      <div class="container">
        <h1>KHAI PHÁ TIỀM NĂNG</h1>
        <p>Cùng Unikoon kiến tạo những cỗ máy mạnh mẽ, đồng hành cùng bạn trên mọi hành trình.</p>
        <a href="#featured-products" class="btn btn-secondary">Khám phá sản phẩm</a>
      </div>
    </section>

    <section class="featured-products" id="featured-products">
      <div class="container">
        <h2 class="section-title">SẢN PHẨM NỔI BẬT</h2>
        <div class="product-grid">
          
          <?php foreach($featured_products as $product): ?>
            <div class="product-card" data-id="<?php echo $product['id']; ?>"> 
              <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" />
              <h3><?php echo htmlspecialchars($product['name']); ?></h3>
              <p><?php echo htmlspecialchars($product['description']); ?></p>
              <a href="#" class="btn-view-detail">Xem chi tiết</a>
              <div class="product-info">
                <?php
                  // Xử lý để chèn <li>Giá</li> vào lại <ul> specs cho modal JS hoạt động
                  $specs_with_price = str_replace(
                    '</ul>', 
                    "<li>Giá: " . htmlspecialchars($product['price']) . "</li></ul>", 
                    $product['specs']
                  );
                  echo $specs_with_price;
                ?>
              </div>

            </div>
          <?php endforeach; ?>

        </div>
      </div>
    </section>

    <section id="mainboard-section">
      <div class="container">
        <h2 class="section-title">Các loại Bo Mạch Chủ</h2>
        <div class="product-grid">
          <?php foreach($mainboard_products as $product): ?>
            <div class="product-card" data-id="<?php echo $product['id']; ?>"> 
              <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" />
              <h3><?php echo htmlspecialchars($product['name']); ?></h3>
              <p><?php echo htmlspecialchars($product['description']); ?></p>
              <a href="#" class="btn-view-detail">Xem chi tiết</a>
              <div class="product-info">
                <?php
                  $specs_with_price = str_replace(
                    '</ul>', 
                    "<li>Giá: " . htmlspecialchars($product['price']) . "</li></ul>", 
                    $product['specs']
                  );
                  echo $specs_with_price;
                ?>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <section id="vga-section">
      <div class="container">
        <h2 class="section-title">Các loại Card Đồ Họa</h2>
        <div class="product-grid">
          <?php foreach($vga_products as $product): ?>
            <div class="product-card" data-id="<?php echo $product['id']; ?>">
              <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" />
              <h3><?php echo htmlspecialchars($product['name']); ?></h3>
              <p><?php echo htmlspecialchars($product['description']); ?></p>
              <a href="#" class="btn-view-detail">Xem chi tiết</a>
              <div class="product-info">
                <?php
                  $specs_with_price = str_replace(
                    '</ul>', 
                    "<li>Giá: " . htmlspecialchars($product['price']) . "</li></ul>", 
                    $product['specs']
                  );
                  echo $specs_with_price;
                ?>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <section id="keyboard-section">
      <div class="container">
        <h2 class="section-title">Các loại Bàn Phím</h2>
        <div class="product-grid">
          <?php foreach($keyboard_products as $product): ?>
            <div class="product-card" data-id="<?php echo $product['id']; ?>">
              <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" />
              <h3><?php echo htmlspecialchars($product['name']); ?></h3>
              <p><?php echo htmlspecialchars($product['description']); ?></p>
              <a href="#" class="btn-view-detail">Xem chi tiết</a>
              <div class="product-info">
                <?php
                  $specs_with_price = str_replace(
                    '</ul>', 
                    "<li>Giá: " . htmlspecialchars($product['price']) . "</li></ul>", 
                    $product['specs']
                  );
                  echo $specs_with_price;
                ?>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <section id="mouse-section">
      <div class="container">
        <h2 class="section-title">Các loại Chuột Gaming</h2>
        <div class="product-grid">
          <?php foreach($mouse_products as $product): ?>
             <div class="product-card" data-id="<?php echo $product['id']; ?>">
              <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" />
              <h3><?php echo htmlspecialchars($product['name']); ?></h3>
              <p><?php echo htmlspecialchars($product['description']); ?></p>
              <a href="#" class="btn-view-detail">Xem chi tiết</a>
              <div class="product-info">
                <?php
                  $specs_with_price = str_replace(
                    '</ul>', 
                    "<li>Giá: " . htmlspecialchars($product['price']) . "</li></ul>", 
                    $product['specs']
                  );
                  echo $specs_with_price;
                ?>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <section class="news">
      </section>
  </main>

  <footer class="footer">
    </footer>


  <div id="product-modal" class="modal">
    <div class="modal-content">
      <span class="close-btn">&times;</span>
      <img id="modal-img" src="" alt="Sản phẩm" />
      <div class="modal-info">
        <h2 id="modal-title">Tên sản phẩm</h2>
        <p id="modal-desc">Mô tả ngắn</p>
        <h3>Thông số chi tiết:</h3>
        <div id="modal-specs"></div> 
        <h3 id="modal-price"></h3>

        <div class="modal-actions" style="margin-top: 20px; border-top: 1px solid #333; padding-top: 20px; display: flex; align-items: center; gap: 15px;">
            <label for="modal-quantity" style="margin-right: 5px; font-size: 1rem;">Số lượng:</label>
            <input type="number" id="modal-quantity" value="1" min="1" style="width: 60px; padding: 8px; font-size: 1rem; background-color: #333; color: #fff; border: 1px solid #555; border-radius: 4px;">
            
            <button id="modal-add-to-cart" class="btn btn-primary" data-id="">Thêm vào giỏ</button>
        </div>
        <p id="modal-cart-message" style="color: green; margin-top: 10px; display: none; font-weight: bold;"></p>
        </div>
    </div>
  </div>

  <script src="script.js"></script>

  <script>
  document.addEventListener("DOMContentLoaded", function() {
    // ... (code cuộn mượt của bạn) ...
  });
  </script>

</body>
</html>