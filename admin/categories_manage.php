<?php
require_once('partials/header.php');

require_once ('../db/dbhelper.php');
require_once ('../db/utility.php');

// Thêm danh mục 
$category_name = $parent_id = '';

if (!empty($_POST)) {
	$category_name = getPOST('category_name');
	$parent_id  = getPOST('parent_id')?:NULL;

	if ($category_name != '' && $parent_id != '') {
	
		$sql = "INSERT INTO category (category_name, parent_id) values ('$category_name', $parent_id)";
		execute($sql);

        $_SESSION['success_message'] = "Danh mục '$category_name' đã được thêm thành công!";
        

	}
}

// Xóa danh mục
if (isset($_GET['delete'])) {
$id = getGet('delete');
$query = "DELETE FROM category WHERE id = $id";
execute($query);
$_SESSION['success_message'] = "Danh mục đã được xóa thành công!";
header("Location:categories_manage.php"); 

}

// PHÂN TRANG
$limit = 10;
$page = isset($_GET['page']) && $_GET['page'] > 0 ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

$total_result = executeSingleResult("SELECT COUNT(*) AS total FROM category");
$total_category = $total_result['total'];
$total_pages = ceil($total_category / $limit);


$query = "SELECT c1.id, c1.category_name, COALESCE(c2.category_name, 'Không có') AS parent_name 
          FROM category AS c1 
          LEFT JOIN category AS c2 ON c1.parent_id = c2.id
          LIMIT $limit OFFSET $offset";

$result = executeResult($query);

?>

        <main>
            <?php require_once('partials/sidebar.php'); ?>

            <!-- admin-content -->
            <div class="admin-content">
                <div class="categories">
                    <h2><i class="fa-solid fa-angles-right"></i> Quản lý danh mục sản phẩm</h2>
                    <!-- Thêm danh mục -->
                    <div class="common-box category-add">
                        <h3>Thêm danh mục sản phẩm</h3>
                            <form action="" method="POST">
                                <label for="category_name">Tên danh mục:</label>
                                <input name="category_name" type="text" id="category_name" placeholder="Nhập tên danh mục" required/>
                                <label for="parent_id">Chọn danh mục cha:</label>
                                <select name="parent_id" id="parent_id">
                                <!-- Nếu chọn danh mục cha thì giá trị của parent_id khi gửi form lên sẽ là NULL -->
                                    <option value="NULL">Danh mục cha</option> 
                                    <?php
                                        $query = "SELECT id, category_name FROM category WHERE parent_id IS NULL";
                                        $parent_category = executeResult($query);
                                        foreach ($parent_category as $row) {
                                            echo "<option value='{$row['id']}'> {$row['category_name']}</option>";
                                        }
                                    ?>
                                </select>
                                <button type="submit" class="btn submit-btn">Thêm</button>
                            </form>
    
                            <?php
                                if (isset($_SESSION['success_message'])) {
                                    echo "<p class='success-message'>{$_SESSION['success_message']}</p>";
                                    unset($_SESSION['success_message']); // Xóa thông báo sau khi hiển thị
                                }
                            ?>
                    </div>
    
                    <!-- Danh sách danh mục -->
                     <div class="common-box category-lists">
                        <h3>Danh sách danh mục sản phẩm</h3>
    
                        <table>
                            <tr>
                                <th>STT</th>
                                <th>Id</th>
                                <th>Tên danh mục</th>
                                <th>Danh mục cha</th>
                                <th>Tùy chỉnh</th>
                            </tr>
                            <?php
                            $stt = 1;
                            foreach ($result as $row):
                            ?>
                        
                            <tr>
                                <td><?php echo $stt++ ?></td> 
                                <td><?= $row['id'] ?></td>
                                <td><?= $row['category_name'] ?></td>
                                <td><?= $row['parent_name']  ?></td>
                                <td>
                                    <a href="categories_edit.php?id=<?= $row['id'] ?>">Sửa</a> | 
                                    <a href="?delete=<?= $row['id'] ?>" 
                                    onclick="return confirm('Bạn có chắc muốn xóa danh mục này?')">Xóa</a>
                                </td>
                            </tr>
                                <?php endforeach; ?>
    
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
