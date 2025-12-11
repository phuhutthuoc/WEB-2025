<?php
// File: user/user_logout.php
session_start();

// Hủy các biến session của user
unset($_SESSION['user_id']);
unset($_SESSION['username']);

// Quay về trang chủ (lên 1 cấp)
header('Location: ../index.php');
exit;
?>