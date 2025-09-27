 <?php
// Truy vấn best-seller
$query = "SELECT id, product_name, price, discount, 
          (SELECT image FROM gallery g WHERE g.product_id = p.id ORDER BY id ASC LIMIT 1) AS image 
          FROM product p 
          ORDER BY p.sold_count DESC 
          LIMIT 3";
$best_sellers = executeResult($query);

// Tin tức
$news_list = executeResult("SELECT id, title FROM news ORDER BY created_at DESC");
?>


 <div class="right-menu">

          <!-- Sản phẩm bán chạy -->
        <div class="container best-seller">
            <div class="title-bestseller">
              <div class="icon-left">
                <i class="fa-solid fa-fire-flame-curved"></i>
              </div>
              <h2>Sản phẩm bán chạy</h2>
            </div>
            <div class="item-bestseller">
                 <?php foreach ($best_sellers as $product): ?>
                    <div class="best-seller-item">
                        <img src="<?= $product['image'] ?>" alt="<?= $product['product_name'] ?>">
                        <h3><?= $product['product_name'] ?></h3>
                        <p class="price">
                            <?php if ($product['discount'] > 0): ?>
                                <span class= "old-price">
                                    <?= number_format($product['price'], 0, ',', '.') ?> VND 
                                </span> 
                                <span class="discount-price">
                                    <?= number_format($product['price'] * (1 - $product['discount'] / 100), 0, ',', '.') ?> VND
                                </span>
                            <?php else: ?>
                                <?= number_format($product['price'], 0, ',', '.') ?> VND
                            <?php endif; ?>
                        </p>
                      <a href="product-detail.php?id=<?= $product['id'] ?>" class="btn-detail-bsl">Xem chi tiết</a>
                    </div>
                <?php endforeach; ?>
            </div>

        </div>

          <!-- Tin tức -->
          <div class="container news">
            <div class="title-news">
              <div class="icon-left">
                <i class="fa-regular fa-newspaper"></i>
              </div>
              <h2>Tin tức</h2>
            </div>
            <div class="item-news">
                  
                  <ul class="news-list">
                      <?php foreach ($news_list as $index => $news): ?>
                          <li class="news-item <?= ($index >= 4) ? 'hidden' : '' ?>">
                              <a href="news_detail.php?id=<?= $news['id'] ?>">
                                  <?= htmlspecialchars($news['title']) ?>
                              </a>
                          </li>
                      <?php endforeach; ?>
                  </ul>
                  <?php if (count($news_list) > 4): ?>
                      <button id="showMoreBtn">Xem thêm</button>
                      <button id="hideBtn" style="display:none;">Thu gọn</button>
                  <?php endif; ?>

            </div>
        </div>
          
</div>

    <script src="script.js"></script>
