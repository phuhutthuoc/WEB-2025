<?php
session_start();
require_once __DIR__ . '/../db.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['admin_id'])) { 
    header('Location: login.php'); 
    exit;
}

// Kiểm tra xem có ID được gửi lên không
if (!isset($_GET['id'])) { 
    header('Location: products.php'); // Quay về trang quản lý sản phẩm
    exit;
}

$id = (int)$_GET['id'];

// Thực hiện xóa
$pdo->prepare('DELETE FROM products WHERE id = ?')->execute([$id]);

// Quay về trang quản lý sản phẩm
header('Location: products.php');
?>