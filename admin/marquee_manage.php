<?php
require_once('partials/header.php');

require_once ('../db/dbhelper.php');
require_once ('../db/utility.php');

$conn = new mysqli(HOST, USERNAME, PASSWORD, DATABASE);

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy danh sách marquee
$sql = "SELECT * FROM marquee ORDER BY sort_order";
$result = executeResult($sql);

// Thêm , sửa marquee
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'] ?? null;
    $message = $_POST['message'];
    $status = $_POST['status'];
    $sort_order = $_POST['sort_order'];

    if ($id) {
        // Cập nhật marquee
        $sql = "UPDATE marquee SET message = ?, status = ?, sort_order = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssii", $message, $status, $sort_order, $id);
        $stmt->execute();
        $_SESSION['success_message'] = "🎉 Marquee đã được cập nhật!";
    } else {
        // Thêm marquee mới
        $sql = "INSERT INTO marquee (message, status, sort_order) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $message, $status, $sort_order);
        $stmt->execute();
        $_SESSION['success_message'] = "🎉 Marquee đã được thêm!";
    }

    header("Location: marquee_manage.php");
    exit();
}


// Xóa marquee
if (isset($_GET['delete'])) {
$id = getGet('delete');
$sql_delete = "DELETE FROM marquee WHERE id = $id";
execute($sql_delete);
$_SESSION['success_message'] = "Marquee đã được xóa thành công!";
header("Location:marquee.php"); 
exit();
}
?>

        <main>
            <?php require_once('partials/sidebar.php'); ?>

            <!-- admin-content -->
            <div class="admin-content">
                <div class="marquee">
                    <h2><i class="fa-solid fa-angles-right"></i> Quản lý marquee</h2>
                    <!-- Thêm marquee -->
                    <div class="common-box marquee-add">                       
                        
                        <?php
                        $edit_marquee = null;

                        if (isset($_GET['edit'])) {
                            $id = $_GET['edit'];
                            $sql = "SELECT * FROM marquee WHERE id = ?";
                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param("i", $id);
                            $stmt->execute();
                            $edit_marquee = $stmt->get_result()->fetch_assoc();
                        }
                        ?>

                    
                        <h3><?= $edit_marquee ? "Sửa Marquee" : "Thêm Marquee"; ?></h3>
                        
                        <form action="marquee_manage.php" method="POST">
                            <?php if ($edit_marquee): ?>
                                <input type="hidden" name="id" value="<?= $edit_marquee['id']; ?>">
                            <?php endif; ?>

                            <label for="message">Nội dung:</label>
                            <input type="text" id="message" name="message" value="<?= $edit_marquee['message'] ?? ''; ?>" required>

                            <label for="status">Trạng thái:</label>
                            <select id="status" name="status">
                                <option value="active" <?= isset($edit_marquee) && $edit_marquee['status'] === 'active' ? 'selected' : ''; ?>>Active</option>
                                <option value="inactive" <?= isset($edit_marquee) && $edit_marquee['status'] === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                            </select>

                            <label for="sort_order">Thứ Tự Hiển Thị:</label>
                            <input type="number" id="sort_order" name="sort_order" value="<?= $edit_marquee['sort_order'] ?? ''; ?>" required>

                            <button class="btn" type="submit"><?= $edit_marquee ? "💾 Cập Nhật" : "➕ Thêm Marquee"; ?></button>
                        </form>
                    
                        <?php
                        if (isset($_SESSION['success_message'])) {
                            echo "<div class='success-message'>" . $_SESSION['success_message'] . "</div>";
                            unset($_SESSION['success_message']); 
                        }
                        ?>
                    </div>
    
                    <!-- Danh sách marquee -->
                     <div class="common-box marquee-lists">
                        <h3>Danh sách các mục marquee</h3>
    
                        <table>
                            <tr>
                                <th>ID</th>
                                <th>Message</th>
                                <th>Trạng thái</th>
                                <th>Thứ Tự hiển thị</th>
                                <th>Tùy chỉnh</th>
                            </tr>
                            <?php foreach ($result as $marquee): ?>
                            <tr>
                                <td><?= $marquee['id']; ?></td>
                                <td><?= $marquee['message']; ?></td>
                                <td><?= $marquee['status']; ?></td>
                                <td><?= $marquee['sort_order']; ?></td>

                                <td>
                                    <a href="?edit=<?= $marquee['id']; ?>">📝 Sửa</a> | 
                                    <a href="?delete=<?= $marquee['id']; ?>" onclick="return confirm('Bạn có chắc muốn xóa?')">🗑️ Xóa</a>
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
