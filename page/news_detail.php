<?php
include 'partials/header.php';
include '../db/utility.php';
    $conn = new mysqli("localhost", "root", "", "qly_ban_pktt");

    if ($conn->connect_error) {
        die("Kết nối thất bại: {$conn->connect_error}");
    }

    $id = $_GET['id'];  // Nhận id từ URL
    $sql = "SELECT * FROM news WHERE id = $id";
    $result = $conn->query($sql);
    $news = $result->fetch_assoc();
?>

    <!-- main content -->
    <main class="main">
        <div class="main-content">

            <!-- left-menu -->
            <?php 
            include 'partials/left-menu.php';
            ?>

            <!-- center content -->
            <div class="center-content">

            <div class="container-news-detail">
                <h2><?= htmlspecialchars($news['title']) ?></h2>
                <p><em>Ngày đăng : <?php echo date('d/m/Y H:i', strtotime($news['created_at'])); ?></em></p>
                <!-- <img src="<?php echo $news['image']; ?>" width="300"> -->
                <p><?= nl2br(htmlspecialchars($news['content'])) ?></p>
                <a class="back-btn" href="./">← Quay lại trang chủ</a>
            </div>
 
            </div>
            

            <!--right menu  -->
            <?php 
                include 'partials/right-menu.php';
            ?>
    
        </div> 
    </main>

    <?php
  include 'partials/footer.php';
    ?>








