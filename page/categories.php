<?php  
include 'partials/header.php';
?>

<?php
require_once('../db/dbhelper.php');

$category_id = isset($_GET['category']) ? intval($_GET['category']) : null;

$category_query = "SELECT category_name FROM category WHERE id = $category_id";
$category_result = executeSingleResult($category_query);
$category_name = $category_result['category_name'] ?? "Danh mục không tồn tại!"; 

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 4;
$offset = ($page - 1) * $limit;

// Kiểm tra số sản phẩm theo danh mục
$total_query = "SELECT COUNT(*) AS total FROM product WHERE category_id = $category_id OR category_id IN (SELECT id FROM category WHERE parent_id = $category_id )";
$total_result = executeSingleResult($total_query);
$total_products = $total_result['total'];
$total_pages = ($total_products > $limit) ? ceil($total_products / $limit) : 1;

$sql = "SELECT p.id, p.product_name, p.price, p.discount,
            (SELECT image FROM gallery g WHERE g.product_id = p.id ORDER BY id ASC LIMIT 1) AS image 
         FROM product p 
         WHERE category_id = $category_id OR category_id IN (SELECT id FROM category WHERE parent_id = $category_id )
          ORDER BY p.created_at DESC";

$sql .= ($total_products > $limit) ? " LIMIT $limit OFFSET $offset" : "";

$result = executeResult($sql);

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
        <h2><?= htmlspecialchars($category_name) ?></h2>
      </div>

    <?php if ($total_products == 0): ?>
    <div class="no-results">
        <p>Không có sản phẩm nào trong danh mục này!</p>
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
                    <a href="?category=<?= $category_id ?>&page=<?= $page - 1 ?>" class="btn-prev"><i class="fa-solid fa-angles-left"></i></a>
                <?php endif; ?>
                
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="?category=<?= $category_id ?>&page=<?= $i ?>" class="<?= ($i == $page) ? 'active' : '' ?>"><?= $i ?></a>
                <?php endfor; ?>

                <?php if ($page < $total_pages): ?>
                    <a href="?category=<?= $category_id ?>&page=<?= $page + 1 ?>" class="btn-next"><i class="fa-solid fa-angles-right"></i></a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
 
        
    </main>

    <?php
   include 'partials/footer.php';
    ?>




   