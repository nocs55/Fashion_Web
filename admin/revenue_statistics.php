<?php
require_once('partials/header.php');

$conn = new mysqli(HOST, USERNAME, PASSWORD, DATABASE);

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy giá trị tháng và năm từ URL để lọc, nếu không có thì lấy tháng/năm hiện tại
$filterMonth = isset($_GET['month']) ? (int)$_GET['month'] : date('m');
$filterYear = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');

// Tính tổng doanh thu của tháng hiện tại
$revenue = $conn->query("SELECT SUM(total_money) AS total FROM orders WHERE status = 'delivered' AND MONTH(order_date) = MONTH(CURDATE()) AND YEAR(order_date) = YEAR(CURDATE())")->fetch_assoc()['total'] ?? 0;

// Đếm tổng số đơn hàng đã hoàn tất
$orders = $conn->query("SELECT COUNT(*) AS total FROM orders WHERE status = 'delivered'")->fetch_assoc()['total'] ?? 0;

// Tính tổng số lượng sản phẩm đã bán
$products = $conn->query("SELECT SUM(od.quantity) AS total FROM order_details od JOIN orders o ON od.order_id = o.id WHERE o.status = 'delivered'")->fetch_assoc()['total'] ?? 0;

// Tính doanh thu theo từng tháng trong năm hiện tại để hiển thị biểu đồ cột
$monthlyRevenue = [];
for ($i = 1; $i <= 12; $i++) {
    $res = $conn->query("SELECT SUM(total_money) AS total FROM orders WHERE status = 'delivered' AND MONTH(order_date) = $i AND YEAR(order_date) = YEAR(CURDATE())")->fetch_assoc();
    $monthlyRevenue[] = $res['total'] ?? 0;
}

// Lấy danh sách sản phẩm đã bán trong tháng được lọc theo $filterMonth và $filterYear
$productList = $conn->query("SELECT p.product_name, SUM(od.quantity) AS total_quantity, SUM(od.total_money) AS revenue FROM order_details od JOIN product p ON od.product_id = p.id JOIN orders o ON od.order_id = o.id WHERE o.status = 'delivered' AND MONTH(o.order_date) = $filterMonth AND YEAR(o.order_date) = $filterYear GROUP BY p.product_name ORDER BY total_quantity DESC");

// Lấy top 5 sản phẩm bán chạy nhất trong tháng để vẽ biểu đồ tròn
$topProducts = [];
$topQuery = $conn->query("SELECT p.product_name, SUM(od.quantity) AS qty FROM order_details od JOIN product p ON od.product_id = p.id JOIN orders o ON od.order_id = o.id WHERE o.status = 'delivered' AND MONTH(o.order_date) = $filterMonth AND YEAR(o.order_date) = $filterYear GROUP BY p.product_name ORDER BY qty DESC LIMIT 5");
while ($row = $topQuery->fetch_assoc()) {
    $topProducts[$row['product_name']] = $row['qty'];
}

?>
        
        <main>
            <?php require_once('partials/sidebar.php'); ?>
    
            <!-- admin-content -->
            <div class="admin-content">
                
                <h2><i class="fa-solid fa-angles-right"></i> Thống kê doanh thu</h2>
                   
                    <div class="revenue">
                       <!-- Hộp thống kê nhanh -->
                        <div class="summary-boxes">
                            <div class="box">
                                <h3>Tổng doanh thu tháng <?= date('m/Y') ?></h3>
                                <p><?= number_format($revenue, 0, ',', '.') ?> VND</p>
                            </div>
                            <div class="box orange">
                                <h3>Tổng đơn hàng đã bán</h3>
                                <p><?= $orders ?></p>
                            </div>
                            <div class="box green">
                                <h3>Tổng sản phẩm đã bán</h3>
                                <p><?= $products ?></p>
                            </div>
                        </div>
    
                        <!-- Biểu đồ cột doanh thu 12 tháng -->
                        <div class="chart-container">
                            <canvas id="revenueChart"></canvas>
                        </div>
    
                        <!-- Biểu đồ tròn top 5 sản phẩm bán chạy -->
                        <div class="pie-container">
                            <h3 style="text-align:center;">Top 5 sản phẩm bán chạy tháng <?= $filterMonth ?>/<?= $filterYear ?></h3>
                            <canvas id="pieChart"></canvas>
                        </div>
    
                        <!-- Form lọc theo tháng và năm -->
                        <div class="filter-form">
                        <form method="get">
                            <label>Tháng:
                            <select name="month">
                                <?php for ($m = 1; $m <= 12; $m++): ?>
                                <option value="<?= $m ?>" <?= $m == $filterMonth ? 'selected' : '' ?>>Tháng <?= $m ?></option>
                                <?php endfor; ?>
                            </select>
                            </label>
                            <label>Năm:
                            <select name="year">
                                <?php for ($y = 2023; $y <= date('Y'); $y++): ?>
                                <option value="<?= $y ?>" <?= $y == $filterYear ? 'selected' : '' ?>><?= $y ?></option>
                                <?php endfor; ?>
                            </select>
                            </label>
                            <button type="submit">Lọc</button>
                        </form>
                        </div>
    
                        <!-- Bảng danh sách sản phẩm đã bán -->
                        <h2 style="text-align:center; margin-top:20px;">Danh sách sản phẩm đã bán trong tháng <?= $filterMonth ?>/<?= $filterYear ?></h2>
                        <table>
                            <thead>
                                <tr>
                                    <th>Tên sản phẩm</th>
                                    <th>Số lượng đã bán</th>
                                    <th>Doanh thu</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $productList->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['product_name']) ?></td>
                                        <td><?= $row['total_quantity'] ?></td>
                                        <td><?= number_format($row['revenue'], 0, ',', '.') ?> VND</td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
    
                        <!-- Script vẽ biểu đồ bằng Chart.js -->
                        <script>
                            const ctx = document.getElementById('revenueChart').getContext('2d');
                            const revenueChart = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: ['Tháng 1','Tháng 2','Tháng 3','Tháng 4','Tháng 5','Tháng 6','Tháng 7','Tháng 8','Tháng 9','Tháng 10','Tháng 11','Tháng 12'],
                                datasets: [{
                                label: 'Doanh thu theo tháng (VND)',
                                data: <?= json_encode($monthlyRevenue) ?>,
                                backgroundColor: 'rgba(0, 119, 182, 0.5)',
                                borderColor: 'rgba(0, 119, 182, 1)',
                                borderWidth: 1,
                                borderRadius: 6
                                }]
                            },
                            options: {
                                responsive: true,
                                scales: {
                                    y: {
                                        min:0,
                                        max:10000000,
                                        beginAtZero: true,
                                        ticks: {
                                        callback: val => new Intl.NumberFormat('vi-VN').format(val) + ' đ'
                                        }
                                    }
                                }
                            }
                        });
    
                        const pieCtx = document.getElementById('pieChart').getContext('2d');
                        new Chart(pieCtx, {
                        type: 'doughnut',
                        data: {
                            labels: <?= json_encode(array_keys($topProducts)) ?>,
                            datasets: [{
                                label: 'Sản phẩm bán chạy',
                                data: <?= json_encode(array_values($topProducts)) ?>,
                                backgroundColor: ['#ff6384', '#36a2eb', '#ffcd56', '#4bc0c0', '#9966ff'],
                                hoverOffset: 10,
                                borderColor: '#fff',
                                borderWidth: 2
                                }]
                            },
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: {
                                        position: 'bottom',
                                        labels: { boxWidth: 20, padding: 15 }
                                    },
                                    tooltip: {
                                        callbacks: {
                                            label: function(context) {
                                                let label = context.label || '';
                                                let value = context.raw;
                                                return `${label}: ${value} sản phẩm`;
                                            }
                                        }
                                    }
                                }
                            }
                        });
                        </script>
    
                    </div>
    
                  
            </div>
        </main>
 <?php
 require_once('partials/footer.php');
?>
