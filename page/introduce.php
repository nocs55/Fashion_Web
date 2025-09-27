<?php
include 'partials/header.php';


require_once ('../db/dbhelper.php');

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
                    <div class="icon"><i class="fa-solid fa-info-circle"></i></div>
                    <h2>Giới thiệu về T&N Fashion</h2>
                </div>

                <div class="introduce introduce-container">
                    <!-- Thông tin chung -->
                    <section class="company-info">
                        <h3><i class="fa-solid fa-store"></i> Về chúng tôi</h3>
                        <p>
                            Chào mừng bạn đến với <strong>T&N Fashion</strong> - điểm đến lý tưởng cho những tín đồ yêu thích 
                            phụ kiện thời trang! Sau <strong>1 năm</strong> hoạt động không ngừng nghỉ, chúng tôi tự hào là 
                            một trong những địa chỉ uy tín trong lĩnh vực bán lẻ phụ kiện thời trang online.
                        </p>
                        <p>
                            T&N Fashion được thành lập với sứ mệnh mang đến cho khách hàng những sản phẩm phụ kiện 
                            chất lượng cao, thiết kế độc đáo và giá cả hợp lý. Chúng tôi tin rằng mỗi phụ kiện nhỏ 
                            đều có thể tạo nên sự khác biệt lớn trong phong cách thời trang của bạn.
                        </p>
                    </section>

                    <!-- Sản phẩm chính -->
                    <section class="product-categories">
                        <h3><i class="fa-solid fa-gem"></i> Sản phẩm của chúng tôi</h3>
                        
                        <div class="category-grid">
                            <div class="category-item">
                                <div class="category-icon">
                                    <i class="fa-solid fa-clock"></i>
                                </div>
                                <h4>Đồng hồ</h4>
                                <p>Bộ sưu tập đồng hồ thời trang đa dạng, từ phong cách cổ điển đến hiện đại, 
                                   phù hợp với mọi phong cách và dịp sử dụng.</p>
                            </div>

                            <div class="category-item">
                                <div class="category-icon">
                                    <i class="fa-solid fa-scissors"></i>
                                </div>
                                <h4>Phụ kiện tóc</h4>
                                <p>Các loại kẹp tóc, băng đô, scrunchie và phụ kiện tóc thời trang giúp bạn 
                                   tạo điểm nhấn hoàn hảo cho mái tóc.</p>
                            </div>

                            <div class="category-item">
                                <div class="category-icon">
                                    <i class="fa-solid fa-ring"></i>
                                </div>
                                <h4>Trang sức</h4>
                                <p>Bộ sưu tập trang sức đa dạng bao gồm:</p>
                                <ul>
                                    <li><i class="fa-solid fa-circle"></i> Bông tai các loại</li>
                                    <li><i class="fa-solid fa-circle"></i> Vòng tay thời trang</li>
                                    <li><i class="fa-solid fa-circle"></i> Vòng cổ đẹp mắt</li>
                                </ul>
                            </div>

                            <div class="category-item">
                                <div class="category-icon">
                                    <i class="fa-solid fa-bag-shopping"></i>
                                </div>
                                <h4>Túi xách & Ví nữ</h4>
                                <p>Bộ sưu tập túi xách và ví nữ thời trang, từ túi công sở thanh lịch đến 
                                   túi dạo phố năng động, đáp ứng mọi nhu cầu của phái đẹp.</p>
                            </div>
                        </div>
                    </section>

                    <!-- Cam kết -->
                    <section class="commitment">
                        <h3><i class="fa-solid fa-handshake"></i> Cam kết của chúng tôi</h3>
                        
                        <div class="commitment-grid">
                            <div class="commitment-item">
                                <i class="fa-solid fa-star"></i>
                                <h4>Chất lượng</h4>
                                <p>Tất cả sản phẩm đều được chọn lọc kỹ càng, đảm bảo chất lượng tốt nhất.</p>
                            </div>


                            <div class="commitment-item">
                                <i class="fa-solid fa-money-bill-wave"></i>
                                <h4>Giá cả hợp lý</h4>
                                <p>Cam kết mang đến sản phẩm chất lượng với mức giá cạnh tranh nhất thị trường.</p>
                            </div>

                            <div class="commitment-item">
                                <i class="fa-solid fa-headset"></i>
                                <h4>Hỗ trợ 24/7</h4>
                                <p>Đội ngũ chăm sóc khách hàng tận tình, sẵn sàng hỗ trợ bạn mọi lúc mọi nơi.</p>
                            </div>
                        </div>
                    </section>

                    <!-- Thông tin liên hệ -->
                    <section class="contact-info">
                        <h3></i> Thông tin liên hệ</h3>
                        <div class="contact-details">
                            <p><i class="fa-solid fa-envelope"></i> <strong>Email:</strong> contact@tnfashion.com</p>
                            <p><i class="fa-solid fa-phone"></i> <strong>Hotline:</strong> 0987 896 333</p>
                            <p><i class="fa-solid fa-clock"></i> <strong>Giờ làm việc:</strong> 10:00 - 22:00 (Thứ 2 - Chủ nhật)</p>
                        </div>
                    </section>

                    <!-- Lời cảm ơn -->
                    <section class="thank-you">
                        <div class="thank-you-content">
                            <i class="fa-solid fa-heart"></i>
                            <h3>Cảm ơn bạn đã tin tương T&N Fashion!</h3>
                            <p>
                                Chúng tôi rất vui mừng được phục vụ bạn và mong muốn trở thành người bạn đồng hành 
                                tin cậy trong hành trình làm đẹp của bạn. Hãy khám phá bộ sưu tập đa dạng của chúng tôi 
                                và tìm cho mình những phụ kiện ưng ý nhất!
                            </p>
                            <div class="cta-buttons">
                                <a href="./" class="btn btn-primary">
                                    <i class="fa-solid fa-shopping-bag"></i> Mua sắm ngay
                                </a>
                                <a href="contact.php" class="btn btn-secondary">
                                    <i class="fa-solid fa-phone"></i> Liên hệ với chúng tôi
                                </a>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
            
            <!--right menu  -->
            <?php 
                include 'partials/right-menu.php';
            ?>
    
        </div>
    </main>

    <style>
       .introduce.introduce-container {
            padding:20px;
            background: white;
            border-radius:5px;

        }
        .introduce .introduce-container {
            max-width: 100%;
            margin: 20px 0;
            line-height: 1.6;

        }

        .introduce .introduce-container section {
            background: #fff;
            margin-bottom: 30px;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .introduce .introduce-container h3 {
            color: #2c5aa0;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e0e0e0;
            font-size: 1.4em;
        }

        .introduce .introduce-container h3 i {
            margin-right: 10px;
            color: #ff6b6b;
        }

        .introduce .company-info p {
            margin-bottom: 15px;
            text-align: justify;
            color: #555;
             line-height: 1.6;

        }

        .introduce .company-info h3 {
            margin-bottom: 10px;
            

        }
        .introduce .category-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin:20px;
        }

        .introduce .category-item {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            transition: transform 0.3s ease;
        }

        .introduce .category-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .introduce .category-icon {
            font-size: 2.5em;
            color: #ff6b6b;
            margin-bottom: 15px;
        }

        .introduce .category-item h4 {
            color: #2c5aa0;
            margin-bottom: 10px;
            font-size: 1.2em;
        }

        .introduce .category-item ul {
            list-style: none;
            padding: 0;
            margin: 10px 0;
        }

        .introduce .category-item ul li {
            padding: 5px 0;
            color: #666;
        }

        .introduce .category-item ul li i {
            color: #ff6b6b;
            margin-right: 8px;
            font-size: 0.8em;
        }

        .introduce .commitment{
            margin:10px;
        }
        .introduce .commitment-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .introduce .commitment-item {
            text-align: center;
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 8px;
            transition: transform 0.3s ease;
        }

        .introduce .commitment-item:hover {
            transform: scale(1.05);
        }

        .introduce .commitment-item i {
            font-size: 2em;
            margin-bottom: 15px;
            display: block;
        }

        .introduce .commitment-item h4 {
            margin-bottom: 10px;
            font-size: 1.1em;
        }

        .introduce .contact-info{
            margin:20px;
             line-height: 1.6;
        } 

        .introduce .contact-info h3{
            font-weight:600;
            
            font-size:1.1em
        } 

        .introduce .contact-details p {
            margin-bottom: 10px;
            font-size: 1.1em;
        }

        .introduce .contact-details i {
            color: #ff6b6b;
            margin-right: 10px;
            width: 20px;
        }
        .introduce .fa-solid.fa-phone{
            font-size: 1.1em;
            
        }
        .introduce .thank-you {
            text-align: center;
            padding: 10px;
            margin:20px;
             line-height: 1.6;

        }

        .introduce .thank-you-content i {
            font-size: 3em;
            color: #e74c3c;
            margin-bottom: 20px;
        }

        .introduce .thank-you h3 {
            color: #2c3e50;
            border: none;
            margin-bottom: 15px;
        }

        .introduce .cta-buttons {
            margin-top: 25px;
        }

        .introduce .btn {
            display: inline-block;
            padding: 12px 25px;
            margin: 0 10px;
            text-decoration: none;
            border-radius: 25px;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .introduce .btn-primary {
            background: #2c5aa0;
            color: white;
        }

        .introduce .btn-primary:hover {
            background: #1e3d6f;
            transform: translateY(-2px);
        }

        .introduce .btn-secondary {
            background: #fff;
            color: #2c5aa0;
            border: 2px solid #2c5aa0;
        }

        .introduce .btn-secondary:hover {
            background: #2c5aa0;
            color: white;
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .introduce .category-grid,
            .introduce .commitment-grid {
                grid-template-columns: 1fr;
            }
            
            .introduce .introduce-container section {
                padding: 15px;
            }

            .introduce .btn {
                display: block;
                margin: 10px 0;
            }
        }
    </style>

    <?php
    include 'partials/footer.php';
    ?>