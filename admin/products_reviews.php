<?php
require_once('partials/header.php');

$conn = new mysqli(HOST, USERNAME, PASSWORD, DATABASE);

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$success_msg = '';
$error_msg = '';

// Lấy product_id từ URL
$product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;

if ($product_id <= 0) {
    $error_msg = "❌ ID sản phẩm không hợp lệ!";
}

// Lấy thông tin sản phẩm
$product_info = null;
if ($product_id > 0) {
    $product_result = $conn->query("SELECT product_name FROM product WHERE id = $product_id");
    if ($product_result && $product_result->num_rows > 0) {
        $product_info = $product_result->fetch_assoc();
    } else {
        $error_msg = "❌ Không tìm thấy sản phẩm!";
    }
}

// Xóa đánh giá
if (isset($_GET['delete_review'])) {
    $review_id = intval($_GET['delete_review']);
    $stmt = $conn->prepare("DELETE FROM reviews WHERE id = ?");
    $stmt->bind_param("i", $review_id);
    if ($stmt->execute()) {
        $success_msg = "🗑️ Đã xóa đánh giá thành công!";
    } else {
        $error_msg = "❌ Lỗi khi xóa đánh giá!";
    }
}

// PHÂN TRANG
$limit = 10;
$page = isset($_GET['page']) && $_GET['page'] > 0 ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Đếm tổng số đánh giá
$total_result = $conn->query("SELECT COUNT(*) AS total FROM reviews WHERE product_id = $product_id");
$total_row = $total_result->fetch_assoc();
$total_reviews = $total_row['total'];
$total_pages = ceil($total_reviews / $limit);

// Lấy danh sách đánh giá theo trang
$reviews_result = $conn->query("
    SELECT r.id, r.rating, r.comment, r.created_at, u.username
    FROM reviews r 
    LEFT JOIN user u ON r.user_id = u.id
    WHERE r.product_id = $product_id
    ORDER BY r.created_at DESC
    LIMIT $limit OFFSET $offset
");

?>

<main>
    
    <?php require_once('partials/sidebar.php'); ?>

    <!-- admin-content -->
    <div class="admin-content">

        <div class="products">
            <h2><i class="fa-solid fa-angles-right"></i> Đánh giá sản phẩm</h2>

            <?php if ($success_msg): ?>
                <div class="msg success"><?= htmlspecialchars($success_msg) ?></div>
            <?php endif; ?>

            <?php if ($error_msg): ?>
                <div class="msg error"><?= htmlspecialchars($error_msg) ?></div>
            <?php endif; ?>

            <?php if ($product_info): ?>
                <div class="common-box">
                    <h3>📦 Sản phẩm: <?= htmlspecialchars($product_info['product_name']) ?> (ID: <?= $product_id ?>)</h3>
                    
                    <?php if ($total_reviews > 0): ?>
                        <p><strong>📊 Tổng số đánh giá: <?= $total_reviews ?></strong></p>
                        
                        <!-- Thống kê đánh giá -->
                        <?php
                        $stats_result = $conn->query("
                            SELECT 
                                AVG(rating) as avg_rating,
                                COUNT(CASE WHEN rating = 5 THEN 1 END) as rating_5,
                                COUNT(CASE WHEN rating = 4 THEN 1 END) as rating_4,
                                COUNT(CASE WHEN rating = 3 THEN 1 END) as rating_3,
                                COUNT(CASE WHEN rating = 2 THEN 1 END) as rating_2,
                                COUNT(CASE WHEN rating = 1 THEN 1 END) as rating_1
                            FROM reviews WHERE product_id = $product_id
                        ");
                        $stats = $stats_result->fetch_assoc();
                        ?>
                        
                        <div class="rating-stats" style="margin: 15px 0; padding: 15px; background: #f9f9f9; border-radius: 6px;">
                            <p><strong>⭐ Điểm trung bình: <?= number_format($stats['avg_rating'], 1) ?>/5</strong></p>
                            <div style="font-size: 14px;">
                                <span>5⭐: <?= $stats['rating_5'] ?> | </span>
                                <span>4⭐: <?= $stats['rating_4'] ?> | </span>
                                <span>3⭐: <?= $stats['rating_3'] ?> | </span>
                                <span>2⭐: <?= $stats['rating_2'] ?> | </span>
                                <span>1⭐: <?= $stats['rating_1'] ?></span>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Danh sách đánh giá -->
                <div class="common-box">
                    <h3>💬 Danh sách đánh giá</h3>
                    
                    <?php if ($reviews_result && $reviews_result->num_rows > 0): ?>
                        <table>
                            <tr>
                                <th>STT</th>
                                <th>Người dùng</th>
                                <th>Đánh giá</th>
                                <th>Bình luận</th>
                                <th>Thời gian</th>
                                <th>Tùy chỉnh</th>
                            </tr>
                            <?php 
                            $start_stt = ($page - 1) * $limit + 1;
                            $stt = $start_stt;
                            while ($review = $reviews_result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $stt++ ?></td>
                                    <td>👤 <?= htmlspecialchars($review['username'] ?? 'Ẩn danh') ?></td>
                                    <td>
                                        <?= str_repeat("⭐", (int)$review['rating']) ?> 
                                        <br><small>(<?= $review['rating'] ?>/5)</small>
                                    </td>
                                    <td style="max-width: 300px;">
                                        <?= nl2br(htmlspecialchars($review['comment'])) ?>
                                    </td>
                                    <td><?= date('d/m/Y H:i', strtotime($review['created_at'])) ?></td>
                                    <td>
                                        <a href="?product_id=<?= $product_id ?>&delete_review=<?= $review['id'] ?>&page=<?= $page ?>" 
                                           onclick="return confirm('Bạn chắc chắn muốn xóa đánh giá này?')" 
                                           style="color: red;">Xóa</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </table>

                        <!-- Phân trang -->
                        <?php if ($total_pages > 1): ?>
                            <div class="pagination" style="text-align:center; margin-top: 20px;">
                                <?php if ($page > 1): ?>
                                    <a href="?product_id=<?= $product_id ?>&page=<?= $page - 1 ?>" style="margin-right: 10px;">⬅️ Trước</a>
                                <?php endif; ?>

                                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                    <a href="?product_id=<?= $product_id ?>&page=<?= $i ?>" 
                                       style="margin: 0 5px; <?= $i == $page ? 'font-weight:bold;text-decoration:underline;' : '' ?>">
                                        <?= $i ?>
                                    </a>
                                <?php endfor; ?>

                                <?php if ($page < $total_pages): ?>
                                    <a href="?product_id=<?= $product_id ?>&page=<?= $page + 1 ?>" style="margin-left: 10px;">Sau ➡️</a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                    <?php else: ?>
                        <p style="text-align: center; padding: 30px;">⚠️ Chưa có đánh giá nào cho sản phẩm này.</p>
                    <?php endif; ?>
                </div>

                <!-- Nút quay lại -->
                <div style="text-align: center; margin-top: 20px;">
                    <a href="products_manage.php" class="btn" style="display: inline-block; padding: 10px 20px; background: #007cba; color: white; text-decoration: none; border-radius: 4px;">
                        🔙 Quay lại quản lý sản phẩm
                    </a>
                </div>

            <?php endif; ?>

        </div>
    </div>
</main>

<?php
require_once('partials/footer.php');
?>