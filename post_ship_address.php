<?php
include "koneksimysql.php";
header("Content-Type: application/json");

$response = array();

// Cek apakah semua parameter tersedia
if (
    isset($_POST['user_id']) &&
    isset($_POST['province_id']) &&
    isset($_POST['province_name']) &&
    isset($_POST['city_id']) &&
    isset($_POST['city_name']) &&
    isset($_POST['address']) &&
    isset($_POST['recipt_name']) &&
    isset($_POST['postal_code'])
) {
    $user_id = $_POST['user_id'];
    $province_id = $_POST['province_id'];
    $province_name = $_POST['province_name'];
    $city_id = $_POST['city_id'];
    $city_name = $_POST['city_name'];
    $address = $_POST['address'];
    $recipt_name = $_POST['recipt_name'];
    $postal_code = $_POST['postal_code'];

    // Validasi sederhana
    if (empty($address) || empty($recipt_name)) {
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "message" => "Field tidak boleh kosong."
        ]);
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO ship_address (
    user_id, province_id, province_name, city_id, city_name, address, recipt_name, postal_code) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    if (
        $stmt->execute([
            $user_id,
            $province_id,
            $province_name,
            $city_id,
            $city_name,
            $address,
            $recipt_name,
            $postal_code
        ])
    ) {
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