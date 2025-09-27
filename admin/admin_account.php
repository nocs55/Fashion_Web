<?php
include '../db/dbhelper.php';
include '../utils/utility.php';

$username = "admin";
$pwd = "123456789";
$password = getPwdSecurity($pwd); // Mã hóa mật khẩu
$role = "admin";

// Thêm tài khoản vào database
$query = "INSERT INTO user (username, password, role) VALUES ('$username', '$password', '$role')";
execute($query);

echo "Admin đã được tạo thành công!";
?>
