<?php
header('Content-Type: application/json'); 

$conn = new mysqli(HOST, USERNAME, PASSWORD, DATABASE);

$category_id = intval($_GET['category_id']);
$query = "SELECT id, category_name FROM category WHERE parent_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $category_id);
$stmt->execute();
$result = $stmt->get_result();

$subcategories = [];
while ($row = $result->fetch_assoc()) {
    $subcategories[] = ["id" => $row["id"], "name" => $row["category_name"]];
}

echo json_encode($subcategories);
exit(); 

?>