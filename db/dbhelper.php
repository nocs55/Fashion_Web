<?php
require_once ('config.php');

/**
 * Su dung cho lenh: insert/update/delete
 */
function execute($sql) {
	// Them du lieu vao database
	//B1. Mo ket noi toi database
	$conn = mysqli_connect(HOST, USERNAME, PASSWORD, DATABASE);
	mysqli_set_charset($conn, 'utf8');

	//B2. Thuc hien truy van insert
	mysqli_query($conn, $sql);
	
	//B3. Dong ket noi database
	mysqli_close($conn);
}


/**
 * Su dung cho lenh: select
 */
function executeResult($sql) {
	// Them du lieu vao database
	//B1. Mo ket noi toi database
	$conn = mysqli_connect(HOST, USERNAME, PASSWORD, DATABASE);
	mysqli_set_charset($conn, 'utf8');

	//B2. Thuc hien truy van insert
	$resultset = mysqli_query($conn, $sql);
	$data      = [];

	while (($row = mysqli_fetch_array($resultset, 1)) != null) {
		$data[] = $row;  //->trả vể mảng
	}

	//B3. Dong ket noi database
	mysqli_close($conn);

	// return $resultset;
	return $data;
}

function executeSingleResult($query) {
    $conn = mysqli_connect(HOST, USERNAME, PASSWORD, DATABASE);
    mysqli_set_charset($conn, 'utf8');

    $resultset = mysqli_query($conn, $query);
    $data = null;

    if ($row = mysqli_fetch_array($resultset, MYSQLI_ASSOC)) {
        $data = $row;  // Lấy bản ghi đầu tiên
    }

    mysqli_close($conn);
    return $data;  // Trả về bản ghi duy nhất (hoặc NULL nếu không có dữ liệu)
	
}

