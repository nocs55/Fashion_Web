<?php  
require_once ('partials/header.php');

$conn = new mysqli(HOST, USERNAME, PASSWORD, DATABASE);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// Xử lý cập nhật hồ sơ khi người dùng gửi form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_SESSION["user_id"];
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone_number = $conn->real_escape_string($_POST['phone_number']);
    $birthday = $conn->real_escape_string($_POST['birthday']);
    $sex = $conn->real_escape_string($_POST['sex']);

    $sql = "UPDATE user SET 
            username='$username', 
            email='$email', 
            phone_number='$phone_number', 
            birthday='$birthday',
            sex='$sex' 
            WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Cập nhật thành công!'); window.location.href='?id=$id';</script>";
        exit;
    } else {
        echo "<script>alert('Lỗi cập nhật!');</script>";
    }
}

// Lấy dữ liệu người dùng từ database 
$user_id = $_SESSION["user_id"];
$query = "SELECT username, email, phone_number, birthday, sex FROM user WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$user = ($result && $result->num_rows > 0) ? $result->fetch_assoc() : [
    'id' => $user_id,
    'username' => '',
    'email' => '',
    'phone_number' => '',
    'birthday' => '',
    'sex' => '',
];

$conn->close();

?>

    <!-- main content -->
    <main class="main">
     
        <!-- center content -->
        <div class="center-content ">
          
            <div class="profile-container">
                <h2>Hồ sơ khách hàng</h2>
                <form action="" method="post">
                    

                    <div class="form-group">
                        <label for="username">Họ tên:</label>
                        <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['username']) ?>" required />
                        <button type="submit" class="update-button">Thay đổi</button>
                    </div>

                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required />
                        <button type="submit" class="update-button">Thay đổi</button>
                    </div>

                    <div class="form-group">
                        <label for="phone_number">Số điện thoại:</label>
                        <input type="text" id="phone_number" name="phone_number" value="<?= htmlspecialchars($user['phone_number']) ?>" required />
                        <button type="submit" class="update-button">Thay đổi</button>
                    </div>

                    <div class="form-group">
                        <label for="birthday">Ngày sinh:</label>
                        <input type="date" id="birthday" name="birthday" value="<?= htmlspecialchars($user['birthday']) ?>" />
                        <button type="submit" class="update-button">Thay đổi</button>
                    </div>

                    <div class="form-group">
                        <label for="sex">Giới tính:</label>
                        <select id="sex" name="sex" >
                            <option value="" <?= $user['sex'] === "" ? 'selected' : '' ?>>Chọn giới tính</option>
                            <option value="male" <?= $user['sex'] === "male" ? 'selected' : '' ?>>Nam</option>
                            <option value="female" <?= $user['sex'] === "female" ? 'selected' : '' ?>>Nữ</option>
                            <option value="other" <?= $user['sex'] === "other" ? 'selected' : '' ?>>Khác</option>
                        </select>
                        <button type="submit" class="update-button">Thay đổi</button>
                    </div>
                </form>
            </div>


            <a href="./" class="back-btn">← Tiếp tục mua hàng</a>

        </div>
     
      </div>
      
 
   
        
    </main>

    <?php
   include 'partials/footer.php';
    ?>




   