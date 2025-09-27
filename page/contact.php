<?php
include 'partials/header.php';
include '../db/utility.php';
$fullname = $email = $phone = $message =  '';

if (!empty($_POST)) {
	$fullname = getPOST('fullname');
	$email    = getPOST('email');
	$phone = getPOST('phone');
	$message  = getPOST('message');

	if ($fullname != '' && $email != '' && $message != '') {

		$sql = "INSERT INTO feedback (name, email, phone, message) values ('$fullname', '$email', '$phone', '$message')";
		
		execute($sql);

        echo "<script>alert('Gửi phản hồi thành công!'); window.location.href='./';</script>";
        die();

	}
}

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

                <div class="contact-container2">
                        <h2>Liên hệ cho chúng tôi</h2>
                        <form class="contact-form" id="contactForm" action="contact.php" method="post" novalidate>
                            <input type="text" name="fullname" placeholder="* Họ tên" required>
                            <input type="email" name="email" placeholder="* Email" required>
                            <input type="text" name="phone" placeholder="Số điện thoại (tuỳ chọn)">
                            <textarea id="message" name="message" rows="5" placeholder="* Nội dung tin nhắn" required></textarea>
    
                            <button type="submit">GỬI LIÊN HỆ</button>
                        </form>
    
                        <div class="back-home">
                        <a href="./">← Quay lại trang chủ</a>
                        </div>    
                   
                    <script>
                            document.getElementById("contactForm").addEventListener("submit", function (e) {
                            const fullname = document.getElementById("fullname").value.trim();
                            const email = document.getElementById("email").value.trim();
                            const message = document.getElementById("message").value.trim();
    
                            // Kiểm tra các trường bắt buộc
                            if (!fullname || !email || !message) {
                                alert("Vui lòng điền đầy đủ các trường có dấu *.");
                                e.preventDefault();
                                return;
                            }
    
                            // Họ tên: chỉ chữ cái và khoảng trắng, ít nhất 2 từ, không hai khoảng trắng liên tiếp
                            const nameRegex = /^[A-Za-zÀ-ỹ]+( [A-Za-zÀ-ỹ]+)+$/;
                            if (!nameRegex.test(fullname) || fullname.includes("  ")) {
                                alert("Họ tên phải gồm ít nhất 2 từ, chỉ chứa chữ cái và khoảng trắng, không được có 2 khoảng trắng liên tiếp.");
                                e.preventDefault();
                                return;
                            }
    
                            // Email cơ bản
                            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                            if (!emailRegex.test(email)) {
                                alert("Email không hợp lệ. Vui lòng nhập đúng định dạng, ví dụ: abc@gmail.com");
                                e.preventDefault();
                                return;
                            }
    
                            // Nếu qua hết, cho phép gửi
                            alert("Thông tin liên hệ đã được gửi thành công!");
                            });
    
                           
                    </script>
    
                                
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








