 <?php
require_once('partials/header.php');

$conn = new mysqli(HOST, USERNAME, PASSWORD, DATABASE);
mysqli_set_charset($conn, 'utf8');
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// --- TRUY VẤN DỮ LIỆU PHẢN HỒI ---
$sql = "SELECT * FROM feedback ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

        
       <main>
            <?php require_once('partials/sidebar.php'); ?>

            <!-- admin-content -->
            <div class="admin-content">
                <div class="feedback">
                <h2><i class="fa-solid fa-angles-right"></i> Quản lý phản hồi của khách hàng</h2>
    
                    <!-- Danh sách các phản hồi -->
                    <div class="common-box feedback-lists">
                        <h3>Danh sách các phản hồi</h3>
                        <table class="contacts-table">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>ID</th>
                                    <th>Họ tên</th>
                                    <th>Email</th>
                                    <th>Điện thoại</th>
                                    <th>Nội dung</th>
                                    <th>Ngày gửi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($result && $result->num_rows > 0): ?>
                                    <?php 
                                        $stt=1;
                                        while ($row = $result->fetch_assoc()): ?>
                                        <tr>
                                            <td><?= $stt++ ?></td>
                                            <td><?= $row['id'] ?></td>
                                            <td><?= htmlspecialchars($row['name']) ?></td>
                                            <td><?= htmlspecialchars($row['email']) ?></td>
                                            <td><?= htmlspecialchars($row['phone']) ?></td>
                                            <td><?= nl2br(htmlspecialchars($row['message'])) ?></td>
                                            <td><?= $row['created_at'] ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr><td colspan="6">Chưa có phản hồi nào!</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
    
                        
                      
    
                    </div>
                </div>
            </div>
       </main>
 <?php
 require_once('partials/footer.php');
?>



