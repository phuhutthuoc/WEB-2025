<?php
session_start();
require_once __DIR__ . '/../db.php';
if (!isset($_SESSION['admin_id'])) { header('Location: login.php'); exit; }
if (!isset($_GET['id'])) { header('Location: dashboard.php'); exit; }
$id = (int)$_GET['id'];
$stmt = $pdo->prepare('SELECT * FROM news WHERE id = ?');
$stmt->execute([$id]);
$post = $stmt->fetch();
if (!$post) { echo 'Không tìm thấy'; exit; }
$err='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $title = $_POST['title'] ?? '';
    $excerpt = $_POST['excerpt'] ?? '';
    $content = $_POST['content'] ?? '';
    if (!$title) $err='Tiêu đề bắt buộc';
    if (!$err) {
        $u = $pdo->prepare('UPDATE news SET title=?, excerpt=?, content=? WHERE id=?');
        $u->execute([$title,$excerpt,$content,$id]);
        header('Location: dashboard.php'); exit;
    }
}
?>
<!doctype html><html lang="vi"><head><meta charset="utf-8"><title>Sửa tin</title></head><body>
<h1>Sửa tin</h1>
<?php if($err) echo '<p style="color:red">'.htmlspecialchars($err).'</p>'; ?>
<form method="post">
<label>Tiêu đề:<br><input name="title" value="<?php echo htmlspecialchars($post['title']); ?>" style="width:600px"></label><br>
<label>Trích dẫn:<br><textarea name="excerpt" rows="3" style="width:600px"><?php echo htmlspecialchars($post['excerpt']); ?></textarea></label><br>
<label>Nội dung:<br><textarea name="content" rows="10" style="width:600px"><?php echo htmlspecialchars($post['content']); ?></textarea></label><br>
<button>Lưu</button>
</form>
<p><a href="dashboard.php">Quay lại</a></p>
</body></html>
