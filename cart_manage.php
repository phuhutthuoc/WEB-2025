<?php
session_start();
require_once __DIR__ . '/db.php'; // Kết nối CSDL

// Khởi tạo giỏ hàng nếu chưa có
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$response = [
    'success' => false,
    'message' => 'Có lỗi xảy ra',
    'cartCount' => count($_SESSION['cart'])
];

// Lấy dữ liệu từ AJAX (gửi dạng JSON)
$data = json_decode(file_get_contents('php://input'), true);

$action = $data['action'] ?? '';
$product_id = isset($data['product_id']) ? intval($data['product_id']) : 0;
$quantity = isset($data['quantity']) ? intval($data['quantity']) : 1;

try {
    if ($product_id > 0) {
        // Kiểm tra xem sản phẩm có tồn tại không
        $stmt = $pdo->prepare("SELECT id FROM products WHERE id = ?");
        $stmt->execute([$product_id]);
        
        if ($stmt->fetch()) {
            // Sản phẩm tồn tại, tiến hành xử lý
            switch ($action) {
                case 'add':
                    // ✅ BẮT ĐẦU: KIỂM TRA ĐĂNG NHẬP
                    // =============================================
                    if (!isset($_SESSION['user_id'])) {
                        $response['success'] = false;
                        $response['message'] = 'Bạn phải đăng nhập để thêm vào giỏ hàng.';
                        $response['action_required'] = 'login'; // Gửi tín hiệu cho JS
                        break; // Dừng ngay lập tức
                    }
                    // =============================================
                    // ✅ KẾT THÚC: KIỂM TRA ĐĂNG NHẬP                    
                    if ($quantity <= 0) $quantity = 1;
                    
                    if (isset($_SESSION['cart'][$product_id])) {
                        // Nếu đã có, cộng dồn số lượng
                        $_SESSION['cart'][$product_id] += $quantity;
                    } else {
                        // Nếu chưa có, thêm mới
                        $_SESSION['cart'][$product_id] = $quantity;
                    }
                    $response['success'] = true;
                    $response['message'] = 'Đã thêm vào giỏ hàng!';
                    break;

                case 'update':
                    if ($quantity > 0) {
                        $_SESSION['cart'][$product_id] = $quantity;
                    } else {
                        unset($_SESSION['cart'][$product_id]); // Xóa nếu số lượng là 0
                    }
                    $response['success'] = true;
                    $response['message'] = 'Cập nhật giỏ hàng thành công!';
                    break;

                case 'remove':
                    if (isset($_SESSION['cart'][$product_id])) {
                        unset($_SESSION['cart'][$product_id]);
                        $response['success'] = true;
                        $response['message'] = 'Đã xóa sản phẩm khỏi giỏ!';
                    }
                    break;
                
                default:
                    $response['message'] = 'Hành động không hợp lệ';
            }
        } else {
            $response['message'] = 'Sản phẩm không tồn tại';
        }
    } else {
         $response['message'] = 'ID sản phẩm không hợp lệ';
    }

} catch (PDOException $e) {
    $response['message'] = 'Lỗi CSDL: ' . $e->getMessage();
}

// Cập nhật lại tổng số (loại) sản phẩm trong giỏ
$response['cartCount'] = count($_SESSION['cart']);

// ✅ SỬA LỖI QUAN TRỌNG: Buộc PHP lưu session NGAY LẬP TỨC
session_write_close();

// Trả về kết quả dạng JSON
header('Content-Type: application/json; charset=utf-8');
echo json_encode($response);
?>