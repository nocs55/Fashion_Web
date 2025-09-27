<?php
require_once ('partials/header.php');
// include "banner.php";


$keyword = isset($_GET['timkiem']) ? trim($_GET['timkiem']) : '';


$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 4;
$offset = ($page - 1) * $limit;

// Kiểm tra số sản phẩm tìm được
$total_query = "SELECT COUNT(*) AS total FROM product WHERE product_name LIKE '%$keyword%'";
$total_result = executeSingleResult($total_query);
$total_products = $total_result['total'];
$total_pages = ($total_products > $limit) ? ceil($total_products / $limit) : 1;

// Nếu có nhiều hơn 4 sản phẩm, thêm phân trang
$query = "SELECT p.id, p.product_name, p.price, p.discount,
            (SELECT image FROM gallery g WHERE g.product_id = p.id ORDER BY id ASC LIMIT 1) AS image
          FROM product p
          WHERE p.product_name LIKE '%$keyword%'
          ORDER BY p.created_at DESC";
$query .= ($total_products >= $limit) ? " LIMIT $limit OFFSET $offset" : ""; // Chỉ phân trang nếu có 4+ sản phẩm

$result = executeResult($query);


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
          
      <div class="spmoi">
        <div class="icon"><i class="fa-solid fa-angles-right"></i></div>
        <h2>Sản phẩm </h2>
      </div>

    <?php if ($total_products == 0): ?>
    <div class="no-results">
        <p>Không có sản phẩm nào phù hợp với từ khóa "<strong><?= $keyword ?></strong>".</p>
        <p>Hãy thử tìm kiếm với từ khóa khác!</p>
    </div>
<?php else: ?>
      <div class="product-grid">
    <?php foreach ($result as $product): ?>
        <div class="product-item">
            <img src="<?= $product['image'] ?>" alt="<?= $product['product_name'] ?>">
            <h3><?= $product['product_name'] ?></h3>
            <p class="price">
                <?php if ($product['discount'] > 0): ?>
                    <span class="old-price">
                        <?= number_format($product['price'], 0, ',', '.') ?> VND
                    </span> 
                    <span class="discount-price">
                        <?= number_format($product['price'] * (1 - $product['discount'] / 100), 0, ',', '.') ?> VND
                    </span>
                <?php else: ?>
                    <?= number_format($product['price'], 0, ',', '.') ?> VND
                <?php endif; ?>
            </p>
            <div class="buttons">
                <a href="product-detail.php?id=<?= $product['id'] ?>" class="btn btn-detail">Chi tiết sản phẩm</a>
            </div>

            <form class="buttons" method="post" action="addtocart.php">
                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                <label for="quantity"><strong>Số lượng:</strong></label>
                <input class="quantity" type="number" name="quantity" value="1" min="1">
                <button class="btn btn-add-cart " type="submit" name="action" value="addtocart"><i class="fa-solid fa-cart-plus"></i></button>
            </form>
        </div>
    <?php endforeach; ?>
</div>
     <?php endif; ?>
     
      </div>
      
      <!--right menu  -->
        <?php 
        include 'partials/right-menu.php';
        ?>
        
      </div>

        <!-- Phân trang  -->
         <?php if ($total_products >= $limit): ?>
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="?timkiem=<?= $keyword ?>&page=<?= $page - 1 ?>" class="btn-prev"><i class="fa-solid fa-angles-left"></i></a>
                <?php endif; ?>
                
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="?timkiem=<?= $keyword ?>&page=<?= $i ?>" class="<?= ($i == $page) ? 'active' : '' ?>"><?= $i ?></a>
                <?php endfor; ?>

                <?php if ($page < $total_pages): ?>
                    <a href="?timkiem=<?= $keyword ?>&page=<?= $page + 1 ?>" class="btn-next"><i class="fa-solid fa-angles-right"></i></a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
 
        
    </main>

    <?php
    include 'partials/footer.php';
    ?>




