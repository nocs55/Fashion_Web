<?php
session_start();
require_once('../db/config.php');

$conn = new mysqli(HOST, USERNAME, PASSWORD, DATABASE);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$user_id = $_SESSION['user_id'] ?? null;
$total_items = 0;

if ($user_id) {
    $query = "SELECT COUNT(DISTINCT product_id) AS total_items FROM cart WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $total_items = $row['total_items'] ?? 0;
} 

echo $total_items;
