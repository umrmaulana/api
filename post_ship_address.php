<?php
include "koneksimysql.php";
header("Content-Type: application/json");

$response = array();

// Cek apakah semua parameter tersedia
if (
    isset($_POST['user_id']) &&
    isset($_POST['province_id']) &&
    isset($_POST['city_id']) &&
    isset($_POST['address']) &&
    isset($_POST['recipt_name'])
) {
    $user_id = $_POST['user_id'];
    $province_id = $_POST['province_id'];
    $city_id = $_POST['city_id'];
    $address = $_POST['address'];
    $recipt_name = $_POST['recipt_name'];

    // Validasi sederhana
    if (empty($address) || empty($recipt_name)) {
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "message" => "Field tidak boleh kosong."
        ]);
        exit();
    }

    // Query insert
    $stmt = $conn->prepare("INSERT INTO ship_addresses (user_id, province_id, city_id, address, recipt_name) 
                            VALUES (?, ?, ?, ?, ?)");
    if ($stmt->execute([$user_id, $province_id, $city_id, $address, $recipt_name])) {
        http_response_code(200);
        echo json_encode([
            "success" => true,
            "message" => "Alamat berhasil ditambahkan."
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            "success" => false,
            "message" => "Gagal menambahkan alamat."
        ]);
    }
} else {
    http_response_code(400);
    echo json_encode([
        "success" => false,
        "message" => "Parameter tidak lengkap."
    ]);
}
?>