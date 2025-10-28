<?php
session_start();
require_once __DIR__ . '/../db.php';
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php'); exit;
}
$err='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $title = $_POST['title'] ?? '';
    $excerpt = $_POST['excerpt'] ?? '';
    $content = $_POST['content'] ?? '';
    if (!$title) $err='Tiêu đề bắt buộc';
    if (!$err) {
        $stmt = $pdo->prepare('INSERT INTO news (title, excerpt, content, created_at) VALUES (?, ?, ?, NOW())');
        $stmt->execute([$title, $excerpt, $content]);
        header('Location: dashboard.php');
        exit;
    }
}
?>
<!doctype html><html lang="vi"><head><meta charset="utf-8"><title>Thêm tin</title></head><body>
<h1>Thêm tin mới</h1>
<?php if($err) echo '<p style="color:red">'.htmlspecialchars($err).'</p>'; ?>
<form method="post">
<label>Tiêu đề:<br><input name="title" style="width:600px"></label><br>
<label>Trích dẫn:<br><textarea name="excerpt" rows="3" style="width:600px"></textarea></label><br>
<label>Nội dung:<br><textarea name="content" rows="10" style="width:600px"></textarea></label><br>
<button>Thêm</button>
</form>
<p><a href="dashboard.php">Quay lại</a></p>
</body></html>
