<?php
require_once __DIR__ . '/db.php';

function get_latest_news($limit = 5) {
    global $pdo;
    $stmt = $pdo->prepare('SELECT id, title, excerpt, content, created_at FROM news ORDER BY created_at DESC LIMIT ?');
    $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

// Lấy danh sách sản phẩm từ database
function get_all_products($limit = 0) {
    global $pdo;
    $sql = "SELECT * FROM products ORDER BY created_at DESC";
    if ($limit > 0) {
        $sql .= " LIMIT " . intval($limit);
    }
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
}

function get_news($id) {
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM news WHERE id = ?');
    $stmt->execute([$id]);
    return $stmt->fetch();
}
function get_pdo_connection() {
    $host = 'localhost';
    $db   = 'unikoon';
    $user = 'root';
    $pass = '';
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];

    return new PDO($dsn, $user, $pass, $options);
}

function get_featured_products($limit = 3) {
    global $pdo;
    // Lấy ngẫu nhiên 3 sản phẩm
    $stmt = $pdo->prepare('SELECT * FROM products ORDER BY RAND() LIMIT ?');
    $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

/**
 * Lấy tất cả sản phẩm theo một danh mục (category)
 */
function get_products_by_category($category) {
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM products WHERE category = ? ORDER BY id DESC');
    $stmt->execute([$category]);
    return $stmt->fetchAll();
}
?>