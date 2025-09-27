<?php
include 'partials/header.php';


$conn = new mysqli(HOST, USERNAME, PASSWORD, DATABASE);
if ($conn->connect_error) {
    die("Kết nối thất bại: {$conn->connect_error}");
}

$user_id = $_SESSION['user_id'];
$order_id = $_GET['id'] ?? 0;

// Lấy thông tin đơn hàng
$sql = "SELECT * FROM orders WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$order_result = $stmt->get_result();

if ($order_result->num_rows == 0) {
    $error_message = "Không tìm thấy đơn hàng hoặc bạn không có quyền truy cập.";
    $show_error = true;
} else {
    $show_error = false;
}

$order = $order_result->fetch_assoc();

// Lấy chi tiết sản phẩm
$products = [];
if (!$show_error) {
    $sql_details = "SELECT od.*, p.product_name, 
            (SELECT image FROM gallery g WHERE g.product_id = p.id ORDER BY id ASC LIMIT 1) AS image 
            FROM order_details od 
            LEFT JOIN product p ON od.product_id = p.id 
            WHERE od.order_id = ?";
    $stmt_details = $conn->prepare($sql_details);
    $stmt_details->bind_param("i", $order_id);
    $stmt_details->execute();
    $products_result = $stmt_details->get_result();

    while ($product = $products_result->fetch_assoc()) {
        $products[] = $product;
    }
}

$conn->close();
?>

<style>
        .error-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 40px;
            text-align: center;
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }

        .error-icon {
            font-size: 64px;
            color: #ee4d2d;
            margin-bottom: 20px;
        }

        .error-title {
            font-size: 24px;
            color: #202124;
            margin-bottom: 12px;
            font-weight: 600;
        }

        .error-message {
            font-size: 16px;
            color: #5f6368;
            margin-bottom: 24px;
            line-height: 1.5;
        }

        .detail-container {
            max-width: 900px;
            margin: 20px auto;
            padding: 0 15px;
        }

        .order-detail-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            overflow: hidden;
            margin-bottom: 20px;
        }

        .detail-header {
            background: linear-gradient(135deg, #ee4d2d, #ff6b35);
            color: white;
            padding: 24px;
            text-align: center;
        }

        .detail-header h2 {
            margin: 0 0 8px 0;
            font-size: 24px;
            font-weight: 600;
        }

        .order-status-large {
            display: inline-block;
            padding: 8px 20px;
            background: rgba(255,255,255,0.2);
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .detail-section {
            padding: 24px;
            border-bottom: 1px solid #f1f3f4;
        }

        .detail-section:last-child {
            border-bottom: none;
        }

        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: #202124;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 16px;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #f8f9fa;
        }

        .info-label {
            color: #5f6368;
            font-weight: 500;
        }

        .info-value {
            color: #202124;
            font-weight: 600;
            text-align: right;
        }

        .product-detail-item {
            display: flex;
            align-items: center;
            padding: 16px 0;
            border-bottom: 1px solid #f8f9fa;
        }

        .product-detail-item:last-child {
            border-bottom: none;
        }

        .product-image-detail {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 12px;
            margin-right: 16px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .product-detail-info {
            flex: 1;
        }

        .product-name-detail {
            font-size: 16px;
            color: #202124;
            margin: 0 0 8px 0;
            font-weight: 500;
        }

        .product-meta {
            display: flex;
            gap: 16px;
            color: #5f6368;
            font-size: 14px;
        }

        .product-price-detail {
            font-size: 18px;
            color: #ee4d2d;
            font-weight: 600;
        }

        .total-section {
            background: #f8f9fa;
            padding: 20px 24px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .total-final {
            font-size: 20px;
            font-weight: 700;
            color: #ee4d2d;
            border-top: 2px solid #ddd;
            padding-top: 12px;
            margin-top: 12px;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: #6c757d;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }

        .back-btn:hover {
            background: #5a6268;
            transform: translateY(-1px);
        }

        @media (max-width: 768px) {
            .detail-container {
                padding: 0 10px;
            }
            
            .detail-section {
                padding: 16px;
            }
            
            .info-grid {
                grid-template-columns: 1fr;
            }
            
            .product-detail-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }
            
            .product-image-detail {
                width: 100%;
                height: 200px;
                margin-right: 0;
            }
        }
</style>

<main class="main">
    <div class="detail-container">
        <?php if ($show_error): ?>
            <div class="error-container">
                <div class="error-icon">⚠️</div>
                <h2 class="error-title">Không thể truy cập đơn hàng</h2>
                <p class="error-message"><?= $error_message ?></p>
                <a href="orders.php" class="back-btn">← Quay lại danh sách đơn hàng</a>
            </div>
        <?php else: ?>
        <a href="orders.php" class="back-btn">
            ← Quay lại danh sách đơn hàng
        </a>

        <?php endif; ?>

        <div class="order-detail-card">
            <div class="detail-header">
                <h2>Đơn hàng #<?= $order['id'] ?></h2>
                <div class="order-status-large">
                    <?php 
                    switch($order['status']) {
                        case 'pending': echo 'Chờ xử lý'; break;
                        case 'approved': echo 'Đã duyệt'; break;
                        case 'shipping': echo 'Đang giao'; break;
                        case 'delivered': echo 'Hoàn thành'; break;
                        case 'cancelled': echo 'Đã hủy'; break;
                        default: echo ucfirst($order['status']); break;
                    }
                    ?>
                </div>
            </div>

            <!-- Thông tin đơn hàng -->
            <div class="detail-section">
                <h3 class="section-title">📋 Thông tin đơn hàng</h3>
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">Ngày đặt:</span>
                        <span class="info-value"><?= date('d/m/Y H:i', strtotime($order['order_date'])) ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Phương thức thanh toán:</span>
                        <span class="info-value">
                            <?= $order['payment_method'] == 'cod' ? 'Thanh toán khi nhận hàng' : 'Chuyển khoản ngân hàng' ?>
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Trạng thái thanh toán:</span>
                        <span class="info-value">
                            <?= $order['payment_status'] == 'paid' ? 'Đã thanh toán' : 'Chưa thanh toán' ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Thông tin giao hàng -->
            <div class="detail-section">
                <h3 class="section-title">🚚 Thông tin giao hàng</h3>
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">Người nhận:</span>
                        <span class="info-value"><?= htmlspecialchars($order['name']) ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Số điện thoại:</span>
                        <span class="info-value"><?= htmlspecialchars($order['phone_number']) ?></span>
                    </div>
                    <div class="info-item" style="grid-column: 1 / -1;">
                        <span class="info-label">Địa chỉ:</span>
                        <span class="info-value"><?= htmlspecialchars($order['address']) ?></span>
                    </div>
                    <?php if ($order['note']): ?>
                    <div class="info-item" style="grid-column: 1 / -1;">
                        <span class="info-label">Ghi chú:</span>
                        <span class="info-value"><?= htmlspecialchars($order['note']) ?></span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Danh sách sản phẩm -->
            <div class="detail-section">
                <h3 class="section-title">📦 Danh sách sản phẩm</h3>
                <?php foreach ($products as $product): ?>
                <div class="product-detail-item">
                    <img src="<?= $product['image'] ?? 'images/no-image.jpg' ?>" 
                         alt="<?= htmlspecialchars($product['product_name']) ?>" 
                         class="product-image-detail">
                    <div class="product-detail-info">
                        <h4 class="product-name-detail"><?= htmlspecialchars($product['product_name']) ?></h4>
                        <div class="product-meta">
                            <span>Số lượng: <?= $product['quantity'] ?></span>
                            <span>Đơn giá: <?= number_format($product['price'], 0, ',', '.') ?>đ</span>
                        </div>
                    </div>
                    <div class="product-price-detail">
                        <?= number_format($product['price'] * $product['quantity'], 0, ',', '.') ?>đ
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Tổng tiền -->
            <div class="total-section">
                <div class="total-row total-final">
                    <span>Tổng cộng:</span>
                    <span><?= number_format($order['total_money'], 0, ',', '.') ?>đ</span>
                </div>
            </div>

            <?php if ($order['status'] == 'pending' && $order['payment_method'] == 'bank_transfer' && $order['payment_status'] == 'pending'): ?>
            <div class="detail-section" style="background: #fff8e1;">
                <h3 class="section-title">💳 Thông tin chuyển khoản</h3>
                <p><strong>Số tài khoản:</strong> 9859099377</p>
                <p><strong>Chủ tài khoản:</strong> NGUYEN THI THU THUY</p>
                <p><strong>Ngân hàng:</strong> Vietcombank</p>
                <p><strong>Nội dung:</strong> DH<?= $order['id'] ?> - <?= $order['name'] ?></p>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php include 'partials/footer.php'; ?>