<?php
require_once('partials/header.php');

require_once('../db/config.php');
require_once('../db/dbhelper.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../page/signin.php');
    exit;
}

?>

       <main>
        
            <?php require_once('partials/sidebar.php'); ?>

            <!-- admin-content -->
            <div class="admin-content">

                <h2>Thống kê tổng quan</h2>
                <div class="stat-container">
                    <div class="stat-box">
                        <h3>Tổng thành viên</h3>
                        <?php
                        $conn = new mysqli(HOST, USERNAME, PASSWORD, DATABASE);
                        $res = $conn->query("SELECT COUNT(*) AS total_users FROM user WHERE deleted != 1 OR deleted IS NULL");
                        $row = $res->fetch_assoc();
                        echo "<p>{$row['total_users']}</p>";
                        ?>
                    </div>
                    <div class="stat-box">
                        <h3>Tổng sản phẩm</h3>
                        <?php
                        $res = $conn->query("SELECT COUNT(*) AS total_products FROM product WHERE deleted != 1 OR deleted IS NULL");
                        $row = $res->fetch_assoc();
                        echo "<p>{$row['total_products']}</p>";
                        ?>
                    </div>
                </div>
    
                <h3>Biểu đồ số lượng sản phẩm theo danh mục</h3>
                <canvas id="productChart" width="800" height="400"></canvas>
                <?php
                $res = $conn->query("SELECT c.category_name, COUNT(p.id) as total 
                                    FROM category c 
                                    LEFT JOIN product p ON c.id = p.category_id AND (p.deleted != 1 OR p.deleted IS NULL)
                                    GROUP BY c.id");
                $labels = [];
                $data = [];
                while ($r = $res->fetch_assoc()) {
                    $labels[] = $r['category_name'];
                    $data[] = $r['total'];
                }
                ?>
                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                <script>
                    const ctx = document.getElementById('productChart').getContext('2d');
                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: <?= json_encode($labels) ?>,
                            datasets: [{
                                label: 'Số lượng sản phẩm',
                                data: <?= json_encode($data) ?>,
                                backgroundColor: 'rgba(26, 188, 156, 0.6)',
                                borderColor: 'rgba(22, 160, 133, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        stepSize: 1
                                    }
                                }
                            }
                        }
                    });
                </script>
            </div>
       </main>
       

 <?php
 require_once('partials/footer.php');
?>