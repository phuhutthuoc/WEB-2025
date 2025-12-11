<?php 
session_start();
require_once __DIR__ . '/functions.php';
$latest = get_latest_news(10);

// ✅ CẬP NHẬT: TÍNH TOÁN SỐ LƯỢNG GIỎ HÀNG
if (!isset($_SESSION['cart'])) { // Cũng nên thêm kiểm tra này ở đây
    $_SESSION['cart'] = [];
}
$cart_count = count($_SESSION['cart']);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tin tức | Unikoon</title>
  <link rel="stylesheet" href="style.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
  <style>
    html { scroll-behavior: smooth; }
    body { margin: 0; background-color: #000; color: #fff; font-family: Arial, sans-serif; }

    /* Hero nền ảnh */
    .hero {
      position: relative;
      width: 100%;
      min-height: 100vh; /* ✅ cho phép nội dung cao hơn 1 màn hình */
      background: url('backround.jpg') no-repeat center center/cover;
      display: flex;
      align-items: flex-start; /* ✅ để phần đầu nằm phía trên */
      justify-content: center;
      padding-top: 120px; /* ✅ tránh header che mất tiêu đề */
      overflow: visible; /* hoặc bỏ luôn dòng này */
    }

    /* Lớp phủ đen mờ giúp chữ dễ đọc */
    .hero::before {
      content: "";
      position: absolute;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background: rgba(0,0,0,0.6);
      z-index: 1;
    }

    /* Vùng tin tức nằm trong hero */
    .news-overlay {
      position: relative;
      z-index: 2;
      width: 80%;
      max-width: 900px;
      background: rgba(0,0,0,0.5);
      border-radius: 12px;
      padding: 40px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.5);
    }

    .news-overlay h2 {
      text-align: center;
      color: #00aaff;
      margin-bottom: 30px;
      font-size: 28px;
      text-transform: uppercase;
    }

    .news-item {
      margin-bottom: 25px;
      border-bottom: 1px solid rgba(255,255,255,0.1);
      padding-bottom: 15px;
    }

    .news-item.hidden {
      display: none;
    }

    .news-title {
      color: #fff;
      font-size: 20px;
      text-decoration: none;
      font-weight: bold;
    }

    .news-title:hover {
      color: #00aaff;
    }

    .meta {
      color: #ccc;
      font-size: 14px;
      margin-top: 4px;
    }

    /* Popup hiển thị chi tiết tin */
    .popup {
      position: fixed; top: 0; left: 0;
      width: 100%; height: 100%;
      background: rgba(0,0,0,0.8);
      display: flex; align-items: center; justify-content: center;
      z-index: 999;
    }
    .popup.hidden { display: none; }
    .popup-content {
      background: #111;
      padding: 20px;
      border-radius: 10px;
      width: 80%;
      max-width: 600px;
      color: #fff;
      position: relative;
    }
    .close-btn {
      position: absolute; top: 10px; right: 15px;
      cursor: pointer; font-size: 22px; color: #fff;
    }
    .close-btn:hover {
      color: #00aaff;
    }

    /* No results message */
    .no-results {
      text-align: center;
      color: #888;
      padding: 20px;
      display: none;
    }

    .no-results.show {
      display: block;
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
          <li><a href="news.php" class="active">Tin tức</a></li>
          <li><a href="https://www.facebook.com/profile.php?id=61574853930792">Cộng đồng</a></li>
          <li><a href="#">Về chúng tôi</a></li>
        </ul>
      </nav>

      <div class="header-actions">
      <form class="style-search" role="search" id="news-search-form">
        <input type="search" id="news-search-input" name="q" placeholder="Tìm tin tức...">
        <i class="fas fa-search"></i>
      </form> 

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
  </header>


  <section class="hero">
    <div class="news-overlay">
      <h2>Tin tức mới nhất</h2>
      <div class="news-list" id="news-list">
        <?php foreach($latest as $n): ?>
        <?php 
          // Định dạng ngày tháng một lần và lưu vào biến
          $formatted_date = date('d/m/Y', strtotime($n['created_at'])); 
        ?>
        <article class="news-item" 
                 data-title="<?php echo htmlspecialchars($n['title']); ?>" 
                 data-excerpt="<?php echo htmlspecialchars($n['excerpt']); ?>"
                 data-date="<?php echo $formatted_date; ?>">  <a href="#" class="news-title" data-content="<?php echo htmlspecialchars($n['content']); ?>">
            <?php echo htmlspecialchars($n['title']); ?>
          </a>
          <p class="meta">Đăng ngày: <?php echo $formatted_date; ?></p> <p><?php echo htmlspecialchars($n['excerpt']); ?></p>
        </article>
      <?php endforeach; ?>
      </div>
      <div class="no-results" id="no-results">
        Không tìm thấy tin tức phù hợp
      </div>
    </div>
  </section>

  <div id="popup" class="popup hidden">
    <div class="popup-content">
      <span class="close-btn">&times;</span>
      <div id="popup-body"></div>
    </div>
  </div>

  <script>
  console.log('=== NEWS PAGE SCRIPT STARTING ===');

  // Header scroll effect
  window.addEventListener('scroll', function() {
      const header = document.querySelector('.header');
      if (window.scrollY > 50) {
          header.classList.add('scrolled');
      } else {
          header.classList.remove('scrolled');
      }
  });

  document.addEventListener('DOMContentLoaded', function() {
    console.log('News page loaded');

    // ==========================================
    // TÌM KIẾM TIN TÚC
    // ==========================================
    const newsSearchInput = document.getElementById('news-search-input');
    const newsSearchForm = document.getElementById('news-search-form');
    const newsItems = document.querySelectorAll('.news-item');
    const noResults = document.getElementById('no-results');

    console.log('News items found:', newsItems.length);

    if (newsSearchForm) {
      newsSearchForm.addEventListener('submit', function(e) {
        e.preventDefault();
        console.log('News search form submit prevented');
      });
    }

    if (newsSearchInput) {
      console.log('News search input found, attaching listener...');
      
      newsSearchInput.addEventListener('input', function() {
        const query = newsSearchInput.value.trim().toLowerCase();
        console.log('Searching news for:', query);
        
        let visibleCount = 0;

        newsItems.forEach(item => {
          const title = item.getAttribute('data-title').toLowerCase();
          const excerpt = item.getAttribute('data-excerpt').toLowerCase();
          const date = item.getAttribute('data-date'); // ✅ Lấy dữ liệu ngày tháng (không cần toLowerCase)

        // ✅ Thêm điều kiện `date.includes(query)` vào biến isMatch
        // Lưu ý: `query` đã được toLowerCase() ở trên, nhưng vì date chỉ có số và dấu '/'
        // nên chúng ta sẽ dùng giá trị gốc từ ô input cho an toàn.
        
        // TỐT HƠN: Lấy giá trị gốc của query để so sánh với date
        const rawQuery = newsSearchInput.value.trim();
        
        const isMatch = title.includes(query) || // query đã là lowercase
                        excerpt.includes(query) || // query đã là lowercase
                        date.includes(rawQuery);   // So sánh date (VD: "02/11/2025") với query gốc (VD: "2025")

          if (query === '' || isMatch) {
            item.classList.remove('hidden');
            item.style.display = 'block';
            visibleCount++;
          } else {
            item.classList.add('hidden');
            item.style.display = 'none';
          }
        });

        console.log('Visible news items:', visibleCount);

        // Hiển thị thông báo nếu không tìm thấy
        if (visibleCount === 0 && query !== '') {
          noResults.classList.add('show');
        } else {
          noResults.classList.remove('show');
        }
      });

      console.log('News search functionality initialized!');
    }

    // ==========================================
    // POPUP CHI TIẾT TIN TỨC
    // ==========================================
    const popup = document.getElementById('popup');
    const popupBody = document.getElementById('popup-body');
    const closeBtn = document.querySelector('.close-btn');

    // JS cho phần hiển thị nội dung nổi lên
    document.querySelectorAll('.news-title').forEach(link => {
      link.addEventListener('click', e => {
        e.preventDefault();
        const content = e.target.dataset.content;
        popupBody.innerHTML = content;
        popup.classList.remove('hidden');
        console.log('Popup opened');
      });
    });

    if (closeBtn) {
      closeBtn.addEventListener('click', () => {
        popup.classList.add('hidden');
        console.log('Popup closed');
      });
    }

    // Đóng popup khi click bên ngoài
    if (popup) {
      popup.addEventListener('click', (e) => {
        if (e.target === popup) {
          popup.classList.add('hidden');
        }
      });
    }

    console.log('=== NEWS PAGE SCRIPTS LOADED ===');
  });
  </script>

</body>
</html>