<?php
session_start();
require_once('../db/config.php');

if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href='signin.php';</script>";
    exit();
}

$conn = new mysqli(HOST, USERNAME, PASSWORD, DATABASE);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION["user_id"];
$product_id = intval($_POST["product_id"]);
$quantity = intval($_POST["quantity"]);

// Kiểm tra số lượng sản phẩm trong kho
$queryStock = "SELECT stock_quantity FROM product WHERE id = ?";
$stmtStock = $conn->prepare($queryStock);
$stmtStock->bind_param("i", $product_id);
$stmtStock->execute();
$resultStock = $stmtStock->get_result();
$rowStock = $resultStock->fetch_assoc();

if ($rowStock["stock_quantity"] < $quantity) {
    echo "<script>alert('Sản phẩm không đủ số lượng! Hiện còn lại: " . $rowStock["stock_quantity"] . " sản phẩm.');</script>";
    exit();
}

// Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
$queryCart = "SELECT quantity FROM cart WHERE user_id = ? AND product_id = ?";
$stmtCart = $conn->prepare($queryCart);
$stmtCart->bind_param("ii", $user_id, $product_id);
$stmtCart->execute();
$resultCart = $stmtCart->get_result();

if ($resultCart->num_rows > 0) {
    $rowCart = $resultCart->fetch_assoc();
    $new_quantity = $rowCart["quantity"] + $quantity;

    // Cập nhật số lượng trong giỏ hàng
    $queryUpdate = "UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?";
    $stmtUpdate = $conn->prepare($queryUpdate);
    $stmtUpdate->bind_param("iii", $new_quantity, $user_id, $product_id);
    $stmtUpdate->execute();
} else {
    // Thêm sản phẩm mới vào giỏ hàng
    $queryInsert = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)";
    $stmtInsert = $conn->prepare($queryInsert);
    $stmtInsert->bind_param("iii", $user_id, $product_id, $quantity);
    $stmtInsert->execute();
}

// Chuyển hướng đến giỏ hàng sau khi thêm sản phẩm
echo "<script>window.location.href='cart.php';</script>";
exit();

?>

