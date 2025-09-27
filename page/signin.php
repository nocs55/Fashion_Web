<?php
include 'partials/header.php';
include '../db/utility.php';

$password = $login_input = '';
$error_message = '';

if (!empty($_POST)) {
	$password = getPOST('password');
	$login_input  = getPOST('login_input');

	if ($password != '' && $login_input != '') {
		
		$password = getPwdSecurity($password);
        $sql = "SELECT * FROM user WHERE (email = '$login_input' OR phone_number = '$login_input') AND password = '$password' AND deleted = 0";
		$data = executeResult($sql);

		if ($data != null && count($data) > 0) {

            $_SESSION['user_id'] = $data[0]['id'];
            $_SESSION['user_name'] = $data[0]['username'];
            $_SESSION['role'] = $data[0]['role']; 
            $_SESSION['email'] = $data[0]['email'];

            // Kiểm tra vai trò & chuyển hướng
            if ($data[0]['role'] == 'admin') {
                echo "<script>window.location.href='../admin';</script>";
 
            } else {
                echo "<script>window.location.href='./';</script>";
            }

			die();
		} else{
            $error_message = "Sai email/số điện thoại hoặc mật khẩu!";
        }
 
	} else{
        $error_message ="Vui lòng nhập đầy đủ thông tin!";
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

               
                <div class="login-container">
                    <h2>Đăng nhập</h2>

                    <?php if ($error_message): ?>
                        <p style="color: red;"><?php echo $error_message; ?></p>
                    <?php endif; ?>


                    <form class="login-form" action="" method="post">
                    <input type="text" name="login_input" placeholder="Số điện thoại/email" required>
                    <input type="password" name="password" placeholder="Mật khẩu" required>
                    <button type="submit" class="login-btn">ĐĂNG NHẬP</button>

                    <!-- <div class="forgot-password">
                        <a href="forgot-password.html">Quên mật khẩu?</a>
                    </div> -->

                    <!-- <div class="divider">HOẶC</div> -->

                    <!-- <div class="social-login">
                        <button type="button" class="fb-btn" onclick="window.location.href='https://www.facebook.com/login'">Facebook</button>
                        <button type="button" class="gg-btn" onclick="window.location.href='https://accounts.google.com/signin'">Google</button>
                    </div> -->

                    <div class="register-text">
                        Bạn mới biết đến T&N? <a href="signup.php">Đăng ký</a>
                    </div>
                    </form>
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








