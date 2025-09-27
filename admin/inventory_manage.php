<?php
require_once('partials/header.php');

require_once ('../db/dbhelper.php');
require_once ('../db/utility.php');

$conn = new mysqli(HOST, USERNAME, PASSWORD, DATABASE);

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Xử lý nhập/xuất kho
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];
    $type = $_POST['type'];
    $note = $conn->real_escape_string($_POST['note']);

    if ($type == 'add') {
        $conn->query("INSERT INTO inventory (product_id, quantity_added, note) VALUES ($product_id, $quantity, '$note')");
        $conn->query("UPDATE product SET stock_quantity = stock_quantity + $quantity WHERE id = $product_id");
        $_SESSION['success_message'] = "✅ Nhập kho thành công!";
    } else {
        $conn->query("INSERT INTO inventory (product_id, quantity_removed, note) VALUES ($product_id, $quantity, '$note')");
        $conn->query("UPDATE product SET stock_quantity = stock_quantity - $quantity WHERE id = $product_id");
        $_SESSION['success_message'] = "✅ Xuất kho thành công!";
    }

    header("Location: inventory_manage.php");
    exit();
}

// Lấy danh sách sản phẩm
$products = $conn->query("SELECT id, product_name FROM product");

// Lấy lịch sử kho
$inventory = $conn->query("
    SELECT i.*, p.product_name 
    FROM inventory i
    JOIN product p ON i.product_id = p.id
    ORDER BY i.date DESC
");
?>

<main>
    <?php require_once('partials/sidebar.php'); ?>

    <!-- admin-content -->
    <div class="admin-content">
        <div class="warehouse">
            <h2><i class="fa-solid fa-angles-right"></i> Quản lý kho hàng</h2>
            
            <!-- Cập nhật kho -->
            <div class="common-box warehouse-add">
                <h3>➕ Cập nhật kho</h3>
                
                <form action="inventory_manage.php" method="POST">
                    <label for="product_id">Sản phẩm:</label>
                    <select id="product_id" name="product_id" required>
                        <option value="">-- Chọn sản phẩm --</option>
                        <?php while ($p = $products->fetch_assoc()): ?>
                            <option value="<?= $p['id'] ?>"><?= $p['product_name'] ?></option>
                        <?php endwhile; ?>
                    </select>

                    <label for="quantity">Số lượng:</label>
                    <input type="number" id="quantity" name="quantity" min="1" required>

                    <label for="note">Ghi chú:</label>
                    <input type="text" id="note" name="note" placeholder="VD: Nhập từ kho A, hư hỏng...">

                    <label for="type">Loại:</label>
                    <select id="type" name="type">
                        <option value="add">Nhập kho</option>
                        <option value="remove">Xuất kho</option>
                    </select>

                    <button class="btn" type="submit">📥 Cập nhật</button>
                </form>

                <?php
                if (isset($_SESSION['success_message'])) {
                    echo "<div class='success-message'>" . $_SESSION['success_message'] . "</div>";
                    unset($_SESSION['success_message']); 
                }
                ?>
            </div>

            <!-- Lịch sử kho -->
            <div class="common-box warehouse-lists">
                <h3>📄 Lịch sử kho</h3>

                <table>
                    <tr>
                        <th>STT</th>
                        <th>Ngày</th>
                        <th>Sản phẩm</th>
                        <th>Nhập</th>
                        <th>Xuất</th>
                        <th>Ghi chú</th>
                    </tr>

                    <?php 
                        $stt = 1;
                        while ($row = $inventory->fetch_assoc()):
                    ?>
                    <tr>
                        <td><?php echo $stt++ ?></td>
                        <td><?= $row['date'] ?></td>
                        <td><?= $row['product_name'] ?></td>
                        <td><?= $row['quantity_added'] ?? '-' ?></td>
                        <td><?= $row['quantity_removed'] ?? '-' ?></td>
                        <td><?= htmlspecialchars($row['note']) ?></td>
                    </tr>
                    <?php endwhile; ?>
                </table>
                   
            </div>
        </div>
    </div>
</main>

<?php
require_once('partials/footer.php');
?>