<?php
session_start(); 
require_once('../db/dbhelper.php');
require_once('../db/config.php');


$is_logged_in = isset($_SESSION['user_id']);

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- reset  css  -->
    <link rel="stylesheet" href="../css/reset.css" />
    <!-- font-awesome -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
      integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />

    <!-- embed fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Sen:wght@400..800&display=swap"
      rel="stylesheet"
    />

    <!-- style css -->
    <link rel="stylesheet" href="../css/style.css" />
    <title>Phụ kiện thời trang</title>

    <script>
        function toggleProfileMenu() {
            var menu = document.getElementById("profile-menu");
            menu.style.display = (menu.style.display === "none") ? "block" : "none";
        }

        // Đóng menu nếu click bên ngoài
        document.addEventListener("click", function(event) {
            var menu = document.getElementById("profile-menu");
            var profile = document.querySelector(".user-profile img");
            if (!menu.contains(event.target) && event.target !== profile) {
                menu.style.display = "none";
            }
        });
    </script>


  </head>
  <body>
    <!-- header -->
    <header class="header fixed">
      <div class="main-content">
        <div class="body">
          <!-- logo -->
          <a class="logo" href="./"
            ><img src="../img/logo.svg" alt="Logo" width="100"
          /></a>

          <!-- navigation : thanh điều hướng -->
          <button class="menu-button">
            <i class="fa-solid fa-bars"></i>
          </button>
          <nav class="nav">
            <ul>
              <?php
              
              $sql = "SELECT * FROM menu ORDER BY sort_order";
              $result = executeResult($sql);
              foreach ($result as $row):
              ?>
                <li><a href="<?= $row['link']; ?>"><?= $row['name']; ?></a></li>
              <?php endforeach; ?>
              </ul>

          </nav>

          <!-- button action  -->
          <div class="action">
            <!-- Form tìm kiếm  -->
            <ul>
              <li class="search-container">
                <form action="search.php" method="GET">
                  <input
                    type="text"
                    name="timkiem"
                    placeholder="Tìm kiếm sản phẩm..."
                    class="search-input"
                  />
                  <button type="submit" class="search-button" title="Tìm kiếm ">
                    <i class="fas fa-search"></i>
                  </button>
                </form>
              </li>
              
              
              <!-- Đăng nhập -->
              <li>
                <?php if ($is_logged_in): ?>
                    <!-- Hiển thị avatar và menu nếu đã đăng nhập -->
                    <div class="user-profile">
                        <img src="https://web.nvnstatic.net/tp/T0295/img/icon-header-1.png?v=9" alt="Avatar"  onclick="toggleProfileMenu()">
                        <div id="profile-menu" class="profile-menu">
                            <a href="profile.php">Xem hồ sơ</a>
                            <a href="orders.php">Xem đơn mua</a>
                            <a href="signout.php">Đăng xuất</a>
                        </div>
                    </div>
                  <?php else: ?>
                    <!-- Hiển thị nút đăng nhập nếu chưa đăng nhập -->
                    <a href="signin.php" title="Đăng nhập">
                      <img
                        loading="lazy"
                        src="https://web.nvnstatic.net/tp/T0295/img/icon-header-1.png?v=9"
                        alt="cart"
                        width="25"
                      />
                    </a>
                <?php endif; ?>

              </li>
              <!-- giỏ hàng -->
              <li class="icon-cart" title="Giỏ hàng">
                <a aria-label="cart" href="cart.php">
                  <span class="cart-menu" aria-hidden="true">
                    <img
                      loading="cart"
                      src="https://web.nvnstatic.net/tp/T0295/img/icon-header-2.png?v=9"
                      alt="cart"
                      width="25"
                    />
                    <span class="count-holder">
                      <span id="cart-badge" class="count">0</span>
                    </span>
                  </span>
                  <script>
                  function updateCartBadge() {
                      fetch("get_cart_count.php")
                      .then(response => response.text())
                      .then(count => {
                          document.getElementById("cart-badge").textContent = count;
                      });
                  }

                  document.addEventListener("DOMContentLoaded", updateCartBadge);
                  </script>


                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </header>
