<?php
require_once('partials/header.php');

require_once ('../db/dbhelper.php');
require_once ('../db/utility.php');

$id = getGet('id');
$query = "SELECT * FROM footer WHERE id = $id";
$footer = executeSingleResult($query);

?>


        <main>
            <?php require_once('partials/sidebar.php'); ?>

            <!-- admin-content -->
            <div class="admin-content">
                <div class="footer-manager">
                    <h2><i class="fa-solid fa-angles-right"></i> Quản lý footer</h2>
                    <!-- Sửa -->
                    <div class="common-box footer-add">
                        <h3>Sửa footer</h3>
                            


                        <form action="" method="POST">
                            <input type="hidden" name="id" value="<?= $footer['id']; ?>">
                            
                            <label for="name">Tên footer:</label>
                            <input type="text" id="section" name="section" value="<?= $footer['section']; ?>" required>

                            <label for="link">Nội dung:</label>
                            <input type="text" id="content" name="content" value="<?= $footer['content']; ?>" required>

                            <label for="sort_order">Thứ Tự Hiển Thị:</label>
                            <input type="number" id="sort_order" name="sort_order" value="<?= $footer['sort_order']; ?>" required>

                            <button class="btn" type="submit">💾 Cập Nhật</button>
                        </form>

                     
                      <a class="btn-back" href="footer_manage.php">⬅️ Quay lại danh sách</a>

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
    $section = getPost('section');
    $content = getPost('content');
    $sort = getPost('sort_order');
    
    if (!empty($section) && !empty($content) && !empty($sort)) {

        $query = "UPDATE footer SET section='$section', content = '$content' , sort_order =$sort WHERE id=$id";

        execute($query);
        
        $_SESSION['success_message'] = "Footer đã được cập nhật thành công!";
        header("Location:footer_manage.php"); 
        exit();
      }
    }
}

?>

