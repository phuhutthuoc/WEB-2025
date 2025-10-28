<?php
session_start();
require_once __DIR__ . '/../db.php';
if (!isset($_SESSION['admin_id'])) { header('Location: login.php'); exit;}
if (!isset($_GET['id'])) { header('Location: dashboard.php'); exit;}
$id=(int)$_GET['id'];
$pdo->prepare('DELETE FROM news WHERE id = ?')->execute([$id]);
header('Location: dashboard.php');
