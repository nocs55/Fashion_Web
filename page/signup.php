<?php
include 'partials/header.php';
include '../db/utility.php';

$username = $phone = $email = $pass = $pass_confirm =  '';


if (!empty($_POST)) {
    $username = getPOST('username');
	$phone = getPOST('phone');
	$email    = getPOST('email');
	$pass = getPOST('pass');
	$pass_confirm  = getPOST('pass_confirm');

    
    if ($pass != $pass_confirm) {
        echo "<script>alert('Đăng ký không thành công!Vui lòng nhập lại xác nhận mật khẩu!'); window.location.href='signup.php';</script>";

    }
	elseif ($phone != '' && $email != '' && $pass != '' && $pass_confirm != '') {
        
            $pass = getPwdSecurity($pass);

            $sql = "INSERT INTO user (username, email, phone_number, password) VALUES ('$username', '$email', '$phone', '$pass')";
            execute($sql);

            echo "<script>alert('✅ Đăng ký thành công!'); window.location.href='signin.php';</script>";
            exit();
	
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

               
                <div class="register-container">
                    <h2>Đăng ký</h2>

                    <form class="register-form" action="#" method="post">
                    <input type="text" placeholder="Họ tên" name= "username" required>
                    <input type="text" placeholder="Số điện thoại" name= "phone" required>
                    <input type="email" placeholder="Email" name = "email" required>
                    
                    <input type="password" placeholder="Mật khẩu" name="pass" required>
                    <input type="password" placeholder="Xác nhận mật khẩu" name="pass_confirm" required>

                    <button type="submit" class="register-btn">ĐĂNG KÝ</button>
                    </form>

                    <!-- <div class="social-register">
                    <button type="button"> Email</button>
                    <button type="button">Facebook</button>
                    </div> -->

                    <div class="login-link">
                    Đã có tài khoản? <a href="signin.php">Đăng nhập</a>
                    </div>
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








