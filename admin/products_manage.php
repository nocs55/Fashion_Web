 <?php
require_once('partials/header.php');

$conn = new mysqli(HOST, USERNAME, PASSWORD, DATABASE);

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$success_msg = '';
$error_msg = '';

// Hiển thị thông báo nếu đã xóa
if (isset($_GET['deleted']) && $_GET['deleted'] == '1') {
    $success_msg = "🗑️ Đã xóa sản phẩm thành công!";
}

// Xóa cứng sản phẩm
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM product WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        header("Location: " . $_SERVER['PHP_SELF'] . "?deleted=1");
        exit;
    } else {
        $error_msg = "❌ Lỗi khi xóa sản phẩm!";
    }
}

// Thêm sản phẩm
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $id = intval($_POST['product_id']);
    $name = $_POST['product_name'];
    $price = intval($_POST['price']);
    $stock = intval($_POST['stock_quantity']);
    $category = intval($_POST['category_id']);
    $discount = intval($_POST['discount']);
    $description = $_POST['description'];

    // Kiểm tra trùng ID
    $check = $conn->query("SELECT id FROM product WHERE id = $id");
    if ($check->num_rows > 0) {
        $error_msg = "❌ ID $id đã tồn tại.";
    } else {
        $stmt = $conn->prepare("INSERT INTO product (id, product_name, price, stock_quantity, category_id, discount, description) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isiiiis", $id, $name, $price, $stock, $category, $discount, $description);
        if ($stmt->execute()) {
            $success_msg = "✅ Đã thêm sản phẩm!";
        } else {
            $error_msg = "❌ Lỗi khi thêm sản phẩm!";
        }
    }
}

// PHÂN TRANG
$limit = 10;
$page = isset($_GET['page']) && $_GET['page'] > 0 ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

$total_result = $conn->query("SELECT COUNT(*) AS total FROM product");
$total_row = $total_result->fetch_assoc();
$total_products = $total_row['total'];
$total_pages = ceil($total_products / $limit);

// Lấy danh sách sản phẩm theo trang
$result = $conn->query("
    SELECT p.id, p.product_name, c.category_name, p.price, p.stock_quantity, p.discount, p.description
    FROM product p 
    LEFT JOIN category c ON p.category_id = c.id
    LIMIT $limit OFFSET $offset
");

?>
        
        <main>
            
            <?php require_once('partials/sidebar.php'); ?>

            <!-- admin-content -->
            <div class="admin-content">

                <div class="products">
                    <h2><i class="fa-solid fa-angles-right"></i> Quản lý sản phẩm</h2>
    
                    <?php if ($success_msg): ?>
                        <div class="msg success"><?= htmlspecialchars($success_msg) ?></div>
                    <?php endif; ?>
    
                    <?php if ($error_msg): ?>
                        <div class="msg error"><?= htmlspecialchars($error_msg) ?></div>
                    <?php endif; ?>
    
                    <!-- Thêm sản phẩm -->
                    <div class="common-box product-add">
                        <h3>➕ Thêm sản phẩm mới</h3>
                        <form method="POST">
                            <label>Mã sản phẩm:</label>
                            <input type="number" name="product_id">
                            
                            <label>Tên sản phẩm:</label>
                            <input type="text" name="product_name" required>
                            
                            <label>Giá:</label>
                            <input type="number" name="price" required>
                            
                            <label>Số lượng tồn kho:</label>
                            <input type="number" name="stock_quantity" required>
                            
                            <label>Danh mục:</label>
                            <select name="category_id" required>
                                <option value="">-- Chọn danh mục --</option>
                                <?php
                                $cats = $conn->query("SELECT id, category_name FROM category");
                                while ($cat = $cats->fetch_assoc()) {
                                    echo "<option value='{$cat['id']}'>" . htmlspecialchars($cat['category_name']) . "</option>";
                                }
                                ?>
                            </select>
                            
                            <label>Giảm giá (%):</label>
                            <input type="number" name="discount" min="0" max="100" value="0" required>
                            
                            <label>Mô tả:</label>
                            <textarea name="description" id="description" rows="3"></textarea>
                            <script>
                               
                            //     CKEDITOR.replace( 'description', {
                            //         filebrowserBrowseUrl: 'ckfinder/ckfinder.html',
                            //         filebrowserUploadUrl: 'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files'
                            //     } );
                            // </script>
                            
                            
                            <button class="btn" type="submit" name="add_product">Thêm sản phẩm</button>
                        </form>
    
    
                    </div>
                    
                    <!-- Danh sách sản phẩm -->
                    <div class="common-box product-lists">
                        <h3>Danh sách sản phẩm</h3>
                        <table>
                    <tr>
                        <th>STT</th>
                        <th>ID</th>
                        <th>Tên sản phẩm</th>
                        <th>Danh mục</th>
                        <th>Giá</th>
                        <th>Tồn kho</th>
                        <th>Giảm giá (%)</th>
                        <th>Mô tả</th>
                        <th>Đánh giá</th> <!-- ✅ Cột mới -->
                        <th>Tùy chỉnh</th>
                    </tr>
                    <?php 
                    $page = isset($_GET['page']) ? intval($_GET['page']) : 1; 
                                $limit = 10; 
                                $start_stt = ($page - 1) * $limit + 1; // Tính STT bắt đầu
                                $stt = $start_stt;
                    $stt = $start_stt; 
                    while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $stt++ ?></td>
                            <td><?= $row['id'] ?></td>
                            <td><?= htmlspecialchars($row['product_name']) ?></td>
                            <td><?= htmlspecialchars($row['category_name']) ?></td>
                            <td><?= number_format($row['price']) ?> VND</td>
                            <td><?= $row['stock_quantity'] ?></td>
                            <td><?= $row['discount'] ?>%</td>
                            <td><?= nl2br(htmlspecialchars($row['description'])) ?></td>
                            <td><a href="products_reviews.php?product_id=<?= $row['id'] ?>" target="_blank">Xem</a></td> <!-- ✅ Link xem -->
                            <td>
                                <a href="products_edit.php?id=<?= $row['id'] ?>">Sửa</a> |
                                <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Bạn chắc chắn muốn xóa?')">Xóa</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </table>

                    </div>
    
                    <!-- Phân trang -->
                    <div class="pagination" style="text-align:center; margin-top: 20px;">
                        <?php if ($page > 1): ?>
                            <a href="?page=<?= $page - 1 ?>" style="margin-right: 10px;">⬅️ Trước</a>
                        <?php endif; ?>
    
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <a href="?page=<?= $i ?>" style="margin: 0 5px; <?= $i == $page ? 'font-weight:bold;text-decoration:underline;' : '' ?>">
                                <?= $i ?>
                            </a>
                        <?php endfor; ?>
    
                        <?php if ($page < $total_pages): ?>
                            <a href="?page=<?= $page + 1 ?>" style="margin-left: 10px;">Sau ➡️</a>
                        <?php endif; ?>
                    </div>
    
                </div>
            </div>
        </main>
<?php
 require_once('partials/footer.php');
?>





