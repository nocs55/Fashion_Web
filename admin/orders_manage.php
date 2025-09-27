<?php
require_once('partials/header.php');
require_once('../db/dbhelper.php');
require_once('../db/utility.php');

$conn = new mysqli(HOST, USERNAME, PASSWORD, DATABASE);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$message = '';
$messageType = '';

// Xử lý các hành động
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['approve_order'])) {
        $order_id = (int)$_POST['order_id'];
        
        $stmt = $conn->prepare("UPDATE orders SET status = 'approved' WHERE id = ?");
        $stmt->bind_param("i", $order_id);
        
        if ($stmt->execute()) {
            $message = "Đã duyệt đơn hàng #$order_id thành công!";
            $messageType = 'success';
        } else {
            $message = "Có lỗi xảy ra khi duyệt đơn hàng!";
            $messageType = 'error';
        }
        $stmt->close();
    }
    
    if (isset($_POST['reject_order'])) {
        $order_id = (int)$_POST['order_id'];
        
        $stmt = $conn->prepare("UPDATE orders SET status = 'cancelled' WHERE id = ?");
        $stmt->bind_param("i", $order_id);
        
        if ($stmt->execute()) {
            $message = "Đã từ chối đơn hàng #$order_id!";
            $messageType = 'warning';
        } else {
            $message = "Có lỗi xảy ra khi từ chối đơn hàng!";
            $messageType = 'error';
        }
        $stmt->close();
    }
    
    if (isset($_POST['update_status'])) {
        $order_id = (int)$_POST['order_id'];
        $new_status = $_POST['new_status'];
        
        $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $new_status, $order_id);
        
        if ($stmt->execute()) {
            $message = "Đã cập nhật trạng thái đơn hàng #$order_id thành công!";
            $messageType = 'success';
        } else {
            $message = "Có lỗi xảy ra khi cập nhật trạng thái!";
            $messageType = 'error';
        }
        $stmt->close();
    }
    
    if (isset($_POST['update_payment'])) {
        $order_id = (int)$_POST['order_id'];
        
        $stmt = $conn->prepare("UPDATE orders SET payment_status = 'paid', payment_date = NOW() WHERE id = ?");
        $stmt->bind_param("i", $order_id);
        
        if ($stmt->execute()) {
            $message = "Đã xác nhận thanh toán cho đơn hàng #$order_id!";
            $messageType = 'success';
        } else {
            $message = "Có lỗi xảy ra khi cập nhật thanh toán!";
            $messageType = 'error';
        }
        $stmt->close();
    }
}

// Lấy chi tiết đơn hàng nếu được yêu cầu
$order = null;
$orderItems = [];
if (isset($_GET['view_order'])) {
    $order_id = (int)$_GET['view_order'];
    
    $stmt = $conn->prepare("
        SELECT o.*, u.username, u.email, u.phone_number as user_phone 
        FROM orders o 
        LEFT JOIN user u ON o.user_id = u.id 
        WHERE o.id = ?
    ");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $order = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    
    if ($order) {
        $stmt = $conn->prepare("
            SELECT od.*, p.product_name, p.image,
            (SELECT image FROM gallery g WHERE g.product_id = p.id ORDER BY id ASC LIMIT 1) AS product_image
            FROM order_details od 
            JOIN product p ON od.product_id = p.id 
            WHERE od.order_id = ?
        ");
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $orderItems[] = $row;
        }
        $stmt->close();
    }
}

// Lấy danh sách đơn hàng với filter và search
$status_filter = $_GET['status'] ?? '';
$search = $_GET['search'] ?? '';
$date_from = $_GET['date_from'] ?? '';
$date_to = $_GET['date_to'] ?? '';

$limit = 15;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Build WHERE clause
$where_conditions = [];
$params = [];
$param_types = '';

if ($status_filter && $status_filter !== 'all') {
    $where_conditions[] = "o.status = ?";
    $params[] = $status_filter;
    $param_types .= 's';
}

if ($search) {
    $where_conditions[] = "(o.name LIKE ? OR u.username LIKE ? OR o.id = ?)";
    $search_param = "%$search%";
    $params[] = $search_param;
    $params[] = $search_param;
    $params[] = $search;
    $param_types .= 'sss';
}

if ($date_from) {
    $where_conditions[] = "DATE(o.order_date) >= ?";
    $params[] = $date_from;
    $param_types .= 's';
}

if ($date_to) {
    $where_conditions[] = "DATE(o.order_date) <= ?";
    $params[] = $date_to;
    $param_types .= 's';
}

$where_clause = empty($where_conditions) ? '' : 'WHERE ' . implode(' AND ', $where_conditions);

// Count total records
$count_sql = "SELECT COUNT(*) as total FROM orders o LEFT JOIN user u ON o.user_id = u.id $where_clause";
if (!empty($params)) {
    $count_stmt = $conn->prepare($count_sql);
    $count_stmt->bind_param($param_types, ...$params);
    $count_stmt->execute();
    $totalRows = $count_stmt->get_result()->fetch_assoc()['total'];
    $count_stmt->close();
} else {
    $totalRows = $conn->query($count_sql)->fetch_assoc()['total'];
}

$totalPages = ceil($totalRows / $limit);

// Get orders
$sql = "
    SELECT o.id, o.name, u.username, o.order_date, o.status, o.total_money, 
           o.payment_method, o.payment_status, o.phone_number,
           COUNT(od.id) as item_count
    FROM orders o 
    LEFT JOIN user u ON o.user_id = u.id 
    LEFT JOIN order_details od ON o.id = od.order_id
    $where_clause 
    GROUP BY o.id
    ORDER BY o.order_date DESC 
    LIMIT $limit OFFSET $offset
";

if (!empty($params)) {
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($param_types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query($sql);
}

// Get statistics
$stats_sql = "
    SELECT 
        COUNT(*) as total_orders,
        SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_orders,
        SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved_orders,
        SUM(CASE WHEN status = 'shipping' THEN 1 ELSE 0 END) as shipping_orders,
        SUM(CASE WHEN status = 'delivered' THEN 1 ELSE 0 END) as delivered_orders,
        SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled_orders,
        SUM(CASE WHEN status = 'delivered' THEN total_money ELSE 0 END) as total_revenue
    FROM orders
";
$stats = $conn->query($stats_sql)->fetch_assoc();
?>

<style>
.admin-orders .page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 2px solid #f1f3f4;
}

.admin-orders .page-title {
    font-size: 28px;
    font-weight: 700;
    color: #202124;
    display: flex;
    align-items: center;
    gap: 12px;
}

.admin-orders .page-title i {
    color: #ee4d2d;
}

/* Stats Cards */
.admin-orders .stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.admin-orders .stat-card {
    background: linear-gradient(135deg, #fff, #fafbfc);
    padding: 24px;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    border: 1px solid #f1f3f4;
    transition: all 0.3s ease;
}

.admin-orders .stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 32px rgba(0,0,0,0.12);
}

.admin-orders .stat-value {
    font-size: 32px;
    font-weight: 700;
    margin-bottom: 8px;
}

.admin-orders .stat-label {
    font-size: 14px;
    color: #5f6368;
    font-weight: 500;
}

.admin-orders .stat-card.pending .stat-value { color: #ff9800; }
.admin-orders .stat-card.approved .stat-value { color: #2196f3; }
.admin-orders .stat-card.shipping .stat-value { color: #ff5722; }
.admin-orders .stat-card.delivered .stat-value { color: #4caf50; }
.admin-orders .stat-card.cancelled .stat-value { color: #f44336; }
.admin-orders .stat-card.revenue .stat-value { color: #ee4d2d; }

/* Filters */
.admin-orders .filters-section {
    background: white;
    padding: 24px;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    margin-bottom: 24px;
}

.admin-orders .filters-form {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    align-items: end;
}

.admin-orders .form-group {
    display: flex;
    flex-direction: column;
}

.admin-orders .form-group label {
    font-weight: 600;
    color: #202124;
    margin-bottom: 8px;
    font-size: 14px;
}

.admin-orders .form-control {
    padding: 12px 16px;
    border: 2px solid #e8eaed;
    border-radius: 8px;
    font-size: 14px;
    transition: all 0.3s ease;
}

.admin-orders .form-control:focus {
    border-color: #ee4d2d;
    box-shadow: 0 0 0 3px rgba(238, 77, 45, 0.1);
    outline: none;
}

.admin-orders .btn {
    padding: 12px 24px;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
}

.admin-orders .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(0,0,0,0.2);
}

.admin-orders .btn-primary {
    background: linear-gradient(135deg, #ee4d2d, #ff6b35);
    color: white;
}

.admin-orders .btn-success {
    background: linear-gradient(135deg, #4caf50, #66bb6a);
    color: white;
}

.admin-orders .btn-warning {
    background: linear-gradient(135deg, #ff9800, #ffb74d);
    color: white;
    width: 100px;
}

.admin-orders .btn-danger {
    background: linear-gradient(135deg, #f44336, #ef5350);
    color: white;
}

.admin-orders .btn-secondary {
    background: #f8f9fa;
    color: #5f6368;
    border: 1px solid #e8eaed;
}

/* Message Alert */
.admin-orders .alert {
    padding: 16px 20px;
    border-radius: 12px;
    margin-bottom: 20px;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 12px;
}

.admin-orders .alert-success {
    background: linear-gradient(135deg, #e8f5e8, #d4edda);
    color: #155724;
    border: 1px solid #c3e6cb;
}

.admin-orders .alert-warning {
    background: linear-gradient(135deg, #fff3cd, #ffeaa7);
    color: #856404;
    border: 1px solid #fdd835;
}

.admin-orders .alert-error {
    background: linear-gradient(135deg, #ffebee, #ffcdd2);
    color: #721c24;
    border: 1px solid #f5c6cb;
}

/* Orders Table */
.admin-orders .orders-table-container {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    overflow: hidden;
}

.admin-orders .orders-table {
    width: 100%;
    border-collapse: collapse;
}

.admin-orders .orders-table th {
    background: linear-gradient(135deg, #fafbfc, #f8f9fa);
    padding: 16px;
    text-align: left;
    font-weight: 600;
    color: #202124;
    border-bottom: 2px solid #f1f3f4;
    font-size: 14px;
}

.admin-orders .orders-table td {
    padding: 16px;
    border-bottom: 1px solid #f8f9fa;
    vertical-align: middle;
}

.admin-orders .orders-table tr:hover {
    background: #fafbfc;
}

.admin-orders .status-badge {
    padding: 6px 12px;
    border-radius: 16px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.admin-orders .status-pending { background: #fff3cd; color: #856404; }
.admin-orders .status-approved { background: #cce7ff; color: #0c5460; }
.admin-orders .status-shipping { background: #ffe0b3; color: #856404; }
.admin-orders .status-delivered { background: #d4edda; color: #155724; }
.admin-orders .status-cancelled { background: #ffcdd2; color: #721c24; }

.admin-orders .payment-badge {
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
}

.admin-orders .payment-pending { background: #fff3cd; color: #856404; }
.admin-orders .payment-paid { background: #d4edda; color: #155724; }
.admin-orders .payment-failed { background: #ffcdd2; color: #721c24; }

/* Order Detail Modal */
.admin-orders .order-detail {
    background: white;
    border-radius: 16px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.15);
    margin: 24px 0;
    overflow: hidden;
}

.admin-orders .order-detail-header {
    background: linear-gradient(135deg, #ee4d2d, #ff6b35);
    color: white;
    padding: 24px;
}

.admin-orders .order-detail-content {
    padding: 24px;
}

.admin-orders .info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 24px;
    margin-bottom: 24px;
}

.admin-orders .info-card {
    background: #fafbfc;
    padding: 20px;
    border-radius: 12px;
    border: 1px solid #f1f3f4;
}

.admin-orders .info-card h4 {
    color: #ee4d2d;
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 12px;
}

.admin-orders .info-item {
    display: flex;
    justify-content: space-between;
    margin-bottom: 8px;
    font-size: 14px;
}

.admin-orders .info-item strong {
    color: #202124;
}

/* Products Table */
.admin-orders .products-table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
}

.admin-orders .products-table th {
    background: #f8f9fa;
    padding: 12px;
    text-align: left;
    font-weight: 600;
    border-bottom: 2px solid #e8eaed;
}

.admin-orders .products-table td {
    padding: 12px;
    border-bottom: 1px solid #f1f3f4;
}

.admin-orders .product-image {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 8px;
}

/* Actions */
.admin-orders .order-actions {
    display: flex;
    gap: 12px;
    padding: 20px;
    background: #fafbfc;
    border-top: 1px solid #f1f3f4;
    flex-wrap: wrap;
}

/* Pagination */
.admin-orders .pagination {
    display: flex;
    justify-content: center;
    gap: 8px;
    margin: 24px 0;
}

.admin-orders .pagination a {
    padding: 8px 12px;
    border: 1px solid #e8eaed;
    border-radius: 6px;
    text-decoration: none;
    color: #5f6368;
    transition: all 0.3s ease;
}

.admin-orders .pagination a:hover {
    background: #ee4d2d;
    color: white;
    border-color: #ee4d2d;
}

.admin-orders .pagination a.active {
    background: #ee4d2d;
    color: white;
    border-color: #ee4d2d;
}

/* Responsive */
@media (max-width: 768px) {
    .admin-orders {
        padding: 15px;
    }
    
    .admin-orders .page-header {
        flex-direction: column;
        gap: 16px;
        align-items: flex-start;
    }
    
    .admin-orders .filters-form {
        grid-template-columns: 1fr;
    }
    
    .admin-orders .orders-table-container {
        overflow-x: auto;
    }
    
    .admin-orders .orders-table {
        min-width: 800px;
    }
    
    .admin-orders .order-actions {
        flex-direction: column;
    }
    
    .admin-orders .info-grid {
        grid-template-columns: 1fr;
    }
}

.admin-orders .quick-actions {
    /* display: flex; */
    gap: 8px;
    
}

.admin-orders .quick-actions .xem{
    width: 50px;
}
.admin-orders .quick-action-btn {
    padding: 6px 12px;
    font-size: 17px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;


}

.admin-orders .back-btn {
    background: #f8f9fa;
    color: #5f6368;
    padding: 12px 24px;
    border-radius: 8px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-weight: 600;
    margin-top: 20px;
    transition: all 0.3s ease;
}

.admin-orders .back-btn:hover {
    background: #e8eaed;
    transform: translateX(-4px);
}
</style>

<main>
    <?php require_once('partials/sidebar.php'); ?>
    
    <div class="admin-content">
        <div class="admin-orders">
            <!-- Page Header -->
            <div class="page-header">
                <h1 class="page-title">
                    <i class="fas fa-shopping-cart"></i>
                    Quản lý đơn hàng
                </h1>
            </div>

            <!-- Message Alert -->
            <?php if ($message): ?>
                <div class="alert alert-<?= $messageType ?>">
                    <i class="fas fa-<?= $messageType === 'success' ? 'check-circle' : ($messageType === 'warning' ? 'exclamation-triangle' : 'times-circle') ?>"></i>
                    <?= $message ?>
                </div>
            <?php endif; ?>

            <?php if (!isset($_GET['view_order'])): ?>
                <!-- Statistics -->
                <div class="stats-grid">
                    <div class="stat-card pending">
                        <div class="stat-value"><?= number_format($stats['pending_orders']) ?></div>
                        <div class="stat-label">Chờ xử lý</div>
                    </div>
                    <div class="stat-card approved">
                        <div class="stat-value"><?= number_format($stats['approved_orders']) ?></div>
                        <div class="stat-label">Đã duyệt</div>
                    </div>
                    <div class="stat-card shipping">
                        <div class="stat-value"><?= number_format($stats['shipping_orders']) ?></div>
                        <div class="stat-label">Đang giao</div>
                    </div>
                    <div class="stat-card delivered">
                        <div class="stat-value"><?= number_format($stats['delivered_orders']) ?></div>
                        <div class="stat-label">Hoàn thành</div>
                    </div>
                    <div class="stat-card cancelled">
                        <div class="stat-value"><?= number_format($stats['cancelled_orders']) ?></div>
                        <div class="stat-label">Đã hủy</div>
                    </div>
                    <div class="stat-card revenue">
                        <div class="stat-value"><?= number_format($stats['total_revenue'] / 1000000, 1) ?>M</div>
                        <div class="stat-label">Doanh thu (VNĐ)</div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="filters-section">
                    <form method="GET" class="filters-form">
                        <div class="form-group">
                            <label>Tìm kiếm</label>
                            <input type="text" name="search" class="form-control" 
                                   placeholder="ID đơn hàng, tên khách hàng..." 
                                   value="<?= htmlspecialchars($search) ?>">
                        </div>
                        
                        <div class="form-group">
                            <label>Trạng thái</label>
                            <select name="status" class="form-control">
                                <option value="">Tất cả trạng thái</option>
                                <option value="pending" <?= $status_filter === 'pending' ? 'selected' : '' ?>>Chờ xử lý</option>
                                <option value="approved" <?= $status_filter === 'approved' ? 'selected' : '' ?>>Đã duyệt</option>
                                <option value="shipping" <?= $status_filter === 'shipping' ? 'selected' : '' ?>>Đang giao</option>
                                <option value="delivered" <?= $status_filter === 'delivered' ? 'selected' : '' ?>>Hoàn thành</option>
                                <option value="cancelled" <?= $status_filter === 'cancelled' ? 'selected' : '' ?>>Đã hủy</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>Từ ngày</label>
                            <input type="date" name="date_from" class="form-control" value="<?= $date_from ?>">
                        </div>
                        
                        <div class="form-group">
                            <label>Đến ngày</label>
                            <input type="date" name="date_to" class="form-control" value="<?= $date_to ?>">
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Orders Table -->
                <div class="orders-table-container">
                    <table class="orders-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Khách hàng</th>
                                <th>Ngày đặt</th>
                                <th>Trạng thái</th>
                                <th>Thanh toán</th>
                                <th>Tổng tiền</th>
                                <th>Sản phẩm</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><strong>#<?= $row['id'] ?></strong></td>
                                    <td>
                                        <div>
                                            <strong><?= htmlspecialchars($row['name']) ?></strong>
                                            <?php if ($row['username']): ?>
                                                <br><small>@<?= htmlspecialchars($row['username']) ?></small>
                                            <?php endif; ?>
                                            <br><small><?= htmlspecialchars($row['phone_number']) ?></small>
                                        </div>
                                    </td>
                                    <td><?= date("d/m/Y H:i", strtotime($row['order_date'])) ?></td>
                                    <td>
                                        <span class="status-badge status-<?= $row['status'] ?>">
                                            <?php
                                            $status_names = [
                                                'pending' => 'Chờ xử lý',
                                                'approved' => 'Đã duyệt', 
                                                'shipping' => 'Đang giao',
                                                'delivered' => 'Hoàn thành',
                                                'cancelled' => 'Đã hủy'
                                            ];
                                            echo $status_names[$row['status']] ?? $row['status'];
                                            ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div>
                                            <span class="payment-badge payment-<?= $row['payment_status'] ?>">
                                                <?= $row['payment_status'] === 'paid' ? 'Đã thanh toán' : 
                                                   ($row['payment_status'] === 'failed' ? 'Thất bại' : 'Chờ thanh toán') ?>
                                            </span>
                                            <br><small><?= $row['payment_method'] === 'cod' ? 'COD' : 'Chuyển khoản' ?></small>
                                        </div>
                                    </td>
                                    <td><strong><?= number_format($row['total_money'], 0, ',', '.') ?>đ</strong></td>
                                    <td><?= $row['item_count'] ?> sản phẩm</td>
                                    <td>
                                        <div class="quick-actions">
                                            <a href="?view_order=<?= $row['id'] ?>" class="btn btn-primary xem" style="padding: 6px 12px; font-size: 12px;">
                                                <p><i class="fas fa-eye"></i>Xem</p>
                                            </a>
                                            <?php if ($row['status'] === 'pending'): ?>
                                                <form method="post" style="display: inline;">
                                                    <input type="hidden" name="order_id" value="<?= $row['id'] ?>">
                                                    <button type="submit" name="approve_order" class="quick-action-btn btn-success" 
                                                            onclick="return confirm('Duyệt đơn hàng này?')">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>



                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                    <div class="pagination">
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <a href="?page=<?= $i ?>&<?= http_build_query(array_filter(['status' => $status_filter, 'search' => $search, 'date_from' => $date_from, 'date_to' => $date_to])) ?>" 
                               class="<?= $i === $page ? 'active' : '' ?>">
                                <?= $i ?>
                            </a>
                        <?php endfor; ?>
                    </div>
                <?php endif; ?>

            <?php else: ?>
                <!-- Order Detail View -->
                <?php if ($order): ?>
                    <div class="order-detail">
                        <div class="order-detail-header">
                            <h2>Chi tiết đơn hàng #<?= $order['id'] ?></h2>
                            <p>Ngày đặt: <?= date("d/m/Y H:i:s", strtotime($order['order_date'])) ?></p>
                        </div>
                        
                        <div class="order-detail-content">
                            <!-- Order Information Grid -->
                            <div class="info-grid">
                                <!-- Customer Info -->
                                <div class="info-card">
                                    <h4><i class="fas fa-user"></i> Thông tin khách hàng</h4>
                                    <div class="info-item">
                                        <span>Tên khách hàng:</span>
                                        <strong><?= htmlspecialchars($order['name']) ?></strong>
                                    </div>
                                    <?php if ($order['username']): ?>
                                        <div class="info-item">
                                            <span>Tài khoản:</span>
                                            <strong><?= htmlspecialchars($order['username']) ?></strong>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($order['email']): ?>
                                        <div class="info-item">
                                            <span>Email:</span>
                                            <strong><?= htmlspecialchars($order['email']) ?></strong>
                                        </div>
                                    <?php endif; ?>
                                    <div class="info-item">
                                        <span>Số điện thoại:</span>
                                        <strong><?= htmlspecialchars($order['phone_number']) ?></strong>
                                    </div>
                                    <?php if ($order['user_phone'] && $order['user_phone'] !== $order['phone_number']): ?>
                                        <div class="info-item">
                                            <span>SĐT tài khoản:</span>
                                            <strong><?= htmlspecialchars($order['user_phone']) ?></strong>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Delivery Info -->
                                <div class="info-card">
                                    <h4><i class="fas fa-shipping-fast"></i> Thông tin giao hàng</h4>
                                    <div class="info-item">
                                        <span>Địa chỉ:</span>
                                        <strong><?= htmlspecialchars($order['address']) ?></strong>
                                    </div>
                                    <?php if ($order['note']): ?>
                                        <div class="info-item">
                                            <span>Ghi chú:</span>
                                            <strong><?= htmlspecialchars($order['note']) ?></strong>
                                        </div>
                                    <?php endif; ?>
                                    <div class="info-item">
                                        <span>Trạng thái:</span>
                                        <span class="status-badge status-<?= $order['status'] ?>">
                                            <?php
                                            $status_names = [
                                                'pending' => 'Chờ xử lý',
                                                'approved' => 'Đã duyệt', 
                                                'shipping' => 'Đang giao',
                                                'delivered' => 'Hoàn thành',
                                                'cancelled' => 'Đã hủy'
                                            ];
                                            echo $status_names[$order['status']] ?? $order['status'];
                                            ?>
                                        </span>
                                    </div>
                                </div>

                                <!-- Payment Info -->
                                <div class="info-card">
                                    <h4><i class="fas fa-credit-card"></i> Thông tin thanh toán</h4>
                                    <div class="info-item">
                                        <span>Phương thức:</span>
                                        <strong><?= $order['payment_method'] === 'cod' ? 'Thanh toán khi nhận hàng (COD)' : 'Chuyển khoản ngân hàng' ?></strong>
                                    </div>
                                    <div class="info-item">
                                        <span>Trạng thái:</span>
                                        <span class="payment-badge payment-<?= $order['payment_status'] ?>">
                                            <?= $order['payment_status'] === 'paid' ? 'Đã thanh toán' : 
                                               ($order['payment_status'] === 'failed' ? 'Thất bại' : 'Chờ thanh toán') ?>
                                        </span>
                                    </div>
                                    <?php if ($order['payment_date']): ?>
                                        <div class="info-item">
                                            <span>Ngày thanh toán:</span>
                                            <strong><?= date("d/m/Y H:i:s", strtotime($order['payment_date'])) ?></strong>
                                        </div>
                                    <?php endif; ?>
                                    <div class="info-item">
                                        <span>Tổng tiền:</span>
                                        <strong style="color: #ee4d2d; font-size: 18px;"><?= number_format($order['total_money'], 0, ',', '.') ?>đ</strong>
                                    </div>
                                </div>
                            </div>

                            <!-- Order Items -->
                            <div class="order-items">
                                <h4><i class="fas fa-shopping-bag"></i> Sản phẩm đã đặt</h4>
                                <table class="products-table">
                                    <thead>
                                        <tr>
                                            <th>Hình ảnh</th>
                                            <th>Tên sản phẩm</th>
                                            <th>Đơn giá</th>
                                            <th>Số lượng</th>
                                            <th>Thành tiền</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $subtotal = 0; ?>
                                        <?php foreach ($orderItems as $item): ?>
                                            <tr>
                                                <td>
                                                    <?php 
                                                    $image = $item['product_image'] ?: $item['image'];
                                                    if ($image): 
                                                    ?>
                                                        <img src="../uploads/<?= htmlspecialchars($image) ?>" 
                                                             alt="<?= htmlspecialchars($item['product_name']) ?>" 
                                                             class="product-image">
                                                    <?php else: ?>
                                                        <div class="product-image" style="background: #f8f9fa; display: flex; align-items: center; justify-content: center;">
                                                            <i class="fas fa-image" style="color: #ccc;"></i>
                                                        </div>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <strong><?= htmlspecialchars($item['product_name']) ?></strong>
                                                </td>
                                                <td><?= number_format($item['price'], 0, ',', '.') ?>đ</td>
                                                <td><?= $item['quantity'] ?></td>
                                                <td><strong><?= number_format($item['total_money'], 0, ',', '.') ?>đ</strong></td>
                                            </tr>
                                            <?php $subtotal += $item['total_money']; ?>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr style="background: #f8f9fa; font-weight: bold;">
                                            <td colspan="4" style="text-align: right;">Tổng cộng:</td>
                                            <td style="color: #ee4d2d; font-size: 18px;"><?= number_format($subtotal, 0, ',', '.') ?>đ</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <!-- Order Actions -->
                            <div class="order-actions">
                                <!-- Status Update -->
                                <form method="post" style="display: inline-flex; gap: 12px; align-items: center;">
                                    <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                    <select name="new_status" class="form-control" style="width: auto;">
                                        <option value="pending" <?= $order['status'] === 'pending' ? 'selected' : '' ?>>Chờ xử lý</option>
                                        <option value="approved" <?= $order['status'] === 'approved' ? 'selected' : '' ?>>Đã duyệt</option>
                                        <option value="shipping" <?= $order['status'] === 'shipping' ? 'selected' : '' ?>>Đang giao</option>
                                        <option value="delivered" <?= $order['status'] === 'delivered' ? 'selected' : '' ?>>Hoàn thành</option>
                                        <option value="cancelled" <?= $order['status'] === 'cancelled' ? 'selected' : '' ?>>Đã hủy</option>
                                    </select>
                                    <button type="submit" name="update_status" class="btn btn-primary">
                                        <i class="fas fa-sync"></i> Cập nhật trạng thái
                                    </button>
                                </form>

                                <!-- Quick Actions -->
                                <?php if ($order['status'] === 'pending'): ?>
                                    <form method="post" style="display: inline;">
                                        <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                        <button type="submit" name="approve_order" class="btn btn-success" 
                                                onclick="return confirm('Duyệt đơn hàng này?')">
                                            <i class="fas fa-check"></i> Duyệt đơn hàng
                                        </button>
                                    </form>
                                    
                                    <form method="post" style="display: inline;">
                                        <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                        <button type="submit" name="reject_order" class="btn btn-danger" 
                                                onclick="return confirm('Từ chối đơn hàng này?')">
                                            <i class="fas fa-times"></i> Từ chối
                                        </button>
                                    </form>
                                <?php endif; ?>

                                <!-- Payment Confirmation -->
                                <?php if ($order['payment_status'] === 'pending'): ?>
                                    <form method="post" style="display: inline;">
                                        <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                        <button type="submit" name="update_payment" class="btn btn-warning" 
                                                onclick="return confirm('Xác nhận đã nhận được thanh toán?')">
                                            <i class="fas fa-money-check"></i> Xác nhận thanh toán
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <a href="orders_manage.php" class="back-btn">
                        <i class="fas fa-arrow-left"></i> Quay lại danh sách
                    </a>

                <?php else: ?>
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-triangle"></i>
                        Không tìm thấy đơn hàng này!
                    </div>
                    <a href="orders_manage.php" class="back-btn">
                        <i class="fas fa-arrow-left"></i> Quay lại danh sách
                    </a>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</main>

<script>
// Auto-hide alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-20px)';
            setTimeout(() => {
                alert.remove();
            }, 300);
        }, 5000);
    });
});

// Confirm before status changes
function confirmAction(action, orderId) {
    const messages = {
        'approve': 'Bạn có chắc chắn muốn duyệt đơn hàng #' + orderId + '?',
        'reject': 'Bạn có chắc chắn muốn từ chối đơn hàng #' + orderId + '?',
        'payment': 'Xác nhận đã nhận được thanh toán cho đơn hàng #' + orderId + '?',
        'status': 'Cập nhật trạng thái đơn hàng #' + orderId + '?'
    };
    
    return confirm(messages[action] || 'Xác nhận thực hiện hành động này?');
}

// Real-time search
let searchTimeout;
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                if (this.value.length >= 3 || this.value.length === 0) {
                    this.form.submit();
                }
            }, 500);
        });
    }
});

// Print order function
function printOrder(orderId) {
    const printWindow = window.open(`print_order.php?id=${orderId}`, '_blank');
    printWindow.onload = function() {
        printWindow.print();
    };
}

// Export orders function
function exportOrders() {
    const params = new URLSearchParams(window.location.search);
    params.set('export', '1');
    window.location.href = 'orders.php?' + params.toString();
}

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl + F để focus vào ô tìm kiếm
    if (e.ctrlKey && e.key === 'f') {
        e.preventDefault();
        const searchInput = document.querySelector('input[name="search"]');
        if (searchInput) {
            searchInput.focus();
            searchInput.select();
        }
    }
});
</script>

<?php
// Close database connection
$conn->close();
require_once('partials/footer.php');
?>