 <?php
require_once('partials/header.php');

require_once ('../db/dbhelper.php');
require_once ('../db/utility.php');

$id = getGet('id');
$query = "SELECT * FROM category WHERE id = $id";
$result = executeSingleResult($query);

?>

        
        <main>
          <?php require_once('partials/sidebar.php'); ?>
          
          <!-- admin-content -->
          <div class="admin-content">
            <div class="categories">
              <h2><i class="fa-solid fa-angles-right"></i> Quản lý danh mục sản phẩm</h2>
  
              <!-- Sửa danh mục -->
                <div class="common-box category-edit">
                  <h3>Sửa danh mục sản phẩm</h3>
                  <?php 
                    if (!$result) {
                      echo "<p class='error-message'>Lỗi: Không tìm thấy danh mục!</p>";
                    } 
                    else { ?>
                      <label for="category_name">Tên danh mục:</label>
                      <form action="" method="POST">
                        <input type="hidden" name="id" value="<?= $result['id'] ?>">
                        <input type="text" name="category_name" value="<?= $result['category_name'] ?>" required>
                        <label for="parent_id">Chọn danh mục cha:</label>
                        <select name="parent_id">
                          <option value="NULL">Danh mục cha</option>
                            <?php
                              $queryParent = "SELECT id, category_name FROM category WHERE parent_id IS NULL";
                              $parentCategories = executeResult($queryParent);
                                  
                              foreach ($parentCategories as $parent) {
                                  $selected = ($result['parent_id'] == $parent['id']) ? "selected" : "";
                                  echo "<option value='{$parent['id']}' $selected>{$parent['category_name']}</option>";
                              }
                                  // Nếu danh mục đang chỉnh sửa có ($result['parent_id']) khớp với id của danh mục cha, 
                                  // thì đánh dấu selected.
                                  // Tạo <option> cho từng danh mục cha với value là id.
                                  // Nếu danh mục cha được chọn, nó sẽ có thuộc tính selected để hiển thị mặc định.
  
                              ?>
                        </select>
  
                        <button type="submit" class="btn update-btn">Cập nhật</button>
                      </form>  
  
                      <a class="btn-back" href="categories_manage.php">⬅️ Quay lại danh sách</a>
  
                    <?php } ?>
                </div>
            </div>
          </div>
        </main>
 <?php
 require_once('partials/footer.php');
?>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST)) {
    $id = getPost('id');
    $category_name = getPost('category_name');
    $parent_id = ($_POST['parent_id'] === "NULL") ? "NULL" : $_POST['parent_id'];
    
      if (!empty($category_name) && isset($parent_id)) {

      $query = "UPDATE category SET category_name='$category_name', parent_id=$parent_id WHERE id=$id";

      execute($query);
      
      $_SESSION['success_message'] = "Danh mục '$category_name' đã được cập nhật thành công!";
      header("Location:categories_manage.php"); 
      exit();
      }
    }
}
?>


