<?php  
require_once ('partials/header.php');


if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href='signin.php';</script>";
    exit();
}
$conn = new mysqli(HOST, USERNAME, PASSWORD, DATABASE);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'] ?? null;

// Xử lý xóa 1 sản phẩm
if (isset($_POST['remove_id'])) {
    $remove_id = intval($_POST['remove_id']);
    if ($user_id) {
        $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
        $stmt->bind_param("ii", $user_id, $remove_id);
        $stmt->execute();
    } else {
        unset($_SESSION['cart'][$remove_id]);
    }
    $_SESSION['toast'] = "🗑️ Đã xóa 1 sản phẩm khỏi giỏ hàng!";
}

// Xử lý cập nhật số lượng
if (isset($_POST['update_id']) && isset($_POST['quantity'])) {
    $update_id = intval($_POST['update_id']);
    $qty = max(1, intval($_POST['quantity']));
    if ($user_id) {
        $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
        $stmt->bind_param("iii", $qty, $user_id, $update_id);
        $stmt->execute();
    } else {
        $_SESSION['cart'][$update_id] = $qty;
    }
    $_SESSION['toast'] = "🔄 Đã cập nhật số lượng sản phẩm!";
}

// Xử lý xóa nhiều sản phẩm
if (isset($_POST['delete_selected']) && !empty($_POST['selected_products'])) {
    foreach ($_POST['selected_products'] as $remove_id) {
        $remove_id = intval($remove_id);
        if ($user_id) {
            $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
            $stmt->bind_param("ii", $user_id, $remove_id);
            $stmt->execute();
        } else {
            unset($_SESSION['cart'][$remove_id]);
        }
    }
    $_SESSION['toast'] = "🗑️ Đã xóa " . count($_POST['selected_products']) . " sản phẩm!";
}

// Xử lý mua nhiều sản phẩm (chưa thực sự xử lý đơn hàng)
if (isset($_POST['buy_selected']) && !empty($_POST['selected_products'])) {
    $_SESSION['toast'] = "🛒 Đã chọn " . count($_POST['selected_products']) . " sản phẩm để mua!";
}

// Lấy thông báo
$toast_message = $_SESSION['toast'] ?? "";
unset($_SESSION['toast']);

// Lấy danh sách giỏ hàng
$cart = [];

if ($user_id) {
    $stmt = $conn->prepare("SELECT product_id, quantity FROM cart WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $cart[$row['product_id']] = $row['quantity'];
    }
} else {
    $cart = $_SESSION['cart'] ?? [];
}

$product_list = [];
if (!empty($cart)) {
    $ids = implode(",", array_keys($cart));
    $sql = "
        SELECT p.*, 
        (SELECT image FROM gallery g WHERE g.product_id = p.id ORDER BY id ASC LIMIT 1) AS image 
        FROM product p
        LEFT JOIN gallery g ON p.id = g.product_id
        WHERE p.id IN ($ids)
        GROUP BY p.id
    ";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $product_list[] = $row;
    }
}


?>

    <!-- main content -->
    <main class="main">
     
        <!-- center content -->
        <div class="center-content ">
            
            <div class="box-cart">
                
                <div class="cart-container ">
      
                    <h2>🛒 Giỏ Hàng</h2>

                    <?php if (!empty($product_list)): ?>
                    <form method="POST" id="cartForm">
                        <div class="cart-header">
                            <div class="header-item checkbox-col">
                            
                            </div>
                            <div class="header-item image-col">Sản phẩm</div>
                            <!-- <div class="header-item info-col">Thông tin & Số lượng</div> -->
                            <div class="header-item subtotal-col">Tổng tiền</div>
                        </div>
                        <div style="display: flex; align-items: center; margin-bottom: 10px; gap: 10px;">
                            <input type="checkbox" id="selectAll" onclick="toggleSelectAll(this)">
                            <label for="selectAll"><strong>Chọn tất cả sản phẩm</strong></label>
                        </div>

                        <?php $total = 0; ?>
                        <?php foreach ($product_list as $product):
                            $qty = $cart[$product['id']];
                            $discount = $product['discount'] ?? 0;
                            $price = $product['price'];
                            $discount_price = $price * (1 - $discount / 100);
                            $subtotal = $discount_price * $qty;
                            $total += $subtotal;
                        ?>
                        <div class="cart-item">
                            <div class="checkbox-col">
                                <input type="checkbox" name="selected_products[]" value="<?= $product['id'] ?>" class="select-item">
                            </div>

                            <img src="<?= $product['image'] ?>" alt="<?= $product['product_name'] ?>">

                            <div class="cart-item-info">
                                <div class="cart-item-name"><?= $product['product_name'] ?></div>
                                <div class="cart-item-price">
                                    <?php if ($discount > 0): ?>
                                <div class="original-price"><?=number_format($price, 0, ',', '.') ?> VND</div>
                                <div class="discounted-price"><?= number_format($discount_price, 0, ',', '.')  ?> VND</div>
                                    <?php else: ?>
                                <div class="discounted-price"><?= number_format($price, 0, ',', '.') ?> VND</div>
                                    <?php endif; ?>
                                </div>

                                <div class="qty-form">
                                    <!-- Giảm -->
                                    <button type="button" class="qty-btn" onclick="updateQuantity(<?= $product['id'] ?>, <?= $qty - 1 ?>)">−</button>

                                    <!-- Nhập -->
                                    <input type="number" value="<?= $qty ?>" min="1" onchange="updateQuantity(<?= $product['id'] ?>, this.value)" style="width: 50px;">

                                    <!-- Tăng -->
                                    <button type="button" class="qty-btn" onclick="updateQuantity(<?= $product['id'] ?>, <?= $qty + 1 ?>)">+</button>
                                </div>

                                <button type="button" class="remove-btn" onclick="removeItem(<?= $product['id'] ?>)">Xóa</button>
                            </div>
                            <div class="subtotal"><strong><?= number_format($subtotal, 0, ',', '.') ?> VND</strong></div>
                        </div>
                        <?php endforeach; ?>

                        <div class="bulk-actions">
                            <button type="submit" name="delete_selected" class="bulk-btn delete-selected-btn" onclick="return confirmDelete()">🗑️ Xóa đã chọn</button>
                            <button type="submit" formaction="checkout.php" name="buy_selected" class="bulk-btn buy-selected-btn" onclick="return confirmBuy()">🛒 Mua đã chọn</button>
                            <span class="selected-count" id="selectedCount">Đã chọn: 0 sản phẩm</span>
                        </div>

                    </form>

                    <div class="total-row">Tổng cộng: <?= number_format($total, 0, ',', '.') ?> VND</div>

                    <div class="footer-actions">
                        <a href="./" class="back-btn">← Tiếp tục mua hàng</a>
                        <button class="checkout-btn"   onclick="buyAll()">Mua tất cả</button>
                    </div>

                    <?php else: ?>
                        <div class="empty">Không có sản phẩm nào trong giỏ hàng.</div>
                        <a href="./" class="back-btn">← Tiếp tục mua hàng</a>
                    <?php endif; ?>
                </div>

                <!-- Hidden forms for individual actions -->
                <form id="updateForm" method="POST" style="display: none;">
                    <input type="hidden" name="update_id" id="updateId">
                    <input type="hidden" name="quantity" id="updateQuantity">
                </form>

                <form id="removeForm" method="POST" style="display: none;">
                    <input type="hidden" name="remove_id" id="removeId">
                </form>

                <!-- TOAST -->
                <?php if ($toast_message): ?>
                    <div id="toast"><?= $toast_message ?></div>
                <?php else: ?>
                    <div id="toast" style="display:none;"></div>
                <?php endif; ?>
               

                
<script>
    function toggleSelectAll(master) {
        document.querySelectorAll('.select-item').forEach(cb => cb.checked = master.checked);
        updateSelectedCount();
    }

    document.querySelectorAll('.select-item').forEach(cb => {
        cb.addEventListener('change', () => {
            const all = document.querySelectorAll('.select-item').length;
            const checked = document.querySelectorAll('.select-item:checked').length;
            document.getElementById('selectAll').checked = all === checked;
            updateSelectedCount();
        });
    });

    function updateSelectedCount() {
        const count = document.querySelectorAll('.select-item:checked').length;
        document.getElementById('selectedCount').textContent = `Đã chọn: ${count} sản phẩm`;
    }

    function confirmDelete() {
        const count = document.querySelectorAll('.select-item:checked').length;
        if (count === 0) {
            showToast('❌ Vui lòng chọn ít nhất một sản phẩm để xóa!');
            return false;
        }
        return true;
    }

    function confirmBuy() {
        const count = document.querySelectorAll('.select-item:checked').length;
        if (count === 0) {
            showToast('❌ Vui lòng chọn ít nhất một sản phẩm để mua!');
            return false;
        }
        return true;
    }

    function updateQuantity(productId, quantity) {
        if (quantity < 1) quantity = 1;
        document.getElementById('updateId').value = productId;
        document.getElementById('updateQuantity').value = quantity;
        document.getElementById('updateForm').submit();
    }

    function removeItem(productId) {
        document.getElementById('removeId').value = productId;
        document.getElementById('removeForm').submit();
        updateCartCount(); //để khi xóa sp thì cập nhật lại cart-count

    }

    function buyAll() {
        showToast("🛍️ Chuyển đến trang thanh toán...");
        window.location.href = 'checkout.php';

    }

    function showToast(msg) {
        const toast = document.getElementById("toast");
        toast.textContent = msg;
        toast.style.display = "block";
        setTimeout(() => { toast.style.display = "none"; }, 3000);
    }


</script>

            </div>
     
        </div>
        
    </main>

    <?php
   include 'partials/footer.php';
    ?>
