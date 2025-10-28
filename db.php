<?php
// db.php - Database connection using PDO
// Configure these values for your environment
$db_host = '127.0.0.1';
$db_name = 'unikoon';
$db_user = 'root';
$db_pass = '';
$dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8mb4";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];
try {
    $pdo = new PDO($dsn, $db_user, $db_pass, $options);
} catch (PDOException $e) {
    // For development show error. In production, log instead.
    exit('Database connection failed: ' . $e->getMessage());
}
?>