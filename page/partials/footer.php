<?php

// Lấy dữ liệu từ bảng footer
$sql = "SELECT section, content FROM footer ORDER BY sort_order";
$result = executeResult($sql); 

$footer_data = [];
$display_counts = [];

foreach ($result as $row) {
    $footer_data[$row['section']][] = $row['content'];
}
?>

<!-- footer -->
    <footer class="footer">
      <div class="main-content">
        
        <ul class="others">
          <li class="other">
            <div class="icon-other"><i class="fa-solid fa-truck-fast"></i></div>
            <div class="text-other"><p class="P1">MIỄN PHÍ GIAO HÀNG</p>
            <P class="P2">Với hóa đơn từ 199.000đ</P></div>
          </li>
          <li class="other">
             <div class="icon-other"><i class="fa-solid fa-box-open"></i></div>
            <div class="text-other"><p class="P1">KIỂM TRA HÀNG KHI NHẬN</p>
            <P class="P2">Hoàn trả miễn phí</P></div>
          </li>
          <li class="other">
            <div class="icon-other"><i class="fa-solid fa-headset"></i></div>
            <div class="text-other"><p class="P1">MUA HÀNG(10H-22H,HẰNG NGÀY)</p>
            <Pre class="P2">CSKH: 0987 896 333 </Pre></div>
          </li>
          <li class="other">
            <div class="icon-other"><i class="fa-solid fa-store"></i></i></div>
            <div class="text-other"><p class="P1">HỆ THỐNG SHOWROOM</p>
            <P class="P2">Sàn thương mại điện tử</P></div>
          </li>
        </ul>
        
        <!-- ======= FOOTER ======== -->


      <ul class="TN-footer">
        <?php foreach ($footer_data as $section => $items): ?>
            <div class="footer-column">
                <h3><?= $section; ?></h3>
                <?php foreach ($items as $item): ?>
                    <p><?= $item; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
      </ul>

        </ul>
      </div>
    </footer>
  </body>
</html>
