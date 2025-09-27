<?php
require_once('partials/header.php');

require_once ('../db/dbhelper.php');
require_once ('../db/utility.php');

$conn = new mysqli(HOST, USERNAME, PASSWORD, DATABASE);

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy danh sách user chưa bị xóa
$sql = "SELECT * FROM user";
$result = executeResult($sql);

// Thêm , sửa user
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'] ?? null;
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $role = $_POST['role'] ?? 'customer';
    $birthday = $_POST['birthday'] ?? null;
    $sex = $_POST['sex'];
    $pass = $_POST['pass_new'] ?? '';

    if ($id) {
        // Nếu không nhập mật khẩu mới, giữ nguyên mật khẩu cũ
        if (!empty($pass)) {
            $pass = getPwdSecurity($pass);
            $sql = "UPDATE user SET username = ?, email = ?, phone_number = ?, password = ?, role = ?, birthday = ?, sex = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssssi", $username, $email, $phone_number, $pass, $role, $birthday, $sex, $id);
        } else {
            $sql = "UPDATE user SET username = ?, email = ?, phone_number = ?, role = ?, birthday = ?, sex = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssssi", $username, $email, $phone_number, $role, $birthday, $sex, $id);
        }
        $stmt->execute();
        $_SESSION['success_message'] = "🎉 User đã được cập nhật!";
    } else {
        // Thêm User mới
        $pass = getPwdSecurity($pass);
        $sql = "INSERT INTO user (username, email, phone_number, password, role, birthday, sex) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssss", $username, $email, $phone_number, $pass, $role, $birthday, $sex);
        $stmt->execute();
        $_SESSION['success_message'] = "🎉 User đã được thêm!";
    }

    header("Location: users_manage.php");
    exit();
}

// Xóa user
if (isset($_GET['delete'])) {
$id = getGet('delete');
$sql = "UPDATE user SET deleted = 1 WHERE id = $id";
execute($sql);
$_SESSION['success_message'] = "Người dùng đã được xóa thành công!";
header("Location:users_manage.php"); 
exit();
}


if (isset($_GET['restore'])) {
$id = getGet('restore');
$sql = "UPDATE user SET deleted = 0 WHERE id = $id";
execute($sql);
$_SESSION['success_message'] = "Người dùng đã được xóa thành công!";
header("Location:users_manage.php"); 
exit();
}
?>

        <main>
            <?php require_once('partials/sidebar.php'); ?>

            <!-- admin-content -->
            <div class="admin-content">
                <div class="user">
                    <h2><i class="fa-solid fa-angles-right"></i> Quản lý thành viên</h2>
                    <!-- Thêm user -->
                    <div class="common-box user-add">                       
                        
                        <?php
                        $edit_user = null;

                        if (isset($_GET['edit'])) {
                            $id = $_GET['edit'];
                            $sql = "SELECT * FROM user WHERE id = ?";
                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param("i", $id);
                            $stmt->execute();
                            $edit_user= $stmt->get_result()->fetch_assoc();
                        }
                        ?>

                    
                        <h3><?= $edit_user ? "Sửa thông tin" : "Thêm thành viên "; ?></h3>
                        
                        <form action="users_manage.php" method="POST">
                            <?php if ($edit_user): ?>
                                <input type="hidden" name="id" value="<?= $edit_user['id']; ?>">
                            <?php endif; ?>

                            <label for="username">Tên người dùng:</label>
                            <input type="text" id="username" name="username" value="<?= $edit_user['username'] ?? ''; ?>" required>

                            <label for="email">Email:</label>
                            <input type="email" id="email" name="email" value="<?= $edit_user['email'] ?? ''; ?>" required>
                            
                            <label for="phone_number">Số điện thoại:</label>
                            <input type="text" id="phone_number" name="phone_number" value="<?= $edit_user['phone_number'] ?? ''; ?>" required>
                            
                            <?php if($edit_user) {?>
                                <label for="pass_new">Mật khẩu mới:</label>
                                <input type="password" id="pass_new" name="pass_new">
                                <div class="note">Để trống nếu không muốn thay đổi mật khẩu.</div>
                            <?php } 
                             else { ?>
                                <label for="pass_new">Mật khẩu:</label>
                                <input type="password" id="pass_new" name="pass_new">
                            <?php } ?>

                            <label for="role">Vai trò:</label>
                            <select id="role" name="role">
                                <option value="user" <?= isset($edit_user) && $edit_user['role'] === 'customer' ? 'selected' : ''; ?>>Customer</option>
                                <option value="admin" <?= isset($edit_user) && $edit_user['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                            </select>

                            <label for="birthday">Ngày sinh:</label>
                            <input type="date" id="birthday" name="birthday" value="<?= $edit_user['birthday'] ?? ''; ?>">
                            
                            <label for="sex">Giới tính:</label>
                            <select id="sex" name="sex">
                                <option value="male" <?= isset($edit_user) && $edit_user['sex'] === 'male' ? 'selected' : ''; ?>>Nam</option>
                                <option value="female" <?= isset($edit_user) && $edit_user['sex'] === 'female' ? 'selected' : ''; ?>>Nữ</option>
                                <option value="other" <?= isset($edit_user) && $edit_user['sex'] === 'other' ? 'selected' : ''; ?>>Khác</option>
                            </select>

                            <button class="btn" type="submit"><?= $edit_user ? "💾 Cập Nhật" : "➕ Thêm thành viên"; ?></button>
                        </form>
                    
                        <?php if($edit_user) :?>
                            <a class="btn-back" href="users_manage.php">⬅️ Quay lại</a>
                            <?php endif; ?>

                        <?php
                        if (isset($_SESSION['success_message'])) {
                            echo "<div class='success-message'>" . $_SESSION['success_message'] . "</div>";
                            unset($_SESSION['success_message']); 
                        }
                        ?>
                    </div>
    
                    <!-- Danh sách user -->
                     <div class="common-box user-lists">
                        <h3>Danh sách các thành viên</h3>
    
                        <table>
                            <tr>    
                                <th>STT</th>
                                <th>ID</th>
                                <th>Tên người dùng</th>
                                <th>Email</th>
                                <th>Số điện thoại</th>
                                <th>Vai trò</th>
                                <th>Ngày sinh</th>
                                <th>Giới tính</th>
                                <th>Tùy chỉnh</th>
                            </tr>

                            <?php 
                                $stt = 1;
                                foreach ($result as $user):
                            ?>

                            <tr>
                                <td><?php echo $stt++ ?></td> 
                                <td><?= $user['id']; ?></td>
                                <td><?= $user['username']; ?></td>
                                <td><?= $user['email']; ?></td>
                                <td><?= $user['phone_number'] ?></td>
                                <td><?= $user['role']; ?></td>
                                <td><?= $user['birthday'] ?? 'Chưa cập nhật'; ?></td>
                                <td><?= $user['sex']; ?></td>
                                <td>
                                    <a href="?edit=<?= $user['id']; ?>"> Sửa</a> |  
                                    <a href="?delete=<?= $user['id']; ?>" onclick="return confirm('Bạn có chắc muốn xóa?')"> Xóa |</a>
                                    <a href="?restore=<?= $user['id']; ?>" onclick="return confirm('Bạn có chắc muốn khôi phục?')"> khôi phục</a>
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
