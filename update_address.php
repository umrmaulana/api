<?php
include "koneksimysql.php";
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id = intval($_POST['id']);
  $province_id = intval($_POST['province_id']);
  $province = $_POST['province_name'];
  $city_id = intval($_POST['city_id']);
  $city = $_POST['city_name'];
  $name = $_POST['recipt_name'];
  $address = $_POST['address'];
  $no_tlp = $_POST['no_tlp'];
  $postal = intval($_POST['postal_code']);

  // Validasi sederhana
  if (empty($address) || empty($name)) {
    http_response_code(400);
    echo json_encode(["message" => "Field tidak boleh kosong."]);
    exit();
  }
  $stmt = $conn->prepare("UPDATE ship_address SET province_id = ?, province_name = ?, city_id = ?, city_name = ?, recipt_name = ?, no_tlp = ?, address = ?, postal_code = ? WHERE id = ?");
  $stmt->bind_param("isissisii", $province_id, $province, $city_id, $city, $name, $no_tlp, $address, $postal, $id);

  if ($stmt->execute()) {
    echo json_encode(["message" => "Alamat diperbarui"]);
  } else {
    http_response_code(500);
    echo json_encode(["message" => "Gagal memperbarui"]);
  }

  $stmt->close();
} else {
  http_response_code(405);
  echo json_encode(["message" => "Metode tidak diizinkan"]);
}
$conn->close();
?>