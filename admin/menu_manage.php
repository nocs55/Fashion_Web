<?php
require_once('partials/header.php');

require_once ('../db/dbhelper.php');
require_once ('../db/utility.php');

$conn = new mysqli(HOST, USERNAME, PASSWORD, DATABASE);

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy danh sách menu
$sql = "SELECT * FROM menu ORDER BY sort_order";
$result = executeResult($sql);

// Thêm menu
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $link = $_POST['link'];
    $sort_order = $_POST['sort_order'];

    $sql = "INSERT INTO menu (name, link, sort_order) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $name, $link, $sort_order);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    $_SESSION['success_message'] = "🎉 Menu '$name' đã được thêm thành công!";

    
}

// Xóa menu
if (isset($_GET['delete'])) {
$id = getGet('delete');
$sql_delete = "DELETE FROM menu WHERE id = $id";
execute($sql_delete);
$_SESSION['success_message'] = "Menu đã được xóa thành công!";
header("Location:menu_manage.php"); 

}
?>

        <main>
            <?php require_once('partials/sidebar.php'); ?>

            <!-- admin-content -->
            <div class="admin-content">
                <div class="menu">
                    <h2><i class="fa-solid fa-angles-right"></i> Quản lý menu</h2>
                    <!-- Thêm danh mục -->
                    <div class="common-box menu-add">
                        <h3>Thêm menu</h3>
                            
                        <form action="" method="POST">
                            <label for="name">Tên Menu:</label>
                            <input type="text" id="name" name="name" placeholder="Nhập tên menu" required>

                            <label for="link">Đường dẫn Menu:</label>
                            <input type="text" id="link" name="link" placeholder="Nhập đường dẫn menu" required>

                            <label for="sort_order">Thứ tự hiển thị:</label>
                            <input type="number" id="sort_order" name="sort_order" placeholder="Nhập thứ tự" required>

                            <button class="btn" type="submit">➕ Thêm Menu</button>
                        </form>

                        <?php
                        if (isset($_SESSION['success_message'])) {
                            echo "<div class='success-message'>" . $_SESSION['success_message'] . "</div>";
                            unset($_SESSION['success_message']); 
                        }
                        ?>
                    </div>
    
                    <!-- Danh sách danh mục -->
                     <div class="common-box menu-lists">
                        <h3>Danh sách menu</h3>
    
                        <table>
                            <tr>
                                <th>ID</th>
                                <th>Tên Menu</th>
                                <th>Liên Kết</th>
                                <th>Thứ Tự hiển thị</th>
                                <th>Tùy chỉnh</th>
                            </tr>
                            <?php foreach ($result as $menu): ?>
                            <tr>
                                <td><?= $menu['id']; ?></td>
                                <td><?= $menu['name']; ?></td>
                                <td><?= $menu['link']; ?></td>
                                <td><?= $menu['sort_order']; ?></td>

                                <td>
                                    <a href="menu_edit.php?id=<?= $menu['id']; ?>">📝 Sửa</a> | 
                                    <a href="?delete=<?= $menu['id']; ?>" onclick="return confirm('Bạn có chắc muốn xóa?')">🗑️ Xóa</a>
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
