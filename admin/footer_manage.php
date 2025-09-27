<?php
require_once('partials/header.php');

require_once ('../db/dbhelper.php');
require_once ('../db/utility.php');

$conn = new mysqli(HOST, USERNAME, PASSWORD, DATABASE);

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy danh sách footer
$sql = "SELECT * FROM footer ORDER BY sort_order";
$result = executeResult($sql);

// Thêm footer
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['section'];
    $content = $_POST['content'];
    $sort_order = $_POST['sort_order'];

    $sql = "INSERT INTO footer (section, content, sort_order) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $name, $content, $sort_order);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    $_SESSION['success_message'] = "🎉 Footer đã được cập nhật thành công!";

    
}

// Xóa footer
if (isset($_GET['delete'])) {
$id = getGet('delete');
$sql_delete = "DELETE FROM footer WHERE id = $id";
execute($sql_delete);
$_SESSION['success_message'] = "Footer đã được cập nhật thành công!";
header("Location:footer_manage.php"); 

}
?>

        <main>
            <?php require_once('partials/sidebar.php'); ?>

            <!-- admin-content -->
            <div class="admin-content">
                <div class="footer-manager">
                    <h2><i class="fa-solid fa-angles-right"></i> Quản lý footer</h2>
                    <!-- Thêm footer -->
                    <div class="common-box footer-add">
                        <h3>Thêm footer</h3>
                            
                        <form action="" method="POST">
                            <label for="name">Tên footer:</label>
                            <input type="text" id="section" name="section" placeholder="Nhập tên footer" required>

                            <label for="link">Nội dung:</label>
                            <input type="text" id="content" name="content" placeholder="Nhập nội dung" required>

                            <label for="sort_order">Thứ tự hiển thị:</label>
                            <input type="number" id="sort_order" name="sort_order" placeholder="Nhập thứ tự" required>

                            <button class="btn" type="submit">➕ Thêm Footer</button>
                        </form>

                        <?php
                        if (isset($_SESSION['success_message'])) {
                            echo "<div class='success-message'>" . $_SESSION['success_message'] . "</div>";
                            unset($_SESSION['success_message']); 
                        }
                        ?>
                    </div>
    
                    <!-- Danh sách footer -->
                     <div class="common-box footer-lists">
                        <h3>Danh sách các mục footer</h3>
    
                        <table>
                            <tr>
                                <th>ID</th>
                                <th>Tên footer</th>
                                <th>Nội dung</th>
                                <th>Thứ Tự hiển thị</th>
                                <th>Tùy chỉnh</th>
                            </tr>
                            <?php foreach ($result as $footer): ?>
                            <tr>
                                <td><?= $footer['id']; ?></td>
                                <td><?= $footer['section']; ?></td>
                                <td><?= $footer['content']; ?></td>
                                <td><?= $footer['sort_order']; ?></td>

                                <td>
                                    <a href="footer_edit.php?id=<?= $footer['id']; ?>">📝 Sửa</a> | 
                                    <a href="?delete=<?= $footer['id']; ?>" onclick="return confirm('Bạn có chắc muốn xóa?')">🗑️ Xóa</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </table>
                           
                    </div>
    
                    
    
                </div>
            </div>
        </main>
 <?php
 require_once('partials/footer.php');
?>
