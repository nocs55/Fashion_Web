<?php
require_once('partials/header.php');

require_once ('../db/dbhelper.php');
require_once ('../db/utility.php');

$conn = new mysqli(HOST, USERNAME, PASSWORD, DATABASE);

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy danh sách banner
$sql = "SELECT * FROM banner";
$result = executeResult($sql);


// Xóa banner
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM banner WHERE id = $id");
    header("Location: index.php");
}

// Cập nhật banner
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $image_link = $_POST['image_link'];
    $link = $_POST['link'];
    $sort_order = $_POST['sort_order'];

    $conn->query("UPDATE banner SET name='$name', image_link='$image_link', link='$link', sort_order='$sort_order' WHERE id=$id");
    header("Location: index.php"); // Trả về trang chính, reset form
}

// Thêm banner
if (isset($_POST['add'])) {
    $name = $_POST['name'];
    $image_link = $_POST['image_link'];
    $link = $_POST['link'];
    $sort_order = $_POST['sort_order'];

    $conn->query("INSERT INTO banner (name, image_link, link, sort_order) VALUES ('$name', '$image_link', '$link', '$sort_order')");
    header("Location: index.php");
}

// Kiểm tra nếu có yêu cầu sửa banner
$editBanner = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $editBanner = $conn->query("SELECT * FROM banner WHERE id = $id")->fetch_assoc();
}
?>

        <main>
            <?php require_once('partials/sidebar.php'); ?>

            <!-- admin-content -->
            <div class="admin-content">
                <div class="banner">
                    <h2><i class="fa-solid fa-angles-right"></i> Quản lý banner</h2>
                    <!-- Thêm/sửa banner -->
                    <div class="common-box banner-add">                       
                       
                        <form action="" method="">
                            <input type="hidden" name="id" value="<?= $editBanner['id'] ?? '' ?>">

                            <label for="name">Tên Banner:</label>
                            <input type="text" id="name" name="name" placeholder="Nhập tên banner" value="<?= $editBanner['name'] ?? '' ?>" required>

                            <label for="image_link">Đường dẫn hình ảnh:</label>
                            <input type="text" id="image_link" name="image_link" placeholder="Nhập link ảnh" value="<?= $editBanner['image_link'] ?? '' ?>" required>

                            <!-- <label for="link">Liên kết:</label>
                            <input type="text" id="link" name="link" placeholder="Nhập liên kết" value="<?= $editBanner['link'] ?? '' ?>"> -->

                            <label for="sort_order">Thứ tự hiển thị:</label>
                            <input type="number" id="sort_order" name="sort_order" placeholder="Nhập thứ tự" value="<?= $editBanner['sort_order'] ?? '' ?>">

                            <button class="btn" type="submit" name="<?= $editBanner ? 'update' : 'add' ?>">
                                <?= $editBanner ? "💾 Cập Nhật Banner" : "➕ Thêm Banner" ?>
                            </button>
                        </form>

                    </div>
    
                    <!-- Danh sách banner -->
                     <div class="common-box banner-lists">
                        <h3>Danh sách các banner</h3>
    
                        <table>
                            <tr>    
                                <th>STT</th>
                                <th>ID</th>
                                <th>Tên</th>
                                <th>Hình ảnh</th>
                                <th>Thứ tự hiển thị</th>
                                <th>Tùy chỉnh</th>
                            </tr>

                            <?php 
                                $stt = 1;
                                foreach ($result as $banner):
                            ?>

                            <tr>
                                <td><?php echo $stt++ ?></td> 
                                <td><?= $banner['id']; ?></td>
                                <td><?= $banner['name']; ?></td>
                                <td><?= $banner['image_link']; ?></td>
                                <td><?= $banner['sort_order']; ?></td>
                                <td>
                                    <a href="?edit=<?= $banner['id']; ?>">📝 Sửa</a> |  
                                    <a href="?delete=<?= $banner['id']; ?>" onclick="return confirm('Bạn có chắc muốn xóa?')">🗑️ Xóa</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </table>
                           
                    </div>
    
                    <script>
                        CKEDITOR.replace('content');
                    </script>
    
                </div>
            </div>
        </main>
 <?php
 require_once('partials/footer.php');
?>
