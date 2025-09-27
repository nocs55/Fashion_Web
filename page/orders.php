<?php
include 'partials/header.php';



$conn = new mysqli(HOST, USERNAME, PASSWORD, DATABASE);
if ($conn->connect_error) {
    die("Kết nối thất bại: {$conn->connect_error}");
}

$user_id = $_SESSION['user_id'];

// Xử lý các action
if (isset($_POST['action']) && isset($_POST['order_id'])) {
    $order_id = (int)$_POST['order_id'];
    $action = $_POST['action'];
    
    switch ($action) {
        case 'cancel':
            // Hủy đơn hàng
            $update_sql = "UPDATE orders SET status = 'cancelled' WHERE id = ? AND user_id = ? AND status = 'pending'";
            $stmt = $conn->prepare($update_sql);
            $stmt->bind_param("ii", $order_id, $user_id);
            if ($stmt->execute() && $stmt->affected_rows > 0) {
                echo "<script>alert('Hủy đơn hàng thành công!'); window.location.reload();</script>";
                exit();
            } else {
                echo "<script>alert('Không thể hủy đơn hàng!');</script>";
                 exit();
            }
            break;
            
        case 'confirm_received':
            // Xác nhận đã nhận hàng
            $update_sql = "UPDATE orders SET status = 'delivered' WHERE id = ? AND user_id = ? AND status = 'shipping'";
            $stmt = $conn->prepare($update_sql);
            $stmt->bind_param("ii", $order_id, $user_id);
            if ($stmt->execute() && $stmt->affected_rows > 0) {
                echo "<script>alert('Xác nhận nhận hàng thành công!'); window.location.reload();</script>";
                exit();
            } else {
                echo "<script>alert('Không thể xác nhận nhận hàng!');</script>";
                exit();
            }
            break;
    }
}

// Xử lý đánh giá sản phẩm
if (isset($_POST['review_action']) && isset($_POST['product_id']) && isset($_POST['rating']) && isset($_POST['comment'])) {
    $product_id = (int)$_POST['product_id'];
    $rating = (int)$_POST['rating'];
    $comment = trim($_POST['comment']);
    
    // Kiểm tra xem đã đánh giá chưa
    $check_sql = "SELECT id FROM reviews WHERE user_id = ? AND product_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ii", $user_id, $product_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows == 0) {
        // Thêm đánh giá mới
        $insert_sql = "INSERT INTO reviews (user_id, product_id, rating, comment, created_at) VALUES (?, ?, ?, ?, NOW())";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("iiis", $user_id, $product_id, $rating, $comment);
        if ($insert_stmt->execute()) {
            echo "<script>alert('Đánh giá thành công!'); window.location.reload();</script>";
            exit();
        } else {
            echo "<script>alert('Lỗi khi đánh giá!');</script>";
            exit();
        }
    } else {
        echo "<script>alert('Bạn đã đánh giá sản phẩm này rồi!');</script>";
        exit();
    }
}

// Lấy danh sách đơn hàng
$sql = "SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Lỗi prepare statement: " . $conn->error);
}
$stmt->bind_param("i", $user_id);
$stmt->execute();
$orders_result = $stmt->get_result();

$orders = [];
while ($order = $orders_result->fetch_assoc()) {
    // Lấy chi tiết sản phẩm cho từng đơn hàng
    $sql_details = "SELECT od.*, p.product_name, 
            (SELECT image FROM gallery g WHERE g.product_id = p.id ORDER BY id ASC LIMIT 1) AS image 
                    FROM order_details od 
                    LEFT JOIN product p ON od.product_id = p.id 
                    WHERE od.order_id = ?";
    $stmt_details = $conn->prepare($sql_details);
    if ($stmt_details) {
        $stmt_details->bind_param("i", $order['id']);
        $stmt_details->execute();
        $products_result = $stmt_details->get_result();
        
        $products = [];
        while ($product = $products_result->fetch_assoc()) {
            $products[] = [
                'name' => $product['product_name'],
                'image' => $product['image'],
                'quantity' => $product['quantity'],
                'price' => $product['price']
            ];
        }
        $stmt_details->close();
    }
    
    $orders[] = [
        'id' => $order['id'],
        'name' => $order['name'],
        'phone_number' => $order['phone_number'],
        'address' => $order['address'],
        'payment_method' => $order['payment_method'],
        'note' => $order['note'],
        'total_money' => $order['total_money'],
        'status' => $order['status'],
        'order_date' => $order['order_date'],
        'payment_status' => $order['payment_status'],
        'products' => $products ?? []
    ];
}


?>

<style>
.orders-container {
    max-width: 1200px;
    margin: 20px auto;
    padding: 0 15px;
}

.orders-tabs {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    margin-bottom: 24px;
    overflow: hidden;
}

.tabs-header {
    display: flex;
    border-bottom: 1px solid #f1f3f4;
    padding: 0;
    background: #fafbfc;
}

.tab {
    padding: 16px 24px;
    cursor: pointer;
    border-bottom: 3px solid transparent;
    color: #5f6368;
    font-weight: 500;
    font-size: 14px;
    transition: all 0.3s ease;
    position: relative;
    background: transparent;
}

.tab:hover {
    background: rgba(238, 77, 45, 0.05);
    color: #ee4d2d;
}

.tab.active {
    color: #ee4d2d;
    border-bottom-color: #ee4d2d;
    background: white;
    font-weight: 600;
}

.tab.active::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 2px;
    background: linear-gradient(90deg, #ee4d2d, #ff6b35);
}

.order-item {
    background: white;
    border-radius: 16px;
    box-shadow: 0 2px 16px rgba(0,0,0,0.06);
    margin-bottom: 20px;
    overflow: hidden;
    border: 1px solid #f1f3f4;
    transition: all 0.3s ease;
}

.order-item:hover {
    box-shadow: 0 8px 32px rgba(0,0,0,0.12);
    transform: translateY(-2px);
}

.order-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 24px;
    border-bottom: 1px solid #f1f3f4;
    background: linear-gradient(135deg, #fafbfc 0%, #f8f9fa 100%);
}

.order-header strong {
    font-size: 16px;
    font-weight: 600;
    color: #202124;
}

.order-status {
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.status-pending { 
    background: linear-gradient(135deg, #fff3cd, #ffeaa7); 
    color: #856404; 
    border: 1px solid #fdd835;
}

.status-approved { 
    background: linear-gradient(135deg, #e3f2fd, #cce7ff); 
    color: #0c5460; 
    border: 1px solid #bee5eb;
}

.status-shipping { 
    background: linear-gradient(135deg, #fff3e0, #ffe0b3); 
    color: #856404; 
    border: 1px solid #ffeaa7;
}

.status-delivered { 
    background: linear-gradient(135deg, #e8f5e8, #d4edda); 
    color: #155724; 
    border: 1px solid #c3e6cb;
}

.status-cancelled { 
    background: linear-gradient(135deg, #ffebee, #ffcdd2); 
    color: #721c24; 
    border: 1px solid #f5c6cb;
}

.order-content {
    padding: 24px;
}

.product-list {
    margin-bottom: 20px;
}

.product-item {
    display: flex;
    align-items: center;
    padding: 16px 20px;
    border-bottom: 1px solid #f8f9fa;
    transition: all 0.2s ease;
}

.product-item:hover {
    background: #fafbfc;
    border-radius: 8px;
    padding-left: 12px;
    padding-right: 12px;
}

.product-item:last-child {
    border-bottom: none;
}

.order-content .product-image {
    width: 70px;
    height: 70px;
    object-fit: cover;
    border-radius: 12px;
    margin-right: 16px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    border: 2px solid #f8f9fa;
}

.product-info {
    flex: 1;
}

.product-name {
    font-size: 15px;
    color: #202124;
    margin: 0 0 6px 0;
    line-height: 1.4;
    font-weight: 500;
}

.product-quantity {
    font-size: 13px;
    color: #5f6368;
    background: #f8f9fa;
    padding: 2px 8px;
    border-radius: 12px;
    display: inline-block;
}

.product-price {
    font-size: 16px;
    color: #ee4d2d;
    text-align: right;
}

.order-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding: 16px;
    background: #f8f9fa;
    border-radius: 12px;
}

.order-details h4 {
    margin: 0 0 6px 0;
    font-size: 16px;
    color: #202124;
    font-weight: 600;
}

.order-date {
    color: #5f6368;
    font-size: 14px;
    font-weight: 500;
}

.order-total {
    text-align: right;
}

.total-amount {
    font-size: 1.8rem;
    font-weight: 600;
    color: #ee4d2d;
    text-shadow: 0 1px 2px rgba(238, 77, 45, 0.1);
}

.order-actions {
    display: flex;
    gap: 12px;
    justify-content: flex-end;
    padding-top: 20px;
    border-top: 1px solid #f1f3f4;
}

.btn {
    padding: 10px 20px;
    border: 1px solid #ddd;
    border-radius: 8px;
    text-decoration: none;
    color: #333;
    font-size: 14px;
    transition: all 0.3s ease;
    cursor: pointer;
    background: white;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 16px rgba(0,0,0,0.1);
}

.btn-primary {
    background: linear-gradient(135deg, #ee4d2d, #ff6b35);
    color: white;
    border-color: #ee4d2d;
    font-weight: 600;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #d63384, #ee4d2d);
    box-shadow: 0 6px 20px rgba(238, 77, 45, 0.3);
}

.btn-success {
    background: linear-gradient(135deg, #28a745, #20c997);
    color: white;
    border-color: #28a745;
    font-weight: 600;
}

.btn-success:hover {
    background: linear-gradient(135deg, #1e7e34, #28a745);
    box-shadow: 0 6px 20px rgba(40, 167, 69, 0.3);
}

.empty-orders {
    text-align: center;
    padding: 80px 20px;
    background: linear-gradient(135deg, white, #fafbfc);
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
}

.empty-orders h3 {
    color: #202124;
    font-size: 24px;
    margin-bottom: 12px;
    font-weight: 600;
}

.empty-orders p {
    color: #5f6368;
    font-size: 16px;
    margin-bottom: 24px;
}

.payment-info {
    background: linear-gradient(135deg, #fff8e1, #fff3c4);
    padding: 20px;
    border-radius: 12px;
    border-left: 4px solid #ffc107;
    margin-top: 16px;
    font-size: 14px;
    box-shadow: 0 2px 8px rgba(255, 193, 7, 0.1);
}

.payment-info strong {
    color: #f57f17;
    font-weight: 600;
}

/* Responsive Design */
@media (max-width: 768px) {
    .orders-container {
        padding: 0 10px;
        margin: 10px auto;
    }
    
    .tabs-header {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    
    .tab {
        white-space: nowrap;
        padding: 12px 16px;
        font-size: 13px;
    }
    
    .order-header {
        padding: 16px;
        flex-direction: column;
        gap: 8px;
        align-items: flex-start;
    }
    
    .order-content {
        padding: 16px;
    }
    
    .product-item {
        padding: 12px 0;
    }
    
    .order-content .product-image {
        width: 60px;
        height: 60px;
        margin-right: 12px;
    }
    
    .product-name {
        font-size: 14px;
    }
    
    .product-price {
        font-size: 14px;
    }
    
    .order-info {
        flex-direction: column;
        gap: 12px;
        text-align: center;
    }
    
    .order-actions {
        flex-direction: column;
        gap: 8px;
    }
    
    .btn {
        text-align: center;
        padding: 12px;
    }
}

/* Animation cho loading */
@keyframes shimmer {
    0% { background-position: -468px 0; }
    100% { background-position: 468px 0; }
}

.loading-shimmer {
    animation: shimmer 1.5s ease-in-out infinite;
    background: linear-gradient(to right, #f6f7f8 8%, #edeef1 18%, #f6f7f8 33%);
    background-size: 800px 104px;
}
</style>

<main class="main">
    <div class="orders-container">
        <div class="orders-tabs">
            <div class="tabs-header">
                <div class="tab active" data-status="all">Tất cả</div>
                <div class="tab" data-status="pending">Chờ xử lý</div>
                <div class="tab" data-status="approved">Đã duyệt</div>
                <div class="tab" data-status="shipping">Đang giao</div>
                <div class="tab" data-status="delivered">Hoàn thành</div>
                <div class="tab" data-status="cancelled">Đã hủy</div>
            </div>
        </div>

        <?php if (count($orders) > 0): ?>
            <?php foreach ($orders as $order): ?>
                <div class="order-item" data-status="<?= $order['status'] ?>">
                    <div class="order-header">
                        <div>
                            <strong>Đơn hàng #<?= $order['id'] ?></strong>
                        </div>
                        <div class="order-status status-<?= $order['status'] ?>">
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
                    
                    <div class="order-content">
                        <!-- Danh sách sản phẩm -->
                        <div class="product-list">
                            <?php foreach ($order['products'] as $product): ?>
                                <div class="product-item">
                                    <img src="<?= $product['image'] ?? 'images/no-image.jpg' ?>" 
                                         alt="<?= htmlspecialchars($product['name']) ?>" 
                                         class="product-image">
                                    <div class="product-info">
                                        <h5 class="product-name"><?= htmlspecialchars($product['name']) ?></h5>
                                        <div class="product-quantity">x<?= $product['quantity'] ?></div>
                                    </div>
                                    <div class="product-price">
                                        <?= number_format($product['price'], 0, ',', '.') ?>đ
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="order-info">
                            <div class="order-details">
                                <div class="order-date"><?= date('d/m/Y H:i', strtotime($order['order_date'])) ?></div>
                            </div>
                            <div class="order-total">
                                <div class="total-amount"><?= number_format($order['total_money'], 0, ',', '.') ?>đ</div>
                            </div>
                        </div>

                        <?php if ($order['status'] == 'pending' && $order['payment_method'] == 'bank_transfer' && $order['payment_status'] == 'pending'): ?>
                            <div class="payment-info">
                                <strong>Thông tin chuyển khoản:</strong><br>
                                STK: 9859099377 - NGUYEN THI THU THUY - Vietcombank<br>
                                Nội dung: DH<?= $order['id'] ?> - <?= $order['name'] ?>
                            </div>
                        <?php endif; ?>

                        <div class="order-actions">
                            <?php 
                                // Lấy sản phẩm đầu tiên trong đơn hàng để làm link mua lại
                                $first_product_id = '';
                                if (!empty($order['products'])) {
                                    // Lấy product_id từ database
                                    $first_product_sql = "SELECT product_id FROM order_details WHERE order_id = ? LIMIT 1";
                                    $first_product_stmt = $conn->prepare($first_product_sql);
                                    $first_product_stmt->bind_param("i", $order['id']);
                                    $first_product_stmt->execute();
                                    $first_product_result = $first_product_stmt->get_result();
                                    if ($first_product_result->num_rows > 0) {
                                        $first_product_id = $first_product_result->fetch_assoc()['product_id'];
                                    }
                                }
                             ?>

   
                            <?php if ($order['status'] == 'delivered'): ?>
                                <a href="product-detail.php?id=<?= $first_product_id ?>" class="btn">Mua lại</a>
                                <button onclick="openReviewModal(<?= $first_product_id ?>)" class="btn">Đánh giá</button>
                            <?php elseif ($order['status'] == 'pending'): ?>
                                <button onclick="cancelOrder(<?= $order['id'] ?>)" class="btn">Hủy đơn hàng</button>
                            <?php elseif ($order['status'] == 'shipping'): ?>
                                <button onclick="confirmReceived(<?= $order['id'] ?>)" class="btn btn-success">Xác nhận đã nhận</button>
                            <?php elseif ($order['status'] == 'approved'): ?>
                                <span class="btn" style="background: #f8f9fa; color: #6c757d; cursor: not-allowed;">Đang chuẩn bị hàng</span>
                            <?php endif; ?>
                            <a href="orders_detail.php?id=<?= $order['id'] ?>" class="btn btn-primary">Xem chi tiết</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty-orders">
                <h3>Chưa có đơn hàng</h3>
                <p>Hãy bắt đầu mua sắm ngay!</p>
                <a href="./" class="btn btn-primary">Mua sắm ngay</a>
            </div>
        <?php endif; ?>
    </div>
</main>

<!-- Form ẩn để xử lý các action -->
<form id="orderActionForm" method="POST" style="display: none;">
    <input type="hidden" name="action" id="actionType">
    <input type="hidden" name="order_id" id="orderId">
</form>

<!-- Modal đánh giá sản phẩm -->
<div id="reviewModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000;">
    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 20px; border-radius: 10px; width: 90%; max-width: 500px;">
        <h3>Đánh giá sản phẩm</h3>
        <form method="POST">
            <input type="hidden" name="review_action" value="1">
            <input type="hidden" name="product_id" id="reviewProductId">
            
            <div style="margin: 15px 0;">
                <label>Đánh giá:</label><br>
                <div class="rating-stars">
                    <?php for($i = 1; $i <= 5; $i++): ?>
                        <span class="star" data-rating="<?= $i ?>" style="font-size: 24px; color: #ddd; cursor: pointer;">★</span>
                    <?php endfor; ?>
                </div>
                <input type="hidden" name="rating" id="selectedRating" value="5">
            </div>
            
            <div style="margin: 15px 0;">
                <label>Nhận xét:</label><br>
                <textarea name="comment" rows="4" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 5px;" placeholder="Chia sẻ trải nghiệm của bạn..."></textarea>
            </div>
            
            <div style="text-align: right; margin-top: 15px;">
                <button type="button" onclick="closeReviewModal()" style="margin-right: 10px; padding: 8px 16px; border: 1px solid #ddd; background: white; border-radius: 5px;">Hủy</button>
                <button type="submit" style="padding: 8px 16px; background: #ee4d2d; color: white; border: none; border-radius: 5px;">Gửi đánh giá</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.tab');
    const orderItems = document.querySelectorAll('.order-item');

    // Tạo thông báo không có đơn hàng
    const emptyMessage = document.createElement('div');
    emptyMessage.className = 'empty-orders';
    emptyMessage.innerHTML = `
        <h3>Không có đơn hàng</h3>
        <p>Chưa có đơn hàng nào trong trạng thái này</p>
    `;
    emptyMessage.style.display = 'none';
    document.querySelector('.orders-container').appendChild(emptyMessage);

    function checkEmptyOrders(statusFilter) {
        let hasVisibleOrders = false;
        orderItems.forEach(item => {
            if (statusFilter === 'all') {
                item.style.display = 'block';
                hasVisibleOrders = true;
            } else {
                const orderStatus = item.dataset.status;
                if (orderStatus === statusFilter) {
                    item.style.display = 'block';
                    hasVisibleOrders = true;
                } else {
                    item.style.display = 'none';
                }
            }
        });

        // Hiển thị thông báo rỗng nếu không có đơn hàng
        if (!hasVisibleOrders && statusFilter !== 'all') {
            emptyMessage.style.display = 'block';
        } else {
            emptyMessage.style.display = 'none';
        }
    }

    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            // Remove active class from all tabs
            tabs.forEach(t => t.classList.remove('active'));
            // Add active class to clicked tab
            this.classList.add('active');

            const statusFilter = this.dataset.status;
            checkEmptyOrders(statusFilter);
        });
    });
});

// Xử lý hủy đơn hàng
function cancelOrder(orderId) {
    if (confirm('Bạn có chắc muốn hủy đơn hàng này?')) {
        document.getElementById('actionType').value = 'cancel';
        document.getElementById('orderId').value = orderId;
        document.getElementById('orderActionForm').submit();
    }
}

// Xử lý xác nhận đã nhận hàng
function confirmReceived(orderId) {
    if (confirm('Bạn có chắc đã nhận được hàng?')) {
        document.getElementById('actionType').value = 'confirm_received';
        document.getElementById('orderId').value = orderId;
        document.getElementById('orderActionForm').submit();
    }
}

// Xử lý đánh giá
function openReviewModal(productId) {
    document.getElementById('reviewProductId').value = productId;
    document.getElementById('reviewModal').style.display = 'block';
}

function closeReviewModal() {
    document.getElementById('reviewModal').style.display = 'none';
}

// Xử lý chọn sao đánh giá
document.addEventListener('DOMContentLoaded', function() {
    const stars = document.querySelectorAll('.star');
    stars.forEach(star => {
        star.addEventListener('click', function() {
            const rating = this.dataset.rating;
            document.getElementById('selectedRating').value = rating;
            
            // Cập nhật màu sao
            stars.forEach((s, index) => {
                if (index < rating) {
                    s.style.color = '#ffc107';
                } else {
                    s.style.color = '#ddd';
                }
            });
        });
    });
});
</script>

<?php include 'partials/footer.php'; ?>