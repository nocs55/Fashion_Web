<?php
require_once('partials/header.php');

require_once ('../db/dbhelper.php');
require_once ('../db/utility.php');

$id = getGet('id');
$query = "SELECT * FROM menu WHERE id = $id";
$menu = executeSingleResult($query);

?>


        <main>
            <?php require_once('partials/sidebar.php'); ?>

            <!-- admin-content -->
            <div class="admin-content">
                <div class="menu">
                    <h2><i class="fa-solid fa-angles-right"></i> Quản lý menu</h2>
                    <!-- Sửa -->
                    <div class="common-box menu-add">
                        <h3>Sửa menu</h3>
                            


                        <form action="" method="POST">
                            <input type="hidden" name="id" value="<?= $menu['id']; ?>">
                            
                            <label for="name">Tên Menu:</label>
                            <input type="text" id="name" name="name" value="<?= $menu['name']; ?>" required>

                            <label for="link">Đường Dẫn Menu:</label>
                            <input type="text" id="link" name="link" value="<?= $menu['link']; ?>" required>

                            <label for="sort_order">Thứ Tự Hiển Thị:</label>
                            <input type="number" id="sort_order" name="sort_order" value="<?= $menu['sort_order']; ?>" required>

                            <button class="btn" type="submit">💾 Cập Nhật</button>
                        </form>

                     
                      <a class="btn-back" href="menu_manage.php">⬅️ Quay lại danh sách</a>

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
    $menu_name = getPost('name');
    $link = getPost('link');
    $sort = getPost('sort_order');
    
    if (!empty($menu_name) && !empty($link) && !empty($sort)) {

        $query = "UPDATE menu SET name='$menu_name', link = '$link' , sort_order =$sort WHERE id=$id";

        execute($query);
        
        $_SESSION['success_message'] = "Mục '$menu_name' đã được cập nhật thành công!";
        header("Location:menu_manage.php"); 
        exit();
      }
    }
}

?>

