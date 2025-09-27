 <?php
require_once('partials/header.php');;

$conn = new mysqli(HOST, USERNAME, PASSWORD, DATABASE);
// Lấy ảnh từ gallery
$id = intval($_GET['id']);
$query = "SELECT image FROM gallery WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$image = $result->fetch_assoc();

?>

        
        <main>
            <?php require_once('partials/sidebar.php'); ?>

            <!-- admin-content -->
            <div class="admin-content">
                <div class="images">
                    <h2><i class="fa-solid fa-angles-right"></i> Quản lý ảnh sản phẩm</h2>
    
                    <!-- Cập nhật ảnh sản phẩm -->
                    <div class="common-box image-edit">
                        <h3>✏️ Cập nhật ảnh sản phẩm</h3>
                        <form action="images_update.php" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="id" value="<?= $id ?>">
                            
                            <label>Ảnh hiện tại:</label>
                            <img src="<?= htmlspecialchars($image['image']) ?>" alt="Ảnh sản phẩm">
    
                            <label>Chọn ảnh mới:</label>
                            <input type="file" name="image" required>
    
                            <button class="btn" type="submit">Cập nhật ảnh</button>
                        </form>
                            
    
    
                            <a class="btn-back" href="images_manage.php">⬅️ Quay lại danh sách</a>
                    </div>
                    
    
                </div>
            </div>
        </main>
 <?php
 require_once('partials/footer.php');
?>>





