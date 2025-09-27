<?php  
include 'partials/header.php';

// Lấy product_id từ URL
$product_id = isset($_GET['id']) ? intval($_GET['id']) : null;


$query = "SELECT p.id, p.product_name, p.price, p.discount,p.description,
            (SELECT image FROM gallery g WHERE g.product_id = p.id ORDER BY id ASC LIMIT 1) AS image 
         FROM product p 
         WHERE id = $product_id";
$product = executeSingleResult($query);

// Lấy tất cả ảnh trong gallery
$gallery_query = "SELECT image FROM gallery WHERE product_id = $product_id ORDER BY id ASC";
$gallery_images = executeResult($gallery_query);

// Lấy đánh giá sản phẩm
$review_sql = "SELECT r.*, u.username 
               FROM reviews r 
               LEFT JOIN user u ON r.user_id = u.id 
               WHERE r.product_id = $product_id 
               ORDER BY r.created_at DESC";
$reviews = executeResult($review_sql);

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
          
      
        <div class="detail">
            <div class="spmoi">
                <div class="icon"><i class="fa-solid fa-angles-right"></i></div>
                <h2><?= htmlspecialchars($product['product_name']) ?></h2>
            </div>
            

            <div class="product-container">
                <div>

                    <div class="img-section">
                        <img id="mainProductImg" src="<?=$product['image'] ?>" alt="<?= htmlspecialchars($product['product_name']) ?>" class="product-img">
                        <div class="thumbnail-gallery">
                            <?php foreach ($gallery_images as $img): ?>
                            <img 
                                src="<?= $img['image'] ?>" 
                                onclick="changeMainImage('<?= $img['image'] ?>')" 
                                alt="thumbnail">
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <script>
                        function changeMainImage(src) {
                            document.getElementById('mainProductImg').src = src;
                        }
                    </script>


                    <form class="buy-box" method="post">
                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">

                        <label for="quantity"><strong>Số lượng:</strong></label>
                        <input type="number" name="quantity" id="quantity" min="1" value="1" required>

                        <?php if (isset($_SESSION['cart_message'])): ?>
                            <div class="alert <?= $_SESSION['cart_message']['type'] === 'error' ? 'alert-error' : 'alert-success' ?>">
                                <?= htmlspecialchars($_SESSION['cart_message']['text']) ?>
                            </div>
                            <?php unset($_SESSION['cart_message']); ?>
                        <?php endif; ?>


                        <button type="submit" formaction="checkout.php" name="action" value="buy_now">Mua ngay</button>
                        <button type="submit" formaction="addtocart.php" name="action" value="addtocart">Thêm vào giỏ hàng</button>
                    </form>
                </div>

                <div class="info-box">
                    <p class="price" ><strong>Giá:</strong>
                        <?php if (!empty($product['discount']) && $product['discount'] > 0): ?>
                            
                            <span class="old-price" >
                                <?= number_format($product['price'], 0, ',', '.') ?> VND
                            </span>
                            <span class="discount-price" >
                                <?= number_format($product['price'] * (1 - $product['discount'] / 100), 0, ',', '.') ?> VND
                            </span>
                        <?php else: ?>
                            <span >
                                <?= number_format($product['price'], 0, ',', '.')  ?> VND
                            </span>
                        <?php endif; ?>
                    </p>
                    <p><strong>Mô tả:</strong></p>
                    <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>

                </div>
            </div>

            <div class="review">
              <h3>Đánh giá sản phẩm</h3>
    
                 <?php if (!empty($reviews)): ?>
                     <?php foreach ($reviews as $r): ?>  
                 <div class="review-item">
                <strong><?= htmlspecialchars($r['username'] ?? "Ẩn danh") ?></strong>
                <p>Đánh giá: <?= str_repeat('⭐', (int)$r['rating']) ?></p>
                <p><?= nl2br(htmlspecialchars($r['comment'] ?? "")) ?></p>
                <small><?= $r['created_at'] ?? "Không có dữ liệu" ?></small>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Chưa có đánh giá nào cho sản phẩm này.</p>
    <?php endif; ?>
</div>


            <a class="back" href="./">← Quay lại</a>
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



   