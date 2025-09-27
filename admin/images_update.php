<?php
require_once('../db/config.php');
$conn = new mysqli(HOST, USERNAME, PASSWORD, DATABASE);

$id = intval($_POST["id"]);
$image_name = basename($_FILES["image"]["name"]);
$target_file = "img/" . $image_name;

// Lấy đường dẫn ảnh cũ từ CSDL
$queryOld = "SELECT image_path FROM gallery WHERE id = ?";
$stmtOld = $conn->prepare($queryOld);
$stmtOld->bind_param("i", $id);
$stmtOld->execute();
$resultOld = $stmtOld->get_result();
$oldImage = $resultOld->fetch_assoc()["image_path"];

// Xóa ảnh cũ
if (file_exists($oldImage)) {
    unlink($oldImage);
}

// Cập nhật ảnh mới
if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
    $queryUpdate = "UPDATE gallery SET image_path = ? WHERE id = ?";
    $stmtUpdate = $conn->prepare($queryUpdate);
    $stmtUpdate->bind_param("si", $target_file, $id);
    $stmtUpdate->execute();

    $_SESSION['message'] = "<p class='success-message'>Ảnh đã được cập nhật thành công!</p>";
} else {
    $_SESSION['message'] = "<p class='error-message'>Lỗi khi cập nhật ảnh!</p>";
}

header("Location: images_manage.php");
exit();
