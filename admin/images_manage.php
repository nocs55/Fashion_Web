 <?php
require_once('partials/header.php');


$conn = new mysqli(HOST, USERNAME, PASSWORD, DATABASE);

// Tải ảnh lên
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $category_id = intval($_POST["category"]);
    $subcategory_id = intval($_POST["subcategory"]);
    
    // Lấy tên danh mục từ bảng `category`
    $queryCat = "SELECT category_name FROM category WHERE id=?";
    $stmtCat = $conn->prepare($queryCat);
    $stmtCat->bind_param("i", $category_id);
    $stmtCat->execute();
    $category = $stmtCat->get_result()->fetch_assoc()["category_name"];

    $querySub = "SELECT category_name FROM category WHERE id=?";
    $stmtSub = $conn->prepare($querySub);
    $stmtSub->bind_param("i", $subcategory_id);
    $stmtSub->execute();
    $subcategory = $stmtSub->get_result()->fetch_assoc()["category_name"];

    // Tạo đường dẫn thư mục
    $target_dir = "img/$category/$subcategory/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true); // Tạo thư mục nếu chưa có
    }

    $image_name = basename($_FILES["image"]["name"]);
    $target_file = $target_dir . $image_name;

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        // Lưu đường dẫn ảnh vào bảng `gallery`
        $query = "INSERT INTO gallery (category, subcategory, image_path) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sss", $category, $subcategory, $target_file);
        $stmt->execute();

        $_SESSION['message'] = "<p class='success-message'>Ảnh đã được tải lên thành công!</p>";
    } else {
        $_SESSION['message'] = "<p class='error-message'>Lỗi khi tải ảnh lên!</p>";
    }

    header("Location: images_manage.php"); 
    exit();
}



// Danh sách ảnh
$limit = 10; 
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

$query = "SELECT g.id, g.image, p.product_name 
          FROM gallery g 
          JOIN product p ON g.product_id = p.id
          LIMIT $limit OFFSET $offset";
$result = $conn->query($query);

$queryTotal = "SELECT COUNT(*) AS total FROM gallery";
$totalResult = $conn->query($queryTotal);
$totalRow = $totalResult->fetch_assoc();
$total_pages = ceil($totalRow['total'] / $limit);
?>


        
       <main>
            <?php require_once('partials/sidebar.php'); ?>

            <!-- admin-content -->
            <div class="admin-content">
                <div class="images">
                    <h2><i class="fa-solid fa-angles-right"></i> Quản lý ảnh sản phẩm</h2>
    
                    <!-- Tải lên ảnh sản phẩm -->
                    <div class="common-box image-edit">
                        <h3>➕ Thêm ảnh </h3>
                        <form action="" method="post" enctype="multipart/form-data">
                            <label for="product_id">Chọn sản phẩm:</label>
                            <select name="product_id" id="product_id">
                                <?php
                                $productQuery = "SELECT id, product_name FROM product";
                                $products = $conn->query($productQuery);
                                while ($product = $products->fetch_assoc()):
                                ?>
                                    <option value="<?= $product['id'] ?>"><?= htmlspecialchars($product['product_name']) ?></option>
                                <?php endwhile; ?>
                            </select>
    
                            <label for="category">Chọn danh mục:</label>
                            <select id="category" name="category">
                                <option value="">---</option>
                                    <?php
                                        $query = "SELECT id, category_name FROM category WHERE parent_id IS NULL";
                                        $categories = $conn->query($query);
                                        while ($category = $categories->fetch_assoc()):
                                    ?>
                                        <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['category_name']) ?></option>
                                    <?php endwhile; ?>
                            </select>
    
                            <label for="subcategory">Chọn danh mục con:</label>
                            <select id="subcategory" name="subcategory">
                                <option value="">---</option>
                            </select>
    
                            <label for="image">Chọn ảnh:</label>
                            <input type="file" name="image" id="image" required>
    
                            <button class="btn btn-up-img" type="submit">Tải lên</button>
                            
                        </form>
                        
                        <script>
                        document.getElementById("category").addEventListener("change", function() {
                            var categoryId = this.value;
                            fetch("images_get_subcategories.php?category_id=" + categoryId)
                            .then(response => response.json())
                            .then(data => {
                                var subcategoryDropdown = document.getElementById("subcategory");
                                subcategoryDropdown.innerHTML = '<option value="">Chọn danh mục con</option>';
                                data.forEach(sub => {
                                    subcategoryDropdown.innerHTML += `<option value="${sub.id}">${sub.name}</option>`;
                                });
                            })
                            .catch(error => console.error("Lỗi:", error));
                        });
                        </script>
    
                        <?php
                            if (isset($_SESSION['message'])) {
                                echo $_SESSION['message'];
                                unset($_SESSION['message']); 
                            }
                        ?>
    
                    </div>
    
                    <!-- Danh sách ảnh -->
                    <div class="common-box image-lists">
                        <h3>Danh sách ảnh sản phẩm</h3>
                        <table>
                            <tr>
                                <th>STT</th>
                                <th>ID</th>
                                <th>Sản phẩm</th>
                                <th>Hình ảnh</th>
                                <th>Tùy chỉnh</th>
                            </tr>
                            <?php
                                $stt= $offset +1; 
                                while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= $stt++ ?></td>
                                <td><?= $row['id'] ?></td>
                                <td><?= htmlspecialchars($row['product_name']) ?></td>
                                <td><img src="<?= htmlspecialchars($row['image']) ?>" alt="Ảnh sản phẩm"></td>
                                <td>
                                    <a href="images_edit.php?id=<?= $row['id'] ?>">Sửa</a> |
                                    <a href="?id=<?= $row['id'] ?>" onclick="return confirm('Bạn chắc chắn muốn xóa ảnh này?')">Xóa</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </table>
                        
                    </div>
                    
    
                    <!-- Phân trang  -->
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




