<?php
// live_search.php
// Tái sử dụng file kết nối CSDL của bạn
require_once __DIR__ . '/db.php'; 

$results = [];

// 1. Chỉ tìm khi có từ khóa (term) gửi lên
if (!empty($_GET['term'])) {
    $term = $_GET['term'];

    try {
        // 2. Chuẩn bị truy vấn
        // Chúng ta cần lấy TẤT CẢ thông tin để hiển thị lên modal
        // Lấy 5 sản phẩm đầu tiên khớp tên
        $sql = "SELECT * FROM products WHERE name LIKE ? LIMIT 5";
        
        $stmt = $pdo->prepare($sql);
        
        // 3. Thực thi
        $searchTerm = "%" . $term . "%";
        $stmt->execute([$searchTerm]);
        
        $results = $stmt->fetchAll();
        
    } catch (PDOException $e) {
        // Nếu có lỗi thì trả về mảng rỗng
        $results = []; 
    }
}

// 4. Luôn trả về kết quả dạng JSON
header('Content-Type: application/json; charset=utf-8');
echo json_encode($results);
?>