<?php  
include 'partials/header.php';

$conn = new mysqli(HOST, USERNAME, PASSWORD, DATABASE);

if ($conn->connect_error) {
    die("Kết nối thất bại: {$conn->connect_error}");
}

// Lấy giỏ hàng
$cart_items = [];
$total_amount = 0;
$user_id = $_SESSION['user_id'];
$order_type = 'cart_all'; // Mặc định là mua tất cả giỏ hàng
$selected_products = []; // Lưu danh sách sản phẩm đã chọn

// Kiểm tra nếu là mua ngay từ trang chi tiết
if (isset($_POST['action']) && $_POST['action'] == 'buy_now') {
    $order_type = 'buy_now';
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);
    
    // Lấy thông tin sản phẩm kèm ảnh và tính giá sau giảm giá
    $sql = "SELECT p.*, 
            (SELECT image FROM gallery g WHERE g.product_id = p.id ORDER BY id ASC LIMIT 1) AS image,
            CASE 
                WHEN p.discount > 0 THEN p.price * (1 - p.discount / 100)
                ELSE p.price
            END AS final_price
            FROM product p WHERE p.id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $row['quantity'] = $quantity;
        $row['total_money'] = $row['final_price'] * $quantity;
        $cart_items[] = $row;
        $total_amount = $row['total_money'];
    }
}
// Kiểm tra nếu là mua từ giỏ hàng (sản phẩm đã chọn)
elseif (isset($_POST['selected_products']) && !empty($_POST['selected_products'])) {
    $order_type = 'cart_selected';
    $selected_products = array_map('intval', $_POST['selected_products']);
    $ids_placeholder = str_repeat('?,', count($selected_products) - 1) . '?';
    
    $sql = "SELECT p.*, c.quantity,
            (SELECT image FROM gallery g WHERE g.product_id = p.id ORDER BY id ASC LIMIT 1) AS image,
            CASE 
                WHEN p.discount > 0 THEN p.price * (1 - p.discount / 100)
                ELSE p.price
            END AS final_price,
            CASE 
                WHEN p.discount > 0 THEN (p.price * (1 - p.discount / 100)) * c.quantity
                ELSE p.price * c.quantity
            END AS total_money
            FROM cart c 
            JOIN product p ON c.product_id = p.id 
            WHERE c.user_id = ? AND p.id IN ($ids_placeholder)";
    
    $stmt = $conn->prepare($sql);
    $types = 'i' . str_repeat('i', count($selected_products));
    $params = array_merge([$user_id], $selected_products);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $cart_items[] = $row;
        $total_amount += $row['total_money'];
    }
}
// Mặc định: lấy tất cả giỏ hàng
else {
    $sql = "SELECT p.*, c.quantity,
            (SELECT image FROM gallery g WHERE g.product_id = p.id ORDER BY id ASC LIMIT 1) AS image,
            CASE 
                WHEN p.discount > 0 THEN p.price * (1 - p.discount / 100)
                ELSE p.price
            END AS final_price,
            CASE 
                WHEN p.discount > 0 THEN (p.price * (1 - p.discount / 100)) * c.quantity
                ELSE p.price * c.quantity
            END AS total_money
            FROM cart c 
            JOIN product p ON c.product_id = p.id 
            WHERE c.user_id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $cart_items[] = $row;
        $total_amount += $row['total_money'];
    }
}

$order_success = false;
$error_message = "";
$order_id = null;
$payment_method = "";

// Xử lý đặt hàng
if (isset($_POST['submit_order'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $address = $conn->real_escape_string($_POST['address']);
    $note = $conn->real_escape_string($_POST['note']);
    $payment_method = $_POST['payment'] ?? 'cod';
    
    
    // Lấy lại thông tin order_type và selected_products từ hidden fields
    $order_type = $_POST['order_type'] ?? 'cart_all';
    if (isset($_POST['hidden_selected_products'])) {
        $selected_products = array_map('intval', explode(',', $_POST['hidden_selected_products']));
    }
    
    // LẤY LẠI THÔNG TIN SẢN PHẨM THEO ĐÚNG ORDER_TYPE
    $cart_items = [];
    $total_amount = 0;
    
    if ($order_type == 'buy_now') {
        // Lấy thông tin từ POST (buy_now case)
        $product_id = intval($_POST['product_id'] ?? 0);
        $quantity = intval($_POST['quantity'] ?? 0);
        
        if ($product_id > 0) {
            $sql = "SELECT p.*, 
                    (SELECT image FROM gallery g WHERE g.product_id = p.id ORDER BY id ASC LIMIT 1) AS image,
                    CASE 
                        WHEN p.discount > 0 THEN p.price * (1 - p.discount / 100)
                        ELSE p.price
                    END AS final_price
                    FROM product p WHERE p.id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $product_id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($row = $result->fetch_assoc()) {
                $row['quantity'] = $quantity;
                $row['total_money'] = $row['final_price'] * $quantity;
                $cart_items[] = $row;
                $total_amount = $row['total_money'];
            }
        }
    } elseif ($order_type == 'cart_selected' && !empty($selected_products)) {
        // Lấy chỉ các sản phẩm đã chọn
        $ids_placeholder = str_repeat('?,', count($selected_products) - 1) . '?';
        
        $sql = "SELECT p.*, c.quantity,
                (SELECT image FROM gallery g WHERE g.product_id = p.id ORDER BY id ASC LIMIT 1) AS image,
                CASE 
                    WHEN p.discount > 0 THEN p.price * (1 - p.discount / 100)
                    ELSE p.price
                END AS final_price,
                CASE 
                    WHEN p.discount > 0 THEN (p.price * (1 - p.discount / 100)) * c.quantity
                    ELSE p.price * c.quantity
                END AS total_money
                FROM cart c 
                JOIN product p ON c.product_id = p.id 
                WHERE c.user_id = ? AND p.id IN ($ids_placeholder)";
        
        $stmt = $conn->prepare($sql);
        $types = 'i' . str_repeat('i', count($selected_products));
        $params = array_merge([$user_id], $selected_products);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
        
        while ($row = $result->fetch_assoc()) {
            $cart_items[] = $row;
            $total_amount += $row['total_money'];
        }
    } else {
        // Lấy tất cả giỏ hàng (cart_all)
        $sql = "SELECT p.*, c.quantity,
                (SELECT image FROM gallery g WHERE g.product_id = p.id ORDER BY id ASC LIMIT 1) AS image,
                CASE 
                    WHEN p.discount > 0 THEN p.price * (1 - p.discount / 100)
                    ELSE p.price
                END AS final_price,
                CASE 
                    WHEN p.discount > 0 THEN (p.price * (1 - p.discount / 100)) * c.quantity
                    ELSE p.price * c.quantity
                END AS total_money
                FROM cart c 
                JOIN product p ON c.product_id = p.id 
                WHERE c.user_id = ?";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        while ($row = $result->fetch_assoc()) {
            $cart_items[] = $row;
            $total_amount += $row['total_money'];
        }
    }

    // Phí giao hàng
    $shipping_method = $_POST['shipping'] ?? 'standard';
    $shipping_fee = 0;
    switch ($shipping_method) {
        case 'fast': $shipping_fee = 25000; break;
        case 'express': $shipping_fee = 50000; break;
        default: $shipping_fee = 0;
    }

    $final_total = $total_amount + $shipping_fee;


// ===== KIỂM TRA TỒN KHO TRƯỚC KHI ĐẶT HÀNG =====
$stock_errors = [];
$insufficient_stock_items = [];

foreach ($cart_items as $item) {
    // Lấy số lượng tồn kho hiện tại
    $check_stock_sql = "SELECT stock_quantity, product_name FROM product WHERE id = ?";
    $check_stmt = $conn->prepare($check_stock_sql);
    $check_stmt->bind_param("i", $item['id']);
    $check_stmt->execute();
    $stock_result = $check_stmt->get_result();
    
    if ($stock_row = $stock_result->fetch_assoc()) {
        $current_stock = $stock_row['stock_quantity'];
        $product_name = $stock_row['product_name'];
        
        // Kiểm tra nếu không đủ hàng
        if ($current_stock < $item['quantity']) {
            $insufficient_stock_items[] = [
                'name' => $product_name,
                'requested' => $item['quantity'],
                'available' => $current_stock
            ];
        }
    }
    $check_stmt->close();
}

// Nếu có sản phẩm không đủ hàng, hiển thị lỗi và dừng lại
if (!empty($insufficient_stock_items)) {
    $error_message = "Không đủ hàng tồn kho cho các sản phẩm sau:\n";
    foreach ($insufficient_stock_items as $item) {
        $error_message .= "• {$item['name']}: Yêu cầu {$item['requested']}, chỉ còn {$item['available']}\n";
    }
    // Không thực hiện đặt hàng, chỉ hiển thị lỗi
} else {
    // ===== TIẾP TUC XỬ LÝ ĐẶT HÀNG NẾU ĐỦ HÀNG =====
    
    // Xác định trạng thái đơn hàng dựa trên phương thức thanh toán
    $order_status = ($payment_method == 'bank_transfer') ? 'waiting_payment' : 'pending';

    // Thêm đơn hàng vào bảng `orders`
    $order_sql = "INSERT INTO orders (user_id, name, phone_number, address, note, order_date, status, total_money, payment_method) 
                  VALUES (?, ?, ?, ?, ?, NOW(), ?, ?, ?)";
    $stmt = $conn->prepare($order_sql);
    $stmt->bind_param("isssssds", $user_id, $name, $phone, $address, $note, $order_status, $final_total, $payment_method);
    
    if ($stmt->execute()) {
        $order_id = $conn->insert_id;

        // Lưu chi tiết từng mặt hàng vào `order_details`
        foreach ($cart_items as $item) {
            $item_sql = "INSERT INTO order_details (order_id, product_id, price, quantity, total_money) 
                         VALUES (?, ?, ?, ?, ?)";
            $item_stmt = $conn->prepare($item_sql);
            $final_price = $item['final_price'] ?? $item['price'];
            $item_total = $final_price * $item['quantity'];
            $item_stmt->bind_param("iiidd", $order_id, $item['id'], $final_price, $item['quantity'], $item_total);
            $item_stmt->execute();
        }

        // Cập nhật sold_count và giảm stock_quantity
        foreach ($cart_items as $item) {
            $update_sql = "UPDATE product SET 
                          sold_count = sold_count + ?, 
                          stock_quantity = stock_quantity - ? 
                          WHERE id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("iii", $item['quantity'], $item['quantity'], $item['id']);
            $update_stmt->execute();
            $update_stmt->close();
        }

        // Xử lý xóa sản phẩm từ giỏ hàng dựa trên order_type
        if ($order_type == 'cart_selected' && !empty($selected_products)) {
            // Xóa chỉ các sản phẩm đã chọn
            foreach ($selected_products as $product_id) {
                $delete_sql = "DELETE FROM cart WHERE user_id = ? AND product_id = ?";
                $delete_stmt = $conn->prepare($delete_sql);
                $delete_stmt->bind_param("ii", $user_id, $product_id);
                $delete_stmt->execute();
            }
        } elseif ($order_type == 'cart_all') {
            // Xóa toàn bộ giỏ hàng
            $delete_sql = "DELETE FROM cart WHERE user_id = ?";
            $delete_stmt = $conn->prepare($delete_sql);
            $delete_stmt->bind_param("i", $user_id);
            $delete_stmt->execute();
        }
        // Nếu là buy_now thì không xóa gì từ cart

        $order_success = true;
    } else {
        $error_message = "Lỗi khi đặt hàng: " . $conn->error;
    }
}
}
?>

<!-- main content -->
<main class="main">
    <div class="main-content">
        <!-- center content -->
        <div class="center-content checkout">
            
            <!-- Thông báo đặt hàng thành công với SweetAlert -->
            <?php if ($order_success): ?>
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                <script>
                    <?php if ($payment_method == 'bank_transfer'): ?>
                        // Thông báo cho thanh toán chuyển khoản
                        Swal.fire({
                            title: '🎉 Đặt hàng thành công!',
                            html: `
                                <div style="text-align: left; margin: 20px 0;">
                                    <p><strong>Mã đơn hàng:</strong> #<?= $order_id ?></p>
                                    <p><strong>Tổng tiền:</strong> <?= number_format($final_total ?? $total_amount, 0, ',', '.') ?> VND</p>
                                    <p style="color: #ff6b6b;"><strong>Trạng thái:</strong> Đang chờ thanh toán</p>
                                    
                                    <hr style="margin: 20px 0;">
                                    
                                    <h4 style="color: #2c3e50; margin-bottom: 15px;">💳 Thông tin chuyển khoản:</h4>
                                    <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin: 10px 0;">
                                        <p><strong>Ngân hàng:</strong> Vietcombank</p>
                                        <p><strong>Số tài khoản:</strong> 9859099377</p>
                                        <p><strong>Chủ tài khoản:</strong> NGUYEN THI THU THUY</p>
                                        <p><strong>Số tiền:</strong> <?= number_format($final_total ?? $total_amount, 0, ',', '.') ?> VND</p>
                                        <p><strong>Nội dung CK:</strong> DH<?= $order_id ?> - <?= $name ?></p>
                                    </div>
                                    
                                    <div style="text-align: center; margin: 15px 0;">
                                        <img src="../img/qr.JPG" 
                                             alt="QR Code" style="width: 200px; height: 200px; border: 1px solid #ddd;">
                                        <p style="font-size: 0.9em; color: #666; margin-top: 8px;">Quét mã QR để chuyển khoản</p>
                                    </div>
                                    
                                    <div style="background: #fff3cd; padding: 10px; border-radius: 5px; border-left: 4px solid #ffc107;">
                                        <p style="margin: 0; font-size: 0.9em;">
                                            <strong>Lưu ý:</strong> Sau khi thanh toán, đơn hàng sẽ được admin xác nhận và xử lý.
                                        </p>
                                    </div>
                                </div>
                            `,
                            icon: 'success',
                            width: '600px',
                            confirmButtonText: 'Xem đơn hàng',
                            cancelButtonText: 'Tiếp tục mua sắm',
                            showCancelButton: true,
                            confirmButtonColor: '#28a745',
                            cancelButtonColor: '#007bff',
                            allowOutsideClick: false
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = 'orders.php';
                            } else if (result.dismiss === Swal.DismissReason.cancel) {
                                window.location.href = 'index.php';
                            }
                        });
                    <?php else: ?>
                        // Thông báo cho thanh toán khi nhận hàng
                        Swal.fire({
                            title: '🎉 Đặt hàng thành công!',
                            html: `
                                <div style="text-align: left; margin: 20px 0;">
                                    <p><strong>Mã đơn hàng:</strong> #<?= $order_id ?></p>
                                    <p><strong>Tổng tiền:</strong> <?= number_format($final_total ?? $total_amount, 0, ',', '.') ?> VND</p>
                                    <p style="color: #ffa500;"><strong>Trạng thái:</strong> Chờ admin duyệt đơn hàng</p>
                                    <p><strong>Thanh toán:</strong> Khi nhận hàng</p>
                                    
                                    <hr style="margin: 20px 0;">
                                    
                                    <div style="background: #d4edda; padding: 15px; border-radius: 8px; border-left: 4px solid #28a745;">
                                        <p style="margin: 0;">
                                            <strong>✅ Cảm ơn bạn đã đặt hàng!</strong><br>
                                            Chúng tôi sẽ liên hệ với bạn để xác nhận đơn hàng trong thời gian sớm nhất.
                                        </p>
                                    </div>
                                </div>
                            `,
                            icon: 'success',
                            width: '500px',
                            confirmButtonText: 'Xem đơn hàng',
                            cancelButtonText: 'Tiếp tục mua sắm',
                            showCancelButton: true,
                            confirmButtonColor: '#28a745',
                            cancelButtonColor: '#007bff'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = 'orders.php';
                            } else if (result.dismiss === Swal.DismissReason.cancel) {
                                window.location.href = './';
                            }
                        });
                    <?php endif; ?>
                </script>
            <?php endif; ?>
            
            <?php if (!$order_success): ?>
                <!-- Form đặt hàng chỉ hiển thị khi chưa đặt hàng thành công -->
                
                <!-- Left Column -->
                <div class="left-column">
                    <!-- Order Items -->
                    <div class="card">
                        <h2 class="card-title">Thông tin đơn hàng</h2>
                        
                        <?php if (!empty($cart_items)): ?>
                            <?php foreach ($cart_items as $item): ?>
                                <div class="order-item">
                                    <img src="<?= $item['image'] ?>" alt="<?= htmlspecialchars($item['product_name']) ?>" style="width: 80px; height: 80px; object-fit: cover;">
                                    <div class="item-details">
                                        <div class="item-name"><?= htmlspecialchars($item['product_name']) ?></div>
                                        <div class="item-price">
                                            <?php if (!empty($item['discount']) && $item['discount'] > 0): ?>
                                                <span style="text-decoration: line-through; color: #999; font-size: 1.2rem;">
                                                    <?= number_format($item['price'], 0, ',', '.') ?> VND
                                                </span>
                                                <span style="color: #e74c3c; font-weight: bold;">
                                                    <?= number_format($item['final_price'] ?? ($item['price'] * (1 - $item['discount'] / 100)), 0, ',', '.') ?> VND
                                                </span>
                                            <?php else: ?>
                                                <span><?= number_format($item['price'], 0, ',', '.') ?> VND</span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="item-quantity">
                                            <span>Số lượng: <?= $item['quantity'] ?></span>
                                            <span class="item-total">
                                                <?= number_format(($item['final_price'] ?? $item['price']) * $item['quantity'], 0, ',', '.') ?> VND
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="order-item">
                                <div class="item-details">
                                    <div class="item-name">Không có sản phẩm nào</div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Customer Information -->
                    <div class="card">
                        <div class="card-header">
                            <h2 class="card-title">Thông tin đặt hàng</h2>
                        </div>
                        
                        <form method="post" action="" id="checkout-form">
                            <!-- Hidden fields để lưu thông tin order type và selected products -->
                            <input type="hidden" name="order_type" value="<?= htmlspecialchars($order_type) ?>">
                            <?php if (!empty($selected_products)): ?>
                                <input type="hidden" name="hidden_selected_products" value="<?= implode(',', $selected_products) ?>">
                            <?php endif; ?>
                            
                            <!-- Thêm hidden fields cho buy_now case -->
                            <?php if ($order_type == 'buy_now'): ?>
                                <input type="hidden" name="product_id" value="<?= intval($_POST['product_id'] ?? 0) ?>">
                                <input type="hidden" name="quantity" value="<?= intval($_POST['quantity'] ?? 0) ?>">
                            <?php endif; ?>
                            
                            <div class="form-group">
                                <label class="form-label">Họ tên *</label>
                                <input type="text" name="name" class="form-input" required 
                                    value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Số điện thoại *</label>
                                <input type="tel" name="phone" class="form-input" required 
                                    value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>"
                                    pattern="[0-9]{10,11}">
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Địa chỉ *</label>
                                <input type="text" name="address" class="form-input" required 
                                    value="<?= htmlspecialchars($_POST['address'] ?? '') ?>">
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Ghi chú</label>
                                <label class="note-small">(Vui lòng ghi phân loại bạn muốn mua ở đây)</label>
                                <textarea name="note" class="form-input form-textarea"><?= htmlspecialchars($_POST['note'] ?? '') ?></textarea>
                            </div>
                            
                            <!-- Shipping Method -->
                            <div class="form-group">
                                <label class="form-label">Hình thức giao hàng</label>
                                <div class="radio-group">
                                    <label class="radio-option">
                                        <input type="radio" name="shipping" value="standard" 
                                            <?= (!isset($_POST['shipping']) || $_POST['shipping'] == 'standard') ? 'checked' : '' ?>
                                            onchange="updateShippingFee()">
                                        <span class="radio-label">Tiêu chuẩn (2-3 ngày) - Miễn phí</span>
                                    </label>
                                    
                                    <label class="radio-option">
                                        <input type="radio" name="shipping" value="fast" 
                                            <?= (isset($_POST['shipping']) && $_POST['shipping'] == 'fast') ? 'checked' : '' ?>
                                            onchange="updateShippingFee()">
                                        <span class="radio-label">Nhanh (1-2 ngày) - 25.000 VND</span>
                                    </label>
                                    
                                    <label class="radio-option">
                                        <input type="radio" name="shipping" value="express" 
                                            <?= (isset($_POST['shipping']) && $_POST['shipping'] == 'express') ? 'checked' : '' ?>
                                            onchange="updateShippingFee()">
                                        <span class="radio-label">Hỏa tốc (trong ngày) - 50.000 VND</span>
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Payment Method -->
                            <div class="form-group">
                                <label class="form-label">Phương thức thanh toán</label>
                                <div class="radio-group">
                                    <label class="radio-option">
                                        <input type="radio" name="payment" value="cod" 
                                            <?= (!isset($_POST['payment']) || $_POST['payment'] == 'cod') ? 'checked' : '' ?>>
                                        <span class="radio-label">💵 Tiền mặt khi nhận hàng</span>
                                    </label>
                                    
                                    <label class="radio-option">
                                        <input type="radio" name="payment" value="bank_transfer" 
                                            <?= (isset($_POST['payment']) && $_POST['payment'] == 'bank_transfer') ? 'checked' : '' ?>>
                                        <span class="radio-label">🏦 Chuyển khoản ngân hàng</span>
                                    </label>
                                </div>
                            </div>
                            
                            <?php if ($error_message): ?>
                                <div style="color: red; margin: 12px 0; font-size: 0.875rem;">
                                    <?= htmlspecialchars($error_message) ?>
                                </div>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="right-column">
                    <div class="card order-summary">
                        <div class="card-header">
                            <h2 class="card-title">Tóm tắt đơn hàng</h2>
                        </div>
                        
                        <div class="summary-row">
                            <span>Tạm tính</span>
                            <span id="subtotal"><?= number_format($total_amount, 0, ',', '.') ?> VND</span>
                        </div>
                        
                        <div class="summary-row">
                            <span>Phí vận chuyển</span>
                            <span id="shipping-fee">Miễn phí</span>
                        </div>
                        
                        <div class="summary-row total">
                            <span>Tổng cộng: </span>
                            <span id="total-amount"><?= number_format($total_amount, 0, ',', '.') ?> VND</span>
                        </div>
                        
                        <button onclick="submitOrder()" class="btn btn-primary btn-block" style="margin-top: 16px;" 
                                <?= empty($cart_items) ? 'disabled' : '' ?>>
                            Đặt hàng ngay
                        </button>
                    </div>
                </div>
                
            <?php endif; ?>
        </div>

        <script>
            const subtotal = <?= $total_amount ?>;
            function updateShippingFee() {
                let fee = 0;
                let text = 'Miễn phí';
                const shipping = document.querySelector('input[name="shipping"]:checked');
                if (shipping) {
                    switch (shipping.value) {
                        case 'fast':
                            fee = 25000; text = '25.000 VND'; break;
                        case 'express':
                            fee = 50000; text = '50.000 VND'; break;
                    }
                }
                document.getElementById('shipping-fee').textContent = text;
                document.getElementById('total-amount').textContent = 
                    new Intl.NumberFormat('vi-VN').format(subtotal + fee) + ' VND';
            }

            function submitOrder() {
                const form = document.getElementById('checkout-form');
                const name = form.name.value.trim();
                const phone = form.phone.value.trim();
                const address = form.address.value.trim();

                if (!name || !phone || !address) {
                    alert('Vui lòng nhập đầy đủ thông tin.');
                    return;
                }

                // Hiển thị loading
                const button = event.target;
                button.disabled = true;
                button.textContent = 'Đang xử lý...';

                const hidden = document.createElement('input');
                hidden.type = 'hidden';
                hidden.name = 'submit_order';
                hidden.value = '1';
                form.appendChild(hidden);
                form.submit();
            }

            updateShippingFee();
        </script>
    </div>
</main>

<?php include 'partials/footer.php'; ?>