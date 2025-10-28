<?php
require_once 'functions.php';
require_once 'db.php'; // nếu có file này, giữ lại; nếu không, xóa dòng này

header('Content-Type: application/json; charset=utf-8');

// --- Lấy ID bài viết ---
if (!isset($_GET['id'])) {
    echo json_encode(['error' => 'Thiếu ID']);
    exit;
}
$id = intval($_GET['id']);

// --- Kết nối database ---
try {
    // Nếu bạn đã có sẵn $pdo trong db.php thì xóa đoạn try..catch này đi
    $pdo = new PDO("mysql:host=localhost;dbname=unikoon;charset=utf8", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Kết nối DB thất bại: ' . $e->getMessage()]);
    exit;
}

// --- Truy vấn bài viết ---
$stmt = $pdo->prepare("SELECT id, title, content, created_at FROM news WHERE id = ?");
$stmt->execute([$id]);
$news = $stmt->fetch(PDO::FETCH_ASSOC);

// --- Trả kết quả JSON ---
if ($news) {
    echo json_encode([
        'id' => $news['id'],
        'title' => $news['title'],
        'content' => nl2br(htmlspecialchars($news['content'], ENT_QUOTES, 'UTF-8')),
        'created_at' => $news['created_at']
    ]);
} else {
    echo json_encode(['error' => 'Không tìm thấy bài viết']);
}
?>
