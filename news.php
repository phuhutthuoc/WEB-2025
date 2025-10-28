<?php 
require_once 'functions.php';
$latest = get_latest_news(10);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Tin tức - Unikoon</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

  <!-- Thanh công cụ giống trang chủ -->
  <header class="navbar">
    <div class="container">
      <h1 class="logo">Unikoon</h1>
      <nav>
        <ul>
          <li><a href="index.php">Trang chủ</a></li>
          <li><a href="news.php" class="active">Tin tức</a></li>
          <li><a href="#">Liên hệ</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <!-- Danh sách tin tức -->
  <section id="news" class="news-section">
    <div class="container">
      <h2>Tin tức mới nhất</h2>
      <div class="news-list">
        <?php foreach($latest as $n): ?>
          <article class="news-item">
            <h3><a href="#" class="news-title" data-content="<?php echo htmlspecialchars($n['content']); ?>">
              <?php echo htmlspecialchars($n['title']); ?>
            </a></h3>
            <p class="meta">Đăng ngày: <?php echo date('d/m/Y', strtotime($n['created_at'])); ?></p>
            <p><?php echo htmlspecialchars($n['excerpt']); ?></p>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- Hộp hiển thị nội dung nổi lên -->
  <div id="popup" class="popup hidden">
    <div class="popup-content">
      <span class="close-btn">&times;</span>
      <div id="popup-body"></div>
    </div>
  </div>

  <script>
  // JS cho phần hiển thị nội dung nổi lên
  document.querySelectorAll('.news-title').forEach(link => {
    link.addEventListener('click', e => {
      e.preventDefault();
      const content = e.target.dataset.content;
      document.getElementById('popup-body').innerHTML = content;
      document.getElementById('popup').classList.remove('hidden');
    });
  });

  document.querySelector('.close-btn').addEventListener('click', () => {
    document.getElementById('popup').classList.add('hidden');
  });
  </script>

</body>
</html>
