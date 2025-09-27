<?php
require_once('partials/header.php');

require_once ('../db/dbhelper.php');
require_once ('../db/utility.php');

$conn = new mysqli(HOST, USERNAME, PASSWORD, DATABASE);

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy danh sách bài viết
$sql = "SELECT * FROM news";
$result = executeResult($sql);

// Xóa bài viết
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM news WHERE id = $id");
    header("Location: news.php");
}

// Cập nhật bài viết
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $image = $_POST['image'];
    $content = $_POST['content'];

    $conn->query("UPDATE news SET title='$title', image='$image', content='$content' WHERE id=$id");
    header("Location: news.php"); // Reset form sau cập nhật
}

// Thêm bài viết mới
if (isset($_POST['add'])) {
    $title = $_POST['title'];
    $image = $_POST['image'];
    $content = $_POST['content'];

    $conn->query("INSERT INTO news (title, image, content) VALUES ('$title', '$image', '$content')");
    header("Location: news.php");
}

// Kiểm tra nếu có yêu cầu sửa bài viết
$editNews = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $editNews = $conn->query("SELECT * FROM news WHERE id = $id")->fetch_assoc();
}
?>

        <main>
            <?php require_once('partials/sidebar.php'); ?>

            <!-- admin-content -->
            <div class="admin-content">
                <div class="news">
                    <h2><i class="fa-solid fa-angles-right"></i> Quản lý tin tức</h2>
                    <!-- Thêm/sửa bài viết -->
                    <div class="common-box news-add">                       
                       
                        <form action="" method="">
                            <input type="hidden" name="id" value="<?= $editNews['id'] ?? '' ?>">

                            <label for="title">Tiêu đề:</label>
                            <input type="text" name="title" placeholder="Tiêu đề" value="<?= $editNews['title'] ?? '' ?>" required>
                            <!-- <input type="text" name="image" placeholder="Link hình ảnh" value="<?= $editNews['image'] ?? '' ?>"> -->

                            <label for="content">Nội dung:</label>
                            <textarea name="content" id="content"><?= $editNews['content'] ?? '' ?></textarea>
                            <button class="btn" type="submit" name="<?= $editNews ? 'update' : 'add' ?>">
                                <?= $editNews ? "Cập Nhật Bài Viết" : "Thêm Bài Viết" ?>
                            </button>
                        </form>

                    </div>
    
                    <!-- Danh sách bài viết -->
                     <div class="common-box news-lists">
                        <h3>Danh sách các bài viết</h3>
    
                        <table>
                            <tr>    
                                <th>STT</th>
                                <th>ID</th>
                                <th>Tiêu đề</th>
                                <!-- <th>Nội dung</th> -->
                                <th>Ngày viết</th>
                                <th>Thao tác</th>
                            </tr>

                            <?php 
                                $stt = 1;
                                foreach ($result as $news):
                            ?>

                            <tr>
                                <td><?php echo $stt++ ?></td> 
                                <td><?= $news['id']; ?></td>
                                <td><?= $news['title']; ?></td>
                                <!-- <td><?= $news['content'] ?></td> -->
                                <td><?= $news['created_at']; ?></td>
                                <td>
                                    <a href="?edit=<?= $news['id']; ?>">📝 Sửa</a> |  
                                    <a href="?delete=<?= $news['id']; ?>" onclick="return confirm('Bạn có chắc muốn xóa?')">🗑️ Xóa</a>
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
