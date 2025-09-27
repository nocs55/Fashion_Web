 <?php
require_once('partials/header.php');

$conn = new mysqli(HOST, USERNAME, PASSWORD, DATABASE);

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$success_msg = '';
$error_msg = '';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Xử lý cập nhật
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_product'])) {
    $name = $_POST['product_name'];
    $price = intval($_POST['price']);
    $stock = intval($_POST['stock_quantity']);
    $category = intval($_POST['category_id']);
    $discount = intval($_POST['discount']);
    $description = $_POST['description'];

    $stmt = $conn->prepare("UPDATE product SET product_name=?, price=?, stock_quantity=?, category_id=?, discount=?, description=?, updated_at=NOW() WHERE id=?");
    $stmt->bind_param("siiiisi", $name, $price, $stock, $category, $discount, $description, $id);

    if ($stmt->execute()) {
        $success_msg = "✅ Cập nhật sản phẩm thành công!";
    } else {
        $error_msg = "❌ Lỗi khi cập nhật sản phẩm!";
    }
}

// Lấy thông tin sản phẩm để hiển thị
$product = $conn->query("SELECT * FROM product WHERE id = $id")->fetch_assoc();
$categories = $conn->query("SELECT id, category_name FROM category");

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
    
                    <!-- Cập nhật sản phẩm -->
                    <div class="common-box product-edit">
                        <h3>✏️ Cập nhật sản phẩm</h3>
                        <?php if ($product): ?>
                            <form method="POST" >
                                <fieldset>
                                    <legend>Sửa thông tin sản phẩm #<?= $product['id'] ?></legend>
    
                                    <label for="product_name">Tên sản phẩm:</label>
                                    <input type="text" name="product_name" id="product_name" value="<?= htmlspecialchars($product['product_name']) ?>" required>
    
                                    <label for="price">Giá:</label>
                                    <input type="number" name="price" id="price" value="<?= $product['price'] ?>" required>
    
                                    <label for="stock_quantity">Số lượng tồn kho:</label>
                                    <input type="number" name="stock_quantity" id="stock_quantity" value="<?= $product['stock_quantity'] ?>" required>
    
                                    <label for="category_id">Danh mục:</label>
                                    <select name="category_id" id="category_id" required>
                                        <option value="">-- Chọn danh mục --</option>
                                        <?php while ($cat = $categories->fetch_assoc()): ?>
                                            <option value="<?= $cat['id'] ?>" <?= ($cat['id'] == $product['category_id']) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($cat['category_name']) ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
    
                                    <label for="discount">Giảm giá (%):</label>
                                    <input type="number" name="discount" id="discount" value="<?= $product['discount'] ?>" min="0" max="100">
    
                                    <label for="description">Mô tả:</label>
                                    <textarea name="description" id="description" rows="4"><?= htmlspecialchars($product['description']) ?></textarea>
                                    <!-- <script>
                                        CKEDITOR.replace('description');
                                    </script> -->
    
                                    <button class="btn btn-edit" type="submit" name="update_product">💾 Lưu thay đổi</button>
                                </fieldset>
                            </form>
    
                            <a class="btn-back" href="products_manage.php">⬅️ Quay lại danh sách</a>
                          
    
                        <?php else: ?>
                            <div class="msg error">Không tìm thấy sản phẩm với ID này!</div>
                        <?php endif; ?>
                        
    
    
                    </div>
                    
    
                </div>
            </div>
        </main>
<?php
 require_once('partials/footer.php');
?>




